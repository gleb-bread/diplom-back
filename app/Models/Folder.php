<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    // Указываем имя таблицы, если оно не соответствует соглашению Laravel (по умолчанию "folders")
    protected $table = 'folders';

    // Указываем первичный ключ
    protected $primaryKey = 'id';

    // Указываем, что первичный ключ является автоинкрементным
    public $incrementing = true;

    // Указываем типы полей для корректного приведения данных
    protected $casts = [
        'private' => 'boolean',
        'archive' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Поля, которые можно массово заполнять
    protected $fillable = [
        'name',
        'project_id',
        'parent_id',
        'user_id',
        'private',
        'archive',
        'hash',
    ];

    // Отключаем автоматическое управление временными метками (если вы их задаете вручную, уберите эту строку)
    public $timestamps = true;

    /**
     * Связь с проектом, к которому принадлежит папка
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Связь с пользователем, создавшим папку
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Связь с родительской папкой
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    /**
     * Связь с дочерними папками
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Связь со страницами, находящимися в этой папке
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'folder_id');
    }
}