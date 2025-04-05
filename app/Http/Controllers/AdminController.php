<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveUser(User $tenant)
    {
        $tenant->update(['is_approved' => true]);

        return view()->with(['message' => 'Tenant approved succesfully']);
    }

    public function revokeUserApproval(User $tenant)
    {
        $tenant->update(['is_approved' => false]);

        return view()->with(['message' => 'Tenant approval revoked succesfully']);
    }
}
