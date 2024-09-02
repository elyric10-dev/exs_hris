<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['action', 'details', 'ip_address', 'user_agent', 'loggable_id', 'loggable_type'];


    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
