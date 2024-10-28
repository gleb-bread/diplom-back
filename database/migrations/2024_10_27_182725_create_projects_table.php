<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Автоинкрементный первичный ключ
            $table->string('type')->nullable(); // Тип проекта (может быть пустым)
            $table->boolean('private')->default(false); // Признак приватности
            $table->boolean('archive')->default(false); // Признак архивации
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Внешний ключ к таблице users
            $table->string('hash')->unique(); // Хэш проекта
            $table->string('ref')->unique(); // Реферальная ссылка
            $table->string('name'); // Название проекта
            $table->string('logo')->nullable(); // Логотип проекта (может быть пустым)
            $table->timestamps(); // Временные метки created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
