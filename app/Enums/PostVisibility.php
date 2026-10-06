<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PostVisibility: string implements HasLabel, HasColor, HasIcon
{
    case Public = 'public';
    case Authenticated = 'authenticated';
    case Restricted = 'restricted';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Public => 'Publik',
            self::Authenticated => 'Pengguna Terdaftar',
            self::Restricted => 'Terbatas',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Public => 'info',
            self::Authenticated => 'warning',
            self::Restricted => 'danger',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Public => Heroicon::OutlinedGlobeAlt,
            self::Authenticated => Heroicon::OutlinedLockOpen,
            self::Restricted => Heroicon::OutlinedLockClosed,
        };
    }
}
