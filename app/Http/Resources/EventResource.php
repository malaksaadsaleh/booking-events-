<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);
       return[
        'id'                => $this->id,
        'title'             => $this->title,
        'describtion'       => $this->describtion,
        'address'           => $this->address,
        'start date'        => $this->start_date,
        'avaliable seats'   => $this->avaliable_seats,
        'location'          => $this->location,
        'image'             => $this->getMedia('main_image')->map(function($media){
            return $media->getUrl();
        }),
        'category'          => new CategoryResource($this->whenloaded('category')),
       ];  

    }
}
