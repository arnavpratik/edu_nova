<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EngagementLog extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}