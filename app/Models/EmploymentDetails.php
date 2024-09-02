<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'position',
        'salary',
        'hire_date',
        'start_date',
        'employment_status',
        'department_id',
        'termination_date',
        'resignation_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'start_date' => 'date',
        'termination_date' => 'date',
        'resignation_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
