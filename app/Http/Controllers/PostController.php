<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = $request->user()->posts()->withTrashed()->with('tags')->orderBy('pinned', 'desc')->get();

        return response()->json($posts);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'pinned' => 'required|boolean',
            'tags' => 'required|array', // Ensure tags is an array
            'tags.*' => 'integer|exists:tags,id', // Each tag must exist
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('cover_image')->store('public/covers');
        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $path,
            'pinned' => $request->pinned,
        ]);

        $post->tags()->attach($request->tags);
        return response()->json($post->load('tags'), 201);
    }

    public function show($id)
    {
        $post = Auth::user()->posts()->withTrashed()->with('tags')->findOrFail($id);
        return response()->json($post);
    }




    public function update(Request $request, $id)
    {
        $post = $request->user()->posts()->withTrashed()->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pinned' => 'required|boolean',
            'tags' => 'required|array', // Ensure tags is an array
            'tags.*' => 'integer|exists:tags,id', // Each tag must exist

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($request->hasFile('cover_image')) {
            // Delete the old image if a new one is uploaded
            Storage::delete($post->cover_image);

            $path = $request->file('cover_image')->store('public/covers');
            $post->cover_image = $path;
        }


        $post->update([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'pinned' => $request->input('pinned'),
        ]);

        $post->tags()->sync($request->input('tags'));

        return response()->json($post->load('tags'));
    }

    public function destroy($id)
    {

        $post = Auth::user()->posts()->withTrashed()->findOrFail($id);


        if ($post->trashed()) {
            return response()->json(['message' => 'Post already deleted'], 422); 
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted (softly)'], 200);

    }

    public function deletedPosts(Request $request)
    {
        $posts = $request->user()->posts()->onlyTrashed()->with('tags')->get();

        return response()->json($posts);

    }

    public function restorePost(Request $request, $id)
    {
        $post = $request->user()->posts()->onlyTrashed()->findOrFail($id);
        $post->restore();
        return response()->json($post->load('tags'));
    }
}