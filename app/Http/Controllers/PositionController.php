<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Position;

use Validator;

class PositionController extends Controller
{
	  public function all () {
	  	$positions = Position::select('id', 'name')->get();
	  	return response()->json($positions, 200);
	  }

	  public function store (Request $req) {
	    $validator = Validator::make($req->all(), [
	      'name' => 'required|unique:positions,name',
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

	  	$position = new Position;
	    $position->name = $req->name;
	  	$position->save();

	    $position = Position::select('id', 'name')
		    					->where('id', $position->id)
		              ->first();

	  	return response()->json($position, 200);
	  }

	  public function update (Request $req) {
	    $validator = Validator::make($req->all(), [
	      'name' => 'required|unique:positions,name,'.$req->id,
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

	    $position = Position::select('id', 'name')
	              ->where('id', $req->id)->first();
	    $position->name = $req->name;
	    $position->update();

	    return response()->json($position, 200);
	  }

	  public function delete_multiple (Request $req) {
	    $ids = $req;
	    $position = Position::whereIn('id', $ids)->select('id', 'name')->get();
	    $response = $position;
	    Position::whereIn('id', $ids)->delete();

	    return response()->json($response, 200);
	  }
}
