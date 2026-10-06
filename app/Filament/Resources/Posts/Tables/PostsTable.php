<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(30)
                    ->searchable()
                    ->wrap()
                    ->tooltip(fn($record) => $record->title),
                TextColumn::make('author.name')
                    ->label('Penulis'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(PostStatus $state): string => $state->getLabel())
                    ->color(fn(PostStatus $state): string => $state->getColor())
                    ->searchable(),

                TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->badge()
                    // ->formatStateUsing(fn(PostVisibility $state): string => $state->getLabel())
                    // ->color(fn(PostVisibility $state): string => $state->getColor())
                    ->searchable(),

                TextColumn::make('segments.name')
                    ->label('Segmen')
                    ->badge()
                    ->separator(',')
                    ->placeholder('Tidak ada segment'),


                TextColumn::make('published_at')
                    ->label('Tanggal Publikasi')
                    ->date('d M Y'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
