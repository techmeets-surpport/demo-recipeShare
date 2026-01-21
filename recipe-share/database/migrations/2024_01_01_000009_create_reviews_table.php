<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'recipe_id']);
            $table->index('recipe_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
