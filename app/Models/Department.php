<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }
     
    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
