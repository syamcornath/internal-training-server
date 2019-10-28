<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;
use JWTAuth;
//use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Hash;
//use Tymon\JWTAuth\PayloadFactory;


class Employee extends Controller
{
  public function __construct()
   {
       $this->middleware('auth:api', ['except' => ['login']]);
   }
    function index()
    {
        return view('login');
    }
    function register(Request $request)
    {
            $output    =   Array();
            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'email' => 'required',
                        'password' => 'required',
                    ]);

      $output['Validation_errors']    =   (object)array();
      if ($validator->fails())
      {
            $output['status']                         =   500;
            $output['message']                        =   "Validation errors";
            $output['oData']                          =   (object)array();
            $errors                                   =   $validator->errors();
            $output['Validation_errors']->name        =   $errors->get('name');
            $output['Validation_errors']->email       =   $errors->get('email');
            $output['Validation_errors']->password    =   $errors->get('password');
      }
      else
      {
         $name       = $request->name;
         $email      = $request->email;
         $password   = $request->password;

  $user = array('name' => $name, 'email' => $email, 'password' => Hash::make($password));
  DB::table('users')->insert($user);

      }

      return response()->json($output, 200);
    }
    function login(Request $request)
    {

      $output    =   Array();
    if($_SERVER['REQUEST_METHOD']=="POST")
    {
      $validator = Validator::make($request->all(), [
                  'email' => 'required',
                  'password' => 'required',
                  'user_type' => 'required',
              ]);

    $output['Validation_errors']    =   (object)array();
    if ($validator->fails())
    {
      $output['status']                         =   500;
      $output['message']                        =   "Validation errors";
      $output['oData']                          =   (object)array();
      $errors                                   =   $validator->errors();
      $output['Validation_errors']->email       =   $errors->get('email');
      $output['Validation_errors']->password    =   $errors->get('password');
      $output['Validation_errors']->user_type   =   $errors->get('user_type');
    }
    else
    {

         $credentials = $request->only('email', 'password','user_type');

          try {
                   if (! $token = JWTAuth::attempt($credentials))
                   {
                     $output['status']                       =   401;
                     $output['message']                      =   response()->json(['error' => 'invalid_credentials'], 400);
                     return response()->json($output, 200);
                      // return response()->json(['error' => 'invalid_credentials'], 400);
                   }
               } catch (JWTException $e)
               {
                  // return response()->json(['error' => 'could_not_create_token'], 500);
                  $output['status']                       =   401;
                  $output['message']                      =   response()->json(['error' => 'could_not_create_token'], 500);
                  return response()->json($output, 200);
               }
               $output['status']                       =   200;
               $output['message']                      =   "Login successfull";
               $output['token']                        =   response()->json(compact('token'));
               //return response()->json(compact('token'));

    //  return response()->json(['error' => 'Unauthorized'], 401);
     //echo "select * from users where email='".$request->get('email')."' and password='".md5($request->get('password'))."'";
     //
     // $users   =   DB::select("select * from users where email='".$request->get('email')."' and password='".md5($request->get('password'))."'");
     //  if(count($users) > 0)
     //  {
     //    $output['status']                       =   1;
     //    $output['message']                      =   "Login successfull";
     //    $output['oData']                        =   $users[0];
     //  }
     //  else
     //  {
     //    $output['status']                       =   0;
     //    $output['message']                      =   "Login failed";
     //    $output['oData']                        =   (object)array();
     //  }
     }
   }
   else
   {
     $output['status']                       =   401;
     $output['message']                      =   "Authentication failed";
   }
     return response()->json($output, 200);

    }
    public function guard()
    {
        return Employee::guard();
    }
    protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => $this->guard()->factory()->getTTL() * 60
       ]);
   }
   public function getAuthenticatedUser()
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
       function invalidAuthentication(Request $request)
       {


            $output['status']                       =   401;
            $output['message']                      =   "Authentication failed";
            return response()->json($output, 200);
       }
       function logout()
       {
          auth()->logout();
          // Pass true to force the token to be blacklisted "forever"
          auth()->logout(true);
          $output['status']                       =   200;
          $output['message']                      =   "Logged out successfully";
          return response()->json($output, 200);
       }
}
?>
