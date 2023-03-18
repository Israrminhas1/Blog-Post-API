<?php

namespace ProjectApi\Controller;

use DI\Container;
use Laminas\Diactoros\Response\JsonResponse;
use ProjectApi\Controller\FileController;
use ProjectApi\Entity\Post;
use ProjectApi\Repository\PostRepository;
use ProjectApi\Repository\PostsCategoriesRepository;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Cocur\Slugify\Slugify;
use PDO;
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
    private PostsCategoriesRepository $postsCategoriesRepository;
    private PDO $pdo;

    public function __construct(Container $container)
    {
     
        $this->postRepository = $container->get('post-repository');
        $this->postsCategoriesRepository = $container->get('posts_categories-repository');
        $this->pdo = $container->get('db');

    }
 
    public function create(Request $request, Response $response, mixed $args): JsonResponse
    {
        try {
            $this->pdo->beginTransaction();

            $inputs = json_decode($request->getBody()->getContents(), true);

            $posts = $this->postRepository->listAllPosts();

            foreach ($posts as $post) {
                if ($post['title'] === $inputs['title']) {
                    throw new \Exception('Post with that title already exists.', 400);
                }
            }

            if (
                empty($inputs['title']) || empty($inputs['content']) || empty($inputs['thumbnail']) ||
                empty($inputs['author']) || empty($inputs['categories'])
            ) {
                throw new \Exception('Missing required fields.');
            }

            $slugify = new Slugify();
            $slug = $slugify->slugify($inputs['title']);

            $postedAt = date('Y-m-d H:i:s');

            $thumbnail = new FileController($inputs['thumbnail']);
            $filePath = 'http://localhost:8888/uploads/' . $thumbnail->handle();

            $post = new Post(
                Uuid::uuid4(),
                $inputs['title'],
                $slug,
                $inputs['content'],
                $filePath,
                $inputs['author'],
                $postedAt,
                $inputs['categories']
            );

            $this->postRepository->store($post);
            $this->postsCategoriesRepository->store($post);

            $this->pdo->commit();

            $output = [
                'id' => $post->id(),
                'title' => $post->title(),
                'slug' => $post->slug(),
                'content' => $post->content(),
                'thumbnail' => $post->thumbnail(),
                'author' => $post->author(),
                'posted_at' => $post->postedAt(),
                'categories' => $post->categories()
            ];

            return new JsonResponse($output);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $inputs = json_decode($request->getBody()->getContents(), true);

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
    public function listAllPosts(Request $request, Response $response, mixed $args): JsonResponse
    {
        $allPosts = $this->postRepository->listAllPosts();

        return new JsonResponse($allPosts);
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

        if (!$post) {
            return new JsonResponse(['error' => 'Id Mismatched / Post not found.'], 404);
        }

        return new JsonResponse($post);
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

        if (!$post) {
            return new JsonResponse(['error' => 'Invalid Slug / Post not found.'], 404);
        }

        return new JsonResponse($post);
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

            $post = $this->postRepository->read($args);

            if (!$post) {
                throw new \Exception('Id Mismatch / Post not found.', 404);
            }

            if (empty($inputs['content']) || empty($inputs['thumbnail'])) {
                throw new \Exception('Missing required fields.', 400);
            }

            $this->postRepository->update($inputs, $args);

            $data = $this->postRepository->read($args);

            return new JsonResponse($data);
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
         $post = $this->postRepository->read($args);
 
         if (!$post) {
             return new JsonResponse(['error' => 'Invalid Post id or Post not found.'], 404);
         }
 
         $this->postRepository->delete($args);
 
         $output = [
             "status" => "Post deleted succesfully"
         ];
 
         return new JsonResponse($output);
     }
}
