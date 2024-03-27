<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Enums\EUserRole;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\NotAuthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryQueryRequest;
use App\Http\Requests\Api\Category\CategoryStoreRequest;
use App\Http\Requests\Api\Category\CategoryUpdateRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\CategoryService;
use App\Http\Traits\Accessable;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class CategoryController extends Controller {

    use Accessable;


    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Category list
     *
     * @OA\Get(
     *    path="/v1/category",
     *    operationId="v1.category.getList",
     *    tags={"Категории"},
     *    summary="Api: Список категорий",
     *    description="Список категорий",
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
     *        @OA\Property(property="data", title="Список категорий", type="array", @OA\Items(ref="#/components/schemas/CategoryResource"))
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
    public function index(CategoryQueryRequest $request): JsonResponse
    {
        try {
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator'),
                Gate::allows('is-user')
            )) {
                throw new NotAuthorizedException();
            }

            $data = $request->validated();

            DB::beginTransaction();

            $list = $this->categoryService->index($data, $data['order'], $data['reverse']);

            DB::commit();

            return CategoryResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Category By ID
     *
     * @OA\Get(
     *   path="/v1/category/{id}",
     *   operationId="v1.category.show",
     *   tags={"Категории"},
     *   summary="Api: Просмотр отдельной категории",
     *   description="Просмотр отдельной категории",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID категории", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Категория", ref="#/components/schemas/CategoryResource"),
     *       examples={
     *         @OA\Examples(example="Some category", summary="Some category",
     *           value={"id":1, "title":"Test", "status":1, "created": "2024-03-01 11:00:00", "updated": "2024-03-01 11:00:00"}
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
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator'),
                Gate::allows('is-user')
            )) {
                throw new NotAuthorizedException();
            }

            DB::beginTransaction();

            $item =  $this->categoryService->show($id);

            DB::commit();

            return CategoryResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Category
     *
     * @OA\Post(
     *    path="/v1/category",
     *    operationId="v1.category.store",
     *    tags={"Категории"},
     *    summary="Api: Добавить категорию",
     *    description="Добавить категорию",
     *    security={{"apiKey": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные новой категории",
     *      @OA\JsonContent(
     *        required={"title"},
     *        @OA\Property(property="title",  title="Заголовок", nullable="false", example="Test", type="string"),
     *        @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *        examples={
     *          @OA\Examples(example="Active category", summary="Active category", value={"title":"Test 1", "status":1}),
     *          @OA\Examples(example="Blocked category", summary="Blocked category", value={"title":"Test 2", "status":2})
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Категория", ref="#/components/schemas/CategoryResource")
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
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        try {
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator')
            )) {
                throw new NotAuthorizedException();
            }

            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->categoryService->store($data);

            DB::commit();

            return CategoryResource::make($item)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update Category
     *
     * @OA\Put(
     *     path="/v1/category/{id}",
     *     operationId="v1.category.update",
     *     tags={"Категории"},
     *     summary="Api: Обновить категорию",
     *     description="Обновить категорию",
     *     security={{"apiKey": {} }},
     *
     *     @OA\Parameter(name="id", description="ID категории", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные категории",
     *       @OA\JsonContent(
     *         @OA\Property(property="title",  title="Заголовок", nullable="true", example="Test", type="string"),
     *         @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Категория", ref="#/components/schemas/CategoryResource")
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
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        try {
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator')
            )) {
                throw new NotAuthorizedException();
            }

            $data = $request->validated();

            DB::beginTransaction();

            $item = $this->categoryService->update($id, $data);

            return CategoryResource::make($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *   path="/v1/category/{id}",
     *   operationId="v1.category.destroy",
     *   summary="Api: Удаление категории",
     *   description="Удаление категории",
     *   tags={"Категории"},
     *   security={{"apiKey": {} }},
     *
     *   @OA\Parameter(name="id", description="ID категории", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Простой ответ", ref="#/components/schemas/MessageResource")
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
    public function destroy(User $user, int $id): JsonResponse
    {
        try {
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator')
            )) {
                throw new NotAuthorizedException();
            }

            DB::beginTransaction();

            $this->categoryService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => 'Категория удалена']])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
