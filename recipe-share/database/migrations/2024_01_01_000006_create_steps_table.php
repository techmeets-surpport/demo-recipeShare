<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();

            $table->index('recipe_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('steps');
    }
};
