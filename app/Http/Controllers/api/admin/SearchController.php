<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Word\SearchCollection as WordSearchCollection;
use App\Http\Resources\Word\SearchItemResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Search;
use App\Models\Word;
use App\Models\Wordoftheday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function latin_to_cyrillic($kirilstring) {
        $cyr = [
            'ч','ш','ю','я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ы','э','ә','ғ','қ','ҳ','ң','ө','ү','ў',
            'Ч','Ш','Ю','Я','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ы','Э','Ә','Ғ','Қ','Ҳ','Ң','Ө','Ү','Ў',
               ];
        $lat = [
            'ch','sh','yu','ya','a','b','v','g','d','ye','yo','j','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','x','c','ı','e','á','ǵ','q','h','ń','ó','ú','w',
            'Ch','Sh','Yu','Ya','A','B','V','G','D','Ye','Yo','J','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','X','C','Í','E','Á','Ǵ','Q','H','Ó','Ú','W',
        ];
        return str_replace($lat, $cyr, $kirilstring);
    }
    public function store(Request $request){
        $request->validate([
            'latin'=>'required',
            'kiril'=>'required',
        ]);
        Search::create([
            'latin'=>$request->latin,
            'kiril'=>$request->kiril,
        ]);
        return response([
            'message' =>'searching created successfully'
        ]);
    }
    public function words2(){
        $search=Search::get();
        return response([
            'message'=>'all searching words',
            'data'=>SearchItemResource::collection($search)
        ]);

    }
    public function words(Request $request){
        $query = Word::query();

        if($search = $request->input('search')){
            $search=$this->latin_to_cyrillic($search);
            $query
            ->whereRaw("latin LIKE '". $search . "%'")
            ->orWhereRaw("kiril LIKE '". $search . "%'");    
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
            ],404); 
        }
    }
    public function show($id)
    {
        $r=Word::find($id);
        if(isset($r)){
            $carbon=Carbon::now()->toDateString();
            Wordoftheday::where('updated_at','<',$carbon)->update(['count'=>0]);
            $word=Word::find($id);
            $word->update([
                'count'=>$word->count+1
            ]);
            $wordday=Wordoftheday::where('word_id',$id)->first();
            if(isset($wordday)){
                $wordday->update(['count'=>$wordday->count+1]);
            }else {
                Wordoftheday::create([
                    'word_id'=>$id,
                    'count'=>1
                ]);
            }
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
