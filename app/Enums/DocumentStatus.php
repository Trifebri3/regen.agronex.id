<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case Draft = 'draft';
    case InProcess = 'in_process';
    case Registered = 'registered';
    case Issued = 'issued';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::InProcess => 'In Process',
            self::Registered => 'Registered',
            self::Issued => 'Issued',
            self::Expired => 'Expired',
        };
    }
}
