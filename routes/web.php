<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('register', 'Employee@register');
Route::post('login', 'Employee@login');
Route::get('invalid_authentication', 'Employee@invalidAuthentication');
Route::get('invalid_authentication', [ 'as' => 'invalid_authentication', 'uses' => 'Employee@invalidAuthentication']);
//Route::post('login', [ 'as' => 'login', 'uses' => 'Employee@login']);
//Route::post('login', 'Employee@login')->name('login');
Route::get('login', 'Employee@login')->name('login');
Route::get('check_token', 'Employee@getAuthenticatedUser');
Route::post('admin/create_question', 'AdminController@createQuestion');
Route::post('admin/delete_question', 'AdminController@deleteQuestion');
//Route::post('create_question', 'Adminc/TestController@createQuestion');
Route::get('admin/list_questions', 'AdminController@listAllQuestions');
Route::get('logout', 'Employee@logout');
Route::post('online_test', 'OnlineTest@getQuestions');
Route::get('get_levels', 'OnlineTest@getLevels');
Route::post('save_answer', 'OnlineTest@saveAnswers');
Route::get('list_tests', 'AdminController@listAllTests');
