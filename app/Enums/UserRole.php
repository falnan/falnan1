<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum UserRole: string implements HasLabel, HasColor, HasIcon
{
    case SuperAdmin = 'super_admin';
    case Author = 'author';
    case Member = 'member';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::SuperAdmin => 'Administrator',
            self::Author => 'Penulis',
            self::Member => 'Anggota',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::SuperAdmin => 'success',
            self::Author => 'success',
            self::Member => 'info',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::SuperAdmin => Heroicon::OutlinedShieldCheck,
            self::Author => Heroicon::OutlinedPencilSquare,
            self::Member => Heroicon::OutlinedUser,
        };
    }
}
