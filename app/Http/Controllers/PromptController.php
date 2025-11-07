<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    // सबैले access गर्न मिलोस् भने यो controller मा auth middleware नलगाउनुहोस्।
    // यदि admin मात्रका लागि हो भने: $this->middleware('auth:admin'); constructor मा राख्नुस्।

    public function index(Request $request)
    {
        // Blade page (JS ले API hit गर्छ)
        return view('admin.prompts.index');
    }

    // List (JSON) with filters
    public function list(Request $request)
{
    $q     = trim($request->query('q', ''));
    $dep   = $request->query('department'); // null | Operations | Productions | Reception
    $sort  = $request->query('sort', 'new'); // new|old|az|za|fav
    $onlyFav = filter_var($request->query('onlyFav', false), FILTER_VALIDATE_BOOLEAN);

    $rows = Prompt::query()
        ->with('creator:id,name'); // ⬅️ eager-load creator

    if ($q !== '') {
        $rows->where(function($x) use ($q){
            $x->where('title','like',"%$q%")
              ->orWhere('client','like',"%$q%")
              ->orWhere('body','like',"%$q%");
        });
    }

    if ($dep && in_array($dep, ['Operations','Productions','Reception'])) {
        $rows->where('department', $dep);
    }

    if ($onlyFav) {
        $rows->where('is_fav', true);
    }

    switch ($sort) {
        case 'old': $rows->orderBy('created_at','asc'); break;
        case 'az':  $rows->orderBy('title','asc'); break;
        case 'za':  $rows->orderBy('title','desc'); break;
        case 'fav': $rows->orderBy('is_fav','desc')->orderBy('updated_at','desc'); break;
        default:    $rows->orderBy('created_at','desc');
    }

    $paginated = $rows->paginate(50);

    // Map to include creator_name without changing your frontend structure
    $data = $paginated->getCollection()->map(function ($p) {
        return [
            'id'           => $p->id,
            'title'        => $p->title,
            'client'       => $p->client,
            'department'   => $p->department,
            'body'         => $p->body,
            'is_fav'       => (bool)$p->is_fav,
            'updated_at'   => $p->updated_at,
            'creator_name' => optional($p->creator)->name, // ⬅️ the new field
        ];
    });

    return response()->json([
        'data'      => $data,
        'total'     => $paginated->total(),
        'last_page' => $paginated->lastPage(),
        'current'   => $paginated->currentPage(),
    ]);
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'client'     => 'nullable|string|max:255',
            'department' => 'required|in:Operations,Productions,Reception',
            'body'       => 'required|string',
        ]);

        $data['created_by'] = auth('admin')->id() ?? null;

        $row = Prompt::create($data);
        return response()->json($row, 201);
    }

    public function update(Request $request, $id)
    {
        $row = Prompt::findOrFail($id);

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'client'     => 'nullable|string|max:255',
            'department' => 'required|in:Operations,Productions,Reception',
            'body'       => 'required|string',
        ]);

        $row->update($data);
        return response()->json($row);
    }

    public function destroy($id)
    {
        $row = Prompt::findOrFail($id);
        $row->delete();
        return response()->json(['ok' => true]);
    }

    public function toggleFav($id)
    {
        $row = Prompt::findOrFail($id);
        $row->is_fav = !$row->is_fav;
        $row->save();

        return response()->json(['is_fav' => $row->is_fav]);
    }

    public function duplicate($id)
    {
        $row = Prompt::findOrFail($id);
        $copy = $row->replicate(['created_at','updated_at']);
        $copy->title = $row->title . ' (copy)';
        $copy->created_by = auth('admin')->id() ?? null;
        $copy->save();

        return response()->json($copy, 201);
    }
}
