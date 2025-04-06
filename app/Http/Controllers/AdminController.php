<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function dashboard()
    {
        $users = User::latest()->paginate(20);

        return view('admins.dashboard')->with(['users' => $users]);
    }

    public function approveUser(User $tenant)
    {
        $tenant->is_approved = true;
        $tenant->save();

        return redirect()->back()->with(['message' => 'Tenant approved succesfully']);
    }

    public function revokeUserApproval(User $tenant)
    {
        $tenant->is_approved = false;
        $tenant->save();

        return redirect()->back()->with(['message' => 'Tenant approval revoked succesfully']);
    }
}
