<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheTimeline extends Model
{
    use HasFactory, HasUuids;

    protected $table = "cache_timeline";

    protected $fillable = ["coder_id", "question_id", "contest_id", "title", "type", "parent", "solved_at"];
}
