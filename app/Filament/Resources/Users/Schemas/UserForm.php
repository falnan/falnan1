<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Select::make('roles')
                    ->label('Peran')
                    ->relationship('roles', 'id')
                    ->default(
                        fn() => Role::where('name', UserRole::Member->value)->value('id')
                    )
                    ->getOptionLabelUsing(
                        fn($value) => UserRole::tryFrom($value)?->getLabel() ?? $value
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => UserRole::from($record->name)->getLabel()
                    )
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('segments')
                    ->label('Segmen')
                    ->relationship('segments', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->minLength(8)
                    ->maxLength(255),

            ]);
    }
}
