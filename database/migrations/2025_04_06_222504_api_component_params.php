<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_component_params', function (Blueprint $table) {
            $table->id(); // Автоматическое добавление id
            $table->foreignId('api_component_id')->constrained('api_components')->onDelete('cascade'); // Связь с таблицей api_requests
            $table->string('key'); 
            $table->string('value'); 
            $table->timestamps(); // Метки времени
        });
    }

    /**
     * Обратный ход миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_component_params');
    }
};