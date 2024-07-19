<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;

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
     */
    public function index()
    {
        $posts = Post::all();
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
     */
    public function store(CreatePostRequest $request)
    {
        $post = Post::create($request->validated());
        return (new PostResource($post))->additional(['message' => 'Post created sucessfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return (new PostResource($post))->additional(['message' => 'Post updated sucessfully']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted sucessfully'], 200);
    }
}
