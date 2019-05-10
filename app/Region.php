<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function branches () {
        return $this->hasMany('App\Branch')->orderBy('name');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            if ($user->branches()->count() > 0) {
                throw new \Exception("Model have child records");
            }
        });
    }
}
