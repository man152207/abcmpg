<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoostingTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BoostingTaskController extends Controller
{
    public function index() {
        $tasks = BoostingTask::with(['dispatcher', 'assignedUser'])
                    ->orderBy('status')
                    ->orderBy('requested_time', 'asc')
                    ->paginate(20);
        return view('admin.boosting.index', compact('tasks'));
    }

    public function store(Request $request) {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'priority' => 'in:Normal,Urgent',
        ]);

        $eta = $request->eta_time ? Carbon::parse($request->eta_time) : (
            BoostingTask::where('status', '!=', 'Done')
                ->orderBy('eta_time', 'desc')
                ->value('eta_time') ? Carbon::parse(BoostingTask::where('status', '!=', 'Done')->orderBy('eta_time', 'desc')->value('eta_time'))->addMinutes(15) : Carbon::now()->addMinutes(15)
        );

        BoostingTask::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'requested_time' => Carbon::now(),
            'eta_time' => $eta,
            'priority' => $request->priority ?? 'Normal',
            'remarks' => $request->remarks,
            'status' => 'Pending',
            'dispatcher_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', 'Task added successfully.');
    }

    public function assign($id) {
        $task = BoostingTask::findOrFail($id);
        $task->assigned_to = Auth::guard('admin')->id();
        $task->status = 'In Progress';
        $task->save();

        return back()->with('success', 'Task claimed successfully.');
    }

    public function complete($id) {
        $task = BoostingTask::findOrFail($id);
        $task->status = 'Done';
        $task->completed_time = Carbon::now();
        $task->save();

        return back()->with('success', 'Task marked as done.');
    }

    public function destroy($id) {
        BoostingTask::findOrFail($id)->delete();
        return back()->with('success', 'Task deleted.');
    }
}