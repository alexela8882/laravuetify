<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Branch;

use Validator;

class BranchController extends Controller
{
    public function all () {
      $branches = Branch::select('id', 'bsched_id', 'region_id', 'name', 'machine_number', 'whscode', 'bm_oic')
                  ->with(['users' => function ($qry) {
                    $qry->select('branch_id', 'first_name', 'last_name');
                  }])
                  ->with(['schedule' => function ($qry) {
                    $qry->select('id',
                      \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ', TIME_FORMAT(time_to, '%h:%i %p')) AS time")
                    );
                  }])
                  ->with(['region' => function ($qry) {
                    $qry->select('id', 'name');
                  }])
                  ->get();
      return response()->json($branches, 200);
    }

    public function store (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:branches,name',
        'region' => 'required',
        'machine_number' => 'required|unique:branches,machine_number',
        'whscode' => 'required|unique:branches,whscode',
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

      $branch = new Branch;
      $branch->name = $req->name;
      $branch->region_id = $req->region;
      $branch->bsched_id = $req->schedule;
      $branch->machine_number = $req->machine_number;
      $branch->whscode = $req->whscode;
      $branch->bm_oic = $req->bm_oic;
      $branch->save();

      $branch = Branch::select('id', 'bsched_id', 'region_id', 'name', 'machine_number', 'whscode', 'bm_oic')
                  ->with(['users' => function ($qry) {
                    $qry->select('branch_id', 'first_name', 'last_name');
                  }])
                  ->with(['region' => function ($qry) {
                    $qry->select('id', 'name');
                  }])
                  ->with(['schedule' => function ($qry) {
                    $qry->select('id',
                      \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ', TIME_FORMAT(time_to, '%h:%i %p')) AS time")
                    );
                  }])
                  ->where('id', $branch->id)
                  ->first();

      return response()->json($branch, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:branches,name,'.$req->id,
        'region' => 'required',
        'machine_number' => 'unique:branches,machine_number,'.$req->id,
        'whscode' => 'unique:branches,whscode,'.$req->id,
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

      $branch = Branch::find($req->id);
      $branch->name = $req->name;
      $branch->region_id = $req->region;
      $branch->bsched_id = $req->schedule;
      $branch->machine_number = $req->machine_number;
      $branch->whscode = $req->whscode;
      $branch->bm_oic = $req->bm_oic;
      $branch->update();

      $branch = Branch::select('id', 'bsched_id', 'region_id', 'name', 'machine_number', 'whscode', 'bm_oic')
                ->with(['region' => function ($qry) {
                  $qry->select('id', 'name');
                }])
                ->with(['schedule' => function ($qry) {
                  $qry->select('id',
                    \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ', TIME_FORMAT(time_to, '%h:%i %p')) AS time")
                  );
                }])
                ->where('id', $req->id)->first();

      return response()->json($branch, 200);
    }

    public function delete_multiple (Request $req) {
      $ids = $req;
      $branch = Branch::whereIn('id', $ids)
                ->select('id', 'bsched_id', 'region_id', 'name', 'machine_number', 'whscode', 'bm_oic')
                ->with(['region' => function ($qry) {
                  $qry->select('id', 'name');
                }])
                ->with(['schedule' => function ($qry) {
                  $qry->select('id',
                    \DB::raw("CONCAT(TIME_FORMAT(time_from, '%h:%i %p'),' - ', TIME_FORMAT(time_to, '%h:%i %p')) AS time")
                  );
                }])
                ->get();
      $response = $branch;
      Branch::whereIn('id', $ids)->delete();

      return response()->json($response, 200);
    }
}
