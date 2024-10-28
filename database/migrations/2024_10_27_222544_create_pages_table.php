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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название страницы');
            $table->string('type')->nullable()->comment('Тип страницы');
            $table->boolean('private')->default(false)->comment('Приватная страница');
            $table->boolean('archive')->default(false)->comment('Архивная страница');
            $table->unsignedBigInteger('user_id')->comment('ID пользователя, создавшего страницу');
            $table->unsignedBigInteger('project_id')->comment('ID проекта, к которому принадлежит страница');
            $table->string('hash', 64)->unique()->comment('Уникальный хэш для страницы');
            $table->timestamps();

            // Определяем внешний ключ для project_id
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
