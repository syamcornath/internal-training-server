<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
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

class AdminController extends Controller
{
  protected $guard = 'Admin';
  public function __construct()
   {

      $this->middleware('auth:api', ['except' => 'login']);

   }
   public function createQuestion(Request $request)
   {

           $validator = Validator::make($request->all(), [
                 'question' => 'required',
                 'level_id' => 'required'
             ]);

            $output['Validation_errors']    =   (object)array();
            if ($validator->fails())
            {
                 $output['status']                         =   500;
                 $output['message']                        =   "Validation errors";
                 $output['oData']                          =   (object)array();
                 $errors                                   =   $validator->errors();
                 $output['Validation_errors']->question    =   $errors->get('question');
                 $output['Validation_errors']->level_id    =   $errors->get('level_id');
            }
            else
            {
                $level_id                                  =   $request->input('level_id');
                $question                                  =   $request->input('question');
                $created_at                                =   date("Y-m-d H:i:s");
                $data=array('level_id'=>$level_id,"question"=>$question,"created_at"=>$created_at);
                $result = DB::table('questions')->insert($data);
                if($result)
                {
                  $output['status']                         =   200;
                  $output['message']                        =   "Question has been added";
                }
                else
                {
                  $output['status']                         =   500;
                  $output['message']                        =   "Saving failed";
                }
            }

            return response()->json($output, 200);

   }
   public function deleteQuestion(Request $request)
   {

           $validator = Validator::make($request->all(), [
                 'question_id' => 'required',
             ]);

            $output['Validation_errors']    =   (object)array();
            if ($validator->fails())
            {
                 $output['status']                         =   500;
                 $output['message']                        =   "Validation errors";
                 $output['oData']                          =   (object)array();
                 $errors                                   =   $validator->errors();
                 $output['Validation_errors']->question_id =   $errors->get('question_id');

            }
            else
            {
                $question_id                                =  $request->input('question_id');
                $result = DB::delete('delete from questions where id='.$question_id.'');
                if($result)
                {
                  $output['status']                         =   200;
                  $output['message']                        =   "Question has been deleted";
                }
                else
                {
                  $output['status']                         =   500;
                  $output['message']                        =   "Sorry failed to delete";
                }
            }

              return response()->json($output, 200);
    }
    public function listAllQuestions()
    {
             $output['status']                              =   200;
             $output['message']                             =   "Success";
             $output['Validation_errors']                   =   (object)array();
             $questions                                     =   DB::select('select * from questions order by id DESC');
             $output['oData']                               =   compact('questions');
             return response()->json($output);
    }
    function listAllTests()
    {

              $output['status']                              =   200;
              $output['message']                             =   "Success";
              $output['Validation_errors']                   =   (object)array();
              $sessions                                      =   DB::select('SELECT distinct(session_id),name FROM employee_answers a,users u where u.id=a.user_id order by a.id DESC ');
              $output['oData']                               =   compact('sessions');
              return response()->json($output);
    }


}
