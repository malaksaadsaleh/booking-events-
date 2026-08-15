<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
     public function index(){
        $categories = Category::get();
        return response()->json([
            'success'    => true,
            'categories' => CategoryResource::collection($categories),
        ],200);
    }

    public function oneCategoty (int $id){
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'success'  => false,
                'message'  => "category not found",
            ],404);}
        return $category;
        
    }

    public function show(int $id){
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'success'  => false,
                'message'  => "category not found",
            ],404);
        }
        return response()->json([
            'success'    => true,
            'category'   =>new CategoryResource($category)
        ],200);
    }

    public function create(CategoryCreateRequest $request){
        $request->validated();

        $category = Category::create([
            'title'  => $request->title,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => "category created successfully",
            'category'  => new CategoryResource($category),
        ],201);
    } 

    public function update(Request $request,int $id ){
        $request->validate([
            'title'   => "required|min:3|string",
        ]);

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'success'  => false,
                'message'  => "category not found",
            ],404);}
        $category->update([
            'title'  => $request->title,
        ]);
        return response()->json([
            'success'   => true,
            'message'   => "category updated",
            'category'  => new CategoryResource($category),
        ],200);

    }

    public function dalete(Request $request, int $id){
        $category = Category::find($id);

         if(!$category){
            return response()->json([
                'success'  => false,
                'message'  => "category not found",
            ],404);}

        $category->delete();
        return response()->json([
            'success'  => true,
            'message'  => "categoyr was deleted",
        ],200);
    }
}
