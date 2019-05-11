<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BranchScheduleClearance
{
    
    public function handle($request, Closure $next) {
      // Show :: Show Branch Schedules permission doesn't affect showing lists
      if (\Request::route()->getName() === "bscheds") {
        if (Auth::user()->hasPermissionTo('Show Branch Schedules')) {
          return $next($request);
        } else {
          if (Auth::user()->hasAnyPermission('Create Branch Schedules',
                                             'Edit Branch Schedules',
                                             'Delete Branch Schedules',
                                             'Show Branches',
                                             'Create Branches',
                                             'Edit Branches',
                                             'Delete Branches')) {
            return $next($request);
          } else {
            return response()->json('Forbideen', 403);
          }
        }
      }

      // Create/Store
      if (\Request::route()->getName() === "bsched.store") {
        if (!Auth::user()->hasPermissionTo('Create Branch Schedules')) {
          return response()->json('Forbideen', 418);
        } else {
          return $next($request);
        }
      }

      // Edit/Update
      if (\Request::route()->getName() === "bsched.update") {
        if (!Auth::user()->hasPermissionTo('Edit Branch Schedules')) {
          return response()->json('Forbideen', 418);
        } else {
          return $next($request);
        }
      }

      // Delete
      if (\Request::route()->getName() === "bsched.delete") {
        if (!Auth::user()->hasPermissionTo('Delete Branch Schedules')) {
          return response()->json('Forbideen', 418);
        } else {
          return $next($request);
        }
      }

      return $next($request);
  }
}