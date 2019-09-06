<?php

namespace App\Models\Auth;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    //
    protected $fillable = ['name', 'display_name', 'description'];
}
