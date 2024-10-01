<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 
 * @property int $id
 * @property int|null $user_id
 * @property string|null $token
 * @property string $method
 * @property string $title
 * @property int $time
 * @property int $status
 * @property array|null $payload
 * @property array|null $headers
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Log extends Model
{
    protected $table = 'logs';

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'token',
        'method',
        'title',
        'time',
        'status',
        'payload',
        'headers',
    ];
}
