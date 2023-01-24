<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWordRequest;
use App\Http\Requests\UpdateWordRequest;
use App\Http\Resources\Word\CountWordResource;
use App\Http\Resources\Word\WordCollection;
use App\Http\Resources\Word\WordItemResource;
use App\Http\Resources\Word\WordResource;
use App\Models\Antonym;
use App\Models\Synonym;
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
        //sinonim so'zlar id sini validatsiya qilish
        if(isset($request->synonyms)){
            $synonyms=$request->synonyms;
            foreach ($synonyms as $key => $value) {
                $request->validate([
                    "synonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //antonim so'zlar id sini validatsiya qilish
        if(isset($request->antonyms)){
            $antonyms=$request->antonyms;
            foreach ($antonyms as $key => $value) {
                $request->validate([
                    "antonyms."."$key"=>'exists:words,id'
                ]);
            }
        }
        //shu so'zlar tegishli bo'lgan kategoriyalar validatsiyasi
        foreach ($request->categories_id as $key => $value) {
            $request->validate([
                "categories_id."."$key" =>'exists:categories,id'
            ]);
        }
        //audio bor yo'qligini tekshirish
        if(isset($request->audio)){
            //audioni vaqt bo'yicha nomlash
            $audioName=time().".".$request->audio->getClientOriginalExtension();
            $request->audio->move(public_path('/audio'),$audioName);
            $result = $request->validated();
            $result['audio'] = 'audio/'.$audioName;
        }
        else{
            $result=$request->validated();
        }
        //so'zni create qilish
        $created_word=Word::create($result);
        $id=$created_word->id;
        //pivot tablitsaga category ni create qilish
        foreach ($request->categories_id as  $key=>$value ){
            WordCategory::create(['category_id'=>$value,'word_id'=>$id]);
        }
        //pivot tablitsaga sinonim so'zlarni qo'shish agar bor bo'lsa
        if(isset($request->synonyms))
            foreach ($synonyms as $key => $value) {
                Synonym::create(['word_id'=>$value,'synonym_word_id'=>$id]);
            }
        
        //pivot tablitsaga antonim so'zlarni qo'shish agar bor bo'lsa
        if(isset($request->antonyms))
            foreach ($antonyms as $key => $value) {
                Antonym::create(['word_id'=>$value,'antonym_word_id'=>$id]);
            }
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWordRequest $request, $id)
    {
        //sinonim so'zlar validatsiyasi
        if(isset($request->synonyms)){
            $synonyms=$request->synonyms;
            foreach ($synonyms as $key => $value) {
                $request->validate([
                    "synonyms."."$key"=>'exists:words,id'
                ]);

             }
        }
        //sinonim so'zlar validatsiyasi
        if(isset($request->antonyms)){
            $antonyms=$request->antonyms;
            foreach ($antonyms as $key => $value) {
                $request->validate([
                    "antonyms."."$key"=>'exists:words,id'
                ]);

             }
        }
        //shu so'zlar tegishli bo'lgan kategoriyalar validatsiyasi
        foreach ($request->categories_id as $key => $value) {
            $request->validate([
                "categories_id."."$key" =>'exists:categories,id'
            ]);
        }

        $audio=Word::find($id);
        //audio bor yo'qligini tekshirish
        if(isset($audio)){
            if(isset($audio->audio))
                unlink($audio->audio);
            if(isset($request->audio)){
                //audioni vaqt bo'yicha nomlash
                $audioName=time().".".$request->audio->getClientOriginalExtension();
                $request->audio->move(public_path('/audio'),$audioName);
                $result = $request->validated();
                $result['audio'] = 'audio/'.$audioName;
            }else
                $result=$request->validated();
            //update qilish
            Word::find($id)->update($result);
            $word=Word::find($id);
            //shu so'zga tegishli bo'lgan kategoriyalarni pivot tablitsadan o'chirish
            $categories=WordCategory::where('word_id',$id)->get();
            foreach ($categories as $key => $value) {
                WordCategory::find($value['id'])->delete();
            }
            //shu so'zga tegishli bo'lgan kategoriyalarni pivot tablitsaga qo'shish
            foreach ($request->categories_id as  $key=>$value ){
                WordCategory::create(['category_id'=>$value,'word_id'=>$id]);
            }
            //sinonim so'zlarni pivot tablitsadan o'chirish
            $synonyms_=Synonym::where('synonym_word_id',$id)->get();
            foreach ($synonyms_ as $key => $value) {
                Synonym::find($value['id'])->delete();
            }
            //sinonim so'zlarni pivot tablitsaga qo'shish
            if(isset($request->synonyms))
                foreach ($synonyms as $key => $value) {
                    Synonym::create(['word_id'=>$value,'synonym_word_id'=>$id]);
                }
            //antonim so'zlarni pivot tablitsadan o'chirish
            $antonyms_=Antonym::where('antonym_word_id',$id)->get();
            foreach ($antonyms_ as $key => $value) {
                Antonym::find($value['id'])->delete();
            }
            //antonim so'zlarni pivot tablitsaga qo'shish
            if(isset($request->antonyms))
                foreach ($antonyms as $key => $value) {
                    Antonym::create(['word_id'=>$value,'antonym_word_id'=>$id]);
                }
            
            return response([
                'message'=>"updated successfully",
                'data'=>new WordResource($word)
            ]);
    }
    else{
        return response([
            'message'=>'id not found',
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
            //pivot tablitsadan category larni o'chirish
            $categories=WordCategory::where('word_id',$id)->get();
            foreach ($categories as $key => $value) {
                $pivot_id=WordCategory::find($value->id)->delete();
            }

            //pivot tablitsadan sinonim larni o'chirish
            $synonyms_=Synonym::where('synonym_word_id',$id)->orWhere('word_id',$id)->get();
            foreach ($synonyms_ as $key => $value) {
                Synonym::find($value['id'])->delete();
            }
            //pivot tablitsadan antonim larni o'chirish
            $antonyms_=Antonym::where('antonym_word_id',$id)->orWhere('word_id',$id)->get();
            foreach ($antonyms_ as $key => $value) {
                Antonym::find($value['id'])->delete();
            }
                        
            //audio faylni o'chirish
            if(isset($request->audio))
                unlink($request->audio);
            //o'chirish
            $request->delete();
            
        
        return response([
            'message'=>"deleted",
        ]);
    }else {
        return response([
            'message'=>"id not found",
        ],404);
    }
    }

    // public function countSort(){
    //    $words = Word::query()->orderBy('count', 'desc')->limit(9);
    //    $word_counts=CountWordResource::collection($words);
    //     return response([
            
    //         'counts'=>$word_counts
    //     ]);
    // }
}
