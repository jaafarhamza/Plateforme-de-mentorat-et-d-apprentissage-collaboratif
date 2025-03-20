<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function validateRegistration(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required', 
                'string', 
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
            'role' => 'sometimes|string|in:admin,mentor,student'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function register(array $data)
    {
        $validatedData = $this->validateRegistration($data);
        return $this->authRepository->register($validatedData);
    }

    public function validateLogin(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function login(array $credentials)
    {
        $validatedCredentials = $this->validateLogin($credentials);
        $user = $this->authRepository->login($validatedCredentials);

        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        return $user;
    }

    public function logout($userId)
    {
        return $this->authRepository->logout($userId);
    }

    public function getUserDetails($userId)
    {
        return $this->authRepository->getUserById($userId);
    }

    public function refreshToken($userId)
    {
        return $this->authRepository->refreshToken($userId);
    }
}