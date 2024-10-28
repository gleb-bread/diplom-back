<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TextComponent
 *
 * @property int $id
 * @property string|null $text
 * @property int $page_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|TextComponent newModelQuery()
 * @method static Builder|TextComponent newQuery()
 * @method static Builder|TextComponent query()
 * @method static Builder|TextComponent whereId(int $value)
 * @method static Builder|TextComponent whereText(string|null $value)
 * @method static Builder|TextComponent wherePageId(int $value)
 * @method static Builder|TextComponent whereCreatedAt(Carbon $value)
 * @method static Builder|TextComponent whereUpdatedAt(Carbon $value)
 *
 * @mixin \Eloquent
 */
class TextComponent extends Model
{
    protected $table = 'text_components';

    /**
     * Поля, которые могут быть массово назначаемы.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'page_id',
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

     /**
     * Связь с моделью Page.
     *
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
