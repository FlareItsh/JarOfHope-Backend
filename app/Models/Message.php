<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[Fillable([
    'uuid',
    'message',
    'category',
    'user_uuid'
])]

class Message extends Model
{
    use HasUuids;

    /**
     * Specify which column acts as the primary UUID key.
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'message_uuid', 'uuid');
    }
}
