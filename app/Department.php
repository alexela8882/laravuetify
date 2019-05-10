<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function usersemployment () {
      return $this->hasMany('App\UserEmployment', 'department_id', 'id');
    }
}
