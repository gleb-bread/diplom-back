<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequest extends Model
{
    use HasFactory;

    protected $table = 'api_requests'; // Укажите правильное имя таблицы, если оно отличается

    protected $fillable = [
        'url',
        'method',
        'headers',
        'cookies',
        'params',
        'status',
        'response',
    ];
}