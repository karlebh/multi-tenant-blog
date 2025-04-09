<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function adminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function adminRegisterForm()
    {
        return view('auth.admin-register');
    }

    public function userRegister(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_photo' => $request->profile_photo,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $user->blog()->create(['name' => $user->name . "'s blog"]);

        return redirect()->route('blogs.index', ['tenant' => $user->load('blog')]);
    }

    public function userLogin(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if (
            ! Hash::check($request->password, $user->password) ||
            ! Auth::attempt(['email' => $user->email, 'password' => $request->password])
        ) {
            return redirect()->back()->with(['message' => 'Invalid credentials']);
        }

        return redirect()->route('blogs.index', ['tenant' => $user]);
    }

    public function AdminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->with(['message' => 'Invalid credentials.']);
    }

    public function AdminRegsiter(AdminRegisterRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_photo' => $request->profile_photo,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($admin));

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form');
    }
}
