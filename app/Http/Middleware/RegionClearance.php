<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RegionClearance
{

    public function handle($request, Closure $next) {
      // Show :: Show Regions permission doesn't affect showing lists
      if ($request->route()->getName() === 'regions') {
        if (Auth::user()->hasPermissionTo('Show Regions')) {
          return $next($request);
        } else {
          if (Auth::user()->hasAnyPermission('Create Regions',
                                             'Edit Regions',
                                             'Delete Regions',
                                             'Show Branches',
                                             'Create Branches',
                                             'Edit Branches',
                                             'Delete Branches')) {
            return $next($request);
          } else {
            return response()->json('Forbidden', 403);
          }
        }
      }

      // Create/Store
      if ($request->route()->getName() === 'region.store') {
        if (!Auth::user()->hasPermissionTo('Create Regions')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      // Edit/Update
      if ($request->route()->getName() === 'region.update') {
        if (!Auth::user()->hasPermissionTo('Edit Regions')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      // Delete
      if ($request->route()->getName() === 'region.delete') {
        if (!Auth::user()->hasPermissionTo('Delete Regions')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      return $next($request);
  }
}
