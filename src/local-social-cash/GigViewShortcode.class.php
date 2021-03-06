<?php

namespace LocalSocialCash;

class GigViewShortcode{
    
    public function returnShortcode(){
        
        if(!(is_user_logged_in())){
            $x = wp_login_url( $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            return ("<h1>You must be logged in to view this page. Please <a href = '$x'>click here</a>.</h1>");
        }
        
        $args = array(
            'post_type' => 'task', 
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        // The Query
        $the_query = new \WP_Query( $args );
        
        $output = '<ul>';
        // The Loop
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $ID = get_the_ID();
                $user =  wp_get_current_user();
                $userID = $user->ID;
                //var_dump($userID); die();
                $user_info = get_userdata($userID);
                $user_email = $user_info->user_email;
                global $post;$backup=$post;
                if(!($this->boolUserHasProofForTask($userID, $ID))){
                    $post=$backup;
                    $output = $output . '<li><a href = "' . get_the_permalink() . '" target = "_blank" />' . get_the_title(). '</a>';
                    $output = $output . $this->returnCommentRoll($user_email, $ID);
                    $output = $output . "</li>";
                }
            }
            $output = $output . '</ul>';
            
            //wp_reset_postdata();
        } else {
            $output = "You're all done! you will receive payment within 24 hours. Thank you!";
        }
        
        return $output;
        
    } 
    
    public function returnCommentRoll($userEmail, $taskID){
        $output = "";
        $args = array(
            'author_email' => $userEmail,
            'include_unapproved' => TRUE,
            'post_id' => $taskID,
        );
        
        // The Query
        $comments_query = new \WP_Comment_Query;
        $comments = $comments_query->query( $args );
        $output = "";
        // Comment Loop
        if ( $comments ) {
            foreach ( $comments as $comment ) {
                $output = '<p><span style = "background-color: green;">DONE</span>  <a onclick="window.location.href=this">refresh</a></p>';
                //$output = $output . '<p>' . $comment->comment_attachment . '</p>';
            }
        } else {
            $output = $output . '<p><span style = "background-color: red;">No comment uploaded yet.</span>  <a onclick="window.location.href=this">refresh</a></p>';        }
        
        return $output;
    }
    
    public function boolUserHasProofForTask($userID, $taskID){
        $boolReturn = FALSE;
        $args = array('post_type' => 'proof', 'post_author' => $userID);
        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $ID = get_the_ID();
                //echo("THE ID IS: $ID <br />");
                $proofMetaReference = get_post_meta( $ID, 'task-ID', true );
                //echo("THE meta IS: $proofMetaReference <br />");
                if($proofMetaReference == $taskID){
                    $boolReturn = TRUE;
                }
            }
            
            
            //wp_reset_postdata();
        } else {
            // no posts found
        }
        return $boolReturn;
    }
    
}