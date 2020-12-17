<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
Route::get('/', 'Auth\LoginController@home');
Route::get('home', 'HomeController@show_home')->name('home');

// Users
Route::get('users/{id}', 'ProfileController@show_profile')->name('user');
Route::get('users/{id}/edit', 'ProfileController@show_edit_profile');

// Project
Route::get('projects/create', 'ProjectController@show_project_form')->name('projects/show_create');
Route::get('projects/{id}', 'ProjectController@show_project')->name('projectAbout');
Route::get('projects/{id}/edit','ProjectController@show_edit_project');

// Forum
Route::get('projects/{id}/forum', 'ForumController@show_forum')->name('projectForum');

// Task List
Route::get('projects/{id}/taskLists', 'TaskController@show_task_lists')->name('projectTaskList');
// Tasks of a list
Route::get('projects/{id}/tasksList/{list_id}/tasks', 'TaskController@show_tasks')->name('projectTasks');
// Task
Route::get('projects/{id}/tasks/{taskId}', 'TaskController@show_task')->name('task');

// Members
Route::get('projects/{id}/members', 'MembersController@show_members')->name('members');


// API User
Route::post('api/users/updatePassword', 'ProfileController@edit_password');
Route::post('api/users/{id}/edit', 'ProfileController@edit')->name('editProfile');
Route::get('api/users/{id}/remove', 'ProfileController@delete')->name('deleteUser');
// API Home
Route::post('api/projects', 'HomeController@search');
// API Project
Route::post('api/projects/create', 'ProjectController@create')->name('projects/create');
Route::post('api/projects/{id}/edit','ProjectController@edit')->name('projects/edit');
// API Forum
Route::post('api/forum/search', 'ForumController@search');
Route::post('api/forum/addQuestion', 'ForumController@addQuestion');
Route::post('api/forum/addAnswer', 'ForumController@addAnswer');
Route::post('api/forum/deleteQuestion', 'ForumController@deleteQuestion');
Route::post('api/forum/deleteAnswer', 'ForumController@deleteAnswer');
// API TaskList
Route::post('api/taskLists/create', 'TaskController@create_task_list');
Route::post('api/taskLists/edit', 'TaskController@edit_task_lists');
Route::post('api/taskLists/delete', 'TaskController@delete_task_list');
Route::post('api/taskLists/assign', 'TaskController@assign');
Route::post('api/taskLists/search', 'TaskController@searchTaskList');
// API Tasks
Route::post('api/tasks/create', 'TaskController@create_task');
Route::post('api/tasks/edit', 'TaskController@edit_task');
Route::post('api/tasks/solve', 'TaskController@solve_task');
Route::post('api/tasks/comment', 'TaskController@add_comment');
Route::post('api/tasks/comment/remove', 'TaskController@remove_comment');
Route::post('api/tasks/search/name', 'TaskController@searchTasksName');
Route::post('api/tasks/search/category', 'TaskController@searchTasksCategory');
Route::post('api/tasks/remove', 'TaskController@remove_task_ajax');
Route::get('api/tasks/{taskId}/remove', 'TaskController@remove_task')->name('remove_task');
// API Members
Route::post('api/projects/members/members', 'MembersController@searchMembers');
Route::post('api/projects/members/teams', 'MembersController@searchTeam');
Route::post('api/projects/{id}/members/add/Member', 'MembersController@addMember')->name('addMember');
Route::post('api/projects/{id}/members/add/Team', 'MembersController@addTeam')->name('addTeam');
Route::post('api/projects/{id}/members/add/TeamMember', 'MembersController@addTeamMember')->name('addTeamMember');
Route::post('api/projects/{id}/members/remove/TeamMember', 'MembersController@removeTeamMember')->name('removeTeamMember');
Route::get('api/projects/{id}/members/delete/{memberId}', 'MembersController@deleteMember')->name('deleteMember');
Route::get('api/projects/{id}/members/upgrade/{memberId}', 'MembersController@upgradeMember')->name('upgradeMember');
Route::get('api/projects/{id}/members/deleteTeam/{teamId}', 'MembersController@deleteTeam')->name('deleteTeam');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
