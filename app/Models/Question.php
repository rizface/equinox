<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Error;
use Hamcrest\Type\IsBoolean;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "contest_id", "title", "description", "test_cases", "is_valid", "level"
    ];
    protected $primaryKey = "id";
    protected $table = "questions";

    public function Contest() {
        return $this->belongsTo(Contest::class);
    }

    public function DecodeParamsForView() {
        $decodedTestCases = [
            "params" => []
        ];

        $this->test_cases = json_decode($this->test_cases);
        foreach ($this->test_cases->params as $key => $params) {
            $newParams = [];
            foreach ($params as $key => $param) {
                if (!is_array($param)) {
                    $decodedParam = json_decode($param);
                
                    if (is_bool($param)) {
                        if ($param) {
                            $param = "true";
                        } else {
                            $param = "false";
                        }
                    } else if ($decodedParam !== null) {
                        $param = $decodedParam;
                    }
                }

                $newParams[$key] = $param;
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

    public function DecodeParams($forView = false) {
        $decodedTestCases = [
            "params" => []
        ];

        $this->test_cases = json_decode($this->test_cases);
        foreach ($this->test_cases->params as $key => $params) {
            $newParams = [];
            foreach ($params as $key => $param) {
                if (!is_array($param)) {
                    $decodedParam = json_decode($param);

                    if (is_bool($param)) {
                        if ($param) {
                            $param = "true";
                        } else {
                            $param = "false";
                        }
                    } else if ($decodedParam !== null) {
                        $param = $decodedParam;
                    } else if ($param[0] == "[" && $param[strlen($param) - 1] == "]") {
                        $param = str_replace(['[', ']'], '', $param);
                        $param = array_map('trim', explode(',', $param));
                    } 
                }

                $newParams[$key] = $param;
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

    public function LevelIs(String $level): bool {
        return $this->level == $level;
    }

    public function Invalidate() {
        $this->is_valid = false;
        $this->save();
    }

    public function GetReports() {
        return DB::table("question_reports")
        ->select(DB::raw("
        question_reports.id as report_id, question_reports.title as report_title, 
        question_reports.description as description, coders.id as reporter_id, coders.name as reporter_name, 
        questions.id as question_id, question_reports.created_at as report_time"))
        ->join("coders", "question_reports.coder_id", "=", "coders.id")
        ->join("questions", "question_reports.question_id", "=", "questions.id")
        ->where("question_id", $this->id)
        ->orderBy("report_time", "desc")
        ->get();
    }
}
