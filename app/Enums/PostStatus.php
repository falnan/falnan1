<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PostStatus: string implements HasLabel, HasColor, HasIcon
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Published = 'published';
    case Archived = 'archived';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::PendingReview => 'Menunggu Review',
            self::Published => 'Dipublikasikan',
            self::Archived => 'Diarsipkan',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::PendingReview => 'warning',
            self::Published => 'success',
            self::Archived => 'danger',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Draft => Heroicon::OutlinedDocument,
            self::PendingReview => Heroicon::OutlinedClock,
            self::Published => Heroicon::OutlinedCheckCircle,
            self::Archived => Heroicon::OutlinedArchiveBox,
        };
    }
}
