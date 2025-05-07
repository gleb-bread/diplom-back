<?php

namespace App\Services\ProjectRights;

use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectRightEnum;
use App\Models\ProjectsRights;

class ProjectRightsService
{
    static array $hasPermissionShow = [
        ProjectRightEnum::Admin->value,
        ProjectRightEnum::Editor->value,
        ProjectRightEnum::Reader->value,
    ];

    static array $hasPermissionEdit = [
        ProjectRightEnum::Admin->value,
        ProjectRightEnum::Editor->value,
    ];

    static array $hasPermissionAddUsers = [
        ProjectRightEnum::Admin->value,
        ProjectRightEnum::Manager->value,
    ];

    /**
     * Получить список прав пользователя в проекте
     */
    public static function checkRightUserAtProject(User $user, Project $project): array
    {
        // Если пользователь является владельцем проекта, то он админ
        if ($user->id === $project->user_id) {
            return [ProjectRightEnum::Admin->value];
        }

        // Иначе ищем его права в таблице ProjectsRights
        $rights = ProjectsRights::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->pluck('right')
            ->toArray();

        return $rights;
    }

    /**
     * Проверка, может ли пользователь просматривать проект
     */
    public static function hasPermissionShowProject(User $user, Project $project): bool
    {
        $userRights = self::checkRightUserAtProject($user, $project);
        return self::hasAnyPermission($userRights, self::$hasPermissionShow);
    }

    /**
     * Проверка, может ли пользователь редактировать проект
     */
    public static function hasPermissionEditProject(User $user, Project $project): bool
    {
        $userRights = self::checkRightUserAtProject($user, $project);
        return self::hasAnyPermission($userRights, self::$hasPermissionEdit);
    }

    /**
     * Проверка, может ли пользователь управлять пользователями проекта
     */
    public static function hasPermissionAddUsers(User $user, Project $project): bool
    {
        $userRights = self::checkRightUserAtProject($user, $project);
        return self::hasAnyPermission($userRights, self::$hasPermissionAddUsers);
    }

    /**
     * Вспомогательная функция: проверка наличия хотя бы одного права
     */
    protected static function hasAnyPermission(array $userRights, array $allowedRights): bool
    {
        return count(array_intersect($userRights, $allowedRights)) > 0;
    }
}
