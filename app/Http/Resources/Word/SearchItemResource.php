<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchItemResource extends JsonResource
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
            'word_name'=>$this->word_name
        ];
    }
}
