<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\VerificationStatus;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['verification_status'] = VerificationStatus::Approved->value;

        return $data;
    }
}
