<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //get all comments a post
    public function index($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post no Found'
            ], 403);
        }

        return response([
            'post' => $post->comments()->with('user:id,name,image')->get()
        ], 200);
    }

    // create a comment
    public function store(Request $request,$id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found'
            ], 403);
        }

        // Validate Field
        $data = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $data['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response([
            'mesage' => 'Comment created',
        ], 200);
    }

    // Update a Comment
    public function update(Request $request,$id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not Found'
            ], 404);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission Denaid'
            ], 403);
        }

        // Validate Field
        $data = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $data['comment']
        ]);

        return response([
            'message' => 'Comment Update'
        ], 200);
    }

    // delte a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment no Found'
            ], 403);
        }

        if(!$comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Comment no Found'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment Delete'
        ], 200);
    }
}
