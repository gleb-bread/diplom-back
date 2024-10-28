<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $type
 * @property bool $private
 * @property bool $archive
 * @property int $user_id
 * @property string $hash
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page whereId(int $value)
 * @method static Builder|Page whereType(string|null $value)
 * @method static Builder|Page wherePrivate(bool $value)
 * @method static Builder|Page whereArchive(bool $value)
 * @method static Builder|Page whereUserId(int $value)
 * @method static Builder|Page whereHash(string $value)
 * @method static Builder|Page whereName(string $value)
 * @method static Builder|Page whereCreatedAt(Carbon $value)
 * @method static Builder|Page whereUpdatedAt(Carbon $value)
 *
 * @mixin \Eloquent
 */
class Page extends Model
{
    protected $table = 'pages';

    /**
     * Поля, которые могут быть массово назначаемы.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'private',
        'archive',
        'user_id',
        'hash',
        'name',
        'project_id'
    ];

    /**
     * Поля, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'private'    => 'boolean',
        'archive'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь с моделью Project.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Связь с текстовыми компонентами.
     *
     * @return HasMany
     */
    public function textComponents(): HasMany
    {
        return $this->hasMany(TextComponent::class);
    }
}
