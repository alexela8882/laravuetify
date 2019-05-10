<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;

use Validator;

class DepartmentController extends Controller
{
    public function all () {
	  	$departments = Department::select('id', 'name')->get();
	  	return response()->json($departments, 200);
	  }

	  public function store (Request $req) {
	    $validator = Validator::make($req->all(), [
	      'name' => 'required|unique:departments,name',
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

	  	$department = new Department;
	    $department->name = $req->name;
	  	$department->save();

	    $department = Department::select('id', 'name')
		    					->where('id', $department->id)
		              ->first();

	  	return response()->json($department, 200);
	  }

	  public function update (Request $req) {
	    $validator = Validator::make($req->all(), [
	      'name' => 'required|unique:departments,name,'.$req->id,
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

	    $department = Department::select('id', 'name')
	              ->where('id', $req->id)->first();
	    $department->name = $req->name;
	    $department->update();

	    return response()->json($department, 200);
	  }

	  public function delete_multiple (Request $req) {
	    $ids = $req;
	    $department = Department::whereIn('id', $ids)->select('id', 'name')->get();
	    $response = $department;
	    Department::whereIn('id', $ids)->delete();

	    return response()->json($response, 200);
	  }
}
