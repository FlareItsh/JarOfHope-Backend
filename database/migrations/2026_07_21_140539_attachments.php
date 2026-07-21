<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            // Foreign key link to messages table
            $table->foreignUuid('message_uuid')->constrained('messages', 'uuid')->onDelete('cascade');

            // File Metadata
            $table->string('file_path');      // Path in storage disk
            $table->string('file_name');      // Original uploaded filename
            $table->string('mime_type');      // Helps display images vs. documents
            $table->unsignedBigInteger('file_size'); // Size in bytes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
