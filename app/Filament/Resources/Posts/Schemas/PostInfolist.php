<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('title')
                            ->label('')
                            ->size('xl')
                            ->weight('bold')
                            ->columnSpanFull(),

                        TextEntry::make('excerpt')
                            ->label('')
                            ->color('gray')
                            ->columnSpanFull(),

                        ImageEntry::make('featured_image')
                            ->label('Gambar Utama')
                            ->getStateUsing(
                                fn($record) => $record->getFirstMediaUrl('featured_image') ?: null
                            )
                            ->imageHeight(320)
                            ->placeholder('Tidak ada gambar')
                            ->columnSpanFull(),

                        TextEntry::make('content')
                            ->label('')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Postingan')
                    ->schema([
                        TextEntry::make('author.name')
                            ->label('Penulis')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(
                                fn($state) => $state instanceof PostStatus
                                    ? $state->getLabel()
                                    : PostStatus::from($state)->getLabel()
                            )
                            ->color(
                                fn($state) => $state instanceof PostStatus
                                    ? $state->getColor()
                                    : PostStatus::from($state)->getColor()
                            ),

                        TextEntry::make('visibility')
                            ->label('Visibilitas')
                            ->badge()
                            ->formatStateUsing(
                                fn($state) => $state instanceof PostVisibility
                                    ? $state->getLabel()
                                    : PostVisibility::from($state)->getLabel()
                            )
                            ->color(
                                fn($state) => $state instanceof PostVisibility
                                    ? $state->getColor()
                                    : PostVisibility::from($state)->getColor()
                            ),

                        TextEntry::make('published_at')
                            ->label('Dipublikasikan')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Belum dipublikasikan'),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable()
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d F Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d F Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
