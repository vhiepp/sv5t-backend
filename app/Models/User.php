<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'sur_name',
        'given_name',
        'phone',
        'class',
        'email',
        'stu_code',
        'date_of_birth',
        'provider',
        'provider_id',
        'role',
        'avatar',
        'password',
        'unit_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'unit_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posted(): HasMany
    {
        return $this->hasMany(Forum::class);
    }

    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'leader_user_id');
    }

    public function approval(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    public function approvalRequest(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function approved(): HasMany
    {
        return $this->hasMany(ApprovalRequestStatus::class);
    }

}