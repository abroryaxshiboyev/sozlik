<?php

namespace App\Http\Resources\Category;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public $collects=CategoryWordResource::class;
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'latin' => $this->latin,
            'kiril'=> $this->kiril,
            'words'=>CategoryWordResource::collection($this->words),
        ];
    }
}
