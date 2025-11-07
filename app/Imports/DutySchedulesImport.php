<?php

namespace App\Imports;

use App\Models\DutySchedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class DutySchedulesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Row keys:', array_keys($row)); // Log the keys of each row
        Log::info('Row values:', $row); // Log the entire row

        return new DutySchedule([
            'duty_date' => \Carbon\Carbon::parse($row['date']), // Adjust this key based on the logged output
            'man_grg' => $row['man_grg'],
            'sharu_tamang' => $row['sharu_tamang'],
            'kalpana_ghale' => $row['kalpana_ghale'],
        ]);
    }
}
