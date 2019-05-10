<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PositionClearance
{
    
  public function handle($request, Closure $next) {
    // Show :: Show Positions permission doesn't affect showing lists
    if ($request->route()->getName() === 'positions') {
      if (Auth::user()->hasPermissionTo('Show Positions')) {
        return $next($request);
      } else {
        if (Auth::user()->hasAnyPermission('Create Positions',
                                           'Edit Positions',
                                           'Delete Positions')) {
          return $next($request);
        } else {
          return response()->json('Forbidden', 403);
        }
      }
    }

    // Create
    if ($request->route()->getName() === 'position.store') {
      if (!Auth::user()->hasPermissionTo('Create Positions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Edit
    if ($request->route()->getName() === 'position.update') {
      if (!Auth::user()->hasPermissionTo('Edit Positions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    // Delete
    if ($request->route()->getName() === 'position.delete') {
      if (!Auth::user()->hasPermissionTo('Delete Positions')) {
        return response()->json('Forbidden', 418);
      } else {
        return $next($request);
      }
    }

    return $next($request);
  }
}
