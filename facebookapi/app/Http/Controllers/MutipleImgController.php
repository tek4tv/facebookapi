<?php

namespace App\Http\Controllers;
include 'C:\Users\maito\OneDrive\Desktop\facebookapi\facebookapi\vendor\autoload.php'; 
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;


class MutipleImgController extends Controller
{      
    public function postMutiple(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken'); 
        $app_id = $request->input('AppId');  
        $app_secret = $request->input('AppSecret');  
        $content = $request->input('Message');       
        $imgs = $request->input('Images'); 
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"];
        $version =$request->input('Version'); 
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' =>  $app_secret,
            'default_graph_version' => $version,
            ]);                            
        try {
            $arrays=array();
            foreach ($imgs as $key => $value) {
                $upload = $fb->post('/me/photos', ['published'=> 'false','source'=> $fb->fileToUpload($value)], $access_token)->getGraphNode()->asArray();
                $img = $upload['id'];                  
                $arrays["attached_media[$key]"] ='{"media_fbid": "'.$img.'"}' ;                
            }  
            $arrays["message"] =$content; 
            $arrays["formatting"] ='MARKDOWN';       
            $response = $fb->post("/$page_id/feed",$arrays,$access_token);        
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          return;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        $graphNode = $response->getGraphNode();
        return $graphNode;        
    }  
    public function GetPages (Request $request){
        $access_token =$request->input('UserToken');         
        try {                     
            $pages = Http::get("https://graph.facebook.com/me?access_token=$access_token"); 
            $user_id =$pages['id'];
           $response= Http::get("https://graph.facebook.com/$user_id/accounts?fields=name,access_token&access_token=$access_token&limit=1000"); 
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }      
        return $response;        
    } 
    public function GetComments (Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('UserToken');
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];
      $post_id = $request->input('PostId');           
      try {                     
        $getCommnets = Http::get("https://graph.facebook.com/$post_id/comments?access_token=$access_token");                           
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }   
        return $getCommnets;
    }
    
    public function HidenCommnets (Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('UserToken');
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];
      $post_id = $request->input('PostId');   
       $comment_id = $request->input('CommentId');      
       $is_hidden =$request->input('Ishidden'); 
       try {                     
        $getCommnets = Http::get("https://graph.facebook.com/$post_id/comments?access_token=$access_token");
       
        foreach ($getCommnets["data"] as $key => $value) {
              $id =$value["id"];
              foreach ($comment_id as $key1 => $value1) {
                if($value1 == $id){
                 $response= Http::post("https://graph.facebook.com/$id/?fields=is_hidden&access_token=$access_token&is_hidden=$is_hidden");
                 print_r(true);
                }else{
                  print_r(false);
                }

              }             
        }                
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }              
      }
      public function GetTokenPage (Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken'); 
        try {                     
          $token = Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token");                              
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $token;
      }
      public function GetPosts (Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"];
         
        try{
          $response = Http::get("https://graph.facebook.com/$page_id/posts?access_token=$access_token");                               
        }catch(Facebook\Exception\ResponseException $e){
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        }catch(Facebook\Exception\SDKException $e){
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        return $response;
      }

      public function ReplyComments(Request $request){    
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $comment_id = $request->input('CommentId');  
        $message = $request->input('MessageReply'); 
             
        try {                            
          $reply = Http::post("https://graph.facebook.com/$comment_id/comments?access_token=$access_token&message=$message");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $reply;
      }

      public function LikeComments(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $comment_id = $request->input('CommentId');       
        try {                              
          $like = Http::post("https://graph.facebook.com/$comment_id/likes?access_token=$access_token");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $like;
      }
      
      public function UnlikeComments(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $comment_id = $request->input('CommentId'); 
       
        try {                              
          $like = Http::delete("https://graph.facebook.com/$comment_id/likes?access_token=$access_token");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $like;
      }
      
      public function EditComments(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $comment_id = $request->input('CommentId');         
        $message = $request->input('MessageEdit'); 
        try {                              
          $like = Http::post("https://graph.facebook.com/$comment_id?fields=message&access_token=$access_token&message=$message");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $like;
      }

      public function DeleteComments(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('UserToken');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $comment_id = $request->input('CommentId'); 
         
        try {                              
          $delete = Http::delete("https://graph.facebook.com/$comment_id?access_token=$access_token");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $delete;
      }
   
}


 
