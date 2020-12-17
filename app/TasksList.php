<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TasksList extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;
  protected $table = 'tasks_list';


  public function user() {
    return $this->belongsTo('App\User');
  }

}
