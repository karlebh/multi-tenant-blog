<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\JsonResponse;

trait MethodHelper
{
    use ResponseTrait;

    private function findTenant(int $tenant_id)
    {
        $tenant =  User::find($tenant_id);

        if (! $tenant) {
            return $this->badRequestResponse('This tenant does not exist');
        }

        return $tenant;
    }
}
