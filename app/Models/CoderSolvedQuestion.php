<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoderSolvedQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $table = "coder_solved_questions";
    protected $fillable = [
        "coder_id",
        "question_id",
    ];
}
