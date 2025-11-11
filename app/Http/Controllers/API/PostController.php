<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="News",
 *     description="API Endpoints for news posts"
 * )
 */
class PostController extends Controller
{
    public function __construct(private PostServiceInterface $postService) {}

    /**
     * @OA\Get(
     *     path="/api/news",
     *     summary="Get all news posts",
     *     tags={"News"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="News list")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $news = $this->postService->getAllPosts($perPage, $search, $sortField, $sortDirection);
        return response()->json([
            'status' => 'success',
            'data' => [
                'news' => $news->items(),
                'pagination' => [
                    'current_page' => $news->currentPage(),
                    'last_page' => $news->lastPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                    'from' => $news->firstItem(),
                    'to' => $news->lastItem()
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/news/{id}",
     *     summary="Get news post details",
     *     tags={"News"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="News post details"),
     *     @OA\Response(response=404, description="News post not found")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $post = $this->postService->getPostById($id);
        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'News post not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => ['news' => $post]]);
    }
} 