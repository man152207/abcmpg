<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('is_popular','DESC')
                           ->orderBy('price','ASC')
                           ->get();
        return view('admin.packages.index', compact('packages'));
    }
}
