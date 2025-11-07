<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\UserPrivilege;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserPrivilegeController extends Controller
{
    public function register_form()
    {
        try {
            // ACTIVE departments load गरेर add.blade मा पठाउँछौं
            $departments = Department::where('is_active', true)->orderBy('name')->get();
            return view('admin.user.add', compact('departments'));
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', 'Failed to load form.');
        }
    }

    public function show()
    {
        try {
            $super_admin = UserPrivilege::where('full_or_partial', 1)->first();

            // >>> यो नै त्यो eager-load लाइन हो <<<
            $users = Admin::with('departments')
                ->when($super_admin, fn($q) => $q->where('id', '!=', $super_admin->user_id))
                ->orderBy('id', 'DESC')
                ->paginate(10);

            return view('admin.user.list', compact('users'));
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', 'Failed to load users.');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
            'password'   => ['required', 'confirmed', Password::defaults()],
            'phone'      => ['required', 'string', 'max:255'],
            // NEW: departments multi-select
            'departments'   => ['nullable','array'],
            'departments.*' => ['integer','exists:departments,id'],
        ]);

        $user = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'phone'    => $request->phone,
        ]);

        UserPrivilege::create([
            'user_id'         => $user->id,
            'option'          => null,
            'full_or_partial' => 0,
        ]);

        // attach selected departments
        $deptIds = (array) $request->input('departments', []);
        if (!empty($deptIds)) {
            $user->departments()->sync($deptIds);
        }

        return redirect('admin/dashboard/user/list')->with('success', 'Admin created.');
    }

    public function search(Request $request)
    {
        try {
            // eager-load departments here too (list मा badges चाहिए)
            $users = Admin::with('departments')
                ->where('name', 'like', '%' . $request->search . '%')
                ->orderBy('id','DESC')
                ->paginate(10);

            return view('admin.user.list', compact('users'));
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', 'Failed to search.');
        }
    }

    public function delete($id)
    {
        try {
            $user = Admin::findOrFail($id);
            $user_privilege = UserPrivilege::where('user_id', $user->id)->first();

            $user->delete();
            if ($user_privilege) {
                $user_privilege->delete();
            }

            return redirect()->route('admin.user.show')->with('status', 'success');
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', 'Delete failed.');
        }
    }

    public function edit($id)
    {
        try {
            // edit view ले departments read-only badges देखाउँछ भने eager-load use
            $user = Admin::with('departments')->findOrFail($id);
            return view('admin.user.update', compact('user'));
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', 'Failed to load user.');
        }
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name'            => ['required','string','max:255'],
            'email'           => ['required','email','max:255', Rule::unique('admins','email')->ignore($admin->id)],
            'phone'           => ['nullable','string','max:255'],
            'password'        => ['nullable','confirmed','min:8'],
            'profile_picture' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('admin_avatars', 'public');

            if ($admin->profile_picture && !Str::startsWith($admin->profile_picture, ['http://','https://'])) {
                Storage::disk('public')->delete($admin->profile_picture);
            }

            $validated['profile_picture'] = $path;
        }

        $admin->update($validated);

        return back()->with('success', 'User updated successfully.');
    }

    public function privilege($id)
    {
        $user = Admin::findOrFail($id);

        // Privileges
        $userPrivilegesRow = UserPrivilege::select('option')->where('user_id', $id)->first();
        $userPrivileges = $userPrivilegesRow && $userPrivilegesRow->option
            ? explode(',', $userPrivilegesRow->option)
            : [];

        // Departments
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $selectedDeptIds = $user->departments()->pluck('departments.id')->toArray();

        return view('admin.user.privilege', compact('user', 'userPrivileges', 'departments', 'selectedDeptIds'));
    }

    public function privilege_store(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        // --- Privileges (accept CSV or array) ---
        $rawPriv = $request->input('privileges', []);
        $privIds = is_array($rawPriv) ? $rawPriv : explode(',', (string)$rawPriv);
        $privIds = array_values(array_unique(array_map('intval', $privIds)));
        $privCsv = implode(',', $privIds);

        $priv = UserPrivilege::firstOrNew(['user_id' => $user->id]);
        $priv->option = $privCsv;
        $priv->full_or_partial = $priv->full_or_partial ?? 0;
        $priv->save();

        // --- Departments (accept array or CSV) ---
        $deptIds = $request->input('departments', []);
        if (!is_array($deptIds)) {
            $deptIds = explode(',', (string)$deptIds);
        }
        $deptIds = array_values(array_unique(array_map('intval', $deptIds)));

        // Validate IDs actually exist
        $validDeptIds = Department::whereIn('id', $deptIds)->pluck('id')->toArray();

        // Sync pivot
        $user->departments()->sync($validDeptIds);

        return response()->json([
            'success'           => true,
            'privileges_saved'  => $privIds,
            'departments_saved' => $validDeptIds,
        ]);
    }
}
