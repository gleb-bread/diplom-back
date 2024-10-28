<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PageComponent
 *
 * @property int $id
 * @property int $page_id
 * @property int $component_id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|PageComponent newModelQuery()
 * @method static Builder|PageComponent newQuery()
 * @method static Builder|PageComponent query()
 * @method static Builder|PageComponent whereId(int $value)
 * @method static Builder|PageComponent wherePageId(int $value)
 * @method static Builder|PageComponent whereComponentId(int $value)
 * @method static Builder|PageComponent whereType(string $value)
 * @method static Builder|PageComponent whereCreatedAt(Carbon $value)
 * @method static Builder|PageComponent whereUpdatedAt(Carbon $value)
 *
 * @mixin \Eloquent
 */
class PageComponent extends Model
{
    protected $table = 'page_component';

    /**
     * Поля, которые могут быть массово назначаемы.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'component_id',
        'type',
    ];

    /**
     * Поля, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
