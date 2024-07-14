<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Common\UserCommon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function list(Request $request)
    {
        try {
            $users = $this->userService->getAllUsers();
            return $this->apiSuccessResponse($users);
        } catch (Exception $e) {
            return $this->apiExceptionResponse($e);
        }
    }

    public function filterUsers(Request $request)
    {
        try {
            $name = $request->query('name');
            $email = $request->query('email');

            $users = $this->userService->filterUsers([
                'name' => $name,
                'email' => $email
            ]);
            return $this->apiSuccessResponse($users);
        } catch (Exception $e) {
            return $this->apiExceptionResponse($e);
        }
    }

    public function getUserById($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if ($user) {
                return $this->apiSuccessResponse($user);
            } else {
                return $this->apiErrorResponse(UserCommon::NOT_FOUND, 404, ApiCommon::ERROR);
            }
        } catch (Exception $e) {
            return $this->apiExceptionResponse($e);
        }
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors(), 400, ApiCommon::VALIADATE);
        }

        try {
            $user = $this->userService->createUser([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => $request['password']
            ]);
            if ($user) {
                return $this->apiSuccessResponse($user, 201);
            } else {
                return $this->apiErrorResponse(UserCommon::CREATE_FAIL, 400, ApiCommon::ERROR);
            }
        } catch (Exception $e) {
            return $this->apiExceptionResponse($e);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $id = $request['id'];
            $user = $this->userService->getUserById($id);
            Gate::authorize('update', $user);
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255'
                ]);
                if ($validator->fails()) {
                    return  $this->apiErrorResponse($validator->errors(), 400, ApiCommon::VALIADATE);
                }
                $data = ['name' => $request['name']];
                $respon = $this->userService->updateUser($user, $data);
                if ($respon) {
                    return $this->apiSuccessResponse($user);
                } else {
                    return $this->apiErrorResponse(UserCommon::UPDATE_FAIL, 400, ApiCommon::VALIADATE);
                }
            } else {
                return $this->apiErrorResponse(UserCommon::NOT_FOUND, 404, ApiCommon::ERROR);
            }
        } catch (AuthorizationException  $e) {
            return $this->apiErrorResponse(UserCommon::UNAUTHORISED, 403, ApiCommon::ERROR);
        } catch (Exception $exception) {
            return $this->apiExceptionResponse($exception);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            Gate::authorize('delete', $user);
            if ($user) {
                $respon = $this->userService->deleteUser($user);
                if ($respon) {
                    return $this->apiSuccessResponse($user);
                } else {
                    return $this->apiErrorResponse(UserCommon::DELETE_FAIL, 400, ApiCommon::VALIADATE);
                }
            } else {
                return $this->apiErrorResponse(UserCommon::NOT_FOUND, 404, ApiCommon::ERROR);
            }
        } catch (AuthorizationException  $e) {
            return $this->apiErrorResponse(UserCommon::UNAUTHORISED, 403, ApiCommon::ERROR);
        } catch (Exception $exception) {
            return $this->apiExceptionResponse($exception);
        }
    }
}
