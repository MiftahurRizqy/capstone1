<?php

declare(strict_types=1);

namespace App\Services;

use Spatie\Permission\Models\Role;

class RolesService
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function getRolesDropdown(): array
    {
        return Role::pluck('name', 'id')->toArray();
    }
}
