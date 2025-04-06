<?php

namespace App\Http\Controllers\API;

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
    use ResponseTrait;
    public function userRegister(CreateUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'profile_photo' => $request->profile_photo,
                'password' => Hash::make($request->string('password')),
            ]);

            if (! $user) {
                $this->badRequestResponse('Could not create user');
            }

            event(new Registered($user));

            Auth::login($user);

            $token = $user->createToken('API TOKEN')->plainTextToken;
            $token = $this->cleanToken($token);

            return $this->successResponse("Registration successful", [
                'token' => $token,
                'user' => $user,
            ], 201);

            return $this->successResponse('User created successfully', ['user' => $user]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function userLogin(UserLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)
                ->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->notFoundResponse('This user does not exist. please register');
            }

            if (! Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
                return $this->badRequestResponse('Invalid credentials', 403);
            }

            $token =
                $this->cleanToken(
                    $user->createToken($user->email)->plainTextToken
                );

            return $this->successResponse('Login successful', [
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function userLogout(Request $request)
    {
        try {
            $user = $request->user();

            $user->tokens()->delete();

            return $this->successResponse('Logout succesful');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function AdminLogin(Request $request)
    {
        try {
            $admin = Admin::where('email', $request->email)
                ->first();

            if (! $admin || ! Hash::check($request->password, $admin->password)) {
                return $this->notFoundResponse('This admin does not exist. please register');
            }

            if (! Auth::attempt(['email' => $admin->email, 'password' => $request->password])) {
                return $this->badRequestResponse('Invalid credentials', 403);
            }

            $token =
                $this->cleanToken(
                    $admin->createToken($admin->email)->plainTextToken
                );

            return $this->successResponse('Login successful', [
                'token' => $token,
                'admin' => $admin
            ]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }
    public function AdminRegsiter(AdminRegisterRequest $request)
    {
        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'profile_photo' => $request->profile_photo,
                'password' => Hash::make($request->string('password')),
            ]);

            if (! $admin) {
                $this->badRequestResponse('Could not create admin');
            }

            event(new Registered($admin));

            Auth::login($admin);

            $token = $admin->createToken('API TOKEN')->plainTextToken;
            $token = $this->cleanToken($token);

            return $this->successResponse("Registration successful", [
                'token' => $token,
                'user' => $admin,
            ], 201);

            return $this->successResponse('Admin created successfully', ['admin' => $admin]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function AdminLogout(Request $request)
    {
        try {
            $user = $request->user();

            $user->tokens()->delete();

            return $this->successResponse('Logout succesful');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    private function cleanToken($token)
    {
        return (explode('|', $token))[1];
    }
}
