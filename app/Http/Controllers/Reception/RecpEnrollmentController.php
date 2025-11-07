<?php
// app/Http/Controllers/Reception/RecpEnrollmentController.php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\RecpStudent;
use App\Models\RecpBatch;
use App\Models\RecpEnrollment;
use Illuminate\Http\Request;

class RecpEnrollmentController extends Controller
{
    public function create(RecpStudent $student)
    {
        $batches = RecpBatch::with('course')
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->get();
            
        return view('reception.enrollments.enroll_student', compact('student', 'batches'));
    }

    public function store(Request $request, RecpStudent $student)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:recp_batches,id',
            'enroll_date' => 'required|date',
            'fee_agreed' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'status' => 'required|in:enrolled,completed,dropped',
            'remarks' => 'nullable|string',
        ]);

        $validated['student_id'] = $student->id;
        $validated['final_fee'] = $validated['fee_agreed'] - $validated['discount'];
        
        RecpEnrollment::create($validated);
        
        return redirect()->route('recp.students.show', $student)
            ->with('success', 'Student enrolled successfully.');
    }
}