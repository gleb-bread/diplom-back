<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiComponentCookie extends Model
{
    use HasFactory;

    // Указываем таблицу, если имя модели не соответствует имени таблицы
    protected $table = 'api_component_cookies';

    // Указываем поля, которые могут быть массово назначены
    protected $fillable = [
        'api_component_id',
        'key',
        'value',
    ];

    // Определение связи с таблицей api_components
    public function apiComponent()
    {
        return $this->belongsTo(ApiComponents::class, 'api_component_id');
    }
}
