<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum IsActive: string implements HasLabel, HasColor, HasIcon
{
    case Active = "1";
    case Inactive = "0";

    public static function fromBool(bool $value): self
    {
        return $value ? self::Active : self::Inactive;
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Nonaktif',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Active => Heroicon::OutlinedCheckCircle,
            self::Inactive => Heroicon::OutlinedXCircle,
        };
    }
}
