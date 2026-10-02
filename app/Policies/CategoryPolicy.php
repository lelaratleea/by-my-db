<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $this->create($user);
    }

    function delete(User $user, Category $category): bool
    {
        // ให้แน่ใจว่ามี products_count
        $category->loadCount('products');

        return $this->update($user) && ($category->products_count === 0);
    }
}