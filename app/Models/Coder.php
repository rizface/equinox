<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Coder extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $table = "coders";
    protected $primaryKey = "id";
    protected $fillable = ["name", "username", "password", "nim"];

    public function AlreadyJoinThisCourse(string $courseId) {
        return Participant::where("contest_id", $courseId)
        ->where("coder_id", $this->id)
        ->count() > 0;
    }

    public function CountSolvedQuestionsPerDifficulty() {
        $result = DB::table("coder_solved_questions")
        ->select(DB::raw("questions.level, count(questions.id)"))
        ->join("questions", "questions.id", "=", "coder_solved_questions.question_id")
        ->groupBy("questions.level")
        ->where("coder_id", "=", $this->id)->get();

        return $result;
    }

    public function CountTotalSolvedQuestions() {
        $result = DB::table("coder_solved_questions")
        ->where("coder_id", "=",$this->id)
        ->count("id");

        return $result;
    }

    public function CountCompletedCourses() {
        return CoderCompleteCourse::where("coder_id", "=", $this->id)->count();
    }

    public function Timeline() {
        $result =  DB::table("coder_solved_questions")
        ->select(DB::raw("questions.*, coder_solved_questions.created_at as solved_at"))
        ->join("questions", "questions.id", "=", "coder_solved_questions.question_id")
        ->where("coder_id", "=", $this->id)
        ->orderBy("coder_solved_questions.created_at","desc")
        ->get();

        return $result;
    }

    public function Validate() {
        if ($this->name === "" || $this->name === null) {
            throw new Error("Name is required");
        }
        
        if ($this->username === "" || $this->username === null) {
            throw new Error("Username is required");
        }

        if ($this->nim === "" || $this->nim === null) {
            throw new Error("NIM is required");
        }
    }

    public function JoinedCourses() {
        return $this->hasMany(Participant::class, "coder_id", "id");
    }
}
