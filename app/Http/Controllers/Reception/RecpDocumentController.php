<?php
namespace App\Http\Controllers\Reception;
use App\Http\Controllers\Controller;
use App\Models\RecpStudent;
use App\Models\RecpDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class RecpDocumentController extends Controller
{
    public function list(Request $req)
    {
        $documents = RecpDocument::with('student', 'handler')->latest()->paginate(20);
        return view('reception/documents/documents_list', compact('documents'));
    }

    public function create(RecpStudent $student)
    {
        return view('reception/documents/new_document', compact('student'));
    }

    public function store(Request $req, RecpStudent $student)
    {
        $data = $req->validate([
            'doc_type' => ['required','string','max:120'],
            'doc_no' => ['nullable','string','max:120'],
            'issued_at' => ['nullable','date'],
            'fee' => ['required','numeric','min:0'],
            'remarks' => ['nullable','string'],
            'file' => ['nullable','file','mimes:pdf,jpg,png','max:2048'],
        ]);
        $data['student_id'] = $student->id;
        $data['handled_by_admin_id'] = Auth::guard('admin')->id();
        if ($req->hasFile('file')) {
            $data['file_path'] = $req->file('file')->store('documents', 'public');
        }
        $doc = RecpDocument::create($data);
        return redirect()->route('recp.students.show', $student)->with('success','Document saved.');
    }

    public function edit(RecpDocument $document)
    {
        $document->load('student', 'handler');
        return view('reception/documents/document_edit', compact('document'));
    }

    public function update(Request $req, RecpDocument $document)
    {
        $data = $req->validate([
            'doc_type' => ['required','string','max:120'],
            'doc_no' => ['nullable','string','max:120'],
            'issued_at' => ['nullable','date'],
            'fee' => ['required','numeric','min:0'],
            'remarks' => ['nullable','string'],
            'file' => ['nullable','file','mimes:pdf,jpg,png','max:2048'],
        ]);
        if ($req->hasFile('file')) {
            if ($document->file_path) Storage::disk('public')->delete($document->file_path);
            $data['file_path'] = $req->file('file')->store('documents', 'public');
        }
        $document->update($data);
        return back()->with('success','Document updated.');
    }

    public function destroy(RecpDocument $document)
    {
        if ($document->file_path) Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->route('recp.doc.list')->with('success','Document deleted (soft).');
    }

    public function receipt(RecpDocument $document)
    {
        $document->load('student', 'handler');
        $pdf = Pdf::loadView('reception/documents/receipt_pdf', compact('document'));
        return $pdf->download('document_receipt_'.$document->id.'.pdf');
    }
}