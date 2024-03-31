<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory, HasUuids, UtilsTrait;

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
        // special case for false / 'false'
        if (str_replace("'", "", $this->expected_return_values->return) == "false") {
            return false;
        }

        if (str_replace("'", "", $this->expected_return_values->return) == "true") {
            return true;
        }


        return $this->expected_return_values->return;
    }

    public function CheckAnswer($result) {
        $isAnswerAccepted = false;
        $status = null;
        $result = base64_decode($result);   

        $decodedResult = json_decode($result);
        if ($decodedResult !== null) {
            $result = $decodedResult;
        }
        
        if (is_bool($this->GetExpectedReturnValues())) {
            $result = boolval($result);
        }

        if ($result===$this->GetExpectedReturnValues()) {
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

    public function Question() {
        return $this->belongsTo(Question::class, "question_id", "id");
    }
}
