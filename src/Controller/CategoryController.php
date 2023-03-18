<?php

namespace ProjectApi\Controller;

use DI\Container;
use Laminas\Diactoros\Response\JsonResponse;
use ProjectApi\Entity\Category;
use ProjectApi\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CategoryController
{
    
    private CategoryRepository $categoryRepository;

    public function __construct(Container $container)
    {
        $this->categoryRepository = $container->get('category-repository');
    }
 /**
     * @OA\Post(
     *     path="/v1/categories/create",
     *     description="Creates a new category in the database",
     *     tags={"Blog - Categories"},
     *     @OA\RequestBody(
     *         description="Category to create.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="aplication/json",
     *             @OA\Schema(
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Json of a newly created category"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     */
    public function create(Request $request, Response $response, mixed $args): JsonResponse
    {
        try {
            $inputs = json_decode($request->getBody()->getContents(), true);

            $categories = $this->categoryRepository->listAllCategories();

            foreach ($categories as $category) {
                if ($category['name'] === $inputs['name']) {
                    throw new \Exception('Category with similar name already exists.', 400);
                }
            }

            if (empty($inputs['name']) || empty($inputs['description'])) {
                throw new \Exception('Missing required fields.');
            }

            $category = new Category(
                Uuid::uuid4(),
                $inputs['name'],
                $inputs['description']
            );

            $this->categoryRepository->store($category);

            $output = [
                'id' => $category->id(),
                'name' => $category->name(),
                'description' => $category->description()
            ];

            return new JsonResponse($output);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
      /**
     * @OA\Get(
     *     path="/v1/categories",
     *     description="List all categories",
     *     tags={"Blog - Categories"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns the list of the categories"
     *     )
     * )
     */
    public function list(Request $request, Response $response, mixed $args): JsonResponse
    {
        $allCategories = $this->categoryRepository->listAllCategories();

        return new JsonResponse($allCategories);
    }
    /**
     * @OA\Get(
     *     path="/v1/categories/{id}",
     *     description="Returns a category by id.",
     *     tags={"Blog - Categories"},
     *     @OA\Parameter(
     *         description="Id of the category",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns json of the fetched category"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Category not found"
     *     )
     * )
     */
    public function read(Request $request, Response $response, mixed $args): JsonResponse
    {
        $category = $this->categoryRepository->read($args);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found.'], 404);
        }

        return new JsonResponse($category);
    }
    
     /**
     * @OA\Put(
     *     path="/v1/categories/update/{id}",
     *     description="Update the category by id",
     *     tags={"Blog - Categories"},
     *     @OA\Parameter(
     *         description="Id of the category",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update category",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="aplication/json",
     *             @OA\Schema(
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="description", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Json of the updated category"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Category not found"
     *     )
     * )
     */
    public function update(Request $request, Response $response, mixed $args): JsonResponse
    {
        try {
            $inputs = json_decode($request->getBody()->getContents(), true);

            $category = $this->categoryRepository->read($args);

            if (!$category) {
                throw new \Exception('Category not found.', 404);
            }

            if (empty($inputs['name']) || empty($inputs['description'])) {
                throw new \Exception('Missing required fields.', 400);
            }

            $this->categoryRepository->update($inputs, $args);

            $data = $this->categoryRepository->read($args);

            return new JsonResponse($data);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 400;
            return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }
    }
     /**
     * @OA\Delete(
     *     path="/v1/categories/delete/{id}",
     *     description="Deletes the category by id.",
     *     tags={"Blog - Categories"},
     *     @OA\Parameter(
     *         description="Id of the category",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns confirmation that the category is deleted succesfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Category not found"
     *     )
     * )
     */
    
    public function delete(Request $request, Response $response, mixed $args): JsonResponse
    {

        $category = $this->categoryRepository->read($args);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found.'], 404);
        }

        $this->categoryRepository->delete($args);


        $output = [
            "status" => "Category deleted"
        ];

        return new JsonResponse($output);
    }
   
}
