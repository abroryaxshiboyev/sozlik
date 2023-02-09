<?php

namespace App\Http\Resources\Word;

use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
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
            'created_at'=>$this->created_at,
            'categories'=>WordCategoryResource::collection($this->categories),
        ];
    }
}
