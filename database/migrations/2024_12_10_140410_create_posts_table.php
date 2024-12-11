<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('cover_image');
            $table->boolean('pinned');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes(); // Add soft deletes
            $table->timestamps();

        });
    }



    public function down()
    {
        Schema::dropIfExists('posts');
    }
};