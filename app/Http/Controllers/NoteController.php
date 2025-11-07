<?php

namespace App\Http\Controllers;

use App\Models\BalanceReject;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function saveNote(Request $request)
    {
        // $user = Auth('admin')->user();
        $note = Note::findorFail(1);

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        $note->note = $request->input('note');
        $note->save();
        $id = 0;
        foreach ($request->datas as $data) {
            $blac = BalanceReject::where('id', $id)->first();

            if ($blac) {
                $blac->update([
                    'customer' => $data['customer'],
                    'USD' => $data['USD'],
                    'Remarks' => $data['Remarks'],
                    'xyz' => $data['xyz'],
                ]);
            }
            $id = $id + 1;
        }
        return response()->json(['success' => true, 'data' => $request->datas]);
    }

    public function getNotes()
    {
        // $user = Auth('admin')->user();
        $note = Note::find(1);
        $datas = BalanceReject::orderBY('id', 'asc')->get();
        return response()->json(['note' => $note, 'datas' => $datas]);
    }
}
