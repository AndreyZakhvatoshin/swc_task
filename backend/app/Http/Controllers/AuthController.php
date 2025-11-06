<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\UseCases\CreateUserCase;
use App\UseCases\LoginUserCase;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, CreateUserCase $createUserCase): JsonResponse
    {
        $data = $request->toData();
        $user = $createUserCase($data);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request, LoginUserCase $loginUserCase): JsonResponse
    {
        $data = $request->toData();

        try {
            $token = $loginUserCase($data);
        } catch (InvalidCredentialsException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return response()->json(['token' => $token]);
    }
}
