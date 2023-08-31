<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\DateHelper;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'sur_name',
        'given_name',
        'slug',
        'phone',
        'slogan',
        'email',
        'stu_code',
        'date_of_birth',
        'provider',
        'provider_id',
        'role',
        'avatar',
        'password',
        'unit_id',
        'class_id',
        'ttsv_id',
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
        'email_verified_at',
        'created_at',
        'updated_at',
        'provider_id',
        'ttsv_id',
        'class_id'
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

    protected static function boot()
    {
        parent::boot();

        // event get data
        static::retrieved(function ($model) {
            $model->classInfo;
            $model->unit;
            // $model->created_time = DateHelper::make($model->created_at);
            // $model->updated_time = DateHelper::make($model->updated_at);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'fullname',
                'separator' => '',
                'onUpdate' => true
            ]
        ];
    }



    public function scopeGetWhereSlug(Builder $query, string $slug)
    {
        return $query->firstWhere('slug', $slug);
    }

    public function posted(): HasMany
    {
        return $this->hasMany(Forum::class, 'user_id', 'id');
    }

    public function classInfo(): HasOne
    {
        return $this->hasOne(ClassInfo::class, 'id', 'class_id');
    }

    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function approval(): HasMany
    {
        return $this->hasMany(Approval::class, 'user_id', 'id');
    }

    public function approvalRequest(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'user_id', 'id');
    }

    public function approved(): HasMany
    {
        return $this->hasMany(ApprovalRequestStatus::class, 'user_id', 'id');
    }

}