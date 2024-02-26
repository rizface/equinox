<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionReport extends Model
{
    use HasFactory, HasUuids;

    protected $table = "question_reports";
    protected $fillable = [
        "question_id",
        "coder_id",
        "title",
        "description"
    ];
}
