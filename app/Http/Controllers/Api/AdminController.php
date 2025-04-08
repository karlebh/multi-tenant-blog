<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\MethodTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use MethodTrait, ResponseTrait;

    public function approveUser(int $user_id)
    {
        $tenant = $this->findTenant($user_id);

        if ($tenant->is_approved) {
            return $this->successResponse('User already approved', ['user' => $tenant]);
        }

        $tenant->update(['is_approved' => true]);

        return $this->successResponse('User approved successfully', ['user' => $tenant->fresh()]);
    }

    public function revokeUserApproval(int $user_id)
    {
        $tenant = $this->findTenant($user_id);

        if (! $tenant->is_approved) {
            return $this->successResponse('User approval is already revoked', ['user' => $tenant]);
        }

        $tenant->update(['is_approved' => false]);

        return $this->successResponse('User approval revoked successfully', ['user' => $tenant->fresh()]);
    }
}
