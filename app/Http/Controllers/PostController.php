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
            'posts' => Post::orderBy('created_at','desc')->with('user:id,name,image')->withCount('comments','likes')
            ->with('likes', function($likes){
                return $likes->where('user_id',auth()->user()->id)
                ->select('id','user_id','post_id')->get();
            })
            ->get()
        ], 200);
    }

    // get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments','likes')->get()
        ], 200);
    }

    // create a post
    public function store(Request $request)
    {
        // Validate fields
        $data = $request->validate([
            'body' => 'required|string'
        ]);

        $image =  $this->saveImages($request->image, 'posts');

        $post = Post::create([
            'body' => $data['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
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
            ], 403);
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
