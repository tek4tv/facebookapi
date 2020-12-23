<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WpController extends Controller
{
    public function PostWordpress(Request $request){
        $title = $request->input('Title'); 
        $content = $request->input('Content'); 
        $api_response = wp_remote_post('http://localhost:8080/wordpress/wp-json/wp/v2/posts', array(
            
            'title'   => $title,
               'status'  => 'draft', // ok, we do not want to publish it immediately
               'content' => $content,
               'categories' => 1, // category ID
               'tags' => '1,4,23' ,// string, comma separated
               'date' => '2015-05-05T10:00:00', // YYYY-MM-DDTHH:MM:SS
               'excerpt' => 'Read this awesome post',
               'password' => '12$45',
               'slug' => 'new-test-post' // part of the URL usually
               // more body params are here:
               // developer.wordpress.org/rest-api/reference/posts/#create-a-post
           )
        );
       $body = json_decode( $api_response['body'] );
 
    // you can always print_r to look what is inside
    // print_r( $body ); // or print_r( $api_response );
    
        if( wp_remote_retrieve_response_message( $api_response ) === 'Created' ) {
            echo 'The post ' . $body->title->rendered . ' has been created successfully';
        }
        return $body;
    }
}

