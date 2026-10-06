<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Segment;
use Illuminate\Auth\Access\HandlesAuthorization;

class SegmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Segment');
    }

    public function view(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('View:Segment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Segment');
    }

    public function update(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('Update:Segment');
    }

    public function delete(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('Delete:Segment');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Segment');
    }

    public function restore(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('Restore:Segment');
    }

    public function forceDelete(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('ForceDelete:Segment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Segment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Segment');
    }

    public function replicate(AuthUser $authUser, Segment $segment): bool
    {
        return $authUser->can('Replicate:Segment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Segment');
    }

}