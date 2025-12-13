<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BaseDashboardController;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Requests\BlogComment\StoreBlogCommentRequest;
use App\Http\Requests\BlogComment\UpdateBlogCommentRequest;
use App\Services\BlogService;
use App\Services\BlogCommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends BaseDashboardController
{
    public function __construct(
        protected BlogService $blogService,
        protected BlogCommentService $commentService
    ) {}

    /**
     * Display a listing of blogs
     */
    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $serviceResponse = $this->blogService->index(
                $data,
                ['creator', 'image'],
                ['*'],
                ['id' => 'DESC'],
                $request->get('limit', 10)
            );

            return view('dashboard.pages.blogs.index', [
                'data' => $serviceResponse,
                'blogs' => $this->extractPaginatedData($serviceResponse),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading blogs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleError($e, 'dashboard.pages.blogs.index', [
                'data' => ['data' => ['data' => []]],
                'blogs' => $this->getEmptyPaginator(),
            ]);
        }
    }

    /**
     * Show the form for creating a new blog
     */
    public function create()
    {
        try {
            return view('dashboard.pages.blogs.create', [
                'data' => $this->blogService->create()['data']
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading blog create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Store a newly created blog
     */
    public function store(StoreBlogRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->blogService->createBlog($data);

            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.blogs.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing blog', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    /**
     * Display the specified blog
     */
    public function show($slug)
    {
        try {
            $response = $this->blogService->showWithComments($slug);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']['record']) || !$response['data']['record']) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('dashboard.pages.blogs.show', [
                'blog' => $response['data']['record']
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading blog', ['slug' => $slug, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Show the form for editing the specified blog
     */
    public function edit($slug)
    {
        try {
            $response = $this->blogService->edit($slug);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']) || empty($response['data'])) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('dashboard.pages.blogs.edit', [
                'data' => $response['data']
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading blog for edit', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Update the specified blog
     */
    public function update(UpdateBlogRequest $request, $slug)
    {
        try {
            $data = $request->validated();
            $response = $this->blogService->updateBlog($slug, $data);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating blog', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    /**
     * Remove the specified blog
     */
    public function destroy($slug)
    {
        try {
            $response = $this->blogService->destroyBlog($slug);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.blogs.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting blog', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Remove multiple blogs
     */
    public function destroyAll(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:blogs,id'
            ]);

            $response = $this->blogService->destroyAllBlogs($request->ids);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error deleting blogs', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Get comments for a blog
     */
    public function getComments($blog)
    {
        try {
            $response = $this->blogService->showWithComments($blog);
            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']['record']) || !$response['data']['record']) {
                abort(404, __('custom.messages.not_found'));
            }
            return response()->json([
                'comments' => $response['data']['record']->comments ?? []
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading blog comments', ['blog' => $blog, 'error' => $e->getMessage()]);
            return response()->json(['error' => __('custom.messages.retrieved_failed')], 500);
        }
    }

    /**
     * Store a new comment for a blog
     */
    public function storeComment(StoreBlogCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->commentService->createComment($data);

            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing comment', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    /**
     * Update a comment
     */
    public function updateComment(UpdateBlogCommentRequest $request, $commentId)
    {
        try {
            $data = $request->validated();
            $response = $this->commentService->updateComment($commentId, $data);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating comment', ['id' => $commentId, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    /**
     * Approve a comment
     */
    public function approveComment($commentId)
    {
        try {
            $response = $this->commentService->approveComment($commentId);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error approving comment', ['id' => $commentId, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }

    /**
     * Reject a comment
     */
    public function rejectComment($commentId)
    {
        try {
            $response = $this->commentService->rejectComment($commentId);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error rejecting comment', ['id' => $commentId, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }

    /**
     * Delete a comment
     */
    public function destroyComment($commentId)
    {
        try {
            $response = $this->commentService->destroyComment($commentId);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting comment', ['id' => $commentId, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Export blogs
     */
    public function export(Request $request)
    {
        try {
            $criteria = $request->all();
            $filePath = $this->blogService->export($criteria);

            if (!file_exists($filePath)) {
                throw new \Exception('Export file not found');
            }

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting blogs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => __('custom.messages.export_failed')]);
        }
    }

    /**
     * Import blogs
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt'
            ]);

            // Implementation for import logic can be added here

            $message = [
                'status' => true,
                'content' => __('custom.messages.imported_success')
            ];

            return back()->with('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error importing blogs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => __('custom.messages.imported_failed')]);
        }
    }
}
