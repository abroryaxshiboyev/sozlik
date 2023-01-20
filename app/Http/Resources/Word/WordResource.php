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
            'categories'=>WordCategoryResource::collection($this->category),
        ];
    }
}
