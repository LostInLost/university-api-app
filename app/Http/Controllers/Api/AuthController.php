<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) return $this->responseError([
            'message' => $validator->errors()->all()
        ]);

        $validatedData = $validator->valid();

        $authenticated = Auth::attempt([
            fn(Builder $query) => $query->where('username', $validatedData['user_email'])->orWhere('email', $validatedData['user_email']),
            'password' => $validatedData['password']
        ]);

        if (!$authenticated) return $this->responseError('Username or Password is wrong.');

        $admin = Admin::find(Auth::user()->id);

        $token = $admin->createToken(Auth::user()->name);

        return $this->responseSuccess([
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'username' => Auth::user()->username,
            'token_type' => 'Bearer',
            'token' => $token->plainTextToken 
        ]);


    }
}
