<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Enums\PostStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'menunggu' => Tab::make('Menunggu Persetujuan')
                ->icon('heroicon-o-bell')
                ->badge(function (Builder $query) {
                    /** @var User|null $user */
                    $user = Auth::user();
                    $query = Post::query()
                        ->where('status', PostStatus::PendingReview);

                    if ($user?->hasRole(UserRole::Author->value)) {
                        $query->where('author_id', $user->id);
                    }

                    $count = $query->count();

                    return $count > 0 ? $count : null;
                })
                ->badgeColor('info')
                ->modifyQueryUsing(
                    function (Builder $query) {
                        $query->where('status', PostStatus::PendingReview);
                    }
                ),
        ];
    }
}
