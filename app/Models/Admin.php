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
    protected $fillable = ["name", "username", "password", "is_valid", "validate_at", "invalidate_at"];

    public function ValidateAdmin() {
        $this->is_valid = true;
        $this->validate_at = now();
        $this->save();
    }

    public function InvalidateAdmin() {
        $this->is_valid = false;
        $this->invalidate_at = now();
        $this->save();
    }
}
