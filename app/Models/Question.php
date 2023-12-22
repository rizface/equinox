<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $this->test_cases = json_decode($this->test_cases);
        $this->numberOfParams = 0; 

        foreach ($this->toArray()["test_cases"]->params[0] as $key => $value) {
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
}
