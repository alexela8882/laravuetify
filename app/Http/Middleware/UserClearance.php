<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserClearance
{
    
    public function handle($request, Closure $next) {
      // Show :: Show User permission doesn't affect showing lists
      if ($request->route()->getName() === 'users') {
        if (Auth::user()->hasPermissionTo('Show Users')) {
            return $next($request);
        } else {
            if (Auth::user()->hasAnyPermission('Create Users',
                                               'Edit Users',
                                               'Delete Users')) {
                return $next($request);
            } else {
                return response()->json('Forbidden', 403);
            }
        }
      }

      // Create
      if ($request->route()->getName() === 'user.store') {
        if (!Auth::user()->hasPermissionTo('Create Users')) {
            return response()->json('Forbidden', 418);
        } else {
            return $next($request);
        }
      }

      // Edit/Update
      if ($request->route()->getName() === 'user.update') {
        if (!Auth::user()->hasPermissionTo('Edit Users')) {
            return response()->json('Forbidden', 418);
        } else {
            return $next($request);
        }
      }

      // Delete
      if ($request->route()->getName() === 'user.delete') {
        if (!Auth::user()->hasPermissionTo('Delete Users')) {
            return response()->json('Forbidden', 418);
        } else {
            return $next($request);
        }
      }

      return $next($request);
    }
}
