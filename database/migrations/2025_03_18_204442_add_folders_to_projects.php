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
        // Создание таблицы folders
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название папки');
            $table->unsignedBigInteger('project_id')->comment('ID проекта, к которому принадлежит папка');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID родительской папки (если есть)');
            $table->unsignedBigInteger('user_id')->comment('ID пользователя, создавшего папку');
            $table->boolean('private')->default(false)->comment('Приватная папка');
            $table->boolean('archive')->default(false)->comment('Архивная папка');
            $table->string('hash', 64)->unique()->comment('Уникальный хэш для папки');
            $table->timestamps();

            // Внешние ключи
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Обновление таблицы pages: добавляем folder_id
        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedBigInteger('folder_id')->nullable()->after('project_id')->comment('ID папки, к которой принадлежит страница');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откат изменений в таблице pages
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn('folder_id');
        });

        // Удаление таблицы folders
        Schema::dropIfExists('folders');
    }
};