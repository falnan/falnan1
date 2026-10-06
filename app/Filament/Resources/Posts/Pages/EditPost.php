<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Enums\PostStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Posts\PostResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Override;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // AUTHOR: Draft → Pending Review


            // SUPER ADMIN: Pending Review → Published
            Action::make('publish')
                ->label('Publikasikan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function (): bool {
                    /** @var User|null $user */
                    $user = Auth::user();

                    return $user?->hasRole(UserRole::SuperAdmin->value)
                        && in_array($this->record->status, [
                            PostStatus::Draft,
                            PostStatus::PendingReview,
                        ], true);
                })
                ->action(function (): void {
                    $this->record->update([
                        'status' => PostStatus::Published,
                        'published_at' => now(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Tulisan berhasil dipublikasikan')
                        ->send();
                }),

            // SUPER ADMIN: Pending Review → Draft
            Action::make('returnToDraft')
                ->label('Kembalikan ke Draft')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(function (): bool {
                    /** @var User|null $user */
                    $user = Auth::user();

                    return $user?->hasRole(UserRole::SuperAdmin->value)
                        && $this->record->status === PostStatus::PendingReview;
                })
                ->action(function (): void {
                    $this->record->update([
                        'status' => PostStatus::Draft,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Tulisan dikembalikan ke draft')
                        ->send();
                }),

            // SUPER ADMIN: Published → Archived
            Action::make('archive')
                ->label('Arsipkan')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(function (): bool {
                    /** @var User|null $user */
                    $user = Auth::user();

                    return $user?->hasRole(UserRole::SuperAdmin->value)
                        && $this->record->status === PostStatus::Published;
                })
                ->action(function (): void {
                    $this->record->update([
                        'status' => PostStatus::Archived,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Tulisan berhasil diarsipkan')
                        ->send();
                }),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Draf')
                ->submit('save'),

            Action::make('submitReview')
                ->label('Ajukan Review')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function (): bool {
                    /** @var User|null $user */
                    $user = Auth::user();

                    return $this->record->status === PostStatus::Draft
                        && $user?->hasRole(UserRole::Author->value);
                })
                ->action(function (): void {
                    $this->record->update([
                        'status' => PostStatus::PendingReview,
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Tulisan diajukan untuk review')
                        ->send();
                }),

        ];
    }
}
