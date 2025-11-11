<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaListRequest;
use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * The media service instance.
     *
     * @var MediaServiceInterface
     */
    protected $mediaService;

    /**
     * Create a new controller instance.
     *
     * @param MediaServiceInterface $mediaService
     * @return void
     */
    public function __construct(MediaServiceInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of media.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $media = $this->mediaService->getPaginatedMedia(24);
        return view('admin.media.index', compact('media'));
    }

    /**
     * Upload a new media file.
     *
     * @param MediaUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(MediaUploadRequest $request)
    {
        $result = $this->mediaService->uploadMedia(
            $request->file('file'),
            $request->only('source')
        );

        return response()->json($result);
    }

    /**
     * List all media files.
     *
     * @param MediaListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(MediaListRequest $request)
    {
        $filters = [];
        
        if ($request->has('source')) {
            $filters['source'] = $request->source;
        }

        $media = $this->mediaService->listMedia($filters);

        return response()->json($media);
    }

    /**
     * Delete a media file.
     *
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Media $media)
    {
        $success = $this->mediaService->deleteMedia($media);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $success 
                    ? __('admin.media.deleted_successfully') 
                    : __('admin.media.delete_error')
            ], $success ? 200 : 500);
        }

        if ($success) {
            return redirect()->route('admin.media.index')
                ->with('success', __('admin.media.deleted_successfully'));
        }

        return redirect()->back()
            ->with('error', __('admin.media.delete_error'));
    }

    protected function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
} 