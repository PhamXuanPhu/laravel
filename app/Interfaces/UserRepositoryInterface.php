<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function filterUsers(array $filter);
    public function getUserById($id);
    public function createUser(array $user);
    public function updateUser(User $user, array $data);
    public function deleteUser(User $user);
}
