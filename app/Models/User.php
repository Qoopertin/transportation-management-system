<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignedLoads(): HasMany
    {
        return $this->hasMany(Load::class, 'assigned_driver_id');
    }

    public function currentLocation(): HasOne
    {
        return $this->hasOne(DriverLocation::class);
    }

    public function breadcrumbs(): HasMany
    {
        return $this->hasMany(DriverBreadcrumb::class);
    }

    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(LoadDocument::class, 'uploaded_by');
    }
}
