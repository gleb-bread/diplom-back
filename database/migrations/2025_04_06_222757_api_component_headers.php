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
        Schema::create('api_component_headers', function (Blueprint $table) {
            $table->id(); // Автоматическое добавление id
            $table->foreignId('api_component_id')->constrained('api_components')->onDelete('cascade'); // Связь с таблицей api_requests
            $table->string('key'); 
            $table->string('value'); 
            $table->timestamps(); // Метки времени
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_component_headers');
    }
};
