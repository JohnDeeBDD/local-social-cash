<?php
namespace LocalSocialCash; 
class FileUploader{    
    
    public function returnReportHTML(){
        /*
        firstName
        lastName
        email
        phone
        state
        FacebookUrl
        file
        */
        $output = <<<output
<form method='POST' enctype='multipart/form-data'>
  <input type="file" name ="userFile" />
  <input type="submit" name="file-from-form"/>
</form>

output;
        return $output;
    }

    public function listenToFormSubmission(){

 


    }
        
    public function xxx(){
        if(is_user_logged_in()){ 
            $output = "";
            $user =  wp_get_current_user();
            global $post; 
            $output = $output ."User ID : ". $user->ID; 
            $output = $output ."<br> "; 
            $output = $output ."Post ID : ". $post->ID;
            return $output;
        }else{
            $url = get_home_url();
            wp_redirect( $url );
            exit;
        }
    }   
}