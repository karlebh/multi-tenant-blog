<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\Admin;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function userLoginForm()
    {
        return view('');
    }

    public function userRegisterForm()
    {
        return view('');
    }

    public function adminLoginForm()
    {
        return view('');
    }

    public function adminRegisterForm()
    {
        return view('');
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

        return redirect()->route('blogs.index', ['tenant_id' => $user->id]);
    }

    public function userLogin(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return redirect()->back()->with(['message' => 'This user does not exist. please register']);
        }

        if (! Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            return redirect()->back()->with(['message' => 'Invalid credentials']);
        }

        return redirect()->route('blogs.index', ['tenant_id' => $user->id]);
    }

    public function userLogout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return redirect()->route('login');
    }

    public function AdminLogin(Request $request)
    {
        $admin = Admin::where('email', $request->email)
            ->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return redirect()->back()->with(['message' => 'This admin does not exist.']);
        }

        if (! Auth::attempt(['email' => $admin->email, 'password' => $request->password])) {
            return redirect()->back()->with(['message' => 'Invalid credentials']);
        }

        return redirect()->route('');
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

        Auth::login($admin);

        return redirect()->route('');
    }

    public function AdminLogout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return redirect()->route('');
    }
}
