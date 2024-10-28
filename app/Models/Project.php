<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string|null $type
 * @property bool $private
 * @property bool $archive
 * @property int $user_id
 * @property string $hash
 * @property string $ref
 * @property string $name
 * @property string|null $logo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder|Projects newModelQuery()
 * @method static Builder|Projects newQuery()
 * @method static Builder|Projects query()
 * @method static Builder|Projects whereId(int $value)
 * @method static Builder|Projects whereType(string|null $value)
 * @method static Builder|Projects wherePrivate(bool $value)
 * @method static Builder|Projects whereArchive(bool $value)
 * @method static Builder|Projects whereUserId(int $value)
 * @method static Builder|Projects whereHash(string $value)
 * @method static Builder|Projects whereRef(string $value)
 * @method static Builder|Projects whereName(string $value)
 * @method static Builder|Projects whereLogo(string|null $value)
 * @method static Builder|Projects whereCreatedAt(Carbon $value)
 * @method static Builder|Projects whereUpdatedAt(Carbon $value)
 *
 * @mixin \Eloquent
 */
class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'type',
        'private',
        'archive',
        'user_id',
        'hash',
        'ref',
        'name',
        'logo',
    ];

    protected $casts = [
        'private'    => 'boolean',
        'archive'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь с моделью User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь один ко многим с моделью Page.
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
