<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function usersemployment () {
      return $this->hasMany('App\UserEmployment', 'position_id', 'id');
    }
}
