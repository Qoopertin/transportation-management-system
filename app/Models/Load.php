<?php

namespace App\Models;

use App\Enums\LoadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Load extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'pickup_address',
        'delivery_address',
        'pickup_at',
        'delivery_at',
        'status',
        'assigned_driver_id',
        'notes',
    ];

    protected $casts = [
        'status' => LoadStatus::class,
        'pickup_at' => 'datetime',
        'delivery_at' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LoadDocument::class);
    }

    public function breadcrumbs(): HasMany
    {
        return $this->hasMany(DriverBreadcrumb::class)->orderBy('captured_at');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [LoadStatus::DELIVERED, LoadStatus::CANCELLED]);
    }

    public function scopeForDriver($query, int $driverId)
    {
        return $query->where('assigned_driver_id', $driverId);
    }
}
