<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        if ($categories->count() > 0) {
            return response()->json([
                'status' => 200,
                'categories' => $categories
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
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($category) {
            return response()->json([
                'status' => 200,
                'message' => 'Category Created Successfully'
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
        $category = Category::find($id);
        if($category){

            return response()->json([
                'status' => 200,
                'category' => $category
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found!'
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $category = Category::find($id);

        if ($category) {

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Category Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found!'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if($category){
            
            $category->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully'
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found!'
            ], 404);
        }
    }
}
