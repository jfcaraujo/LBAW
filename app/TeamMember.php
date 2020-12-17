<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'team_member';
  
  }
  