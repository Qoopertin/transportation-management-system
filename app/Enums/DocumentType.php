<?php

namespace App\Enums;

enum DocumentType: string
{
    case POD = 'pod';
    case BOL = 'bol';
    case PHOTO = 'photo';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::POD => 'Proof of Delivery',
            self::BOL => 'Bill of Lading',
            self::PHOTO => 'Photo',
            self::OTHER => 'Other',
        };
    }
}
