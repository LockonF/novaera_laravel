<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Mockery\CountValidator\Exception;
use Tymon\JWTAuth\Exceptions;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{

    /**
     * @param Request $request
     * @return null
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     * @throws \Exception
     */

    public static function checkUser()
    {

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return null;
            }

        } catch (Exceptions\TokenExpiredException $e) {

            throw $e;

        } catch (Exceptions\TokenInvalidException $e) {

            throw $e;

        } catch (Exceptions\JWTException $e) {
            throw $e;
        }
        return $user;
    }



    public function authenticate(Request $request)
    {
        $credentials = $request->only('username','password');
        if($credentials['username'] =='' or $credentials['password']=='')
        {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }


    /*
     * Función para crear usuarios
     */

    public function register(Request $request)
    {
        $user = User::where('username',$request->username)->first();
        if($user ==null)
        {
            $user = new User();
            $user->username = $request->username;
            $user->password = Hash::make($request->input('password'));
            try{
                $user->save();
                return response()->json(['message'=>'success'],200);
            }catch (QueryException $e)
            {
                return response()->json(['message'=>'save_error'],500);
            }
        }
        else
            return response()->json(['message'=>'user_already_exists'],500);

    }

}
