<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function GetNumberOfQuestions() {
        return Question::where("contest_id", $this->id)->count();
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
}
