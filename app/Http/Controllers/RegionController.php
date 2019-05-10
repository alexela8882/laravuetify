<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Region;

use Validator;

class RegionController extends Controller
{
    public function all () {
    	$regions = Region::select('id', 'name')
                 ->with(['branches' => function ($qry) {
                  $qry->select('region_id', 'name');
                 }])
                 ->get();
    	return response()->json($regions, 200);
    }

    public function store (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:regions,name',
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

    	$region = new Region;
      $region->name = $req->name;
    	$region->save();

      $region = Region::select('id', 'name')
                ->with(['branches' => function ($qry) {
                  $qry->select('region_id', 'name');
                }])
                ->where('id', $region->id)
                ->first();

    	return response()->json($region, 200);
    }

    public function update (Request $req) {
      $validator = Validator::make($req->all(), [
        'name' => 'required|unique:regions,name,'.$req->id,
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

      $region = Region::select('id', 'name')
                ->where('id', $req->id)->first();
      $region->name = $req->name;
      $region->update();

      return response()->json($region, 200);
    }

    public function delete_multiple (Request $req) {
      $ids = $req;
      $region = Region::whereIn('id', $ids)
                ->with(['branches' => function ($qry) {
                  $qry->select('region_id', 'name');
                }])
                ->select('id', 'name')->get();
      $response = $region;
      Region::whereIn('id', $ids)->delete();

      return response()->json($response, 200);
    }
}
