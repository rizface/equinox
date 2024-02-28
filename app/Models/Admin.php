<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, HasUuids;
    
    protected $table = "admins";
    protected $primaryKey = "id";
    protected $fillable = ["name", "username", "password", "is_valid"];

    public function ValidateAdmin() {
        $this->is_valid = true;
        $this->save();
    }
}
