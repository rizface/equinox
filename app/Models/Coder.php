<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Coder extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $table = "coders";
    protected $primaryKey = "id";
    protected $fillable = ["name", "username", "password", "nim"];

}