<?php

namespace ProjectApi\Controller;

use DI\Container;
use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use ProjectApi\Controller\FileController;
use ProjectApi\Entity\Post;
use ProjectApi\Repository\PostRepository;
use ProjectApi\Repository\CategoryRepository;
use ProjectApi\Validator\PostValidator;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Cocur\Slugify\Slugify;
use OpenApi\Annotations as OA;

class PostController
{
    /**
     * @OA\Post(
     *     path="/v1/posts/create",
     *     description="This will create a new post.",
     *     tags={"Blog - Posts"},
     *     @OA\RequestBody(
     *         description="Formart of json to be requested. In categories section the existing category id in the database will be used.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="aplication/json",
     *             @OA\Schema(
     *                  @OA\Property(property="title", type="string", description="Add Title of Post"),
     *                  @OA\Property(property="content", type="string", description="Add Content of Post"),
     *                  @OA\Property(property="thumbnail", type="string", description="Add Thumbnail of Post"),
     *                  @OA\Property(property="author", type="string", description="Add Author of Post"),
     *                  @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="string", format="uuid", description="Add Id of Categories to which Post belong")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Json Response  of the created post"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     */

    private PostRepository $postRepository;
    private CategoryRepository $categoryRepository;


    public function __construct(Container $container)
    {

        $this->postRepository = $container->get('post-repository');
        $this->categoryRepository = $container->get('category-repository');
    }

    public function create(Request $request, Response $response, mixed $args): JsonResponse
    {
        $inputs = json_decode($request->getBody()->getContents(), true);

        PostValidator::validate($inputs);
        try {
            $slugify = new Slugify();
            $slug = $slugify->slugify($inputs['title']);



            $thumbnail = new FileController($inputs['thumbnail']);
            $filePath = $_ENV['APP_URL'] . '/' . 'uploads/' . $thumbnail->handle();

            $post = new Post(
                Uuid::uuid4(),
                $inputs['title'],
                $slug,
                $inputs['content'],
                $filePath,
                $inputs['author'],
                new DateTimeImmutable(),
            );
            foreach ($inputs['categories'] as $r) {
                $category = $this->categoryRepository->getByIdString($r['id']);
                $post->addCategory($category);
            }
            $this->postRepository->store($post);
            $output = ["success"];

            return new JsonResponse($output);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
     /**
     * @OA\Get(
     *     path="/v1/posts",
     *     description="List of all available posts",
     *     tags={"Blog - Posts"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns the list of posts"
     *     )
     * )
     */
    public function list(Request $request, Response $response, mixed $args): JsonResponse
    {
        $allPosts = $this->postRepository->listAllPosts();

        return $this->toJson($allPosts);
    }

    private function toJson(array $posts): JsonResponse
    {
        $postsCategories = [];
        foreach ($posts as $post) {
            $postsCategories[] = $post->toArray();
        }
        return new JsonResponse($postsCategories);
    }
       /**
     * @OA\Get(
     *     path="/v1/posts/{id}",
     *     description="Returns a post by id.",
     *     tags={"Blog - Posts"},
     *     @OA\Parameter(
     *         description="Id of the post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns all the properties of the post"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Id Mismatched / Post not found"
     *     )
     * )
     */
    public function read(Request $request, Response $response, mixed $args): JsonResponse
    {
        $post = $this->postRepository->read($args);

        return new JsonResponse($post->toArray());
    }

        /**
     * @OA\Get(
     *     path="/v1/posts/{slug}",
     *     description="Returns a post by slug.",
     *     tags={"Blog - Posts"},
     *     @OA\Parameter(
     *         description="Slug of the post",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns all the properties of the post"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid Slug / Post not found"
     *     )
     * )
     */
    public function readBySlug(Request $request, Response $response, mixed $args): JsonResponse
    {
        $post = $this->postRepository->readBySlug($args);

        return new JsonResponse($post->toArray());
    }
     /**
     * @OA\Put(
     *     path="/v1/posts/update/{id}",
     *     description="Update the post by id",
     *     tags={"Blog - Posts"},
     *     @OA\Parameter(
     *         description="Id of the post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update post",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="aplication/json",
     *             @OA\Schema(
     *                  @OA\Property(property="content", type="string"),
     *                  @OA\Property(property="thumbnail", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Json of the updated post"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Id Mismatch / Post not found"
     *     )
     * )
     */
    public function update(Request $request, Response $response, mixed $args): JsonResponse
    {
        try {
            $inputs = json_decode($request->getBody()->getContents(), true);

            $this->postRepository->read($args);


            $slugify = new Slugify();
            $inputs['slug'] = $slugify->slugify($inputs['title']);
            $this->postRepository->update($inputs, $args);

            $output = [
                'status' => 'success',
            ];


            return new JsonResponse($output);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: 400;
            return new JsonResponse(['error' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/posts/delete/{id}",
     *     description="Delete post by id.",
     *     tags={"Blog - Posts"},
     *     @OA\Parameter(
     *         description="Id of the post",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns confirmation if post is deleted succesfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Invalid Post id or Post not found"
     *     )
     * )
     */

    public function delete(Request $request, Response $response, mixed $args): JsonResponse
    {

        $this->postRepository->read($args);

        $this->postRepository->delete($args);

        $output = [
            "status" => "Post deleted succesfully"
        ];

        return new JsonResponse($output);
    }
}
