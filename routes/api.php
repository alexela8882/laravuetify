<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['prefix' => 'users', 'middleware' => ['jwt.auth', 'user_clearance']], function () {

	Route::get('/', 'UserController@all')->name('users');
	Route::post('/', 'UserController@store')->name('user.store');
	Route::put('/update', 'UserController@update')->name('user.update');
	Route::patch('/delete/multiple', 'UserController@delete_multiple')->name('user.delete');

});

Route::group(['prefix' => 'positions', 'middleware' => ['jwt.auth', 'position_clearance']], function () {

	Route::get('/', 'PositionController@all')->name('positions');
	Route::post('/', 'PositionController@store')->name('position.store');
	Route::put('/update', 'PositionController@update')->name('position.update');
	Route::patch('/delete/multiple', 'PositionController@delete_multiple')->name('position.delete');

});

Route::group(['prefix' => 'departments', 'middleware' => ['jwt.auth', 'department_clearance']], function () {

	Route::get('/', 'DepartmentController@all')->name('departments');
	Route::post('/', 'DepartmentController@store')->name('department.store');
	Route::put('/update', 'DepartmentController@update')->name('department.update');
	Route::patch('/delete/multiple', 'DepartmentController@delete_multiple')->name('department.delete');

});

Route::group(['prefix' => 'user-employments', 'middleware' => ['jwt.auth', 'user_employment_clearance']], function () {

	Route::get('/', 'UserEmploymentController@all')->name('user.employments');
	Route::put('/update', 'UserEmploymentController@update')->name('user.employment.update');

});

Route::group(['prefix' => 'branches', 'middleware' => ['jwt.auth', 'branch_clearance']], function () {

	Route::get('/', 'BranchController@all')->name('branches');
	Route::post('/', 'BranchController@store')->name('branch.store');
	Route::put('/update', 'BranchController@update')->name('branch.update');
	Route::patch('/delete/multiple', 'BranchController@delete_multiple')->name('branch.delete');

});

Route::group(['prefix' => 'bscheds', 'middleware' => ['jwt.auth', 'bsched_clearance']], function () {

	Route::get('/', 'BranchScheduleController@all')->name('bscheds');
	Route::post('/', 'BranchScheduleController@store')->name('bsched.store');
	Route::put('/update', 'BranchScheduleController@update')->name('bsched.update');
	Route::patch('/delete/multiple', 'BranchScheduleController@delete_multiple')->name('bsched.delete');

});

Route::group(['prefix' => 'regions', 'middleware' => ['jwt.auth', 'region_clearance']], function () {

	Route::get('/', 'RegionController@all')->name('regions');
	Route::post('/', 'RegionController@store')->name('region.store');
	Route::put('/update', 'RegionController@update')->name('region.update');
	Route::patch('/delete/multiple', 'RegionController@delete_multiple')->name('region.delete');

});

Route::group(['prefix' => 'roles', 'middleware' => ['jwt.auth', 'role_clearance']], function () {

	Route::get('/', 'RoleController@all')->name('roles');
	Route::post('/', 'RoleController@store')->name('role.store');
	Route::put('/update', 'RoleController@update')->name('role.update');
	Route::patch('/delete/multiple', 'RoleController@delete_multiple')->name('role.delete');

});

Route::group(['prefix' => 'permissions', 'middleware' => ['jwt.auth', 'authorization_clearance']], function () {

	Route::get('/', 'PermissionController@all')->name('permissions');
	Route::post('/', 'PermissionController@store')->name('permission.store');
	Route::put('/update', 'PermissionController@update')->name('permission.update');
	Route::patch('/delete/multiple', 'PermissionController@delete_multiple')->name('permission.delete');

});