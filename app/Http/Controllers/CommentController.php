<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('recipe', 'user')->get();

        if ($comments->count() > 0) {
            return response()->json([
                'status' => 200,
                'comments' => $comments
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
            'content' => 'required|string|max:191',
            'recipe_id' => 'required|exists:recipes,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $comment = Comment::create([
            'content' => $request->content,
            'recipe_id' => $request->recipe_id,
            'user_id' => $request->user_id,
        ]);

        if ($comment) {
            return response()->json([
                'status' => 200,
                'message' => 'Comment Created Successfully'
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
        $comment = Comment::with('recipe', 'user')->find($id);
        if ($comment) {
            return response()->json([
                'status' => 200,
                'comment' => $comment
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Comment Not Found!'
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:191',
            'recipe_id' => 'required|exists:recipes,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $comment = Comment::find($id);

        if ($comment) {
            $comment->update([
                'content' => $request->content,
                'recipe_id' => $request->recipe_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Comment Updated Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Comment Not Found!'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Comment deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Comment Not Found!'
            ], 404);
        }
    }
}
