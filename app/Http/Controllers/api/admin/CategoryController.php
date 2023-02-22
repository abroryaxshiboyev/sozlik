<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryItemResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\DateResource;
use App\Http\Resources\Category\OneCategoryResource;
use App\Models\Category;
use App\Models\WordCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sortDate(Request $request){
        $limit = $request->input('limit', 10);
        $category=Category::orderBy('created_at','desc');
        $count=count($category->get());
        $category_=$category->paginate($limit);
        return response([
            'message' => 'date sorting',
            'data'=>DateResource::collection($category_),
            'total' => $count
        ]);
    }
    public function index()
    {
        return response([
            'message'=>"all categories",
            'data'=>new CategoryCollection(Category::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $letter_create=Category::create($request->validated());
        return response([
            'message'=>"created category",
            'data'=>new OneCategoryResource($letter_create)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $limit = $request->input('limit', 10);
        $words=Category::find($id);
        if(isset($words)){
            $words->setRelation('words', 
            $words->words()->orderBy('latin')->paginate($limit)
            );
            $count=WordCategory::where('category_id',$id)->count();

            return response([
                'message'=>'one category',
                'data'=>new CategoryResource($words),
                'words_total'=>$count
                ]); 
        }else
        response([
            'message'=>'id not found',
            ],404); 
    }
    public function show2($id,Request $request)
    {
        $words=Category::find($id);

        if(isset($words)){
            return response([
                'message'=>'one category',
                'data'=>new OneCategoryResource($words),
            ]); 
        }
        else{
            return response([
                'message'=>'id not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $r=Category::find($id);
        if(isset($r)){
            Category::find($id)->update($request->validated());
            $Letter=Category::find($id);
            return response([
                'message'=>"updated successfully",
                'data'=>new CategoryItemResource($Letter)
            ]);
        }
        else{
            return response([
                'message'=>'id not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request=Category::find($id);
        if(isset($request)){
            $request->delete(); 
        
            return response([
                'message'=>"delete this category"
            ],200);
        }
        else {
            return response([
                'message'=>"id not found"
            ],404);
        }
    }
}
