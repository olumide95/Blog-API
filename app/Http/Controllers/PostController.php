<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="Blog API", version="1")
 */
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        $posts = Post::paginate();
        return PostResource::collection($posts)->additional(['message' => 'Post retrieved sucessfully']);
    }

    /**
     * Store a newly created resource in storage.
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreatePostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created",
     *         @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     *     security={{"sanctum":{}}}
     * )
     *
     * @param CreatePostRequest $request
     *
     * @return JsonResource
     */
    public function store(CreatePostRequest $request): JsonResource
    {
        $post = Post::create($request->validated());
        return (new PostResource($post))->additional(['message' => 'Post created sucessfully']);
    }

    /**
     * Display the specified resource.
     * @param Post $post
     *
     * @return JsonResource
     */
    public function show(Post $post): JsonResource
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     * @param CreatePostRequest $request
     * @param Post $post
     *
     * @return JsonResource
     */
    public function update(CreatePostRequest $request, Post $post): JsonResource
    {
        $post->update($request->validated());
        return (new PostResource($post))->additional(['message' => 'Post updated sucessfully']);
    }


    /**
     * Remove the specified resource from storage.
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted sucessfully'], 200);
    }
}
