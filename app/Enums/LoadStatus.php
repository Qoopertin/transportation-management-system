<?php

namespace App\Enums;

enum LoadStatus: string
{
    case PENDING = 'pending';
    case ASSIGNED = 'assigned';
    case EN_ROUTE = 'en_route';
    case ARRIVED_PICKUP = 'arrived_pickup';
    case LOADED = 'loaded';
    case IN_TRANSIT = 'in_transit';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ASSIGNED => 'Assigned',
            self::EN_ROUTE => 'En Route to Pickup',
            self::ARRIVED_PICKUP => 'Arrived at Pickup',
            self::LOADED => 'Loaded',
            self::IN_TRANSIT => 'In Transit',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'bg-gray-100 text-gray-800',
            self::ASSIGNED => 'bg-blue-100 text-blue-800',
            self::EN_ROUTE => 'bg-yellow-100 text-yellow-800',
            self::ARRIVED_PICKUP => 'bg-purple-100 text-purple-800',
            self::LOADED => 'bg-indigo-100 text-indigo-800',
            self::IN_TRANSIT => 'bg-orange-100 text-orange-800',
            self::DELIVERED => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
        };
    }
}
