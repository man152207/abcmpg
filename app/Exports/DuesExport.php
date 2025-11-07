<?php
namespace App\Exports;
use App\Models\RecpEnrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DuesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return RecpEnrollment::with('student', 'batch.course')
            ->whereRaw('(fee_agreed - discount - (SELECT COALESCE(SUM(amount),0) FROM recp_payments WHERE enrollment_id = recp_enrollments.id)) > 0')
            ->get()->map(function($due) {
                return [
                    'student' => $due->student->full_name,
                    'batch' => $due->batch->course->title . ' - ' . $due->batch->name,
                    'due' => $due->due_amount,
                ];
            });
    }

    public function headings(): array
    {
        return ['Student', 'Batch', 'Due Amount'];
    }
}