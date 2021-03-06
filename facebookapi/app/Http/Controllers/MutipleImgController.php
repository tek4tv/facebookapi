<?php

namespace App\Http\Controllers;
include 'C:\Users\maito\OneDrive\Desktop\facebookapi\facebookapi\vendor\autoload.php'; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use FacebookAds\Object\AdVideo;
use FacebookAds\Object\Fields\AdVideoFields;
class MutipleImgController extends Controller
{      
    public function postMutiple(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('Token'); 
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
    public function ScheduledPublishTime(Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('Token'); 
      $app_id = $request->input('AppId');  
      $app_secret = $request->input('AppSecret');  
      $content = $request->input('Message');       
      $imgs = $request->input('Images'); 
      $scheduled_publish_time =$request->input('Time'); 
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
              $upload = $fb->post('/me/photos', ['published'=> 'false','temporary'=>'true','source'=> $fb->fileToUpload($value)], $access_token)->getGraphNode()->asArray();
              $img = $upload['id'];                  
              $arrays["attached_media[$key]"] ='{"media_fbid": "'.$img.'"}' ;                
          }          
          date_default_timezone_set('Asia/Ho_Chi_Minh');
          $datetime = date($scheduled_publish_time); 
          $arrays["message"] =$content; 
          $arrays["published"] ="false";       
         $arrays["scheduled_publish_time"] =strtotime($datetime);
        //  $arrays["scheduled_publish_time"] =strtotime("+6 minutes");
          $arrays["unpublished_content_type"] ="SCHEDULED";    
        //  print_r($arrays);
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
    public function PostVideo(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('Token'); 
        $app_id = $request->input('AppId');  
        $app_secret = $request->input('AppSecret'); 
        $title=$request->input('Title'); 
        $content = $request->input('Message');       
        $videos = $request->input('Videos');
        $image = $request->input('Thumbnail');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"];
        $version =$request->input('Version');       
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' =>  $app_secret,
            'default_graph_version' => $version,
            ]);
        if (empty($image)) {
          $videoData  = [          
            'title' => $title,
            'description' => $content,
            'source' => $fb->videoToUpload($videos)          
          ];
            }else{
              $videoData  = [          
                'title' => $title,
                'description' => $content,
                'source' => $fb->videoToUpload($videos),   
                'thumb' =>  $fb->fileToUpload($image)
              ];
            }
              
        try {         
            $response = $fb->post("/$page_id/videos",$videoData,$access_token);        
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
    public function PublishVideo(Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('Token'); 
      $app_id = $request->input('AppId');  
      $app_secret = $request->input('AppSecret'); 
      $title=$request->input('Title'); 
      $content = $request->input('Message');       
      $videos = $request->input('Videos');
      $image = $request->input('Thumbnail');
      $scheduled_publish_time =  $request->input('Time');
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];
      $version =$request->input('Version');       
      $fb = new Facebook([
          'app_id' => $app_id,
          'app_secret' =>  $app_secret,
          'default_graph_version' => $version,
          ]);
      date_default_timezone_set('Asia/Ho_Chi_Minh');
      $datetime = date($scheduled_publish_time);
      if (empty($image)) {
        $videoData  = [          
          'title' => $title,
          'description' => $content,
          'source' => $fb->videoToUpload($videos),   
         // 'thumb' =>  $fb->fileToUpload($image),
          'scheduled_publish_time' => strtotime($datetime),
          'published' => 'false'
        ];  
          }else{
            $videoData  = [          
              'title' => $title,
              'description' => $content,
              'source' => $fb->videoToUpload($videos),   
              'thumb' =>  $fb->fileToUpload($image),
              'scheduled_publish_time' => strtotime($datetime),
              'published' => 'false'
            ];  
          }
            
     
     // print_r($videoData) ;   
      try {         
          $response = $fb->post("/$page_id/videos",$videoData,$access_token);        
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
    public function EditPostVideo(Request $request){
      $page_id=$request->input('PageId');
      $post_id = $request->input('PostId'); 
      $user_token = $request->input('Token'); 
      $app_id = $request->input('AppId');  
      $app_secret = $request->input('AppSecret');  
      $content = $request->input('Message');          
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
          $arrays["message"] =$content; 
          $arrays["formatting"] ='MARKDOWN';       
          $response = $fb->post("/$post_id",$arrays,$access_token);        
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

    public function EditPostImage(Request $request){
      $page_id=$request->input('PageId');
      $post_id = $request->input('PostId'); 
      $user_token = $request->input('Token'); 
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
          $response = $fb->post("/$post_id",$arrays,$access_token);        
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
        $access_token =$request->input('Token');         
        try {                     
            $pages = Http::get("https://graph.facebook.com/me?access_token=$access_token"); 
            $user_id =$pages['id'];
           $response= Http::get("https://graph.facebook.com/$user_id/accounts?fields=name,global_brand_page_name,access_token&access_token=$access_token&limit=1000"); 
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
      $user_token = $request->input('Token');
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
      $user_token = $request->input('Token');
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
        $user_token = $request->input('Token'); 
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
        $user_token = $request->input('Token');
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
        $user_token = $request->input('Token');
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
        $user_token = $request->input('Token');
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
        $user_token = $request->input('Token');
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
        $user_token = $request->input('Token');
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
        $user_token = $request->input('Token');
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

      public function LikesPost(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('Token');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $post_id = $request->input('PostId'); 
         
        try {                              
          $get = Http::get("https://graph.facebook.com/$post_id/likes?access_token=$access_token");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $get;
      }

      public function SharePost(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('Token');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $post_id = $request->input('PostId');        
        try {                              
          $get = Http::get("https://graph.facebook.com/$post_id/sharedposts?access_token=$access_token");                           
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return $get;
      }  
      
      public function Count(Request $request){
        $page_id = $request->input('PageId'); 
        $user_token = $request->input('Token');
        $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
        $access_token =$user_token_value["access_token"]; 
        $post_id = $request->input('PostId');        
        try {                              
          $shares = Http::get("https://graph.facebook.com/$post_id/sharedposts?access_token=$access_token");   
          $countshare=count($shares["data"]);
          $likes = Http::get("https://graph.facebook.com/$post_id/likes?access_token=$access_token");  
          $countlike=count($likes["data"]);
          $comments = Http::get("https://graph.facebook.com/$post_id/comments?access_token=$access_token"); 
          $countcoumment=count($comments["data"]);
         
          $object = (object) array(
            'shares' => $countshare,
            'likes' => $countlike,
            'comments'=>$countcoumment
        ); 
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          return json_encode($object);
      }

    public function UploadCover(Request $request){
      $page_id=$request->input('PageId');     
      $user_token = $request->input('Token'); 
      $app_id = $request->input('AppId');  
      
      $app_secret = $request->input('AppSecret');
      $coverImg =$request->input('CoverImage');    
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];
      
      $version =$request->input('Version');       
      $fb = new Facebook([
          'app_id' => $app_id,
          'app_secret' =>  $app_secret,
          'default_graph_version' => $version,
          ]);              
      try {      
        $photo_uploaded = $fb->post('/me/photos', ['no_story'=> 'true','source'=> $fb->fileToUpload($coverImg)], $access_token)->getGraphNode()->asArray();                              
        $cover = $fb->post("/$page_id", array(
          'cover' => $photo_uploaded['id'],
          'offset_x' => 0, // optional
          'offset_y' => 0, // optional
          'no_feed_story' => true // suppress automatic cover image story, optional
        ),$access_token );       
          } catch(Facebook\Exception\ResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }   
          $graphNode = $cover->getGraphNode();
          return $graphNode; 
         
      }

    public function LiveVideo(Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('Token');
      $title = $request->input('Title');
      $description = $request->input('Description');
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];    
      try {                    
       
        $live = Http::post("https://graph.facebook.com/v3.3/$page_id/live_videos?status=LIVE_NOW&title=$title&description=$description&access_token=$access_token");
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }   
       
        return $live;
    }
    
    public function EndLiveVideo(Request $request){
      $page_id = $request->input('PageId'); 
      $user_token = $request->input('Token');
      $live_video_id = $request->input('LiveVideoId');
      $user_token_value =  Http::get("https://graph.facebook.com/$page_id?fields=access_token&access_token=$user_token"); 
      $access_token =$user_token_value["access_token"];    
      try {                                  
        $upload = Http::post("https://graph.facebook.com/v3.3/$live_video_id?end_live_video=true&access_token=$access_token");
        } catch(Facebook\Exception\ResponseException $e) {
          return 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exception\SDKException $e) {
          return 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }   
       
        return $upload;
     }
    public function CreateAvatar(Request $request){
      $page_id=$request->input('PageId');     
      $user_token = $request->input('Token'); 
      $app_id = $request->input('AppId');  
      $app_secret = $request->input('AppSecret');        
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
        $upload = $fb->post('/me/photos', ['published'=> 'false','source'=> $fb->fileToUpload($imgs)], $access_token)->getGraphNode()->asArray();
        $id= $upload["id"];
       
        $response =Http::post("https://graph.facebook.com/$page_id/picture?photo=$id&access_token=$access_token");    
   
      } catch(Facebook\Exception\ResponseException $e) {
        return 'Graph returned an error: ' . $e->getMessage();
       
      } catch(Facebook\Exception\SDKException $e) {
        return 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }     
      return true;  
    }

}
