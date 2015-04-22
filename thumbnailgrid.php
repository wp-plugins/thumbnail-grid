<?php
/*
Plugin Name: Featured Image Thumbnail Grid
Plugin URI: http://www.shooflysolutions.com/premium-thumbnail-grid-wordpress-plugin/
Description: This is the new version of the Featured Image Thumbnail Grid. Display Thumbnail Grid using Featured Images
Version: 5.2
Author: A. R. Jones
Author URI: http://shooflysolutions.com
*/

/*
Copyright (C) 2013, 2014 Nomad Coder, Shoofly Solutions
Contact me at http://www.shooflysolutions.com

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
/*
Order By for links
orderby 
(string) Value to sort bookmarks on. Defaults to 'name'. Valid options:
'url'
'name' - Default
'owner' - User who added bookmark through bookmarks Manager.
'rating'
'visible'
'length' - The length of the bookmark name, shortest to longest.
'rand' - Display bookmarks in random order.
Order By for Posts
'none' - No order (available with Version 2.8).
'ID' - Order by post id. Note the captialization.
'author' - Order by author.
'title' - Order by title.
'name' - Order by post name (post slug).
'date' - Order by date.
'modified' - Order by last modified date.
'parent' - Order by post/page parent id.
'rand' - Random order.
'comment_count' - Order by number of comments (available with Version 2.9).
'menu_order' - Order by Page Order. Used most often for Pages (Order field in the Edit Page Attributes box) and for Attachments (the integer fields in the Insert / Upload Media Gallery dialog), but could be used for any post type with distinct 'menu_order' values (they all default to 0).
'meta_value' - Note that a 'meta_key=keyname' must also be present in the query. Note also that the sorting will be alphabetical which is fine for strings (i.e. words), but can be unexpected for numbers (e.g. 1, 3, 34, 4, 56, 6, etc, rather than 1, 3, 4, 6, 34, 56 as you might naturally expect). Use 'meta_value_num' instead for numeric values.
'meta_value_num' - Order by numeric meta value (available with Version 2.8). Also note that a 'meta_key=keyname' must also be present in the query. This value allows for numerical sorting as noted above in 'meta_value'.
'post__in' - Preserve post ID order given in the post__in array (available with Version 3.5).
*/

if (!defined('SFLY_TBGRD_VERSION'))
    define('SFLY_TBGRD_VERSION', '3.1.1');


$ver = constant('SFLY_TBGRD_VERSION');

add_shortcode("thumbnailgrid", "thumbnailgrid_handler");
add_shortcode("bkthumbnailgrid", "bkthumbnailgrid_handler");
$admin = new sfly_tbgrid_admin();
class sfly_tbgrid_admin
{
 
    public function __construct()
    {
       
           add_action('admin_menu', array($this, 'thumbnailgrid_menu_settings'));
           add_action('admin_init', array($this, 'thumbnailgrid_init_settings'));
    }
  
    function thumbnailgrid_menu_settings() {
     
        add_options_page("Thumbnail Grid Settings", "Thumbnail Grid Settings", 'manage_options', 'sfly_tgrid_settings', array($this, 'sfly_display_menu_settings'));
    }
  
     function thumbnailgrid_init_settings()
    {
     
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_load_styles' );
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_compress' );
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_generic_thumb');

    }
    function sfly_display_menu_settings() {
        //Save settings with nonces the new clean & easy way - not compatable pre 2.7
        ?> 
        </pre>
        <div class="wrap" style="width:80%; margin: auto">
         <div style="width: 400px; float: left">
                <form action="options.php" method="post" name="sfly_tgrid_options"><!--send to the Options.Php file-->
      
                <?php
                   settings_fields( 'sfly_thumbnailgrid' ); //must be after the form tag
                   $css_load = get_option('sfly_tbgrid_load_styles', 'header'); //get the options
                   $css_compress = get_option('sfly_tbgrid_compress', '0');
               
                ?>
                <h2>Shoofly Thumbnail Grid Settings</h2>
                <div style="padding-bottom: 10px">
                    <h3>Stylesheets</h3>
                     <input name="sfly_tbgrid_compress" id="sfly_tbgrid_compress" type="checkbox" value="1" <?php checked( '1', $css_compress ) ; ?>>Load Compressed Stylesheet</input>
              
                    <div style="padding:25px 0;">
             
                    <input type="radio" id="sfly_tbgrid_load_styles" name="sfly_tbgrid_load_styles" <?php if($css_load == 'header') echo 'checked="checked"'; ?> value="header">Load the style sheet in the header - this setting loads the stylesheet on every page. This may slow your website down</input>
                    </div>
            
                    <div>
                    <input type="radio" id="sfly_tbgrid_load_styles" name="sfly_tbgrid_load_styles"  <?php if($css_load == 'footer') echo 'checked="checked"'; ?> value="footer">Load in footer - this setting loads the styleshee on pages where thumbnail grid is in use. Loading in the footer. Thumbnails may take a second to properly style</input>
                
                             </div>
            
                </div>
                 <div style="text-align:center; padding:10px;"><input type="submit" name="Submit" value="Update" /></div></form>
            </div>
       
         <div style="margin: 40px auto; width:250px; float:right;border: black 1px solid;padding-left: 25px;padding-bottom: 25px;">  
            <h3>Support</h3>
            <div><a href="http://www.shooflysolutions.com/software/featured-image-thumbnail-grid-for-wordpress/#a1" target="_blank">Installation </a></div>
            <div><a href="http://www.shooflysolutions.com/software/featured-image-thumbnail-grid-for-wordpress/#a5" target="_blank">Settings</a></div>
            <div><a href="http://www.shooflysolutions.com/software/featured-image-thumbnail-grid-for-wordpress/#a2" target="_blank">Overview</a></div>
            <div><a href="http://www.shooflysolutions.com/shortcodes/" target="_blank">Shortcodes</a></div>
            <div><a href="http://www.shooflysolutions.com/?p=237" target="_blank">Filters</a></div>
            <div><a href="http://www.shooflysolutions.com/featured-image-thumbnail-grid-extensions/" target="_blank">Extensions</a></div>
            <div><a href="http://www.shooflysolutions.com/faqs/" target="_blank">Faqs</a></div>
     
            <div>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <h3>Donations for extended support are appreciated!</h3>
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="NERK4N9L2QSUL">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
            <div >
                <a href="">Rate this plugin!</a>
            </div>
        </div>
         <div style="clear: both; margin: auto; width: 400px; border: 1px black solid; width: 100%;text-align: center;padding: 0 0 25px 0;">
            <h3>Thumbnail Grid Extensions:</h3>
            <div><a href="http://www.shooflysolutions.com/software/featured-image-thumbnail-grid-for-wordpress/paging-addon-extension-featured-thumbnail-grid-plugin-for-wordpress/">Paging extension</a></div>
            <div><a href="http://www.shooflysolutions.com/software/featured-image-thumbnail-grid-for-wordpress/featured-image-thumbnail-grid-extensions/popular-sort-featured-image-thumbnail-extension/">Sorting extension</a></div>
        </div>
 
         </div>
       <pre> 
    <?php
   
    }
}

function bkthumbnailgrid_handler($atts) {
    //Include Stylesheet
    
    $tg = new sfly_thumbnailgrid();
    $output = $tg->bkthumbnailgrid_function($atts);
    return $output;
}
function thumbnailgrid_handler($atts) {
 
    $tg = new sfly_thumbnailgrid();
    $output = $tg->thumbnailgrid_function($atts);
    return $output;
}
	 /**
	 * Function for Shortcode.
	 * $atts = array of options
     * 
	 */
    
     $css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the options
    
    
     if ($css_load == "header")
     {
         
          
            add_action( 'wp_enqueue_scripts',  array('sfly_thumbnailgrid' , 'scripts_method' ));
     }
  

    class sfly_thumbnailgrid
    {
    public function __construct()
    {

     $css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the options
   
     if ($css_load == "footer")
           sfly_thumbnailgrid::scripts_method();
       
    }
   public static function scripts_method()
    {
         $css_compress = (get_option('sfly_tbgrid_compress', '0'));
         $css_filename = $css_compress == "1" ? "thumbnailgrid-compressed.css" :  "thumbnailgrid.css";
         wp_enqueue_style( 'sfly-tbgrd-css', plugins_url( 'css/' . $css_filename , __FILE__ ) );
    }
    function getClass($filtername, $class)
    {
 
        $newclass = apply_filters($filtername, $class); 
        $newclass = ($newclass != '') ? ' class="' .$newclass . '"': '';
        return $newclass;
    }
    function getStyle($filtername, $style)
    {
        $newstyle="";
        $newstyle = apply_filters( $filtername, $style );
        $newstyle = $newstyle != '' ? ' style="' .$newstyle . '"': '';
        return $newstyle;
        
    }   
    function getStyleWithSize($filtername, $height, $width) 
    {
        $newstyle = "";
        if ($height || $width)
        {
            $newstyle .= ($height) ? 'height:' .$height . ';': '';
            $newstyle .=  ($width) ? 'width:' .$width . ';': '';
        } 
        $newstyle = apply_filters($filtername, $newstyle); 
        $newstyle = ($newstyle != '') ? ' style="' .$newstyle . '"': '';
        return $newstyle;
    }
    function getGridNavContent($page, $location, $max)
    {   //if $page is blank or null, grid is not paged and nothing should be returned
     

        $content = apply_filters('sfly_tbgrid_grid_nav_content', $page, $location, $max);

        return $content;
    }

    function  setTitleStyle($showcaption,  $width,  $captionheight)
    {
           $titlestyle = '';
        
           if ($showcaption == 'FALSE' || $showcaption == 'false')
           {
                $titlestyle .=  "display:none";
           }
           else
           {
               if ($width != '' && $width != 'auto')
                    $titlestyle .= "width:" . $width . ';'; 
              
                if ($captionheight != '')
                    $titlestyle .= 'height:' . $captionheight . ';overflow:hidden;';
           }
        
           return $titlestyle;
    }
    function setGridStyle($width, $aligngrid)
    {
        $gridstyle  = "";
         if ($width != '')
            $gridstyle .= 'width:' . $width . ';';
        if ($aligngrid == 'left')
            $gridstyle .= 'float:left;';
        else if ($aligngrid == 'right')
            $gridstyle .=  'float:right;';
        else if ($aligngrid == 'center')
            $gridstyle .=  'margin:auto;';
  
        return $gridstyle;
    }
    function todayArray()
    {
        $today = getdate();
        return array(
                'year'  => $today['year'],
			    'month' => $today['mon'],
			    'day'   => $today['mday'],
               );
    }
    function defaults($atts)
    {
        return shortcode_atts( array(
            'height' => '',                
            'width' => '',
            'gridwidth' =>'',
            'showcaption' => 'TRUE',
            'captionheight' => '',
            'captionwidth' => '',
            'wraptext' => 'FALSE',
            'posts_per_page' => -1,
            'aligngrid' => '',
            'imagesize' => 'thumbnail',
            'cellwidth' => '',
            'cellheight' => '',
            'before' => '',
            'after' => '',
            'post__in' => '',
            'post__not_in' => '',
    
            'tag_slug__and' => '',
            'tag_slug__in' => '',
            'post_parent__in' => '',
            'post_parent__not_in' => '',
   
            'today' => FALSE,
            'inclusive' => FALSE,
            'debug_query' => FALSE
         
             ), $atts  );
    }
    function getSettings(&$atts)
    {
     
         $settings = new stdClass;
          if (!$atts)
                $atts = $this->defaults($atts);
      
        extract($this->defaults($atts));
          
           
	       $atts = apply_filters( 'sfly_tbgrd_settings', $atts);//do something with settings
         
         
          
           unset($atts["height"]);
           unset($atts["width"]);
           unset($atts["gridwidth"]);
           unset($atts['showcaption']);
           unset($atts['captionheight']);
           unset($atts['captionwidth']);
           unset($atts['cellheight']);
           unset($atts['cellwidth']);
           unset($atts['wraptext']);
           unset($atts['aligngrid']);
           unset($atts['post__in']);
           unset($atts['post__not_in']);
           unset($atts['tag_slug__and']);
           unset($atts['tag_slug__in']);
           unset($atts['post_parent__in']);
           unset($atts['post_parent__not_in']);
           unset($atts['before']);
           unset($atts['after']);
           unset($atts['today']);
           unset($atts['inclusive']);
            unset($atts['debug_query']); //this is not a valid attribute
       
           if ($after || $before )
           {
               $before_after = array();
               if ($after == 'today')
                    $after = $this->todayArray();
                if ($before == 'today')
                    $before = $this->todayArray();

                if ($after)
                    $before_after['after'] = $after;
                if ($before)
                    $before_after['before'] = $before;
                
                if ($inclusive != FALSE)
                    $before_after['inclusive'] = TRUE;
               
                $atts['date_query'] = $before_after;
                
           }
 
           if ($today != FALSE)
           {
               $atts['date_query'] = $this->todayArray();
           }
         
           if ($post__in)
            $atts['post__in'] =  explode(",",  $post__in);
           if ($post__not_in)
            $atts['post__not_in'] = explode(",", $post__not_in);
 
           if ($tag_slug__and)
            $atts['tag_slug__and'] = explode(",", $tag_slug__and);
           if ($tag_slug__in)
            $atts['tag_slug__in'] = explode(",", $tag_slug__in);
           if ($post_parent__in)
             $atts['post_parent__in'] = explode(",", $post_parent__in);
           if ($post_parent__not_in)
            $atts['post_parent__not_in'] = explode($post_parent__not_in);


           $settings->height = $height;
           $settings->width = $width;
           $settings->gridwidth = $gridwidth;
           $settings->imagesize = $imagesize;
           $settings->debug = $debug_query;
           $griditemstyle = "";
           if ($cellwidth)
                $griditemstyle .= "width:" . $cellwidth . ";";
           if ($cellheight)
                $griditemstyle .= "height:" .$cellheight . ";";

           $settings->griditemstyle = $this->getStyle('sfly_tbgrid_griditem_style', $griditemstyle);
           $settings->griditemclass = $this->getClass('sfly_tbgrid_griditem_class', 'griditemleft');
           //Get the Grid Container Style
           $titlestyle = $this->setTitleStyle($showcaption, $width, $captionheight);
         
           $gridstyle = $this->setGridStyle($gridwidth, $aligngrid);
           $settings->titlelinkstyle = "";
           if ($wraptext == 'TRUE' || $wraptext == "true")
                    $settings->titlelinkstyle .= "white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap!important;word-wrap: break-word!important;";
           if ($captionwidth != '')
                    $settings->titlelinkstyle .= "width:" . $captionwidth . ';margin:auto;';
            
           $settings->gridstyle = $this->getStyle('sfly_tbgrid_grid_style', $gridstyle);
           $settings->gridclass = $this->getClass('sfly_tbgrid_grid_class', 'thumbnailgridcontainer');
           $settings->imagestyle = $this->getStyleWithSize('sfly_tbgrid_image_style', $settings->height, $settings->width);
           $settings->imageclass = $this->getClass('sfly_tbgrid_image_class', '');
           //Get the Image div Style
           $settings->postimgstyle =     $this->getStyleWithSize('sfly_tbgrid_postimagediv_style', $settings->height, $settings->width);
           $settings->postimgclass = $this->getClass('sfly_tbgrid_postimagediv_class', 'postimage');
            //Get the grid item style
  
            //Get the title style
        
           $settings->titlestyle = $this->getStyle('sfly_tbgrid_title_style', $titlestyle);
           
           $settings->titleclass = $this->getClass('sfly_tbgrid_title_class', 'postimage-title' );
            //Get the title link style
           $settings->titlelinkstyle = $this->getStyle('sfly_tbgrid_titlelink_style', $settings->titlelinkstyle);
           $settings->titlelinkclass = $this->getStyle('sfly_tbgrid_titlelink_class', '');
           
           return $settings;
        }
    function thumbnailgrid_function($atts) {
        
        wp_reset_query();
       
        $settings = new stdClass();
     
     
       $settings = $this->getSettings($atts);    
      
         
        // The Loop
        $this->thumbnailgrid_addqueryfilter($settings->debug);
        $the_query = new WP_Query($atts);

        $this->thumbnailgrid_removequeryfilter();
        $max = $the_query->max_num_pages;
       
        if (isset($atts['paged']))
          $gridnavcontenttop = $this->getGridNavContent($atts['paged'], 'top', $max);
        else
          $gridnavcontenttop = '';
          $ret = '<div class="thumbnailblock">' . $gridnavcontenttop .
            '<div ' . $settings->gridclass . ' ' .$settings->gridstyle .'>';
             
         while ( $the_query->have_posts() ) :$the_query->the_post();
           
            $thumb = new stdClass;
            $thumb->permalink = get_permalink();
            $thumb->title = get_the_title();
            $thumb->extra = apply_filters('sfly_tbgrid_extra_info', '');
            $thumb->target = '';
            $thumb->image_id = get_post_thumbnail_id();
            
            $image_url = wp_get_attachment_image_src($thumb->image_id, $settings->imagesize, true);
          
            $thumb->image_url = $image_url[0] ;
         
            $ret .= $this->theThumbnail($settings, $thumb);
                       
        endwhile;
    
        if (isset($atts['paged']))
            $gridnavcontentbottom = $this->getGridNavContent($atts['paged'], 'bottom', $max);
        else
            $gridnavcontentbottom = "";
        $ret .= '</div>';
        $ret .= $gridnavcontentbottom;
        $ret .= '</div>';
        wp_reset_postdata();
        return $ret;
    }
  
 	     /**
	     * Function for Shortcode.
	     * $atts = array of options
         * 
	     */
    public function theThumbnail($settings, $thumb)
    {
        
            if ($thumb->target != '')
            {
                $thumb->target = ' target="' .$target .'"';
            }
            if ($thumb->image_url)
                $thumbnail = '<img '. $settings->imageclass .' src="' .$thumb->image_url .'"' .$settings->imagestyle . '/>';
            else
                $thumbnail = '<div ' . $settings->imagestyle .'></div>';
 
            $ret = '<div ' . $settings->griditemclass .' ' .$settings->griditemstyle.'>'
                . '<div '.$settings->postimgclass .$settings->postimgstyle .'>
            <a href="' .$thumb->permalink .'" title="' .$thumb->title .'"> ' .$thumbnail .'</a> 
	        </div>' . '<div ' . $settings->titleclass .' ' .$settings->titlestyle .'>
            <a '. $settings->titlelinkstyle .' href="' .$thumb->permalink .'" title="' .$thumb->title .'"> ' .$thumb->title .'</a> 
		       
	        </div>' . $thumb->extra . '                    
            </div>';
            return $ret;
     }
    function bkthumbnailgrid_function($atts) {
        if ($atts)
        {
           $settings = $this->getSettings($atts);
           $the_query = new WP_Query($atts);
        }
        $bookmarks = get_bookmarks( $atts );

    // Loop through each bookmark and print formatted output
       
        $gstyle = "";
         $ret = '<div class="thumbnailblock">
            <div ' . $settings->gridclass . ' ' .$settings->gridstyle .'>';

       // $titlelength = 20;
        foreach ( $bookmarks as $bookmark ) { 
             $thumb = new stdClass;
            $thumb->permalink = $bookmark->link_url;
            $thumb->title = $bookmark->link_name;
            $thumb->target = $bookmark->link_target;
            $thumb->image_url = $bookmark->link_image;
            $thumb->extra = '';
            $thumb->image_id = $bookmark->link_id;
            $ret .= $this->theThumbnail($settings, $thumb);
        }
        wp_reset_postdata();
         $ret .=  '</div></div>';
        return $ret;
    }
    function thumbnailgrid_addqueryfilter($debug_query = FALSE){
        add_filter('posts_join', array($this, 'new_join') );
        add_filter('posts_orderby', array($this, 'new_order') );
        add_filter('posts_where', array($this, 'new_where'));
        add_filter('posts_fields', array($this, 'new_fields'));
         
        if ($debug_query != FALSE)
            add_filter( 'posts_request', array($this, 'dump_request' ));
    }
    function thumbnailgrid_removequeryfilter(){
        remove_filter('posts_join', array($this, 'new_join') );
        remove_filter('posts_orderby', array($this, 'new_order'));
        remove_filter('posts_where', array($this, 'new_where'));
        remove_filter('posts_fields', array($this, 'new_fields'));
    }
    function new_fields($pfields)
    {
      $pfields = apply_filters('shfly_tgrd_posts_fields', $pfields);
      return ($pfields);
    }

   function new_join($pjoin){
      $pjoin = apply_filters('shfly_tgrd_posts_join', $pjoin);
      return ($pjoin);
    }
   function new_where($pwhere){
       $pwhere = apply_filters('shfly_tgrd_posts_where', $pwhere);
	    return ($pwhere);
   }
   function new_order( $oby ){
      
        $oby = apply_filters('shfly_tgrd_posts_orderby', $oby);
        return ($oby);
   }

 

    function dump_request( $input ) {

    var_dump($input);

    return $input;
}
}?>
