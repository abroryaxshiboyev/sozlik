<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public $collects=SearchItemResource::class;
    public function toArray($request)
    {
        return [
            'message' => 'all words',
            'data'=>$this->collection,
            'total'=>$this->total(),
        ];
    }
}
