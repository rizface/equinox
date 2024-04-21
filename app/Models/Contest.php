<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class Contest extends Model
{
    use HasFactory, HasUuids;

    protected $table = "contests";
    protected $primaryKey = "id";
    protected $fillable = [
        "admin_id", "title", "started_at", "ended_at"
    ];

    public function Questions() {
        return $this->hasMany(Question::class);
    }

    public function ValidQuestions() {
        return $this->hasMany(Question::class)
        ->where("is_valid", true);
    }

    public function GetNumberOfQuestions() {
        return Question::where("contest_id", $this->id)->count();
    }

    public function GetNumberOfValidQuestions() {
        return Question::where("contest_id", $this->id)
        ->where("is_valid", true)
        ->count();
    }

    public function ThisIsMyContest() {
        return $this->admin_id == Auth::guard("admin")->user()->id;
    }

    public function Validate() {
        if(!$this->title || $this->title == "") {
            return "Title is required";
        }

        return null;
    }

    public function GetNumberOfParticipants() {
        return Participant::where("contest_id", $this->id)->count();
    }

    public function Joined() {
        return Participant::where("coder_id", Auth::guard("coder")->user()->id)
        ->where("contest_id", $this->id)
        ->count() > 0;
    }

    public function IsCompleteByCurrentUser() {
        return CoderCompleteCourse::where("coder_id", Auth::guard("coder")->user()->id)
        ->where("course_id", $this->id)
        ->count() > 0;
    }

    public function GetLeaderboard() {
        $questionIds = Question::where("contest_id", $this->id)->pluck("id");
        return CoderSolvedQuestion::whereIn("question_id", $questionIds)
        ->select("coder_id", DB::raw("count(*) as total"))
        ->groupBy("coder_id")
        ->orderBy("total", "desc")
        ->get();
    }
}
