<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostCommentController extends Controller
{

    public function list(Request $request)
    {
        $request->validate([
            'post_id'       => 'required|exists:posts,id',
        ]);

        $comments = Comment::where('post_id', $request->post_id)->where('parent_id', null);
        $comments = $comments->get()->load('children', 'user');
        return ok(__('strings.comment.list'), [
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    public function create(Request $request)
    {

        $request->validate([
            'post_id'       => 'required|exists:posts,id',
            'comment'       => 'required|string|max:256',
            'parent_id'     => 'nullable|exists:comments,id'
        ]);


        // Fetch the post to check its comment control settings
        $post = Post::findOrFail($request->post_id);
        $userId = Auth::id();

        // Check comment control settings
        if ($post->comment_control === 'N') {
            // 'N' = No one can comment
            return error(__('strings.comment.disabled_comment'));
        }

        //Check post owner add comment or other user
        if ($post->user_id !== $userId && $post->comment_control === 'C' && !$this->isUserConnected($post->user_id, $userId)) {
            // 'C' = Only connections can comment
            return error(__('strings.comment.no_permission'));
        }

        $request['user_id'] = $userId;

        Comment::create($request->only('user_id', 'post_id', 'comment', 'parent_id'));

        return ok(__('strings.comment.create'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment'       => 'required|string|max:256',
        ]);

        $comment = Comment::findOrFail($id);

        $userId = Auth::id();

        if ($comment->user_id !== $userId) {
            return error(__('strings.comment.no_permission'));
        }

        $comment->update($request->only('comment'));

        return ok(__('strings.comment.update'));
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);

        $userId = Auth::id();

        if ($comment->user_id !== $userId) {
            return error(__('strings.comment.no_access'));
        }

        $comment->delete();

        return ok(__('strings.comment.delete'));
    }

    public function likeDislike($id)
    {
        $user = Auth::user();
        $comment = Comment::findOrFail($id);
        // return $comment;
        // Check if the user has already liked the comment
        if (!$user->likedComments()->where('comment_id', $id)->exists()) {
            $user->likedComments()->attach($comment->id);
            return ok(__('strings.comment.like'));
        } else {
            $user->likedComments()->detach($comment->id);
            return ok(__('strings.comment.dislike'));
        }
    }

    /**
     * Check if the authenticated user is connected to the post owner
     *
     * @param string $postOwnerId
     * @param string $userId
     * @return bool
     */
    private function isUserConnected($postOwnerId, $userId)
    {
        // Check for a connection where the status is 'A' (Accepted)
        $connection = DB::table('connections')
            ->where(function ($query) use ($postOwnerId, $userId) {
                $query->where('user_id', $postOwnerId)
                    ->where('connection_id', $userId)
                    ->orWhere('user_id', $userId)
                    ->where('connection_id', $postOwnerId);
            })
            ->where('status', 'A') // check the connection is accepted
            ->exists(); // Check if a matching record exists

        return $connection;
    }
}
