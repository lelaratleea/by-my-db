<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    function list(User $user): bool
    {
        return $user->isAdministrator();
    }

    function view(User $user, User $target): bool
    {
        return $user->isAdministrator();
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user, User $target): bool
    {
        return $user->isAdministrator();
    }

    // เปลี่ยน role ได้เฉพาะ ADMIN และห้ามเปลี่ยนของตัวเอง
    function updateRole(User $user, User $target): bool
    {
        return $user->isAdministrator() && !$user->is($target);
    }

    // ลบได้เฉพาะ ADMIN และห้ามลบตัวเอง
    function delete(User $user, User $target): bool
    {
        return $user->isAdministrator() && !$user->is($target);
    }
}