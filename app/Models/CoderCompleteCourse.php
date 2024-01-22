<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoderCompleteCourse extends Model
{
    use HasFactory, HasUuids;

    protected $table = "coder_complete_courses";
    protected $fillable = [
        "coder_id", "course_id"
    ];
}
