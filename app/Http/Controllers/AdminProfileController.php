<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AdminProfileController extends Controller
{
    public function edit()
    {
        try {
            // Retrieve the authenticated admin
            $admin = Auth('admin')->user();

            return view('admin.profile', compact('admin'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request)
{
    // 1) Validate: password केवल non-empty हुँदा मात्र validate होस्
    $validated = $request->validate([
        'name'  => ['required','string','max:255'],
        'email' => ['required','string','email','max:255','unique:admins,email,'.auth('admin')->id()],
        // 'sometimes|filled' => key: empty छ भने यो rule लागू हुँदैन
        'password' => ['sometimes','filled','string','min:8','confirmed'],
        'phone' => ['required','string','max:15'],
        'profile_picture' => ['sometimes','file','image','mimes:jpg,jpeg,png,webp','max:2048'],
    ]);

    $admin = auth('admin')->user();
    $adminModel = Admin::findOrFail($admin->id);

    // 2) Base data (password बिना)
    $data = [
        'name'  => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
    ];

    // 3) Password set only if filled (non-empty)
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->input('password'));
    }
    // Note: filled() ले empty string भए false दिन्छ, त्यसैले पुरानो password untouched

    // 4) Photo upload (optional)
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('admin_avatars', 'public');

        // पुरानो local file भए delete (external URL भए छोड्ने)
        if ($adminModel->profile_picture && !Str::startsWith($adminModel->profile_picture, ['http://','https://'])) {
            Storage::disk('public')->delete($adminModel->profile_picture);
        }
        $data['profile_picture'] = $path;  // "admin_avatars/xyz.jpg"
    }

    // 5) Update
    $adminModel->update($data);

    return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully!');
}
}
