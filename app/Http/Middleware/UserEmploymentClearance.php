<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserEmploymentClearance
{
    
    public function handle($request, Closure $next) {
      // Show :: Show User Employments permission doesn't affect showing lists
      if ($request->route()->getName() === 'user.employments') {
        if (Auth::user()->hasPermissionTo('Show User Employments')) {
          return $next($request);
        } else {
          if (Auth::user()->hasAnyPermission('Edit User Employments')) {
            return $next($request);
          } else {
            abort('403');
          }
        }
      }

      // Edit
      if ($request->route()->getName() === 'user.employment.update') {
        if (!Auth::user()->hasPermissionTo('Edit User Employments')) {
          abort('403');
        } else {
          return $next($request);
        }
      }

      return $next($request);
    }
}
