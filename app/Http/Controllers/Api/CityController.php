<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    use ApiResponse;

    public function index() 
    {
        $cities = City::all(['id', 'name']);

        return $this->responseSuccess([
            'cities' => $cities
        ]);
    }

    public function detail($id)
    {
        $city = City::find($id);

        if (!$city) return $this->responseError('City not found.');
    
        return $this->responseSuccess($city->only(['name']));   
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
        ]);

        if ($validator->fails()) return $this->responseError($validator->errors()->first());

        $validatedData = $validator->valid();

        // $admin = $request->user()->
        
        // $city = City::create($validatedData);
        $city = $request->user()->cities()->create($validatedData);


        if (!$city) return $this->responseError('City failed created.');

        return $this->responseSuccess('City successfully created.');
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
        ]);

        if ($validator->fails()) return $this->responseError($validator->errors()->first());

        
        $city = City::find($id);

        if (!$city) return $this->responseError('City not found.');

        $validatedData = $validator->valid();

        $edit = $city->update($validatedData);

        if (!$edit) return $this->responseError('City update failed.');

        return $this->responseSuccess('City successfully updated.');
    }

    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) return $this->responseError('City not found.');

        $delete = $city->delete();

        if (!$delete) return $this->responseError('City delete failed.');

        return $this->responseSuccess('City successfully deleted.');
    }
}
