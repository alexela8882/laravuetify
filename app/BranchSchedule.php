<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSchedule extends Model
{
    public function branches () {
      	return $this->hasMany('App\Branch', 'bsched_id', 'id');
    }
}
