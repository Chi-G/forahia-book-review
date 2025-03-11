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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('review');
            $table->unsignedTinyInteger('rating');
            $table->unsignedTinyInteger('book_id');

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->timestamps();

            // so this command helps to create a foreign key constraint in a single code line.
            // so to use the code line below, you have to comment out line 17 and 20
            // $table->foreignId('book_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
