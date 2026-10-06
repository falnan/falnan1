<?php

namespace App\Filament\Resources\Segments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SegmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Segmen')
                    ->required(),
                TextInput::make('slug')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->label('Deskripsi'),
            ]);
    }
}
