<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
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
    public function index()
    {
        return response([
            'message'=>"Hamma kategoriyalar",
            'data'=>new CategoryCollection(Category::all())]);
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
            'message'=>"qo'shildi",
            'data'=>new CategoryResource($letter_create)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r=Category::find($id);
        if(isset($r)){
            return response([
                'message'=>'category',
                'data'=>new CategoryResource(Category::find($id))]); 
        }
        else{
            return response([
                'message'=>'id not'
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
                'message'=>"o'zgartirildi",
                'data'=>new CategoryResource($Letter)
            ]);
        }
        else{
            return response([
                'message'=>'id not'
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
            $pivot=WordCategory::where('category_id',$id)->get();
            if(!empty($pivot[0])){
                return response([
                    'message'=>"there are words belonging to this category"
                ]);
        }
        $request->delete(); 
        }
        return response([
            'message'=>"o'chirildi"
        ]);
    }
}
