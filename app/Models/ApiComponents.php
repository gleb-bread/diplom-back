<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiComponents extends Model
{
    use HasFactory;

    // Указываем таблицу, если имя модели не соответствует имени таблицы
    protected $table = 'api_components';

    static $type = 'api';
    static $typeFields = ['api'];

    // Указываем поля, которые могут быть массово назначены
    protected $fillable = [
        'name',
        'method',
        'url',
        'page_id'
    ];

    // Определение связи один ко многим с таблицей api_component_params
    public function params()
    {
        return $this->hasMany(ApiComponentParam::class, 'api_component_id');
    }

    // Определение связи один ко многим с таблицей api_component_cookies
    public function cookies()
    {
        return $this->hasMany(ApiComponentCookie::class, 'api_component_id');
    }

    // Определение связи один ко многим с таблицей api_component_headers
    public function headers()
    {
        return $this->hasMany(ApiComponentHeader::class, 'api_component_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
