<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_messages', function (Blueprint $table) {

            $table->id();

            /*
            group
            */

            $table->foreignId('group_id')
                ->constrained('chat_groups')
                ->cascadeOnDelete();

            /*
            sender
            */

            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            content
            */

            $table->longText('message')
                ->nullable();

            /*
            type
            */

            $table->enum('type', [

                'text',
                'image',
                'video',
                'audio',
                'file',
                'location',
            ])->default('text');

            /*
            file
            */

            $table->string('file')
                ->nullable();

            /*
            location
            */

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_messages');
    }
};