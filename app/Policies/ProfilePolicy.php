<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function show_profile(User $user)
    {
      // Any authenticated user can see a profile
      return Auth::check();
    }

    public function show_edit_profile(User $auth, User $user)
    {
      // Only the user can edit his profile
      return $auth->id == $user->id;
    }

    public function edit(User $auth, User $user)
    {
      // Only the user can edit his profile
      return $auth->id == $user->id;
    }

    public function edit_password(User $auth, User $user)
    {
      // Only the user can edit his password
      return $auth->id == $user->id;
    }

    public function delete(User $auth, User $user)
    {
      // Only the user can delete his profile
      return $auth->id == $user->id;
    }
}
