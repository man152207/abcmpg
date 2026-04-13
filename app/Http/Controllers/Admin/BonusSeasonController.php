<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusSeason;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BonusSeasonController extends Controller
{
    /**
     * Return currently active bonus season (JSON).
     */
    public function show()
    {
        $season = BonusSeason::where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        if (!$season) {
            return response()->json([
                'active' => false,
                'label'  => 'Inactive',
            ]);
        }

        return response()->json([
            'active'        => true,
            'id'            => $season->id,
            'start_date'    => Carbon::parse($season->start_date)->format('Y-m-d'),
            'end_date'      => Carbon::parse($season->end_date)->format('Y-m-d'),
            'bonus_percent' => $season->bonus_rate,
            'min_spend'     => $season->min_spend,
            'claim_days'    => $season->claim_days,
            'label'         => sprintf(
                'Active: %s - %s (%s%%, %s days claim)',
                Carbon::parse($season->start_date)->format('d M Y'),
                Carbon::parse($season->end_date)->format('d M Y'),
                $season->bonus_rate ?? 0,
                $season->claim_days ?? 7
            ),
        ]);
    }

    /**
     * Create / update active bonus season (via AJAX).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
            'bonus_percent' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'min_spend'     => ['nullable', 'numeric', 'min:0'],
            'claim_days'    => ['nullable', 'integer', 'min:0', 'max:365'],
        ]);

        // सबै पुराना active season हरू deactivate
        BonusSeason::where('is_active', true)->update(['is_active' => false]);

        $season = BonusSeason::create([
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'bonus_rate' => $data['bonus_percent'] ?? null,
            'min_spend'  => $data['min_spend'] ?? null,
            'claim_days' => $data['claim_days'] ?? 7,
            'is_active'  => true,
        ]);

        return response()->json([
            'status'        => 'ok',
            'id'            => $season->id,
            'start_date'    => Carbon::parse($season->start_date)->format('Y-m-d'),
            'end_date'      => Carbon::parse($season->end_date)->format('Y-m-d'),
            'bonus_percent' => $season->bonus_rate,
            'min_spend'     => $season->min_spend,
            'claim_days'    => $season->claim_days,
            'message'       => 'Bonus Season: ' .
                Carbon::parse($season->start_date)->format('d M Y') . ' - ' .
                Carbon::parse($season->end_date)->format('d M Y') .
                ' | ' . ($season->bonus_rate ?? 0) . '% | ' .
                ($season->claim_days ?? 7) . ' days claim window',
        ]);
    }

    /**
     * Turn off current bonus season.
     */
    public function deactivate()
    {
        BonusSeason::where('is_active', true)->update(['is_active' => false]);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Bonus season turned off.',
        ]);
    }
}
