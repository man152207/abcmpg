<?php
namespace App\Http\Controllers\Reception;
use App\Http\Controllers\Controller;
use App\Models\RecpStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecpStudentController extends Controller
{
    public function list(Request $req)
    {
        $q = RecpStudent::query()->latest();
        if ($s = $req->get('s')) {
            $q->where(function($x) use ($s) {
                $x->where('full_name','like',"%$s%")->orWhere('phone','like',"%$s%");
            });
        }
        $students = $q->paginate(20);
        return view('reception/students/students_list', compact('students'));
    }

    public function show(RecpStudent $student)
    {
        $student->load('enrollments.batch.course', 'payments', 'documents');
        return view('reception/students/student_show', compact('student'));
    }

    public function create()
    {
        return view('reception/students/student_create');
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'full_name' => ['required','string','max:150'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:150'],
            'guardian_name'=> ['nullable','string','max:150'],
            'address' => ['nullable','string','max:200'],
            'dob' => ['nullable','date'],
            'remarks' => ['nullable','string'],
        ]);
        $data['created_by'] = Auth::guard('admin')->id();
        $student = RecpStudent::create($data);
        return redirect()->route('recp.students.show', $student)->with('success','Student created.');
    }

    public function edit(RecpStudent $student)
    {
        return view('reception/students/student_edit', compact('student'));
    }

    public function update(Request $req, RecpStudent $student)
    {
        $data = $req->validate([
            'full_name' => ['required','string','max:150'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:150'],
            'guardian_name'=> ['nullable','string','max:150'],
            'address' => ['nullable','string','max:200'],
            'dob' => ['nullable','date'],
            'status' => ['required','string'],
            'remarks' => ['nullable','string'],
        ]);
        $student->update($data);
        return back()->with('success','Student updated.');
    }

    public function destroy(RecpStudent $student)
    {
        $student->delete();
        return redirect()->route('recp.students.list')->with('success','Student deleted (soft).');
    }
}