<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\IsActive;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->description('Informasi utama dan status akun pengguna.')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->weight('bold'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->icon('heroicon-o-envelope'),

                        TextEntry::make('is_active')
                            ->label('Status Akun')
                            ->badge()
                            ->state(fn($record) => IsActive::from((string) $record->is_active)),

                        TextEntry::make('verification_status')
                            ->label('Status Verifikasi')
                            ->badge(),
                    ])
                    ->columns(2),

                Section::make('Akses Pengguna')
                    ->description('Peran dan segmen yang dimiliki pengguna.')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Peran')
                            ->badge()
                            ->icon("heroicon-o-check-circle")
                            ->state(fn($record) => UserRole::from($record->roles->first()->name)),
                        // ->formatStateUsing(fn($state) => UserRole::tryFrom($state)?->getLabel() ?? $state)
                        // ->color(fn($state) => UserRole::tryFrom((string) $state)?->getColor())
                        // ->icon(fn($state) => UserRole::tryFrom((string) $state)?->getIcon()),

                        TextEntry::make('segments.name')
                            ->label('Segmen')
                            ->badge()
                            ->separator(',')
                            ->placeholder('Tidak memiliki segmen'),
                    ])
                    ->columns(2),

                Section::make('Informasi Sistem')
                    ->description('Informasi waktu pembuatan dan perubahan data.')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
