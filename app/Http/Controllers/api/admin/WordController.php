<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\Word\WordCollection;
use App\Http\Resources\Word\WordItemResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Word;
use App\Models\WordCategory;
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

        if($search = $request->input('search')){
            $query
            ->whereRaw("latin LIKE '%". $search . "%'")
            ->orWhereRaw("kiril LIKE '%". $search . "%'");
        }
        elseif($search=$request->input('letter')){
            $query
            ->whereRaw("latin LIKE '". $search . "%'")
            ->orWhereRaw("kiril LIKE '". $search . "%'");
        }elseif ($request->input('sortBy')=='count') {
            $query
            ->orderBy('count', 'desc');
        }elseif ($request->input('sortBy')=='latin') {
            $query
            ->orderBy('latin', 'asc');
        }elseif ($request->input('sortBy')=='kiril') {
            $query
            ->orderBy('kiril', 'asc');
        }

        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $result = $query->paginate($limit, ['*'], 'page', $page);

        return response(new WordCollection($result));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWordRequest $request)
    {
        foreach ($request->categories_id as $key => $value) {
            $request->validate([
                "categories_id."."$key" =>'exists:categories,id'
            ]);
        }
        
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
        foreach ($request->categories_id as  $key=>$value ){
            WordCategory::create(['category_id'=>$value,'word_id'=>$created_word->id]);
        }
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
            if(isset($audio->audio))
                unlink($audio->audio);
            if(isset($request->audio)){
                $audioName=time().".".$request->audio->getClientOriginalExtension();
                $request->audio->move(public_path('/audio'),$audioName);
                $result = $request->validated();
                $result['audio'] = 'audio/'.$audioName;
            }else
                $result=$request->validated();
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
            $categories=new WordResource($request);
            foreach ($categories->category as $key => $value) {
                $pivot_id=WordCategory::where('word_id',$id)->get()[0];
                $pivot_id->delete();
            }
            $request->delete();
            if(isset($request->audio))
                unlink($request->audio);
        }
        return response([
            'message'=>"o'chirildi"
        ]);
    }


}
