<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchCollection;
use App\Http\Resources\Word\SearchCollection as WordSearchCollection;
use App\Models\Letter;
use App\Models\Search;
use App\Models\Word;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Nette\Utils\Paginator as UtilsPaginator;

class SearchController extends Controller
{
    public function words(Request $request){
        $query = Word::query();

        if($search = $request->input('search')){
            $query
            ->whereRaw("latin LIKE '%". $search . "%'")
            ->orWhereRaw("kiril LIKE '%". $search . "%'");
            if($search!="")
                Search::create([
                    'word_name' => $search
                ]);
            
        }
        elseif($search=$request->input('letter')){
            // $query
            // ->whereRaw("latin LIKE '". $search . "%'")
            // ->orWhereRaw("kiril LIKE '". $search . "%'");
            $words=Word::where('latin', 'LIKE', $search.'%')->orWhere('kiril', 'LIKE', $search.'%')->get();
            $letters=Letter::where('latin',$search)->orWhere('kiril', 'LIKE', $search.'%')->get();
            if(count($letters)==2){
                if ($letters[0]['latin']==$search) {
                    $letter=$letters[0]['latin'][0];
                    $status="latin";
                } elseif($letters[1]['latin']==$search){
                    $letter=$letters[1]['latin'][0];
                    $status="latin";
                }
                elseif ($letters[0]['kiril']==$search) {
                    $letter=$letters[0]['kiril'][0];
                    $status="kiril";
                } else {
                    $letter=$letters[1]['kiril'][0];
                    $status="kiril";
                }
            }else{
                if ($letters[0]['latin']==$search) {
                    $letter=$letters[0]['latin'][0];
                    $status="latin";
                }else{
                    $letter=$letters[0]['kiril'][0];
                    $status="kiril";
                }
            }
            $array=[];
            foreach ($words as $word){
                $str=$word[$status][0];
                if($letter==$str)
                    $array[]=$word;
                }
        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $offset = ($page * $perPage) - $perPage;

        $requestData =  new LengthAwarePaginator(
        array_slice($array, $offset, $perPage, true),
        count($array),
        $perPage,
        $page
        );
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
            'data'=>$searching
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
            ]); 
        }
    }
}
