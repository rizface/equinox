<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "contest_id", "title", "description", "test_cases"
    ];
    protected $primaryKey = "id";
    protected $table = "questions";

    public function Contest() {
        return $this->belongsTo(Contest::class);
    }

    public function DecodeParams() {
        $decodedTestCases = [
            "params" => []
        ];

        $this->test_cases = json_decode($this->test_cases);
        foreach ($this->test_cases->params as $key => $params) {
            $newParams = [];
            foreach ($params as $key => $param) {
                $newParams[$key] = json_decode($param);
            }
            array_push($decodedTestCases["params"],$newParams);
        }

        $this->test_cases = $decodedTestCases;

        $this->numberOfParams = 0; 
        foreach ($this["test_cases"]["params"][0] as $key => $value) {
            $this->numberOfParams++;
        }

        // dont count the return value
        $this->numberOfParams-=1;
    }

    public function Validate() {
        if (!$this->title || $this->title == "") {
            throw new Error("Title is required");
        }

        if(!$this->description || $this->description == "") {
            throw new Error("Description is required");
        }
    }

    public function JoinedTheCourse() {
        return Participant::where("contest_id",  $this->contest_id)
        ->where("coder_id", Auth::guard("coder")->user()->id)
        ->count() > 0;
    }

    public function IsSolvedByCurrentUser() {
        return CoderSolvedQuestion::where("question_id", $this->id)
        ->where("coder_id", Auth::guard("coder")->user()->id)
        ->count() > 0;
    }

    public function GetSolvers() {
        return $this->hasMany(CoderSolvedQuestion::class, "question_id", "id");
    }
}
