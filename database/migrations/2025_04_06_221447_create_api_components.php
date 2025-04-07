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
        // Создание таблицы api_components
        Schema::create('api_components', function (Blueprint $table) {
            $table->id(); // Автоматическое добавление id
            $table->text('name')->nullable()->default(null); // Название компонента
            $table->string('method', 8)->nullable()->default(null); // Метод запроса (GET, POST и т. д.)
            $table->text('url')->nullable()->default(null); // URL для API запроса
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade')->onUpdate('cascade'); // Связь с таблицей pages
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
        // Удаление таблицы api_components
        Schema::dropIfExists('api_components');
    }
};
