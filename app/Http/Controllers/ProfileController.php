<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\User;
use Hash;

class ProfileController extends Controller
{
  public function show_profile($id) {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    if($id==0) return redirect("home");

    $profile = User::find($id);

    $this->authorize('show_profile',$profile);

    return view('pages.profile')->with('profile', $profile);
  }

  public function show_edit_profile($id) {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    $profile = User::find($id);

    $this->authorize('show_edit_profile',$profile);

    return view('pages.profile_edit')->with('profile', $profile);
  }

  public function edit(Request $request, $id) {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    $profile = User::find($id);

    $this->authorize('edit',$profile);

    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:profile,email,'.$id,
      'username' => 'required|string|max:255|unique:profile,username,'.$id,
      'imagefile' => 'nullable|max:10000',
    ]);

    $profile->name = $request['name'];
    $profile->username = $request['username'];
    $profile->email = $request['email'];

    if ($request['imagefile']!=NULL){
      $image=$request->file("imagefile");
      $profileImageName = "profileImage-" . Auth::id() . ".png";
      $image->move('profile_images/', $profileImageName);
      $profile->image=$profileImageName;
    }

      $profile->save();
      return redirect('users/'.$id)->with('has_saved', "true");
  }

  public function edit_password(Request $request) {
    if (!Auth::check()) return redirect('/login');
    $profile = Auth::user();

    $this->authorize('edit',$profile);

    $validatedData = $request->validate([
      'current_pass' => 'required|string|min:6',
      'new_pass' => 'required|string|confirmed|min:6',
    ]);
    if (Hash::check($request['current_pass'], $profile->password)) {
      $profile->password = bcrypt($request['new_pass']);
      $profile->save();
      return 0;
    }
    else {
      return 1;
    }
  }

  public function delete($id) {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    $user = User::find($id);

    $this->authorize('delete',$user);
    if ($user->image != 'EmptyUserPicture.jpg')
      File::delete('profile_images/' . ($user->image));
    $user->delete();

    return redirect('logout');
  }
}
