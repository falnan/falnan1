<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Enums\UserRole;
use App\Filament\Resources\Posts\PostResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected static bool $canCreateAnother = false;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user?->hasRole(UserRole::Author->value)) {
            $data['author_id'] = $user->id;

            if (($data['visibility'] ?? null) === PostVisibility::Restricted->value) {
                abort(403);
            }
        }

        return $data;
    }

    protected function createWithStatus(PostStatus $status): void
    {
        $this->data['status'] = $status->value;
        $this->data['published_at'] = $status === PostStatus::Published
            ? now()
            : null;

        $this->create();
    }

    protected function getFormActions(): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        $actions = [
            $this->getCreateFormAction()
                ->label('Simpan Draf')
                ->icon('heroicon-o-document-text'),
        ];

        if ($user?->hasRole(UserRole::SuperAdmin->value)) {
            $actions[] = Action::make('publish')
                ->label('Publikasikan')
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->action(fn() => $this->createWithStatus(PostStatus::Published));
        }

        if ($user?->hasRole(UserRole::Author->value)) {
            $actions[] = Action::make('submitForReview')
                ->label('Ajukan Publikasi')
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->action(fn() => $this->createWithStatus(PostStatus::PendingReview));
        }

        $actions[] = $this->getCancelFormAction()
            ->label('Batal');

        return $actions;
    }
}
