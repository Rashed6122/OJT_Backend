<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tags|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tag = Tag::create($request->all());
        return response()->json($tag, 201); // 201 Created
    }


    public function update(Request $request, $id)
    {

        $tag = Tag::findOrFail($id);


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id, // Unique except current tag
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tag->update($request->all());

        return response()->json($tag);
    }


    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json(['message' => 'Tag deleted'], 200);

    }
}