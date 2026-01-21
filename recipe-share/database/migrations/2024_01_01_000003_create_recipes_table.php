<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('title', 100);
            $table->text('description');
            $table->integer('cooking_time')->comment('調理時間（分）');
            $table->integer('servings')->comment('人数');
            $table->string('difficulty', 10)->default('medium'); // easy, medium, hard
            $table->string('main_image')->comment('storage/recipes/xxx.jpg');
            $table->boolean('is_public')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('category_id');
            $table->index('is_public');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipes');
    }
};
