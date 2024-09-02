<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testing extends Model
{
    use HasFactory;

    
     
    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
