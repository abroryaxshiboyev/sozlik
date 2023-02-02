<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
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
