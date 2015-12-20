<?php

namespace App\Http\Controllers;

use App\Models\TRL;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Database\QueryException;
use App\Exceptions\UnauthorizedException;


class TRLController extends Controller
{
    public function show()
    {
        try{
            AuthenticateController::checkUser(null);
            $trl = TRL::get();
            return response()->json(['TRL'=>$trl]);

        }catch (QueryException $e)
        {
            return response()->json(['message'=>'server_error','exception'=>$e->getMessage()],500);
        }catch (Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
    }
}
