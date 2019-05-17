<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DepartmentClearance
{
    
    public function handle($request, Closure $next) {
      // Show :: Show Departments permission doesn't affect showing lists
      if ($request->route()->getName() === 'departments') {
        if (Auth::user()->hasPermissionTo('Show Departments')) {
          return $next($request);
        } else {
          if (Auth::user()->hasAnyPermission('Create Departments',
                                             'Edit Departments',
                                             'Delete Departments',
                                             'Show User Employments',
                                             'Create User Employments',
                                             'Edit User Employments',
                                             'Delete User Employments')) {
            return $next($request);
          } else {
            return response()->json('Forbidden', 403);
          }
        }
      }

      // Create/Store
      if ($request->route()->getName() === 'department.store') {
        if (!Auth::user()->hasPermissionTo('Create Departments')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      // Edit/Update
      if ($request->route()->getName() === 'department.update') {
        if (!Auth::user()->hasPermissionTo('Edit Departments')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      // Delete
      if ($request->route()->getName() === 'department.delete') {
        if (!Auth::user()->hasPermissionTo('Delete Departments')) {
          return response()->json('Forbidden', 418);
        } else {
          return $next($request);
        }
      }

      return $next($request);
    }
}
