<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\JsonResource;

class WorddayResource extends JsonResource
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
            'example_latin'=>$this->example_latin,
            'example_kiril'=>$this->example_kiril,
            'audio'=>$this->audio,
            'created_at'=>$this->created_at,
            'categories'=>WordCategoryResource::collection($this->categories),
        ];
    }
}
