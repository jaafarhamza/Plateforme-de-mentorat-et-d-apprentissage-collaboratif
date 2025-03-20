<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Spatie\Permission\Models\Role;

class AuthRepository implements AuthRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(array $data)
    {
        // Create user
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        if (isset($data['role'])) {
            $role = Role::firstOrCreate(['name' => $data['role']]);
            $user->assignRole($role);
        }

        return $user;
    }

    public function login(array $credentials)
    {
        $user = $this->model->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    public function logout($userId)
    {
        $user = $this->model->findOrFail($userId);
        $user->tokens()->delete();
        return true;
    }

    public function getUserById($userId)
    {
        return $this->model->with('roles', 'permissions')->findOrFail($userId);
    }

    public function refreshToken($userId)
    {
        $user = $this->model->findOrFail($userId);
        $user->tokens()->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }
}