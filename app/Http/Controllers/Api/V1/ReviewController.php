<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Exceptions\NotAuthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Review\ReviewQueryRequest;
use App\Http\Requests\Api\Review\ReviewStoreRequest;
use App\Http\Requests\Api\Review\ReviewUpdateRequest;
use App\Http\Resources\Api\ReviewResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\ReviewService;
use App\Http\Traits\Accessable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;


class ReviewController extends Controller {

    use Accessable;


    /**
     * @var ReviewService
     */
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Review list
     *
     * @OA\Get(
     *    path="/v1/review",
     *    operationId="v1.review.getList",
     *    tags={"Отзывы"},
     *    summary="Api: Список отзывов",
     *    description="Список отзывов",
     *    security={{"apiKey": {} }},
     *
     *    @OA\Parameter(name="q", description="Поиск по совпадению", example="Test", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="items", description="Поиск по товарам", example="1,2,3", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="users", description="Поиск по авторам", example="1,2,3", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="status", description="Статус", in="query", required=false, allowEmptyValue=true, schema={"type": "integer", "enum": {1, 2}, "default": 1}),
     *    @OA\Parameter(name="page", description="Номер страницы", in="query", required=false, example="1", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="limit", description="Записей на страницу", in="query", required=false, example="10", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="order", description="Соротировка по полю", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"title", "status", "created"}, "default": "title"}),
     *    @OA\Parameter(name="reverse", description="Реверс сортировки", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"asc", "desc"}, "default": "asc"}),
     *
     *    @OA\Response(response=200, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Список товаров", type="array", @OA\Items(ref="#/components/schemas/ReviewResource"))
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
    public function index(ReviewQueryRequest $request): JsonResponse
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

            $list = $this->reviewService->index($data);

            DB::commit();

            return ReviewResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Review By ID
     *
     * @OA\Get(
     *   path="/v1/review/{id}",
     *   operationId="v1.Review.show",
     *   tags={"Отзывы"},
     *   summary="Api: Просмотр отдельного отзыва",
     *   description="Просмотр отдельного отзыва",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID отзыва", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Карточка отзыва", ref="#/components/schemas/ReviewResource"),
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

            $review = $this->reviewService->show($id);

            DB::commit();

            return ReviewResource::make($review)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Review
     *
     * @OA\Post(
     *    path="/v1/review",
     *    operationId="v1.review.store",
     *    tags={"Отзывы"},
     *    summary="Api: Добавить отзыв",
     *    description="Добавить отзыв",
     *    security={{"apiKey": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные нового отзыва",
     *      @OA\JsonContent(
     *        required={"article", "title", "category_id", "price"},
     *        @OA\Property(property="user_id", title="ID пользователя", nullable="false", example="1", type="integer"),
     *        @OA\Property(property="item_id", title="ID товара", nullable="false", example="1", type="integer"),
     *        @OA\Property(property="rate", title="Оценка", nullable="false", example="1", type="integer"),
     *        @OA\Property(property="body", title="Отзыв", nullable="true", example="Test", type="string"),
     *        @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *        @OA\Property(property="images", title="Изображения", nullable="true", example="1,2", type="string"),
     *        examples={
     *          @OA\Examples(example="Review vs images", summary="Review vs images", value={"user_id":1, "item_id":1,
     *            "body": "Test description", "rate":5, "status":1, "images":"1,2"}),
     *          @OA\Examples(example="Review vs out images", summary="Review vs out images", value={"user_id":1,"item_id":1,
     *            "body": "Test description", "rate":3}),
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/ReviewResource")
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
    public function store(ReviewStoreRequest $request): JsonResponse
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

            $review = $this->reviewService->store($data);

            DB::commit();

            return ReviewResource::make($review)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update Review
     *
     * @OA\Put(
     *     path="/v1/review/{id}",
     *     operationId="v1.review.update",
     *     tags={"Отзывы"},
     *     summary="Api: Обновить отзыв",
     *     description="Обновить отзыв",
     *     security={{"apiKey": {} }},
     *
     *     @OA\Parameter(name="id", description="ID товара", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные отзыва",
     *       @OA\JsonContent(
     *         @OA\Property(property="user_id", title="ID пользователя", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="item_id", title="ID товара", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="rate", title="Оценка", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="body", title="Отзыв", nullable="true", example="Test", type="string"),
     *         @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="images", title="Изображения", nullable="true", example="1,2", type="string")
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/ReviewResource")
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
    public function update(ReviewUpdateRequest $request, int $id): JsonResponse
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

            $review = $this->reviewService->update($id, $data);

            return ReviewResource::make($review)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *   path="/v1/review/{id}",
     *   operationId="v1.review.destroy",
     *   summary="Api: Удаление отзыва",
     *   description="Удаление отзыва",
     *   tags={"Отзывы"},
     *   security={{"apiKey": {} }},
     *
     *   @OA\Parameter(name="id", description="ID отзыва", in="path", required=true, example="1", @OA\Schema(type="integer")),
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
    public function destroy(int $id): JsonResponse
    {
        try {
            if (!$this->anyOfMany(
                Gate::allows('is-admin'),
                Gate::allows('is-moderator')
            )) {
                throw new NotAuthorizedException();
            }

            DB::beginTransaction();

            $this->reviewService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => __('msg.review.deleted')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
