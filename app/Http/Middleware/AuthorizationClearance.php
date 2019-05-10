<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthorizationClearance {

  public function handle($request, Closure $next) {
    if (\Auth::check()) {
      $user = User::all()->count();
      if (!($user == 1)) {
        if (!Auth::user()->hasPermissionTo('Administer roles & permissions')) { //If user does not have this permission
          return response()->json('Forbidden', 403);
        }
      }
    }

    // Prevent deletion of Roles & Permissions
    if ($request->route()->getName() === 'permission.update'||
        $request->route()->getName() === 'role.delete' ||
        $request->route()->getName() === 'permission.delete') {
      return response()->json('Forbidden', 418);
    }

    return $next($request);
  }
}
