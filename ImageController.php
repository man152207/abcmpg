<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasfile('images')) {
            foreach($request->file('images') as $image) {
                $name = $image->getClientOriginalName();
                $image->move(public_path().'/images/', $name);  
            }
        }

        return back()->with('success', 'Images uploaded successfully');
    }
}



