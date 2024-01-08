<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ["coder_id", "contest_id", "answered_question"];

    public function Course() {
        return $this->belongsTo(Contest::class, "contest_id");
    }

    public function Coder() {
        return $this->belongsTo(Coder::class, "coder_id");
    }
}
