<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionClearance
{
    
  public function handle($request, Closure $next) {
    // Show :: Show Permissions permission doesn't affect showing lists
    if ($request->route()->getName() === 'permissions') {
      if (Auth::user()->hasPermissionTo('Show Permissions')) {
        return $next($request);
      } else {
        if (Auth::user()->hasAnyPermission('Create Permissions',
                                           'Edit Permissions',
                                           'Delete Permissions',
                                           'Show Roles',
                                           'Create Roles',
                                           'Edit Roles',
                                           'Delete Roles')) {
          return $next($request);
        } else {
          return response()->json('Forbidden', 403);
        }
      }
    }

    // Create
    if ($request->route()->getName() === 'permission.store') {
      if (!Auth::user()->hasPermissionTo('Create Permissions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Edit/Update
    if ($request->route()->getName() === 'permission.update') {
      if (!Auth::user()->hasPermissionTo('Edit Permissions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Delete
    if ($request->route()->getName() === 'permission.delete') {
      if (!Auth::user()->hasPermissionTo('Delete Permissions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    return $next($request);
  }
}
