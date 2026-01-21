<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('quantity', 50);
            $table->integer('display_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('recipe_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
};
