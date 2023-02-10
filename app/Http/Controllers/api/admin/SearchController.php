<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchCollection;
use App\Http\Resources\Word\SearchCollection as WordSearchCollection;
use App\Http\Resources\Word\SearchItemResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Letter;
use App\Models\Search;
use App\Models\Word;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Nette\Utils\Paginator as UtilsPaginator;

class SearchController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'word_name'=>'required',
        ]);
        Search::create([
            'word_name' => $request->word_name,
        ]);
        return response([
            'message' =>'searching created successfully'
        ]);
    }

    public function words(Request $request){
        $query = Word::query();

        if($search = $request->input('search')){
            $query
            ->whereRaw("latin LIKE '%". $search . "%'")
            ->orWhereRaw("kiril LIKE '%". $search . "%'");    
        }
        elseif($search=$request->input('letter')){
            $perPage = $request->input('limit', 10);
            $page = $request->input('page', 1);
            $letters=Letter::where('latin',$search)->orWhere('kiril', 'LIKE', $search.'%')->get();
            $count=count($letters);
            if($count==2){
                $words=Word::where('latin', 'LIKE', $search.'%')->orWhere('kiril', 'LIKE', $search.'%')->get();
                if ($letters[0]['latin']==$search) {
                    $letter=$letters[0]['latin'][0];
                }else{
                    $letter=$letters[1]['latin'][0];
                }
                $array=[];
                foreach ($words as $word){
                    $str=$word['latin'][0];
                    if($letter==$str)
                        $array[]=$word;
                    }
                $offset = ($page * $perPage) - $perPage;

                $requestData =  new LengthAwarePaginator(
                array_slice($array, $offset, $perPage, true),
                count($array),
                $perPage,
                $page
                );
            }else{
                $requestData=Word::where('latin', 'LIKE', $search.'%')->orWhere('kiril', 'LIKE', $search.'%')->paginate($perPage);   
            }
            
        return response(new WordSearchCollection($requestData));
            
        }elseif ($request->input('sortBy')=='count') {
            $query
            ->orderBy('count', 'desc');
        }elseif ($request->input('sortBy')=='latin') {
            $query
            ->orderBy('latin', 'asc');
        }elseif ($request->input('sortBy')=='kiril') {
            $query
            ->orderBy('kiril', 'asc');
        }elseif ($request->input('sortBy')=='date') {
            $query
            ->orderBy('created_at', 'desc');
        }
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $result = $query->paginate($limit, ['*'], 'page', $page);
        
        return response(new WordSearchCollection($result));
    }
    public function index(){
        $searching=Search::all();
        return response([
            'message'=>'all searching words',
            'data'=>SearchItemResource::collection($searching)
        ]);
    }
    public function destroy($id){
        $search=Search::find($id);
        if(isset($search)){
            $search->delete();
            return response([
                'message'=>'deleted',
            ]);
        }
        else {
            return response([
                'message'=>'id not found',
            ],404); 
        }
    }
    public function show($id)
    {
        $r=Word::find($id);
        if(isset($r)){
            $word=Word::find($id);
            $word->update([
                'count'=>$word->count+1
            ]);
            return response([
                'message'=>'one word',
                'data'=>new WordResource(Word::find($id))]); 
        }
        else{
            return response([
                'message'=>'id not found',
            ], 404);
        }
    }
}
