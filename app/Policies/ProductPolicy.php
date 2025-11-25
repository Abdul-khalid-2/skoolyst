<?php
// app/Policies/ProductPolicy.php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'school-admin', 'shop-owner']);
    }

    public function view(User $user, Product $product)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('shop-owner')) {
            return $product->shop->user_id === $user->id;
        }

        if ($user->hasRole('school-admin')) {
            return $product->school_id && $user->schools->contains('id', $product->school_id);
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['super-admin', 'school-admin', 'shop-owner']);
    }

    public function update(User $user, Product $product)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('shop-owner')) {
            return $product->shop->user_id === $user->id;
        }

        if ($user->hasRole('school-admin')) {
            return $product->school_id &&
                $user->schools->contains('id', $product->school_id) &&
                $product->association?->can_manage_products;
        }

        return false;
    }

    public function delete(User $user, Product $product)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($user->hasRole('shop-owner')) {
            return $product->shop->user_id === $user->id;
        }

        if ($user->hasRole('school-admin')) {
            return $product->school_id &&
                $user->schools->contains('id', $product->school_id) &&
                $product->association?->can_manage_products;
        }

        return false;
    }

    public function updateStock(User $user, Product $product)
    {
        return $this->update($user, $product);
    }
}
