<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmployment extends Model
{
    public function user () {
      return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function department () {
      return $this->belongsTo('App\Department', 'department_id', 'id');
    }

    public function position () {
      return $this->belongsTo('App\Position', 'position_id', 'id');
    }

    public function branch () {
      return $this->belongsTo('App\Branch', 'branch_id', 'id');
    }
}
