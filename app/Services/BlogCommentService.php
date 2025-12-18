<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Repositories\BlogCommentRepository;
use Symfony\Component\HttpFoundation\Response;

class BlogCommentService extends BaseService
{
    public function __construct(
        protected BlogCommentRepository $commentRepository
    ) {
        parent::__construct($commentRepository);
    }

    /**
     * Create a new comment
     */
    public function createComment(array $commentData)
    {
        return $this->store($this->prepareCommentData($commentData));
    }

    /**
     * Update a comment
     */
    public function updateComment($id, array $commentData)
    {
        return $this->update($this->prepareCommentData($commentData), $id, 'id');
    }

    /**
     * Approve a comment
     */
    public function approveComment($id)
    {
        return $this->update(['is_active' => StatusEnum::ACTIVE->value], $id, 'id');
    }

    /**
     * Reject a comment
     */
    public function rejectComment($id)
    {
        return $this->update(['is_active' => StatusEnum::INACTIVE->value], $id, 'id');
    }

    /**
     * Delete a comment
     */
    public function destroyComment($id)
    {
        return $this->destroy('id', $id, function ($comment) {
            // Delete all replies to this comment
            $this->commentRepository->deleteAll('parent_id', [$comment->id]);
        });
    }

    /**
     * Delete multiple comments
     */
    public function destroyAllComments(array $ids)
    {
        return $this->destroyAll($ids, function ($comment) {
            // Delete all replies to this comment
            $this->commentRepository->deleteAll('parent_id', [$comment->id]);
        });
    }

    /**
     * Prepare comment data for storage
     */
    protected function prepareCommentData(array $commentData): array
    {
        $data = [
            'blog_id' => $commentData['blog_id'],
            'comment' => $commentData['comment'],
            'is_active' => $commentData['is_active'] ?? StatusEnum::INACTIVE->value,
        ];

        // Handle parent_id for nested comments
        if (isset($commentData['parent_id'])) {
            $data['parent_id'] = $commentData['parent_id'];
        }

        // If user is authenticated
        $user = auth()->user();
        if ($user) {
            $data['user_id'] = $user->id;
        } else {
            // For guest comments
            $data['name'] = $commentData['name'] ?? null;
            $data['email'] = $commentData['email'] ?? null;
        }

        return $data;
    }

    /**
     * Get comments for a specific blog
     */
    public function getCommentsByBlog($blogId, $data = [])
    {
        $data['blog_id'] = $blogId;
        return $this->index($data, ['user', 'replies.user'], ['*'], ['created_at' => 'DESC']);
    }

    /**
     * Get all comments with pagination
     */
    public function index($data, $with = ['user', 'blog'], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        return parent::index($data, $with, $columns, $order, $limit);
    }
}

