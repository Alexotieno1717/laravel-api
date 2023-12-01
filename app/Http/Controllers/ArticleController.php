<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mockery\Exception;

class ArticleController extends Controller
{
    public function index()
    {
//        $articles = Article::latest('id')->paginate(10);
        $articles = Article::with('tags')->latest()->paginate(10);
        return response(['articles' => $articles]);
    }

    public function show(Article $article)
    {
        return response(['article' => $article]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $imagePath = Storage::url($imagePath);
        }

        $article = auth()->user()->articles()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        if ($request->has('tags')) {
            $article->tags()->attach($request->input('tags'));
        }

        return response(['article' => $article], 201);
    }

//    public function store(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'title' => 'required|string|max:255',
//            'description' => 'required|string',
//            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//            'tags' => 'nullable|array',
//            'tags.*' => 'exists:tags,id',
//        ]);
//
//        if ($validator->fails()) {
//            return response(['errors' => $validator->errors()->all()], 422);
//        }
//
//        $imagePath = null;
//
//        if ($request->hasFile('image')) {
//            // Upload the image to the storage and get the path
//            $imagePath = $request->file('image')->store('public/images');
//            // You can also generate a unique filename if needed: $request->file('image')->storeAs('public/images', uniqid() . '.' . $request->file('image')->extension());
//            // Make the path accessible via web
//            $imagePath = Storage::url($imagePath);
//        }
//
//        $article = Auth::user()->articles()->create([
//            'title' => $request->title,
//            'description' => $request->description,
//            'image' => $imagePath,
//        ]);
//
//        return response(['article' => $article], 201);
//    }


//    public function update(Request $request, Article $article)
//    {
//
//        // Validation rules for the update request
////        $validator = Validator([
////            'title' => 'string|max:255',
////            'description' => 'string',
////            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
////        ]);
//        $validator = Validator::make($request->all(), [
//            'title' => 'required|string|max:255',
//            'description' => 'required|string|max:1000',
//        ]);
//
//        // Check if validation fails
//        if ($validator->fails()) {
//            // If validation fails, return an error response with validation errors
//            return response(['errors' => $validator->errors()], 422);
//        }
//
////        dd($validator);
//
//
//
//        // Authorize the update action based on policies or other authorization logic
////        $this->authorize('update', $article);
//
//        // Default to the existing image path
//        $imagePath = $article->image;
//
//        // Check if a new image is provided in the request
//        if ($request->hasFile('image')) {
//            // Upload the new image to the storage and get the path
//            $imagePath = $request->file('image')->store('public/images');
//            // Make the path accessible via the web
//            $imagePath = Storage::url($imagePath);
//        }
//
////        dd($article);
//
//        // Update the article with the provided data
//        $article->update([
//            'title' => $request->get('title'),
//            'description' => $request->get('description'),
//            'image' => $imagePath,
//        ]);
//
//
//        // Return a success response with the updated article
//        return response(['article' => $article]);
//    }
    public function update(Request $request, Article $article)
    {
        // Validation rules for the update request
        $validator = Validator::make([
            'title' => $article->title,
            'description' => $article->description,

        ], [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Default to the existing image path
        $imagePath = $article->image;

        // Check if a new image is provided in the request
        if ($request->hasFile('image')) {
            // Upload the new image to storage and get the path
            $imagePath = $request->file('image')->store('public/images');
            // Make the path accessible via the web
            $imagePath = Storage::url($imagePath);
        }

        if ($request->has('tags')) {
            $article->tags()->sync($request->input('tags'));
        }

        // Update the article with the provided data
        $article->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        // Return a success response with the updated article
        return response()->json(['article' => $article]);
    }
//    public function update(Request $request, Article $article)
//    {
//        $validator = Validator::make($request->all(), [
//            'title' => 'required|string|max:255',
//            'description' => 'required|string',
//            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust image validation as needed
//        ]);
//
//        if ($validator->fails()) {
//            return response(['errors' => $validator->errors()->all()], 422);
//        }
//
//        $this->authorize('update', $article);
//
//        $article->update([
//            'title' => $request->title,
//            'description' => $request->description,
//            'image' => $request->hasFile('image') ? $this->uploadImage($request->file('public/images')) : $article->image,
//        ]);
//
//        return response(['article' => $article]);
//    }

    public function destroy(Article $article)
    {

        try {
            // $this->authorize('delete', $article);
            // Storage::delete($article->path);
            $article->delete();
            return response()->json(['message' => 'Article deleted', 'status' => 200]);
        }catch (Exception $exception){
            return response()->json($exception->getMessage(),500);
        }
    }

}
