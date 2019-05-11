<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleClearance
{
    
  public function handle($request, Closure $next) {
    // Show :: Show Roles permission doesn't affect showing lists
    if ($request->route()->getName() === 'roles') {
      if (Auth::user()->hasPermissionTo('Show Roles')) {
        return $next($request);
      } else {
        if (Auth::user()->hasAnyPermission('Create Roles',
                                           'Edit Roles',
                                           'Delete Roles',
                                           'Show Users',
                                           'Create Users',
                                           'Edit Users',
                                           'Delete Users')) {
          return $next($request);
        } else {
          return response()->json('Forbidden', 403);
        }
      }
    }

    // Create
    if ($request->route()->getName() === 'role.store') {
      if (!Auth::user()->hasPermissionTo('Create Roles')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Edit/Update
    if ($request->route()->getName() === 'role.update') {
      if (!Auth::user()->hasPermissionTo('Edit Roles')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Delete
    if ($request->route()->getName() === 'role.delete') {
      if (!Auth::user()->hasPermissionTo('Delete Roles')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // for users that requires roles
    if ($request->route()->getName() === 'users') {
      if (Auth::user()->hasPermissionTo('Show Roles')) {
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

    return $next($request);
  }
}
