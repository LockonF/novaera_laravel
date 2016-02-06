<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if(!$token){
            throw new Exceptions\JWTException('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return response()->json(['token'=>$token]);
    }


    /**
     * @param Request $request
     * @return null
     * @throws Exceptions\JWTException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInvalidException
     * @throws \Exception
     */

    public static function checkUser($permissions ='User')
    {

        $admin = ['Admin'];
        $supervisor = ['Admin','Supervisor'];
        $userPermissions = ['Admin','Supervisor','User'];

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                throw new Exceptions\JWTException;
            }
            if($permissions!=null)
            {
                switch($permissions)
                {
                    case 'User':
                        $validatePermissions= $userPermissions;
                        break;
                    case 'Supervisor':
                        $validatePermissions= $supervisor;
                        break;
                    case 'Admin':
                        $validatePermissions = $admin;
                        break;
                    default :
                        $validatePermissions = $userPermissions;
                        break;

                }
                if(!in_array($user->type,$validatePermissions))
                    throw new \App\Exceptions\UnauthorizedException;
            }
            return $user;
        } catch (Exceptions\TokenExpiredException $e) {
            throw $e;
        } catch (Exceptions\TokenInvalidException $e) {
            throw $e;
        } catch (Exceptions\JWTException $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username','password');
        if($credentials['username'] =='' or $credentials['password']=='')
        {
            return response()->json(['error' => 'Usuario o Contraseña Incorrectas'], 401);
        }
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Usuario o Contraseña Incorrectas'], 500);
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




    public function getUser()
    {
        try
        {
            $user= self::checkUser(null);
            $user->load('Persona');
            return response()->json($user);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch(UnauthorizedException $e)
        {
            return response()->json(['unauthorized'], $e->getStatusCode());
        }
        catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

    }

}
