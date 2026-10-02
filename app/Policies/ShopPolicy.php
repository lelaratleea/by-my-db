<?php

namespace App\Policies;

use App\Models\User;

class ShopPolicy
{
    function list(User $user): bool
    {
        // ทุกคนที่ login ดูรายการได้
        return true;
    }

    function view(User $user): bool
    {
        return $this->list($user);
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $this->create($user);
    }

    function delete(User $user): bool
    {
        return $this->update($user);
    }
}