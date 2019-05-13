<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Article extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Return All Fields
        // return parent::toArray($request);

        // Return a specific fields
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at->format('M d, Y')
        ];

    }

    public function with($request){
        return [
            'version' => '1.0.0',
            'author' => 'Gerry Barron'
        ];
    }
}
