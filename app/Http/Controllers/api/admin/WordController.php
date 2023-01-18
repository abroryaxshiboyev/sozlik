<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\Word\WordCollection;
use App\Http\Resources\Word\WordItemResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Word::query();

        if ($request->limit)
        {
            $orders = $query->paginate($request->limit);
        } else {
            $orders = $query->paginate();
        }
        return new WordCollection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWordRequest $request)
    {
        if(isset($request->audio)){
            $audioName=time().".".$request->audio->getClientOriginalExtension();
            $request->audio->move(public_path('/audio'),$audioName);
            $result = $request->validated();
            $result['audio'] = 'audio/'.$audioName;
        }
        else{
            $result=$request->validated();
        }
        $created_word=Word::create($result);
        return response([
            'message'=>"qo'shildi",
            'data'=>new WordResource($created_word)
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r=Word::find($id);
        if(isset($r)){
            $word=Word::find($id);
            $word->update([
                'count'=>$word->count+1
            ]);
            return response([
                'message'=>'word',
                'data'=>new WordResource(Word::find($id))]); 
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
    public function update(UpdateWordRequest $request, $id)
    {
        $audio=Word::find($id);
        if(isset($audio)){
            unlink($audio->audio);
            $audioName=time().".".$request->audio->getClientOriginalExtension();
            $request->audio->move(public_path('/audio'),$audioName);
            $result = $request->validated();
            $result['audio'] = 'audio/'.$audioName;
            Word::find($id)->update($result);
            $word=Word::find($id);
            return response([
                'message'=>"o'zgartirildi",
                'data'=>new WordResource($word)
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
        $request=Word::find($id);
        
        if(isset($request)){
        $request->delete();
        unlink($request->audio);
        }
        return response([
            'message'=>"o'chirildi"
        ]);
    }

    // public function search(Request $request){
    //     $query = Word::query();
        
    //     if($word = $request->word)
    //     {
    //         $data = $query->where(`latin`,"LIKE", "%$word%") OR (`kirill`, "LIKE", "%$word%");
    //     }
    // }
}
