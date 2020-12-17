<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;
  protected $table = 'project';


  public function user() {
    return $this->belongsTo('App\User');
  }

	public function task_lists() {
		return $this->hasMany('App\TasksList');
	}

}
