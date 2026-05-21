<?php

namespace App\Policies;

use App\Models\ShopSchoolAssociation;
use App\Models\User;

class ShopSchoolAssociationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'shop-owner']);
    }

    public function view(User $user, ShopSchoolAssociation $association): bool
    {
        return $user->hasRole('super-admin')
            || $user->id === $association->shop?->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'shop-owner']);
    }

    public function update(User $user, ShopSchoolAssociation $association): bool
    {
        // Shop owner can edit their own pending associations; super-admin can edit any
        return $user->hasRole('super-admin')
            || ($user->id === $association->shop?->user_id
                && ($association->status instanceof \BackedEnum
                    ? $association->status->value
                    : $association->status) === 'pending');
    }

    public function delete(User $user, ShopSchoolAssociation $association): bool
    {
        // Shop owner can delete their own associations; super-admin can delete any
        return $user->hasRole('super-admin')
            || $user->id === $association->shop?->user_id;
    }

    public function restore(User $user, ShopSchoolAssociation $association): bool
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, ShopSchoolAssociation $association): bool
    {
        return $user->hasRole('super-admin');
    }
}
