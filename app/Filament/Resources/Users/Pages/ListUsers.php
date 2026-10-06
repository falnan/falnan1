<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending_review' => Tab::make('Menunggu Verifikasi')
                ->icon('heroicon-o-bell')
                ->badge(function () {
                    $count = User::query()
                        ->where('verification_status', VerificationStatus::Pending)
                        ->count();
                    return $count > 0 ? $count : null;
                })
                ->badgeColor('info')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('verification_status', VerificationStatus::Pending)
                ),
            'accepted' => Tab::make('Diterima')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('verification_status', VerificationStatus::Approved)
                ),
            'rejected' => Tab::make('Ditolak')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('verification_status', VerificationStatus::Rejected)
                ),
        ];
    }
}
