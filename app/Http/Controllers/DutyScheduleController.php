<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DutySchedule;

class DutyScheduleController extends Controller
{
    /**
     * Show planner screen.
     *
     * Loads a continuous 6-week (42-day) window starting from the earliest duty_date in DB.
     * If DB is empty: falls back to prev+current month so UI remains usable.
     */
    public function index(Request $request)
    {
        $today = Carbon::now('Asia/Kathmandu');

        // Find earliest row in DB
        $firstRow = DutySchedule::orderBy('duty_date','asc')->first();

        if ($firstRow) {
            $windowStart = Carbon::parse($firstRow->duty_date, 'Asia/Kathmandu')->copy();
            $windowEnd   = $windowStart->copy()->addDays(41); // 42 days

            $dutySchedules = DutySchedule::whereBetween('duty_date', [
                    $windowStart->toDateString(),
                    $windowEnd->toDateString(),
                ])
                ->orderBy('duty_date','asc')
                ->get();

            // Default rosters for UI
            $operationsRoster = [
                [
                    'name'     => 'Kalpana Ghale',
                    'title'    => 'Operations Manager',
                    'weekday'  => '09:00-17:00',
                    'saturday' => '10:00-18:00',
                    'active'   => true,
                ],
                [
                    'name'     => 'Sharu',
                    'title'    => '',
                    'weekday'  => '09:00-17:00',
                    'saturday' => '10:00-18:00',
                    'active'   => true,
                ],
                [
                    'name'     => 'Prakriti Shrestha',
                    'title'    => '',
                    'weekday'  => '10:00-18:00',
                    'saturday' => '10:00-18:00',
                    'active'   => true,
                ],
            ];

            $coverPoolRoster = [
                [
                    'name'        => 'Receptionist',
                    'duty_time'   => '10:00-18:00',
                    'tags'        => ['Ops-capable'],
                    'cap'         => 5,
                    'unavailable' => false,
                ],
                [
                    'name'        => 'Aayusha Gurung',
                    'duty_time'   => '10:00-18:00',
                    'tags'        => ['Ops-capable'],
                    'cap'         => 5,
                    'unavailable' => false,
                ],
            ];

            return view('DutySchedule.index', [
                'currentMonth'     => $windowStart->format('F Y').' - '.$windowEnd->format('F Y'),
                'dutySchedules'    => $dutySchedules,
                'operationsRoster' => $operationsRoster,
                'coverPoolRoster'  => $coverPoolRoster,
                'windowStart'      => $windowStart->toDateString(),
            ]);
        }

        // DB empty: fallback
        $currentMonthStart = $today->copy()->startOfMonth();
        $currentMonthEnd   = $today->copy()->endOfMonth();

        $prevMonthStart = $today->copy()->subMonth()->startOfMonth();
        $prevMonthEnd   = $today->copy()->subMonth()->endOfMonth();

        $dutySchedules = DutySchedule::whereBetween('duty_date', [
                $prevMonthStart->toDateString(),
                $currentMonthEnd->toDateString(),
            ])
            ->orderBy('duty_date','asc')
            ->get();

        $operationsRoster = [
            [
                'name'     => 'Kalpana Ghale',
                'title'    => 'Operations Manager',
                'weekday'  => '09:00-17:00',
                'saturday' => '10:00-18:00',
                'active'   => true,
            ],
            [
                'name'     => 'Sharu',
                'title'    => '',
                'weekday'  => '09:00-17:00',
                'saturday' => '10:00-18:00',
                'active'   => true,
            ],
            [
                'name'     => 'Prakriti Shrestha',
                'title'    => '',
                'weekday'  => '10:00-18:00',
                'saturday' => '10:00-18:00',
                'active'   => true,
            ],
        ];

        $coverPoolRoster = [
            [
                'name'        => 'Receptionist',
                'duty_time'   => '10:00-18:00',
                'tags'        => ['Ops-capable'],
                'cap'         => 5,
                'unavailable' => false,
            ],
            [
                'name'        => 'Aayusha Gurung',
                'duty_time'   => '10:00-18:00',
                'tags'        => ['Ops-capable'],
                'cap'         => 5,
                'unavailable' => false,
            ],
        ];

        return view('DutySchedule.index', [
            'currentMonth'     => $today->format('F Y'),
            'dutySchedules'    => $dutySchedules,
            'operationsRoster' => $operationsRoster,
            'coverPoolRoster'  => $coverPoolRoster,
            'windowStart'      => null, // no known 6-week window yet
        ]);
    }

    /**
     * saveMonth()
     *
     * Frontend sends array of rows (could be full 42 days).
     * We WIPE only the calendar month of the FIRST row’s month, then insert all rows from payload.
     */
    public function saveMonth(Request $request)
    {
        $data = $request->validate([
            'schedule'                        => 'required|array|min:1',
            'schedule.*.duty_date'            => 'required|date',
            'schedule.*.day_name'             => 'required|string',
            'schedule.*.is_holiday'           => 'sometimes|boolean',
            'schedule.*.remarks'              => 'sometimes|array',
            'schedule.*.operations_on'        => 'sometimes|array',
            'schedule.*.operations_off'       => 'sometimes|array',
            'schedule.*.covers'               => 'sometimes|array',
            'schedule.*.shift_overrides'      => 'sometimes|array',
            'schedule.*.preleave_plan'        => 'sometimes|array',
        ]);

        $rows = $data['schedule'];

        // wipe only the month of the first row
        $firstRowDate = Carbon::parse($rows[0]['duty_date'])->startOfMonth();
        $lastRowDate  = $firstRowDate->copy()->endOfMonth();

        DutySchedule::whereBetween('duty_date', [
            $firstRowDate->toDateString(),
            $lastRowDate->toDateString()
        ])->delete();

        // re-insert provided rows
        foreach ($rows as $row) {
            DutySchedule::create([
                'duty_date'        => $row['duty_date'],
                'day_name'         => $row['day_name'] ?? Carbon::parse($row['duty_date'], 'Asia/Kathmandu')->format('l'),
                'is_holiday'       => $row['is_holiday'] ?? false,

                'operations_on'    => $row['operations_on']    ?? [],
                'operations_off'   => $row['operations_off']   ?? [],
                'covers'           => $row['covers']           ?? [],
                'shift_overrides'  => $row['shift_overrides']  ?? [],
                'preleave_plan'    => $row['preleave_plan']    ?? [],

                // remarks as pipe-joined string
                'remarks'          => isset($row['remarks'])
                    ? implode(' | ', $row['remarks'])
                    : null,

                // legacy columns (kept null)
                'staff1'           => null,
                'staff2'           => null,
                'staff3'           => null,
                'production'       => null,
                'reception'        => null,
                'helper'           => null,
            ]);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Saved to database',
        ]);
    }

    /**
     * generateWindow()
     *
     * Optional backend generator for 42-day preview from start_date.
     */
    public function generateWindow(Request $request)
    {
        $payload = $request->validate([
            'start_date'           => 'required|date',
            'operations_roster'    => 'required|array|min:1',
            'cover_pool'           => 'required|array',
            'preleave_preferences' => 'sometimes|array',
        ]);

        $start = Carbon::parse($payload['start_date'], 'Asia/Kathmandu')->startOfDay();
        $end   = $start->copy()->addDays(41);

        $opsRoster   = array_values($payload['operations_roster']);
        $coverRoster = array_values($payload['cover_pool']);
        $prefMap     = $payload['preleave_preferences'] ?? [];

        $coverLoad = [];
        foreach ($coverRoster as $c) {
            $coverLoad[$c['name']] = [
                'count'       => 0,
                'cap'         => $c['cap']         ?? 5,
                'tags'        => $c['tags']        ?? [],
                'unavailable' => $c['unavailable'] ?? false,
                'duty_time'   => $c['duty_time']   ?? '10:00-18:00',
            ];
        }

        $results = [];
        $dualCounter = 0;

        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $dayName = $cursor->format('l');
            $isSat   = ($dayName === 'Saturday');
            $dateStr = $cursor->toDateString();

            $row = [
                'duty_date'        => $dateStr,
                'day_name'         => $dayName,
                'is_holiday'       => false,
                'remarks'          => [],
                'operations_on'    => [],
                'operations_off'   => [],
                'covers'           => [],
                'shift_overrides'  => [],
                'preleave_plan'    => [],
            ];

            if ($isSat) {
                $rotIndex = $this->rotationIndexForSaturday($cursor, $start);

                $A = $opsRoster[$rotIndex % count($opsRoster)]['name'] ?? null;
                $B = $opsRoster[($rotIndex + 1) % count($opsRoster)]['name'] ?? null;
                $C = $opsRoster[($rotIndex + 2) % count($opsRoster)]['name'] ?? null;

                $dualNeeded = false;

                if ($dualNeeded) {
                    $row['operations_on']    = array_filter([$A, $B]);
                    $row['operations_off']   = array_filter([$C]);
                    $row['remarks'][]        = 'Dual Sat (High workload)';

                    $choice = ($dualCounter % 2 === 0) ? 0 : 1;
                    $dualCounter++;

                    $defaultThu = $choice === 0 ? $A : $B;
                    $defaultFri = $choice === 0 ? $B : $A;

                    $pref = $prefMap[$dateStr] ?? [];
                    $thuOffList = $pref['Thu'] ?? array_filter([$defaultThu]);
                    $friOffList = $pref['Fri'] ?? array_filter([$defaultFri]);

                    $row['preleave_plan']['Thursday'] = $thuOffList;
                    $row['preleave_plan']['Friday']   = $friOffList;

                } else {
                    $row['operations_on']    = array_filter([$A]);
                    $offPeople               = array_filter([$B,$C]);
                    $row['operations_off']   = $offPeople;
                    $row['remarks'][]        = 'Single Sat (Weekly rotation)';

                    if ($offPeople) {
                        $row['remarks'][] = 'Sat off: '.implode(', ',$offPeople);
                    }

                    $row['preleave_plan']['Friday'] = array_filter([$A]);
                }
            } else {
                $row['operations_on']  = array_map(fn($p) => $p['name'], $opsRoster);
                $row['operations_off'] = [];
            }

            $results[$dateStr] = $row;
        }

        // Thu/Fri preleave + cover assignment
        foreach ($results as $dateStr => &$row) {
            if ($row['day_name'] !== 'Saturday') continue;

            $preleave = $row['preleave_plan'];
            foreach ($preleave as $weekdayName => $peopleOffList) {
                if (!count($peopleOffList)) continue;

                $thatDay = Carbon::parse($row['duty_date'], 'Asia/Kathmandu')->copy();
                for ($step = 1; $step <= 3; $step++) {
                    $candidate = $thatDay->copy()->subDays($step);
                    if ($candidate->format('l') === $weekdayName) {
                        $key = $candidate->toDateString();
                        if (!isset($results[$key])) break;

                        $targetRow =& $results[$key];

                        foreach ($peopleOffList as $offPerson) {
                            if (!in_array($offPerson, $targetRow['operations_off'])) {
                                $targetRow['operations_off'][] = $offPerson;
                            }

                            $targetRow['operations_on'] = array_values(
                                array_diff($targetRow['operations_on'], [$offPerson])
                            );

                            $remark = $weekdayName === 'Thursday'
                                ? 'Thu off (pre-dual)'
                                : (count($row['operations_on']) > 1
                                    ? 'Fri off (pre-dual)'
                                    : 'Fri off for Sat single');

                            if (!in_array($remark, $targetRow['remarks'])) {
                                $targetRow['remarks'][] = $remark;
                            }

                            $coverChoice = $this->pickCoverForPerson(
                                $offPerson,
                                $coverLoad
                            );

                            if ($coverChoice) {
                                $targetRow['covers'][$offPerson] = [
                                    'name' => $coverChoice['name'],
                                    'time' => $coverChoice['duty_time'],
                                ];
                                $targetRow['remarks'][] =
                                    'Cover: '.$coverChoice['name'].
                                    ' (10-18) - '.$remark;
                            }
                        }

                        unset($targetRow);
                        break;
                    }
                }
            }
        }
        unset($row);

        $out = array_values($results);

        return response()->json([
            'ok'      => true,
            'window'  => [
                'start' => $start->toDateString(),
                'end'   => $end->toDateString(),
            ],
            'schedule'=> $out,
        ]);
    }

    /**
     * updateDay() — upsert a single date.
     */
    public function updateDay(Request $request)
    {
        $row = $request->validate([
            'duty_date'       => 'required|date',
            'day_name'        => 'required|string',
            'is_holiday'      => 'sometimes|boolean',
            'remarks'         => 'sometimes|array',
            'operations_on'   => 'sometimes|array',
            'operations_off'  => 'sometimes|array',
            'covers'          => 'sometimes|array',
            'shift_overrides' => 'sometimes|array',
            'preleave_plan'   => 'sometimes|array',
        ]);

        $dateStr = $row['duty_date'];

        $record = DutySchedule::firstOrNew([
            'duty_date' => $dateStr,
        ]);

        $record->day_name = $row['day_name']
    ?? $record->day_name
    ?? Carbon::parse($dateStr, 'Asia/Kathmandu')->format('l');
        $record->is_holiday      = $row['is_holiday'] ?? $record->is_holiday ?? false;
        $record->operations_on   = $row['operations_on']   ?? $record->operations_on   ?? [];
        $record->operations_off  = $row['operations_off']  ?? $record->operations_off  ?? [];
        $record->covers          = $row['covers']          ?? $record->covers          ?? [];
        $record->shift_overrides = $row['shift_overrides'] ?? $record->shift_overrides ?? [];
        $record->preleave_plan   = $row['preleave_plan']   ?? $record->preleave_plan   ?? [];

        $record->remarks         = isset($row['remarks'])
            ? implode(' | ', $row['remarks'])
            : $record->remarks;

        // legacy cols
        $record->staff1     = $record->staff1     ?? null;
        $record->staff2     = $record->staff2     ?? null;
        $record->staff3     = $record->staff3     ?? null;
        $record->production = $record->production ?? null;
        $record->reception  = $record->reception  ?? null;
        $record->helper     = $record->helper     ?? null;

        $record->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Day updated',
            'data'    => $record,
        ]);
    }

    /**
     * deleteAll()
     * Deletes ALL rows from DutySchedule (entire schedule).
     */
    public function deleteAll(Request $request)
    {
        $count = DutySchedule::query()->delete();

        return response()->json([
            'ok'      => true,
            'message' => "Deleted entire schedule ({$count} rows).",
        ]);
    }

    /* ----------------- Helpers ----------------- */

    /**
     * rotationIndexForSaturday()
     * Which Saturday number since the window start?
     */
    protected function rotationIndexForSaturday(Carbon $thisSaturday, Carbon $windowStart): int
    {
        $cursor = $windowStart->copy();
        $count  = 0;

        while ($cursor->lessThanOrEqualTo($thisSaturday)) {
            if ($cursor->format('l') === 'Saturday') {
                if ($cursor->isSameDay($thisSaturday)) {
                    return $count;
                }
                $count++;
            }
            $cursor->addDay();
        }

        return $count;
    }

    /**
     * pickCoverForPerson()
     * Pick best cover from pool: prefer Ops-capable, under cap, lowest load.
     */
    protected function pickCoverForPerson(string $offPerson, array &$coverLoad): ?array
    {
        // Stage 1: require Ops-capable
        $eligible = array_filter($coverLoad, function ($meta) {
            return !$meta['unavailable']
                && in_array('Ops-capable', $meta['tags'] ?? [])
                && $meta['count'] < $meta['cap'];
        });

        // Stage 2: fallback to anyone under cap
        if (empty($eligible)) {
            $eligible = array_filter($coverLoad, function ($meta) {
                return !$meta['unavailable'] && $meta['count'] < $meta['cap'];
            });
        }

        if (empty($eligible)) {
            return null;
        }

        uasort($eligible, function ($a,$b) { return $a['count'] <=> $b['count']; });

        $chosenName = array_key_first($eligible);
        if ($chosenName === null) {
            return null;
        }

        $coverLoad[$chosenName]['count']++;

        return [
            'name'      => $chosenName,
            'duty_time' => $coverLoad[$chosenName]['duty_time'] ?? '10:00-18:00',
        ];
    }
}
