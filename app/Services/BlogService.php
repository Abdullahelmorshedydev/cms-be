<?php

namespace App\Services;

use App\Builders\BlogBuilder;
use App\Enums\StatusEnum;
use App\Repositories\BlogRepository;
use App\Repositories\BlogCommentRepository;
use App\Traits\MediaHandler;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BlogService extends BaseService
{
    use MediaHandler;

    public function __construct(
        protected BlogRepository $blogRepository,
        protected BlogCommentRepository $commentRepository,
        protected BlogBuilder $builder
    ) {
        parent::__construct($blogRepository);
    }

    public function create()
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->create(),
            __('custom.messages.retrieved_success'),
            []
        );
    }

    public function edit($slug)
    {
        $blog = $this->blogRepository->findOneByWith(
            ['slug' => $slug],
            ['*'],
            ['creator', 'comments.user', 'comments.replies.user', 'image']
        );

        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->edit($blog),
            __('custom.messages.retrieved_success')
        );
    }

    public function createBlog(array $blogData)
    {
        return $this->store($this->prepareBlogData($blogData), function ($blog) use ($blogData) {
            // Upload featured image if provided
            if (!empty($blogData['image'])) {
                $this->uploadImage($blogData['image'], $blog, 'image');
            }
        });
    }

    public function updateBlog(string $blogSlug, array $blogData)
    {
        return $this->update($this->prepareBlogData($blogData), $blogSlug, 'slug', function ($blog) use ($blogData) {
            // Update featured image if provided
            if (!empty($blogData['image'])) {
                $this->removeImages($blog);
                $this->uploadImage($blogData['image'], $blog, 'image');
            }
        });
    }

    protected function prepareBlogData(array $blogData): array
    {
        $data = [
            'title' => $blogData['title'],
            'slug' => Str::slug($blogData['title']['en']),
            'content' => $blogData['content'],
            'excerpt' => $blogData['excerpt'] ?? null,
            'is_active' => $blogData['is_active'] ?? StatusEnum::ACTIVE->value,
            'meta_title' => $blogData['meta_title'] ?? null,
            'meta_description' => $blogData['meta_description'] ?? null,
            'meta_keywords' => $blogData['meta_keywords'] ?? null,
            'published_at' => $blogData['published_at'] ?? now(),
        ];

        // Set created_by if provided (for create) or leave it as is (for update)
        if (isset($blogData['created_by'])) {
            $data['created_by'] = $blogData['created_by'];
        } elseif (!isset($blogData['id'])) {
            // If creating a new blog and no creator specified, use authenticated user
            $user = auth()->user();
            $data['created_by'] = $user ? $user->id : null;
        }

        return $data;
    }

    public function destroyBlog($slug)
    {
        return $this->destroy('slug', $slug, function ($blog) {
            // Delete all related media
            $this->removeImages($blog);

            // Delete all comments for this blog
            $this->commentRepository->deleteAll('blog_id', [$blog->id]);
        });
    }

    public function destroyAllBlogs(array $ids)
    {
        return $this->destroyAll($ids, function ($blog) {
            // Delete all related media
            $this->removeImages($blog);

            // Delete all comments for this blog
            $this->commentRepository->deleteAll('blog_id', [$blog->id]);
        });
    }

    /**
     * Upload single image for blog
     */
    protected function uploadImage($image, $blog, string $type)
    {
        $this->uploadImages([$image], $blog, $type, 'desktop', $type);
    }

    /**
     * Get blog with comments
     */
    public function showWithComments($slug)
    {
        $blog = $this->blogRepository->findOneByWith(
            ['slug' => $slug],
            ['*'],
            ['creator', 'activeComments.user', 'activeComments.activeReplies.user', 'image']
        );

        return returnData(
            [],
            Response::HTTP_OK,
            [
                'record' => $blog
            ],
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Get blogs with pagination and filters
     */
    public function index($data, $with = ['creator', 'image'], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        return parent::index($data, $with, $columns, $order, $limit);
    }
}

