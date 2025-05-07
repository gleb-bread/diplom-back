<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Enums\ProjectRightEnum;

/**
 * App\Models\PageComponent
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $right
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
class ProjectsRights extends Model
{
    protected $table = 'projects_rights';

    /**
     * Поля, которые могут быть массово назначаемы.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'right',
    ];

    /**
     * Поля, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'right' => ProjectRightEnum::class,
    ];
}
