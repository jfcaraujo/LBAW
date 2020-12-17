<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'task';

    protected $fillable = [
        'id', 'name', 'description', 'id_list', 'creator', 'created', 'solver', 'solved', 'category',
    ];



    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
