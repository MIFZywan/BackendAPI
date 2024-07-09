<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('user', 'category')->get();

        if ($recipes->count() > 0) {
            return response()->json([
                'status' => 200,
                'recipes' => $recipes
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:1000',
            'ingredients' => 'required|string',
            'steps' => 'required|string',
            'image' => 'nullable|string|max:191',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'steps' => $request->steps,
            'image' => $request->image,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
        ]);

        if ($recipe) {
            return response()->json([
                'status' => 200,
                'message' => 'Recipe Created Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong!'
            ], 500);
        }
    }

    public function show($id)
    {
        $recipe = Recipe::with('user', 'category')->find($id);
        if ($recipe) {
            return response()->json([
                'status' => 200,
                'recipe' => $recipe
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Recipe Not Found!'
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:1000',
            'ingredients' => 'required|string',
            'steps' => 'required|string',
            'image' => 'nullable|string|max:191',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $recipe = Recipe::find($id);

        if ($recipe) {
            $recipe->update([
                'title' => $request->title,
                'description' => $request->description,
                'ingredients' => $request->ingredients,
                'steps' => $request->steps,
                'image' => $request->image,
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Recipe Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Recipe Not Found!'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $recipe = Recipe::find($id);
        if ($recipe) {
            $recipe->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Recipe deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Recipe Not Found!'
            ], 404);
        }
    }
}
