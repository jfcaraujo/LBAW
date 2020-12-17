<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum_question extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'forum_question';
  
  }
  