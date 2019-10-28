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

class OnlineTest extends Controller
{
  public function __construct()
   {
       $this->middleware('auth:api', ['except' => ['login']]);
   }
  function getLevels()
  {
     $output['Validation_errors']                =   (object)array();
     $levels                                     =   DB::select('select * from levels order by id ASC ');
     $output['oData']                            =   compact('levels');
     $output['status']                           =   200;
     $output['message']                          =   "success";
     return response()->json($output, 200);
  }
  function getQuestions(Request $request)
  {
    $validator = Validator::make($request->all(), [
          'level_id' => 'required'
      ]);

     $output['Validation_errors']               =   (object)array();
     if ($validator->fails())
     {
      $output['status']                         =   500;
      $output['message']                        =   "Validation errors";
      $output['oData']                          =   (object)array();
      $errors                                   =   $validator->errors();
      $output['Validation_errors']->level_id    =   $errors->get('level_id');
     }
     else
     {
      $level_id                                   =   $request->input('level_id');
      $questions                                  =   DB::select('select * from questions where level_id='.$level_id.' order by RAND() limit 2 ');
      $output['oData']                            =   compact('questions');
      $output['status']                           =   200;
      $output['message']                          =   "success";
     }
     return response()->json($output, 200);
  }
  function saveAnswers(Request $request)
  {
    $validator = Validator::make($request->all(), [
          'answer' => 'required'
      ]);

     $output['Validation_errors']               =   (object)array();
     if ($validator->fails())
     {
      $output['status']                         =   500;
      $output['message']                        =   "Validation errors";
      $output['oData']                          =   (object)array();
      $errors                                   =   $validator->errors();
      $output['Validation_errors']->answer      =   $errors->get('answer');
     }
     else
     {
      $user                                     =  JWTAuth::parseToken()->authenticate();
      $userArray                                =  json_decode($user);
      $userId                                   =  $userArray->id;
      $i                                        = 0;
      $sessionId                                = $userId."-".date("YmdHis");
      foreach($request->input('answer') as $key => $value)
      {
        $data[$i]["user_id"]                    =  $userId;
        $data[$i]["session_id"]                 =  $sessionId;
        $data[$i]["question_id"]                =  $key;
        $data[$i]["answer"]                     =  $value;
        $i++;
      }
      $result = DB::table('employee_answers')->insert($data);
      if($result)
      {
        $output['status']                         =   200;
        $output['message']                        =   "Answers has been saved";
      }
      else
      {
        $output['status']                         =   500;
        $output['message']                        =   "Saving failed";
      }
     }
     return response()->json($output, 200);
  }
}
?>
