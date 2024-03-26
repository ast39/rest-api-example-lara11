<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ServerErrorDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryFilterRequest;
use App\Http\Requests\Api\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ErrorResource;
use App\Http\Resources\Api\MessageResource;
use App\Http\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller {

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
     */
    public function index(CategoryFilterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $list = $this->categoryService->index($data);

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
     *   path="/v1/category/show/{id}",
     *   operationId="v1.category.show",
     *   tags={"Категории"},
     *   summary="API: Просмотр отдельной категории",
     *   description="Просмотр отдельной категории",
     *   security={{"apiAuth": {} }},
     *
     *   @OA\Parameter(name="id", description="ID категории", in="path", required=true, example="1", @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", title="Категория", ref="#/components/schemas/CategoryResource")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad Request"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=500, description="server not available")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $item =  $this->categoryService->show($id);

            DB::commit();

            return CategoryResource::collection($item)->response();
        } catch(\Exception $e) {

            DB::rollBack();
            Log::error(__METHOD__, ['msg' => $e->getMessage()]);

            return ErrorResource::make(new ServerErrorDto($e->getMessage(), $e->getCode()))->response();
        }
    }

    /**
     * Add Category
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        try {
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
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        try {
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
     * Delete Category
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->categoryService->destroy($id);

            DB::commit();

            return MessageResource::make()
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
