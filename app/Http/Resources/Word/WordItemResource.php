<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\JsonResource;

class WordItemResource extends JsonResource
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
            'category_id'=>$this->category_id,
            'latin'=>$this->latin,
            'kiril'=>$this->kiril,
            'description'=>$this->description,
            'count'=>$this->count,
            'audio'=>$this->audio,
            'category'=>new WordCategoryResource($this->category),
        ];
    }
}
