<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserQueryRequest;
use App\Http\Requests\Api\User\UserStoreRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class UserController extends Controller {

    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * User list
     *
     * @OA\Get(
     *    path="/v1/user",
     *    operationId="v1.user.getList",
     *    tags={"Пользователи"},
     *    summary="Api: Список пользователей",
     *    description="Список пользователей",
     *    security={{"apiKey": {} }},
     *
     *    @OA\Parameter(name="q", description="Поиск по совпадению", example="Test", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="status", description="Статус", in="query", required=false, allowEmptyValue=true, schema={"type": "integer", "enum": {1, 2}, "default": 1}),
     *    @OA\Parameter(name="page", description="Номер страницы", in="query", required=false, example="1", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="limit", description="Записей на страницу", in="query", required=false, example="10", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="order", description="Соротировка по полю", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"title", "status", "created"}, "default": "title"}),
     *    @OA\Parameter(name="reverse", description="Реверс сортировки", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"asc", "desc"}, "default": "asc"}),
     *
     *    @OA\Response(response=200, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Список пользователей", type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *      )
     *    ),
     *    @OA\Response(response=400, description="Bad Request",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=401, description="Unauthenticated",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=500, description="server not available",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *  )
     */
    public function index(UserQueryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $list = $this->userService->index($data);

            DB::commit();

            return UserResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * User By ID
     *
     * @OA\Get(
     *   path="/v1/user/{id}",
     *   operationId="v1.user.show",
     *   tags={"Пользователи"},
     *   summary="Api: Просмотр отдельного пользователя",
     *   description="Просмотр отдельного пользователя",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID пользователя", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Пользователь", ref="#/components/schemas/UserResource"),
     *       examples={
     *         @OA\Examples(example="Some user", summary="Some user",
     *           value={"id":1, "name":"Test User", "email":"test@test.com", "status":1, "created": "2024-03-01 11:00:00", "updated": "2024-03-01 11:00:00"}
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad Request",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=401, description="Unauthenticated",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=404, description="Not found",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     *   @OA\Response(response=500, description="server not available",
     *     @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *   ),
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $item =  $this->userService->show($id);

            DB::commit();

            return UserResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add user
     *
     * @OA\Post(
     *    path="/v1/user",
     *    operationId="v1.user.store",
     *    tags={"Пользователи"},
     *    summary="Api: Добавить пользователя",
     *    description="Добавить пользователя",
     *    security={{"apiKey": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные нового пользователя",
     *      @OA\JsonContent(
     *        required={"name", "email", "password", "password_confirmation"},
     *        @OA\Property(property="name", title="ФИО", nullable="false", type="string"),
     *        @OA\Property(property="email", title="Логин (E-mail)", nullable="false", type="string"),
     *        @OA\Property(property="password", title="Пароль", nullable="false", type="string"),
     *        @OA\Property(property="password_confirmation", title="Пароль еще раз", nullable="false", type="string"),
     *        @OA\Property(property="status", title="Статус", nullable="true", type="integer"),
     *        examples={
     *          @OA\Examples(example="Active user", summary="Active user",
     *            value={"name":"Test 1", "email":"tets1@test.com", "password":"qwerty", "password_confirmation": "qwerty", "status":1}),
     *          @OA\Examples(example="Blocked user", summary="Blocked user",
     *            value={"name":"Test 2", "email":"tets1@test.com", "password":"qwerty", "password_confirmation": "qwerty", "status":2})
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Пользователь", ref="#/components/schemas/UserResource")
     *      )
     *    ),
     *    @OA\Response(response=400, description="Bad Request",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=401, description="Unauthenticated",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=500, description="server not available",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *  ),
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->userService->store($data);

            DB::commit();

            return UserResource::make($item)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update User
     *
     * @OA\Put(
     *     path="/v1/user/{id}",
     *     operationId="v1.user.update",
     *     tags={"Пользователи"},
     *     summary="Api: Обновить пользователя",
     *     description="Обновить пользователя",
     *     security={{"apiKey": {} }},
     *
     *     @OA\Parameter(name="id", description="ID пользователя", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные пользователя",
     *       @OA\JsonContent(
     *         @OA\Property(property="name", title="ФИО", nullable="true", type="string"),
     *         @OA\Property(property="password", title="Пароль", nullable="true", type="string"),
     *         @OA\Property(property="password_confirmation", title="Пароль еще раз", nullable="true", type="string"),
     *         @OA\Property(property="status", title="Статус", nullable="true", type="integer"),
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Пользователь", ref="#/components/schemas/UserResource")
     *       )
     *     ),
     *     @OA\Response(response=400, description="Bad Request",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=404, description="Not found",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *     @OA\Response(response=500, description="server not available",
     *       @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *     ),
     *   ),
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->userService->update($id, $data);

            return UserResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Delete USer
     *
     * @OA\Delete(
     *    path="/v1/user/{id}",
     *    operationId="v1.user.destroy",
     *    summary="Api: Удаление пользователя",
     *    description="Удаление пользователя",
     *    tags={"Пользователи"},
     *    security={{"apiKey": {} }},
     *
     *    @OA\Parameter(name="id", description="ID пользователя", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *    @OA\Response(response=200, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Простой ответ", ref="#/components/schemas/MessageResource")
     *      )
     *    ),
     *    @OA\Response(response=400, description="Bad Request",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=401, description="Unauthenticated",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *    @OA\Response(response=500, description="server not available",
     *      @OA\JsonContent(@OA\Property(property="data", title="Ответ с ошибкой", ref="#/components/schemas/ErrorResource"))
     *    ),
     *  )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->userService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => 'Пользователь удален']])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
