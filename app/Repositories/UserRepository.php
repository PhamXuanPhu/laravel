<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Exception;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        try {
            return Cache::remember('users', 10, function () {
                return User::all();
            });
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }

    public function filterUsers(array $filter)
    {
        try {
            return Cache::remember('filter_users' . serialize($filter), 10, function () use ($filter) {
                return User::query()->filter($filter)->get();
            });
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }

    public function getUserById($id)
    {
        try {
            return Cache::remember('user_' . $id, 10, function () use ($id) {
                return User::find($id);
            });
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }

    public function createUser(array $user)
    {
        try {
            $user = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);
            // Cache::put('user_' . $user->id, $user, 10);
            return $user;
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }

    public function updateUser(User $user, array $data)
    {
        try {
            return Cache::remember('user_' . $user->id, 10, function () use ($user, $data) {
                return $user->update($data);
            });
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }

    public function deleteUser(User $user)
    {
        try {
            Cache::forget('user_' . $user->id);
            return $user->delete();
        } catch (Exception $e) {
            return null;
            // throw $e;
        }
    }
}
