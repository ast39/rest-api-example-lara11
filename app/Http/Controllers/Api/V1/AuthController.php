<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserWrongAuthDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;


class AuthController extends Controller {

    protected UserService $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = $this->userService->create($data);

            DB::commit();

            return UserResource::make($user)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = $this->userService->getByEmail($data['email']);

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new UserWrongAuthDataException();
            }

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'token' => $token,
            ]);
        } catch(\Exception $e) {
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            return UserResource::make($user)->response();
        } catch(\Exception $e) {
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();

            return MessageResource::make($request)
                ->additional(['data' => ['msg' => 'Авторизация сброшена']])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
