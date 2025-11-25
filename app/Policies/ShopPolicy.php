<?php
// app/Policies/ShopPolicy.php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'school-admin', 'shop-owner']);
    }

    public function view(User $user, Shop $shop)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('shop-owner')) {
            return $shop->user_id === $user->id;
        }

        if ($user->hasRole('school-admin')) {
            return $shop->isAssociatedWithSchool($user->schools->first());
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'school-admin', 'shop-owner']);
    }

    public function update(User $user, Shop $shop)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $shop->user_id === $user->id;
    }

    public function delete(User $user, Shop $shop)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $shop->user_id === $user->id;
    }

    public function manageAssociations(User $user, Shop $shop)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('school-admin')) {
            return $shop->isAssociatedWithSchool($user->schools->first());
        }

        return $shop->user_id === $user->id;
    }

    public function viewAssociations(User $user, Shop $shop)
    {
        return $this->view($user, $shop);
    }
}
