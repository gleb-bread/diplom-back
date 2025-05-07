<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProjectsRights;
use App\Models\Project;
use App\Enums\ProjectRightEnum;

class ProjectRightsController extends Controller
{
    static public function createRightAtProject(User $user, Project $project, ProjectRightEnum $right){
        $right = ProjectsRights::create([
            'project_id' =>  $project->id,
            'user_id' => $user->id,
            'right' => $right->value,
        ]);

        return $right;
    }
}
