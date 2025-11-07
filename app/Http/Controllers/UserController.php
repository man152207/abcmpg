<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{

    public function dashboard()
    {
        try {
            return view('user.dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function login_form()
    {
        try {
            return view('user.login');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function register_form()
    {
        try {
            return view('user.register');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function register(Request $request)
    {
        try {
            // dd($request->name);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'max:255'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
            ]);

            return redirect('/dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'password' => ['required', 'string', 'max:255'],
            ]);
            Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));

            return redirect('/dashboard');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect('/');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function search(Request $request) {
        $query = $request->search;
        $users = Admin::where('phone', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%")
                    ->select('id', 'name', 'phone')
                    ->limit(10)
                    ->get();

        return response()->json($users);
    }
}
