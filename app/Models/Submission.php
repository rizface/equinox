<?php

namespace App\Models;

use App\Traits\UtilsTrait;
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
        "is_correct",
        "message",
    ];

    public function DecodeParamsAndReturnValue() {
        $this->params = json_decode($this->params);
        $this->expected_return_values = json_decode($this->expected_return_values);
    }

    private function GetExpectedReturnValues() {
        return $this->expected_return_values->return;
    }

    public function CheckAnswer($result) {
        $isAnswerAccepted = false;
        $status = null;
        if ($result == $this->GetExpectedReturnValues()) {
            $isAnswerAccepted = true;
        }

        if ($isAnswerAccepted) {
            $status = "accepted";
        }

        if (!$isAnswerAccepted) {
            $status = "wrong answer";
        }

        $this->is_correct = $isAnswerAccepted;
        $this->result = json_encode(["return" => $result]);
        $this->status = $status;
    }

    public function GetCoderAnswer() {
        return json_decode($this->result)->return;
    }
}
