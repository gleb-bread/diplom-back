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
        Schema::create('text_components', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable(); // Поле для текста
            $table->unsignedBigInteger('page_id'); // Связь с таблицей страниц
            $table->timestamps(); // Поля created_at и updated_at

            // Установка внешнего ключа на page_id
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_components');
    }
};
