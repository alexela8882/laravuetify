<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission AS Perm;

use Validator;

class PermissionController extends Controller
{
    public function all () {
    	$perms = Perm::select('id', 'name')->get();
    	return response()->json($perms, 200);
    }

    public function store (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:permissions,name',
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

    	$perm = new Perm;
      $perm->name = $req->name;
    	$perm->guard_name = 'api';
    	$perm->save();

      $perm = Perm::select('id', 'name')
                  ->where('id', $perm->id)
                  ->first();

    	return response()->json($perm, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:permissions,name,'.$req->id,
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

      $perm = Perm::select('id', 'name')->where('id', $req->id)->first();
      $perm->name = $req->name;
      $perm->guard_name = 'api';
      $perm->update();

      return response()->json($perm, 200);
    }

    public function delete_multiple (Request $req) {
      $ids = $req;
      $perm = Perm::whereIn('id', $ids)->select('id', 'name')->get();
      $response = $perm;
      Perm::whereIn('id', $ids)->delete();

      return response()->json($response, 200);
    }
}
