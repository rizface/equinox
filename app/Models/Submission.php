<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = "id";
    protected $fillable = [
        "batch_token",
        "submission_token",
        "question_id",
        "coder_id",
        "lang_id",
        "source_code",
        "params",
        "expected_return_values",
        "status",
        "result",
        "is_correct"
    ];

    public function DecodeParamsAndReturnValue() {
        $this->params = json_decode($this->params);
        $this->expected_return_values = json_decode($this->expected_return_values);
    }
}
