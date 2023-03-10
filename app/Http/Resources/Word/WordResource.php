<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'latin'=>$this->latin,
            'kiril'=>$this->kiril,
            'description_latin'=>$this->description_latin,
            'description_kiril'=>$this->description_kiril,
            'count'=>$this->count,
            'audio'=>$this->audio,
            'created_at'=>$this->created_at,
            'categories'=>WordCategoryResource::collection($this->categories),
            'synonyms'=>SynonymResource::collection($this->synonyms),
            'antonyms'=>SynonymResource::collection($this->antonyms)
        ];
    }
}
