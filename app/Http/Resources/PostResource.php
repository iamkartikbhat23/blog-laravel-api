<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Resources\Json\JsonResource;
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'author' => $this->author->name,
            'author_id' => $this->author_id,
            'likes' => $this->likes->count(),
            'user_like_status' => $this->user_like_status,
            'image' => $this->image_url,
            'created_on' => $this->created_at->format('d/m/Y'),
        ];
    }
}
