<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'comment';
  
    protected $fillable = [
        'id_task', 'creator', 'text',
    ];
    

  public function user() {
    return $this->belongsTo('App\User');
  }

}
  