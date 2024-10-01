<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $second_name
 * @property string|null $patronymic
 * @property bool $delayed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereId(int $value)
 * @method static Builder|User whereLogin(string $value)
 * @method static Builder|User whereName(string $value)
 * @method static Builder|User whereEmail(string $value)
 * @method static Builder|User wherePassword(string $value)
 * @method static Builder|User whereSecondName(string $value)
 * @method static Builder|User wherePatronymic(string $value)
 * @method static Builder|User whereDelayed(bool $value)
 * @method static Builder|User whereCreatedAt(Carbon $value)
 * @method static Builder|User whereUpdatedAt(Carbon $value)
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Атрибуты модели по умолчанию.
     *
     * @var array
     */
    protected $attributes = [
        'name'        => null,
        'second_name' => null,
        'patronymic'  => null,
        'delayed'     => true,
    ];

    /**
     * Поля, которые могут быть массово назначаемы.
     *
     * @var array
     */
    protected $fillable = [
        'login',
        'name',
        'email',
        'password',
        'second_name',
        'patronymic',
        'delayed',
    ];

    /**
     * Поля, которые должны быть скрыты для массивов.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Поля, которые должны быть приведены к nативным типам.
     *
     * @var array
     */
    protected $casts = [
        'delayed'     => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Получить полное имя пользователя (name + second_name).
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->second_name;
    }

    /**
     * Установить хэш пароля.
     *
     * @param string $password
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
