<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Common\UserCommon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function unauthorised(Request $request)
    {
        return $this->apiErrorResponse(UserCommon::UNAUTHORISED, 403, ApiCommon::ERROR);
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors(), 400, ApiCommon::VALIADATE);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $accessToken = $user->createToken($user->email)->accessToken;

                $data = [];
                $data['user'] = $user;
                $data['accessToken'] = $accessToken;
                return response()->json($data);
                return $this->apiSuccessResponse($data);
            } else {
                return $this->apiErrorResponse(UserCommon::UNAUTHORISED, 403, ApiCommon::ERROR);
            }
        } catch (\Exception $e) {
            return $this->apiExceptionResponse($e);
        }
    }
}
