<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Exceptions\NotAuthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\OrderQueryRequest;
use App\Http\Requests\Api\Order\OrderStoreRequest;
use App\Http\Requests\Api\Order\OrderUpdateRequest;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\OrderService;
use App\Http\Traits\Accessable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;


class OrderController extends Controller {

    use Accessable;


    /**
     * @var OrderService
     */
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Order list
     *
     * @OA\Get(
     *    path="/v1/order",
     *    operationId="v1.order.getList",
     *    tags={"Заказы"},
     *    summary="Api: Список заказов",
     *    description="Список заказов",
     *    security={{"apiKey": {} }},
     *
     *    @OA\Parameter(name="q", description="Поиск по совпадению", example="Test", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="user", description="Пользователи", example="1,2,3", in="query", required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="status", description="Статусы заказа", example="1,2,3", in="query", required=false, required=false, @OA\Schema(type="string")),
     *    @OA\Parameter(name="page", description="Номер страницы", in="query", required=false, example="1", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="limit", description="Записей на страницу", in="query", required=false, example="10", @OA\Schema(type="integer", format="int32")),
     *    @OA\Parameter(name="order", description="Соротировка по полю", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"title", "status", "created"}, "default": "title"}),
     *    @OA\Parameter(name="reverse", description="Реверс сортировки", in="query", required=false, allowEmptyValue=true, schema={"type": "string", "enum": {"asc", "desc"}, "default": "asc"}),
     *
     *    @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Список заказов", type="array", @OA\Items(ref="#/components/schemas/OrderResource"))
     *       )
     *     ),
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
    public function index(OrderQueryRequest $request): JsonResponse
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

            $list = $this->orderService->index($data);

            DB::commit();

            return OrderResource::collection($list)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Order By ID
     *
     * @OA\Get(
     *   path="/v1/order/{id}",
     *   operationId="v1.order.show",
     *   tags={"Заказы"},
     *   summary="Api: Просмотр отдельного заказа",
     *   description="Просмотр отдельного заказа",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID заказа", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Карточка заказа", ref="#/components/schemas/OrderResource")
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

            $Order =  $this->orderService->show($id);

            DB::commit();

            return OrderResource::make($Order)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Order
     *
     * @OA\Post(
     *    path="/v1/order",
     *    operationId="v1.order.store",
     *    tags={"Заказы"},
     *    summary="Api: Добавить заказ",
     *    description="Добавить заказ",
     *    security={{"apiKey": {} }},
     *
     *    @OA\RequestBody(
     *      required=true,
     *      description="Данные нового заказа",
     *      @OA\JsonContent(
     *        required={"article", "title", "category_id", "price"},
     *        @OA\Property(property="body", title="Примечание", nullable="false", example="Test", type="string"),
     *        @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *        @OA\Property(property="items", title="Товары", nullable="false", example="1,2", type="string"),
     *        examples={
     *          @OA\Examples(example="Active Order", summary="Active Order", value={"article":"ABCDEF", "title":"Test 1",
     *            "category_id": 1, "price":490.90, "status":1, "images":"1,2"}),
     *          @OA\Examples(example="Blocked Order", summary="Blocked Order", value={"article":"DFGERT","title":"Test 2",
     *            "category_id": 1, "price":590.90, "status":2}),
     *        }
     *      ),
     *    ),
     *    @OA\Response(response=201, description="successful operation",
     *      @OA\JsonContent(
     *        @OA\Property(property="data", title="Карточка заказа", ref="#/components/schemas/OrderResource")
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
    public function store(OrderStoreRequest $request): JsonResponse
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

            $Order = $this->orderService->store($data);

            DB::commit();

            return OrderResource::make($Order)->response()->setStatusCode(201);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Update Order
     *
     * @OA\Put(
     *     path="/v1/order/{id}",
     *     operationId="v1.order.update",
     *     tags={"Заказы"},
     *     summary="Api: Обновить заказ",
     *     description="Обновить заказ",
     *     security={{"apiKey": {} }},
     *
     *     @OA\Parameter(name="id", description="ID заказа", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Обновленные данные заказа",
     *       @OA\JsonContent(
     *         @OA\Property(property="article", title="Артикул", nullable="true", example="ABCDEF", type="string"),
     *         @OA\Property(property="title", title="Заголовок", nullable="true", example="Test", type="string"),
     *         @OA\Property(property="category_id", title="Категория", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="price", title="Цена", nullable="true", example="499.90", type="decimal"),
     *         @OA\Property(property="status", title="Статус", nullable="true", example="1", type="integer"),
     *         @OA\Property(property="images", title="Изображения", nullable="true", example="1,2", type="string")
     *       ),
     *     ),
     *     @OA\Response(response=200, description="successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="data", title="Карточка товара", ref="#/components/schemas/OrderResource")
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
    public function update(OrderUpdateRequest $request, int $id): JsonResponse
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

            $Order = $this->orderService->update($id, $data);

            return OrderResource::make($Order)->response();
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
     *   path="/v1/order/{id}",
     *   operationId="v1.order.destroy",
     *   summary="Api: Удаление заказа",
     *   description="Удаление заказа",
     *   tags={"Заказы"},
     *   security={{"apiKey": {} }},
     *
     *   @OA\Parameter(name="id", description="ID заказа", in="path", required=true, example="1", @OA\Schema(type="integer")),
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

            $this->orderService->destroy($id);

            DB::commit();

            return MessageResource::make(true)
                ->additional(['data' => ['msg' => __('msg.Order.deleted')]])
                ->response()
                ->setStatusCode(200);
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }
}
