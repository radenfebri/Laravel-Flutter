<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    // get post all
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at','desc')->with('user:id,name,image')->withCount('comments','likes')->get()
        ], 200);
    }

    // get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments','likes')->get()
        ]);
    }

    // create a post
    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string'
        ]);

        $post = Post::create([
            'body' => $data['body'],
            'user_id' => auth()->user()->id
        ]);

        // for now skip for post image
        return response([
            'message' => 'Post Created',
            'post' => $post
        ], 200);
    }

    // update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not Found'
            ], 404);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission Denaid'
            ], 403);
        }

        // Validate Field
        $data = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $data['body']
        ]);

        // for now skip for post image
        return response([
            'message' => 'Post Update',
            'post' => $post
        ], 200);
    }

    // Delete Post
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not Found'
            ], 404);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission Denaid'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post Delete',
            'post' => $post
        ], 200);
    }
}
