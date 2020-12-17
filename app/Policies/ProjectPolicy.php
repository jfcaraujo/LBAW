<?php

namespace App\Policies;

use App\User;
use App\Project;
use App\Member;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function isAuthenticated(User $user, Project $project)
    {
        return Auth::check();
    }

    public function isMember(User $user, Project $project)
    {
        return Member::whereId_project($project->id)->whereId_profile($user->id)->exists();
    }

    public function isAdmin(User $user, Project $project)
    {
        return Member::whereId_project($project->id)->whereId_profile($user->id)->whereCoordinator(true)->exists();
    }
}
