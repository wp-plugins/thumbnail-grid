<?php

class sfly_html_admin_messages
{

    public $path = "";
    public $fullpath = "";
    public $id = "";
    public $ver = "";
    function sfly_html_admin_messages($path, $file, $ver, $id)
    {
        $this->id = $id;
        $this->fullpath = $path . '/' . $ver . '/' .$file;
        $this->path = $path;
        $this->ver = $ver;
        
        add_action('admin_notices', array($this, 'sfly_tbgrd_admin_notice'));
        add_action('admin_init', array($this, 'sfly_tbgrd_nag_ignore'));
    }
    function get_html_output(){
        $result_body = "";
        if(function_exists('wp_remote_get')){
            
            $url_query = $this->fullpath ."?ver=$this->ver";
      
            $result = wp_remote_get($url_query);
            if($result['response']['code']==200){
                $result_body = $result['body'];
            }
        }
    
        return $result_body;

    }
    function sfly_tbgrd_admin_notice() {
	    global $current_user ;
        $user_id = $current_user->ID;
        $ver = str_replace ( "." , "_" , $this->ver);
        $getvar = $this->id .'_nag_ignore_' . $ver;
        $metavar = $this->id .'_ignore_notice_' . $ver;
     
        if ( ! get_user_meta($user_id, $metavar) ) {
            $message = trim($this->get_html_output());
            if ($message != "")
            {
                echo '<div class="updated"><p>';
                printf(__($message .' | <a href="%1$s">Hide Notice</a>'), '?'.$getvar.'=0');
                echo "</p></div>";
	        }
        }
    }
    function sfly_tbgrd_nag_ignore() {
	    global $current_user;
        $user_id = $current_user->ID;
        $ver = str_replace ( "." , "_" , $this->ver);
             $getvar = $this->id .'_nag_ignore_' . $ver;
             $metavar = $this->id .'_ignore_notice_' . $ver;
        if ( isset($_GET[$getvar]) && '0' == $_GET[$getvar] ) {
            add_user_meta($user_id, $metavar, 'true', true);
		    if ( wp_get_referer() ) {
            /* Redirects user to where they were before */
                wp_safe_redirect( wp_get_referer() );
		    } else {
                /* This will never happen, I can almost gurantee it, but we should still have it just in case*/
                wp_safe_redirect( home_url() );
		    }
	    }
    }
}
?>