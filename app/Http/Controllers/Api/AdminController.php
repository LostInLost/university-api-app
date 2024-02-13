<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email:dns'],
            'password' => ['required', 'min:8', 'max:32']
        ]);

        if ($validator->fails()) return $this->responseError($validator->errors()->all());

        $validatedData = $validator->valid();

        $validatedData['password'] = Hash::make($validatedData['password']);

        $create = Admin::create($validatedData);

        if (!$create) return $this->responseError('Admin create failed.');

        return $this->responseSuccess('Admin successfully created.');

    }
    
}
