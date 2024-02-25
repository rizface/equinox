<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $table = "notifications";
    protected $fillable = [
        "from_id",
        "to_id",
        "question_id",
        "seen",
        "title",
        "message"
    ];

    public function MarkAsSeen() {
        $this->seen = true;
        $this->save();
    }

    public function Sender() {
        return $this->belongsTo(Coder::class, "from_id");
    }

    public function Question() {
        return $this->belongsTo(Question::class, "question_id");
    }
}
