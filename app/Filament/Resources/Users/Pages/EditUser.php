<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\VerificationStatus;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Override;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->visible(
                    fn() => $this->record->verification_status === VerificationStatus::Approved
                )
                ->submit('save'),

            Action::make('approved')
                ->label('Terima')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => in_array(
                    $this->record->verification_status,
                    [
                        VerificationStatus::Pending,
                        VerificationStatus::Rejected,
                    ],
                    true
                ))
                ->requiresConfirmation()
                ->action(fn() => $this->record->update([
                    'verification_status' => VerificationStatus::Approved,
                ])),

            Action::make('rejected')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(
                    fn() => $this->record->verification_status === VerificationStatus::Pending
                )
                ->requiresConfirmation()
                ->action(fn() => $this->record->update([
                    'verification_status' => VerificationStatus::Rejected,
                ])),

            Action::make('cancel')
                ->label('Batal')
                ->color('gray')
                ->url(url()->previous()),
        ];
    }
}
