<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BranchSchedule as BSched;

use Validator;

class BranchScheduleController extends Controller
{
    public function all () {
    	$bscheds = BSched::select('id',
                   'time_from',
                   'time_to',
                   \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ',TIME_FORMAT(time_to, '%h:%i %p')) AS time"))
                  ->with(['branches' => function ($qry) {
                    $qry->select('bsched_id', 'name');
                  }])
                  ->get();
    	return response()->json($bscheds, 200);
    }

    public function store (Request $req) {
      $validator = Validator::make($req->all(), [
        'time_from' => 'required',
        'time_to' => 'required',
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

    	$bsched = new BSched;
      $bsched->time_from = $req->time_from;
      $bsched->time_to = $req->time_to;
    	$bsched->save();

      $bsched = BSched::select('id',
                  'time_from',
                  'time_to',
                  \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ',TIME_FORMAT(time_to, '%h:%i %p')) AS time"))
                  ->with(['branches' => function ($qry) {
                    $qry->select('bsched_id', 'name');
                  }])
                  ->where('id', $bsched->id)
                  ->first();

    	return response()->json($bsched, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'time_from' => 'required',
        'time_to' => 'required',
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

      $bsched = BSched::find($req->id);
      $bsched->time_from = $req->time_from;
      $bsched->time_to = $req->time_to;
      $bsched->update();

      $bsched = BSched::select('id',
                'time_from',
                'time_to',
                \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ',TIME_FORMAT(time_to, '%h:%i %p')) AS time"))
                ->with(['branches' => function ($qry) {
                  $qry->select('bsched_id', 'name');
                }])
                ->where('id', $req->id)->first();

      return response()->json($bsched, 200);
    }

    public function delete_multiple (Request $req) {
      $ids = $req;
      $bsched = BSched::whereIn('id', $ids)
                ->select('id', 'time_from', 'time_to')
                ->with(['branches' => function ($qry) {
                  $qry->select('bsched_id', 'name');
                }])
                ->get();
      $response = $bsched;
      BSched::whereIn('id', $ids)->delete();

      return response()->json($response, 200);
    }
}
