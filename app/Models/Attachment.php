<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[Fillable([
    'message_uuid',
    'file_path',
    'file_name',
    'mime_type',
    'file_size',
])]

#[Hidden([
    'file_path', // Prevents internal server paths from being exposed via JSON
])]

class Attachment extends Model
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
     * Get the message that owns the attachment.
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_uuid', 'uuid');
    }
}
