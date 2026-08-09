<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AttachmentResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        if ($this->relationLoaded('user')) {
            $data['user'] = $this->user;
        }
        if ($this->relationLoaded('attachments')) {
            $data['attachments'] = AttachmentResource::collection($this->attachments);
        }
        if ($this->relationLoaded('replies')) {
            $data['replies'] = MessageResource::collection($this->replies);
        }
        return $data;
    }
}