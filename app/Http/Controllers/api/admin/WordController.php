<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\Word\DateResource;
use App\Http\Resources\Word\WordCollection;
use App\Http\Resources\Word\WorddayResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Antonym;
use App\Models\Search;
use App\Models\Synonym;
use App\Models\Word;
use App\Models\WordCategory;
use App\Models\Wordoftheday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WordController extends Controller
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
    ///Abror Yaxshiboyev
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sortDate(Request $request){
        $limit = $request->input('limit', 10);
        $words=Word::orderBy('created_at','desc');
        $count=count($words->get());
        $words_=$words->paginate($limit);
        return response([
            'message' => 'date sorting',
            'data'=>DateResource::collection($words_),
            'total'=>$count
        ]);
    }
    
    public function index(Request $request)
    {
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
        
        $user=auth()->user();
        //sinonim so'zlar id sini validatsiya qilish
        if($request->synonyms){
            $request['synonyms']=explode(',',$request->synonyms);
            $synonyms=$request['synonyms'];
            foreach ($synonyms as $key => $value) {
                $request->validate([
                    "synonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //antonim so'zlar id sini validatsiya qilish
        if($request->antonyms){
            $request['antonyms']=explode(',',$request->antonyms);
            $antonyms=$request['antonyms'];
            foreach ($antonyms as $key => $value) {
                $request->validate([
                    "antonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //shu so'zlar tegishli bo'lgan kategoriyalar validatsiyasi
        // if(isset($request->categories_id)){
        //     foreach ($request->categories_id as $key => $value) {
        //         $request->validate([
        //             "categories_id."."$key" =>'exists:categories,id'
        //         ]);
        //     }
        // }
        //audio bor yo'qligini tekshirish
        $result = $request->validated();
        if(isset($request->audio)){
            //audioni vaqt bo'yicha nomlash
            $audioName=time().".".$request->audio->getClientOriginalExtension();
            $request->audio->move(public_path('audio/'),$audioName);
            
            $result['audio'] = $audioName;
        }
       
        $result['user_id']=$user->id;
        // $result['user_id']=1;
        //so'zni create qilish
        $created_word=Word::create($result);
        $word=Word::find($created_word->id);
        $word->categories()->sync([$request['categories_id']]);
        $word->synonyms()->sync($request['synonyms']);
        $word->antonyms()->sync($request['antonyms']);

        return response([
            'message'=>"created word",
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

    public function wordday(){
        $kunsozi=Wordoftheday::orderBy('count','desc')->first();      
        return response([
            'message'=>'word of the day',
            'data'=>new WorddayResource(Word::find($kunsozi->word_id))
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWordRequest $request,$id)
    {
         //sinonim so'zlar id sini validatsiya qilish
        if($request->synonyms){
            $request['synonyms']=explode(',',$request->synonyms);
            $synonyms=$request['synonyms'];
            foreach ($synonyms as $key => $value) {
                $request->validate([
                    "synonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //antonim so'zlar id sini validatsiya qilish
        if($request->antonyms){
            $request['antonyms']=explode(',',$request->antonyms);
            $antonyms=$request['antonyms'];
            foreach ($antonyms as $key => $value) {
                $request->validate([
                    "antonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //shu so'zlar tegishli bo'lgan kategoriyalar validatsiyasi
        // foreach ($request->categories_id as $key => $value) {
        //     $request->validate([
        //         "categories_id."."$key" =>'exists:categories,id'
        //     ]);
        // }
            $audio=Word::find($id);
            if(isset($audio)){
                //audio bor yo'qligini tekshirish
                if(isset($request->audio)){
                    if(isset($audio->audio))
                        unlink('audio/'.$audio->audio);
                    //audioni vaqt bo'yicha nomlash
            
                    $audioName=time().".".$request->audio->getClientOriginalExtension();
                    $request->audio->move(public_path('/audio'),$audioName);
                    $result = $request->validated();
                    $result['audio'] = $audioName;
            }else
                $result=$request->validated();
            //update qilish
            Word::find($id)->update($result);
            $word=Word::find($id);
            if($request->categories_id)
                $word->categories()->sync([$request['categories_id']]);
            if($request->synonyms)
                $word->synonyms()->sync($request['synonyms']);
            if($request->antonyms)
                $word->antonyms()->sync($request['antonyms']);

            return response([
                'message'=>"updated successfully",
                'data'=>new WordResource($word)
            ]);
        }else
            return response([
                'message'=>"id not found",
            ],404);
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
            //pivot tablitsadan category larni o'chirish
            WordCategory::where('word_id',$id)->delete();


            //pivot tablitsadan sinonim larni o'chirish
            Synonym::where('synonym_word_id',$id)->orWhere('word_id',$id)->delete();
            
            //pivot tablitsadan antonim larni o'chirish
            Antonym::where('antonym_word_id',$id)->orWhere('word_id',$id)->delete();
                            
            //audio faylni o'chirish
            // if(isset($request->audio))
            //     unlink('audio/'.$request->audio);
            //o'chirish
            $request->delete();
                
            
            return response([
                'message'=>"deleted",
            ],200);
        }
        else
            return response([
                'message'=>"id not found",
            ],404);
    }

    
}
