<?php

namespace App\Models;
//import 

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_role_id', //FK
        'manager_id', //FK
        // 'department_id', //FK
        'created_by_user_id', //FK
        'username',
        'email',
        'password',
        // 'first_name',
        // 'middle_name',
        // 'last_name',
        // 'contact_no',
        // 'address',
        // 'date_of_birth',
        // 'hire_date',
        // 'position',
        // 'salary',
        // 'employment_status',
        // 'termination_date',
        // 'resignation_date',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'date_of_birth' => 'date',
        // 'hire_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',

        'active' => 'boolean',
        'locked' => 'boolean',
        'disabled' => 'boolean',
        // 'termination_date' => 'date',
        // 'resignation_date' => 'date',
        // 'salary' => 'decimal:2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELATIONSHIPS
    public function refreshTokens(): HasMany
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function personalInformation(): HasOneThrough
    {
        return $this->hasOneThrough(PersonalInformation::class, User::class, 'id', 'user_id');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    // SCOPES

    public function scopeUnremembered($query)
    {
        return $query->where('remember_token', null);
    }
}
