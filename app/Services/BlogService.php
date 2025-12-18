<?php

namespace App\Services;

use App\Builders\BlogBuilder;
use App\Enums\StatusEnum;
use App\Repositories\BlogRepository;
use App\Repositories\BlogCommentRepository;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BlogService extends BaseService
{
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
            [
                'slug' => $slug
            ],
            ['*'],
            [
                'creator',
                'comments',
                'comments.user',
                'comments.replies.user',
                'image'
            ]
        );

        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->edit($blog),
            __('custom.messages.retrieved_success')
        );
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

        if (isset($blogData['created_by'])) {
            $data['created_by'] = $blogData['created_by'];
        } elseif (!isset($blogData['id'])) {
            $user = auth()->user();
            $data['created_by'] = $user ? $user->id : null;
        }

        return $data;
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
}

