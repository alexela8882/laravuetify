<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

	public function users () {
    	return $this->hasMany('App\User');
	}

  public function schedule () {
      return $this->belongsTo('App\BranchSchedule', 'bsched_id', 'id');
  }

  public function region () { // for pending transaction monitoring web app
      return $this->belongsTo('App\Region');
  }

  protected static function boot() {
      parent::boot();

      static::deleting(function($user) {
          if ($user->users()->count() > 0) {
              throw new \Exception("Model have child records");
          }
      });
  }
}
