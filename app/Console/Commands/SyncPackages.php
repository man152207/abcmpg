<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use App\Models\Package;

class SyncPackages extends Command
{
    protected $signature = 'mpg:sync-packages';
    protected $description = 'Sync active pricing packages (only from active sections) from Lovable/Supabase to local MySQL packages table';

    public function handle()
    {
        $cfg = config('services.supabase_public');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            $this->error('Supabase URL/key not set. Check SUPABASE_URL and SUPABASE_PUBLIC_KEY or SUPABASE_PUBLISHABLE_KEY.');
            return self::FAILURE;
        }

        $base = rtrim($cfg['url'], '/');

        // ---- Common headers
        $headers = [
            'apikey'        => $cfg['key'],
            'Authorization' => 'Bearer '.$cfg['key'],
            'Accept'        => 'application/json',
            'Prefer'        => 'count=exact',
        ];

        /**
         * 1) Prefetch feature master (id -> name/description/price)
         *    Only active features (RLS-public)
         */
        $featUrl = $base.'/rest/v1/pricing_features?apikey='.urlencode($cfg['key']);
        $featQuery = [
            'select'    => 'id,name,description,price,is_active',
            'is_active' => 'eq.true',
            'limit'     => 1000,
            'order'     => 'order_num.asc',
        ];
        $featRes = Http::withHeaders($headers)->get($featUrl, $featQuery);
        if (!$featRes->ok()) {
            $this->error("Fetch features failed: {$featRes->status()} ".$featRes->body());
            return self::FAILURE;
        }
        $featureRows = $featRes->json() ?? [];
        $featureById = [];
        foreach ($featureRows as $fr) {
            if (!empty($fr['id'])) {
                $featureById[$fr['id']] = [
                    'name'        => $fr['name']        ?? null,
                    'description' => $fr['description'] ?? null,
                    'price'       => $fr['price']       ?? null,
                ];
            }
        }

        /**
         * 2) Fetch packages BUT:
         *    - package.status must be 'active'
         *    - section must be active (inner join filter)
         */
        $pkgUrl  = $base.'/rest/v1/pricing_packages?apikey='.urlencode($cfg['key']);
        $pkgQuery = [
            // package active
            'status' => 'eq.active',

            // join sections; keep only active sections
            // NOTE: "!inner" performs inner join; we can then filter joined table columns
            'select' => 'id,title,price,status,currency,is_popular,features,updated_at,section_id,pricing_sections!inner(id,status,name)',
            'pricing_sections.status' => 'eq.active',

            'order'  => 'updated_at.desc',
        ];
        $res = Http::withHeaders($headers)->get($pkgUrl, $pkgQuery);

        if (!$res->ok()) {
            $this->error("Fetch packages failed: {$res->status()} ".$res->body());
            return self::FAILURE;
        }

        $rows = $res->json() ?? [];
        $count = 0;

        foreach ($rows as $it) {
            // ---- 2a) Enrich features with readable names
            $enrichedFeatures = [];
            if (!empty($it['features']) && is_array($it['features'])) {
                foreach ($it['features'] as $f) {
                    // f may be {"feature_id":"..."} and may include custom_price
                    $fid = is_array($f) ? ($f['feature_id'] ?? null) : null;
                    if (!$fid) { continue; }

                    $master = $featureById[$fid] ?? null;
                    $row = ['feature_id' => $fid];

                    // add name/description/default_price if we have them
                    if ($master) {
                        if (!empty($master['name']))        { $row['name'] = $master['name']; }
                        if (!empty($master['description'])) { $row['description'] = $master['description']; }
                        if (isset($master['price']))        { $row['default_price'] = $master['price']; }
                    }

                    // keep custom_price if present in source
                    if (isset($f['custom_price']) && $f['custom_price'] !== null) {
                        $row['custom_price'] = $f['custom_price'];
                    }

                    $enrichedFeatures[] = $row;
                }
            }

            // ---- 2b) Build local payload (only columns that exist)
            $payload = [
                'external_id' => $it['id'] ?? null,
                'synced_at'   => now(),
            ];

            $maybe = [
                'code'       => $it['code']        ?? null,
                'name'       => $it['title']       ?? $it['name'] ?? null,
                'price'      => $it['price']       ?? null,
                'currency'   => $it['currency']    ?? null,
                'features'   => $enrichedFeatures ?: ($it['features'] ?? null),
                'is_popular' => $it['is_popular']  ?? null,
                // status=text ('active'/'inactive') → active(bool)
                'active'     => isset($it['status'])
                                ? (strtolower($it['status']) === 'active')
                                : (isset($it['is_active']) ? (bool)$it['is_active'] : null),
            ];

            foreach ($maybe as $col => $val) {
                if (Schema::hasColumn('packages', $col) && !is_null($val)) {
                    $payload[$col] = $val;
                }
            }

            // fallback for NOT NULL name
            if (Schema::hasColumn('packages', 'name') && empty($payload['name'])) {
                $payload['name'] = 'Package '.substr((string)($it['id'] ?? uniqid()), 0, 8);
            }

            // ---- 2c) Upsert into local DB by external_id
            Package::updateOrCreate(
                ['external_id' => $payload['external_id']],
                $payload
            );

            $count++;
        }

        $this->info("Packages synced: {$count}");
        return self::SUCCESS;
    }
}
