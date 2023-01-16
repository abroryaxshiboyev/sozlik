<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use App\Http\Resources\LetterResource;
use App\Models\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'message'=>"Hamma harflar",
            'data'=>LetterResource::collection(Letter::all())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLetterRequest $request)
    {
        $letter_create=Letter::create($request->validated());
        return response([
            'message'=>"qo'shildi",
            'data'=>new LetterResource($letter_create)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response([
            'message'=>'category',
            'data'=>new LetterResource(Letter::find($id))]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLetterRequest $request, $id)
    {
        Letter::find($id)->update($request->validated());
        $Letter=Letter::find($id);
        return response([
            'message'=>"o'zgartirildi",
            'data'=>new LetterResource($Letter)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Letter::find($id)->delete();

        return response([
            'message'=>"o'chirildi"
        ]);
    }
}
