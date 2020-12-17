<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/mutiple',"MutipleImgController@postMutiple");

Route::post('/video',"MutipleImgController@PostVideo");

Route::post('/EditPostImage',"MutipleImgController@EditPostImage");

Route::post('/EditPostVideo',"MutipleImgController@EditPostVideo");

Route::post('/pages',"MutipleImgController@GetPages");

Route::post('/getComments',"MutipleImgController@GetComments");

Route::delete('/deleteComments',"MutipleImgController@DeleteComments");

Route::post('/replyComments',"MutipleImgController@ReplyComments");

Route::post('/likeComments',"MutipleImgController@LikeComments");

Route::delete('/unlikeComments',"MutipleImgController@UnlikeComments");

Route::post('/editComments',"MutipleImgController@EditComments");

Route::post('/hidden',"MutipleImgController@HidenCommnets");

Route::post('/getTokenPage',"MutipleImgController@GetTokenPage");

Route::post('/getPosts',"MutipleImgController@GetPosts");

Route::post('/uploadS3',"MutipleImgController@insertAction");

Route::post('/likes',"MutipleImgController@LikesPost");

Route::post('/shares',"MutipleImgController@SharePost");

Route::post('/counts',"MutipleImgController@Count");

Route::post('/uploadCover',"MutipleImgController@UploadCover");


