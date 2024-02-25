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
        "for_all_coder",
        "for_all_course_admin",
        "for_admin_id",
        "for_coder_id",
        "send",
        "title",
        "message"
    ];

    public function MarkAsSeen() {
        $this->seen = true;
        $this->save();
    }
}
