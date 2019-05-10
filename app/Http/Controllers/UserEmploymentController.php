<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserEmployment AS Employment;

use Validator;

class UserEmploymentController extends Controller
{
    public function all () {
    	$employments = Employment::select('id',
                          'user_id',
                          'position_id',
                          'department_id',
                          'branch_id',
                          'sss',
                          'payroll',
                          'time_from',
                          'time_to',
                          \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ',TIME_FORMAT(time_to, '%h:%i %p')) AS time"),
                          'remarks',
                          'last_date_reported')
             ->with(['user' => function ($qry) {
            	 $qry->select('id', \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"));
             }])
             ->with(['position' => function ($qry) {
            	 $qry->select('id', 'name');
             }])
             ->with(['department' => function ($qry) {
            	 $qry->select('id', 'name');
             }])
             ->with(['branch' => function ($qry) {
            	 $qry->select('id', 'name');
             }])
             ->get();
    	return response()->json($employments, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'sss' => 'required|unique:user_employments,sss,'.$req->id,
        'payroll' => 'required',
        'branch' => 'required',
        'position' => 'required',
        'department' => 'required',
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

      $employment = Employment::find($req->id);
      $employment->sss = $req->sss;
      $employment->payroll = $req->payroll;
      $employment->branch_id = $req->branch;
      $employment->position_id = $req->position;
      $employment->department_id = $req->department;
      $employment->time_from = $req->time_from;
      $employment->time_to = $req->time_to;
      $employment->update();

      // update user table: branch_id
      $user = User::where('id', $employment->user_id)->first();
      $user->branch_id = $employment->branch_id;
      $user->update();

      $updatedEmployment = Employment::select('id',
                          'user_id',
                          'position_id',
                          'department_id',
                          'branch_id',
                          'sss',
                          'payroll',
                          'time_from',
                          'time_to',
                          \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ',TIME_FORMAT(time_to, '%h:%i %p')) AS time"),
                          'remarks',
                          'last_date_reported')
       ->with(['user' => function ($qry) {
      	 $qry->select('id', \DB::raw("CONCAT(first_name,' ',last_name) AS full_name"));
       }])
       ->with(['position' => function ($qry) {
      	 $qry->select('id', 'name');
       }])
       ->with(['department' => function ($qry) {
      	 $qry->select('id', 'name');
       }])
       ->with(['branch' => function ($qry) {
      	 $qry->select('id', 'name');
       }])
       ->where('id', $employment->id)
       ->first();

      return response()->json($updatedEmployment, 200);
    }
}
