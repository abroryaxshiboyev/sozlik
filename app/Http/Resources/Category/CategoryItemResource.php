<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryItemResource extends JsonResource
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
            'words_total'=> count($this->words)
        ];
    }
}
