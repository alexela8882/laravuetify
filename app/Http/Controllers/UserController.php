<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserEmployment AS Employment;

use Validator;

class UserController extends Controller
{
    public function all () {
    	$users = User::select('id',
                            'branch_id',
                            \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"),
                            'first_name',
                            'last_name',
                            'email')
               ->with(['roles' => function ($qry) {
                $qry->with('permissions');
               }])
               ->with(['branch' => function ($qry) {
                $qry->select('id', 'name');
               }])
               ->get();
    	return response()->json($users, 200);
    }

    public function store (Request $req) {
      $validator = Validator::make($req->all(), [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users,email',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->getMessages();
        $obj = $validator->failed();
        $result = [];
        foreach($obj as $input => $rules){
          $i = 0;
          foreach($rules as $rule => $ruleInfo){
            $key = $rule;
            $key = $input.'|'.strtolower($key);
            $result[$key] = $errors[$input][$i];
            $i++;
          }
        }
        return response()->json($result, 422);
      }

    	$user = new User;
      $user->first_name = $req->first_name;
      $user->last_name = $req->last_name;
    	$user->branch_id = $req->branch;
    	$user->email = $req->email;
    	$user->password = bcrypt('123$qweR');
    	$user->save();

      // Save for User Employment
      $employment = new Employment;
      $employment->user_id = $user->id;
      $employment->branch_id = $user->branch_id;
      $employment->save();

      $roles = $req->roles;
      if (isset($roles)) {
        $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
      }
      else {
        $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
      }

      $newUser = User::select('id',
                              'branch_id',
                              \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"),
                              'first_name',
                              'last_name',
                              'email')
                 ->with(['roles' => function ($qry) {
                  $qry->with('permissions');
                 }])
                 ->with(['branch' => function ($qry) {
                  $qry->select('id', 'name');
                 }])
                 ->where('id', $user->id)
                 ->first();

    	return response()->json($newUser, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users,email,'.$req->id,
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->getMessages();
        $obj = $validator->failed();
        $result = [];
        foreach($obj as $input => $rules){
          $i = 0;
          foreach($rules as $rule => $ruleInfo){
            $key = $rule;
            $key = $input.'|'.strtolower($key);
            $result[$key] = $errors[$input][$i];
            $i++;
          }
        }
        return response()->json($result, 422);
      }

      $user = User::find($req->id);
      $user->first_name = $req->first_name;
      $user->last_name = $req->last_name;
      $user->branch_id = $req->branch;
      $user->email = $req->email;
      $user->update();

      // Update user_employments table
      $employment = Employment::where('user_id', $user->id)->first();
      $employment->branch_id = $user->branch_id;
      $employment->update();

      $roles = $req->roles;
      if (isset($roles)) {
        $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
      }
      else {
        $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
      }

      $updatedUser = User::select('id',
                                  'branch_id',
                                  \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"),
                                  'first_name',
                                  'last_name',
                                  'email')
                     ->with(['roles' => function ($qry) {
                      $qry->with('permissions');
                     }])
                     ->with(['branch' => function ($qry) {
                      $qry->select('id', 'name');
                     }])
                     ->where('id', $user->id)
                     ->first();

      return response()->json($updatedUser, 200);
    }

    public function delete_multiple (Request $req) {
      $ids = $req;
      $user = User::whereIn('id', $ids)->select('id', 'branch_id', 'first_name', 'last_name', 'email')->get();
      $response = $user;
      User::whereIn('id', $ids)->delete();
      Employment::whereIn('user_id', $ids)->delete();

      return response()->json($response, 200);
    }
}
