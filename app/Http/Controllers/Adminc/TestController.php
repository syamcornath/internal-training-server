<?php

namespace App\Http\Controllers\Adminc;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;
use JWTAuth;
//use App\Http\Controllers\Adminc;
//use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Hash;
//use Tymon\JWTAuth\PayloadFactory;


class TestController extends Controller
{
  public function __construct()
   {
      // $this->middleware('auth:api', ['except' => ['login']]);
   }
   public function createQuestion()
   {
     try {

             if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
             }

     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

      return response()->json(['token_expired'], $e->getStatusCode());

     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

           return response()->json(['token_absent'], $e->getStatusCode());

     }

     return response()->json(compact('user'));
   }
}
