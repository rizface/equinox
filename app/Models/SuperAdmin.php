<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "name",
        "username",
        "password"
    ];
    
    protected $table = "superadmins";
}
