<?php
/*
Plugin Name: Featured Image Thumbnail Grid
Plugin URI: http://www.shooflysolutions.com/premium-thumbnail-grid-wordpress-plugin/
Description: Display Thumbnail Grid using Featured Images
Version: 5.4
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
new sfly_tbgrid_admin();
class sfly_tbgrid_admin
{
    var  $postid;
    public function __construct()
    {   
           add_action('admin_menu', array($this, 'thumbnailgrid_menu_settings')); //Add an admin menu
           add_action('admin_init', array($this, 'thumbnailgrid_init_settings')); //Do Initialization stuff
    }
   //Create an option page for the thumbnail grid 
    function thumbnailgrid_menu_settings() {
     
        add_options_page("Thumbnail Grid Settings", "Thumbnail Grid Settings", 'manage_options', 'sfly_tgrid_settings', array($this, 'sfly_display_menu_settings'));
    }
    //Register thumbnail settings
  
     function thumbnailgrid_init_settings()
    {
     
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_load_styles' );
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_compress' );
        register_setting('sfly_thumbnailgrid', 'sfly_tbgrid_generic_thumb');

    }
    //Admin menu settings
    function sfly_display_menu_settings() {
        
        ?> 
        </pre>
        <div class="wrap" style="width:80%; margin: auto">
         <div style="width: 400px; float: left">
                <form action="options.php" method="post" name="sfly_tgrid_options"><!--send to the Options.Php file-->
      
                <?php
                   settings_fields( 'sfly_thumbnailgrid' ); // Use the sfly_thumbnail_grid context
                   $css_load = get_option('sfly_tbgrid_load_styles', 'header'); //get the load option style
                   $css_compress = get_option('sfly_tbgrid_compress', '0'); // Use compressed stylesheet
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
                <h3>Thank you for using our plugin. Donations for extended support are appreciated but never required!</h3>
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

//callable handler for bookmark thumbnails - this is obsolete functionality
function bkthumbnailgrid_handler($atts) {
    //Include Stylesheet
    
    $tg = new sfly_thumbnailgrid();
    $output = $tg->bkthumbnailgrid_function($atts);
    return $output;
}

//callable handle regular thumbnails 
function thumbnailgrid_handler($atts) {
 
    $tg = new sfly_thumbnailgrid($atts);
    $output = $tg->thumbnailgrid_function($atts);
    return $output;
}

$css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the load option
    
//If option to load css in header load it now by calling static function scripts_method
if ($css_load == "header")
{
    add_action( 'wp_enqueue_scripts',  array('sfly_thumbnailgrid' , 'scripts_method' ));
}
  

class sfly_thumbnailgrid
{
public function __construct($atts = NULL)
{
    if (isset($atts))
    {
        if (isset($atts['aligngrid']))
        {
            if ($atts['aligngrid'] == "autocenter")
            {
              //  wp_enqueue_script( 'jquery-ui-core' );
               // wp_enqueue_script('jquery-effects-core');
                wp_enqueue_script( 'sfly-tbgrd-js', plugins_url( 'js/thumbnailgrid.js'  , __FILE__ ) );
            }
        }
    }   
    $css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the options
   //If option to load css is set to footer, load it now.
    if ($css_load == "footer")
        sfly_thumbnailgrid::scripts_method();
    
       
}
public static function scripts_method()
{
        //check to see which file to load, compressed or uncompressed
        $css_compress = (get_option('sfly_tbgrid_compress', '0'));
        $css_filename = $css_compress == "1" ? "thumbnailgrid-compressed.css" :  "thumbnailgrid.css";
        wp_enqueue_style( 'sfly-tbgrd-css', plugins_url( 'css/' . $css_filename , __FILE__ ) );
}


//Default Attributes
function defaults($atts)
{
    return shortcode_atts( array(
        'height' => '',                 //Thumbnail Height                
        'width' => '',                  //Thumbnail Width
        'gridwidth' =>'',               //Width of Grid
        'showcaption' => 'TRUE',        //Display Caption/Title
        'captionheight' => '',          //Caption Height - Set for wrap around titles
        'captionwidth' => '',           //Caption Width - defaults to Thumbnail Width
        'wraptext' => 'FALSE',          //Wrap the Caption/Title text instead of elipsis ...
        'posts_per_page' => -1,         //Set to display all posts
        'aligngrid' => '',              //Align the grid left/center/right
        'imagesize' => 'thumbnail',     //Image size, Thumbnail, Medium, Full etc.
        'cellwidth' => '',              //Cell width (not currently useful)
        'cellheight' => '',             //Cell height (not currently useful)
        'today' => FALSE,               //If using before or after, set this to show today's post only
        'debug_query' => FALSE,         //Debug query for WP_Query function. Dumps value. Only for debugging
        'maxgridwidth' => '',           //Max width
        //These are Wordpress settings that do not work natively with the plugin so had to be coded
        'before' => '',                 //before date - can be set to 'today'
        'after' => '',                  //after date - can be set to 'today'
        'post__in' => '',               //Comma delimited list of post id's to be displayed
        'post__not_in' => '',           //Display all posts except posts in this Comma delimited list
        'tag__not_in' => '',            //Display all posts except posts in with tags in this comma delimited list (ids)
        'category__not_in' => '',       //Display all posts except posts with categories in this comma delimited lists
        'category__and' => '',          //Display all posts that are in all of these categories
        'author__not_in' => '',        //Display all posts except posts by these authors (ids)
        'tag_slug__and' => '',          //Comma delimited list of tags to determine posts displayed
        'tag_slug__in' => '',           //Commad delimited list of tags to ignore
        'tag__and' => '',               //display posts that contain all of these tags
        'post_parent__in' => '',        //Comma delimited list of parent id's to determine posts displayed (use for pages)
        'post_parent__not_in' => '',    //Comma delimited of parent id's to ignore
        'inclusive' => FALSE,           //Used with the before and after dates to make those dat
         
            ), $atts  );
}

//Get & filter settings
function getSettings(&$atts)
{
     
        $settings = new stdClass; //Empty object or storing settings
        if (!$atts)
            $atts = $this->defaults($atts); //Get Default Settings & Attributes if nothing was sent
        
        extract($this->defaults($atts)); //Extract Settings and Attributes
        
	    $atts = apply_filters( 'sfly_tbgrd_settings', $atts);//do something with settings

        $this->postid = get_the_ID();
         
        //unset all unecessary attributes - leave regular post queries.        
        unset($atts["height"]);
        unset($atts["width"]);
        unset($atts["maxgridwidth"]);
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
        unset($atts['tag__not_in']);
        unset($atts['tag__and']);
        unset($atts['category__not_in']);
        unset($atts['category__and']);
        unset($atts['author__not_in']);
        unset($atts['post_parent__in']);
        unset($atts['post_parent__not_in']);
        unset($atts['before']);
        unset($atts['after']);
        unset($atts['today']);
        unset($atts['inclusive']);
        unset($atts['debug_query']); 
        unset($atts['aligngrid']);
        if (!$atts) //if it's empty, use minimum attributes so that it works
        {
            $atts['imagesize'] = 'thumbnail';
            $atts['[posts_per_page'] = -1;
        }
    
       //Create the attributes for date range queries
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
        //Add todays date if the query is for today only
        if ($today != FALSE)
        {
            $atts['date_query'] = $this->todayArray();
           
        }
        
        //Create the attributes to include/exclud posts based on id's tags & parents
        if ($post__in)
        $atts['post__in'] =  explode(",",  $post__in);
        if ($post__not_in)
            $atts['post__not_in'] = explode(",", $post__not_in);
 
        if ($tag__not_in)
            $atts['tag__not_in'] = explode(",", $tag__not_in);

        if ($category__not_in)
            $atts['category__not_in'] = explode(",", $category__not_in);
        if ($category__and)
            $atts['category__and'] = explode(",", $category__and);
       if ($category__not_in)
            $atts['author__not_in'] = explode(",", $author__not_in);   
        if ($tag_slug__and)
             $atts['tag_slug__and'] = explode(",", $tag_slug__and);
        if ($tag_slug__in)
            $atts['tag_slug__in'] = explode(",", $tag_slug__in);
        if ($tag__and)
            $atts['tag__and'] = explode(",", $tag__and);
        if ($post_parent__in)
            $atts['post_parent__in'] = explode(",", $post_parent__in);
        if ($post_parent__not_in)
        $atts['post_parent__not_in'] = explode("," , $post_parent__not_in); //fixed 7/10/15

    
  

        //Fill in the settings fields  
        $settings->height = $height;
        $settings->width = $width;
        $settings->gridwidth = $gridwidth;
        $settings->maxgridwidth = $maxgridwidth;
        $settings->imagesize = $imagesize;
        $settings->debug = $debug_query;
        $griditemstyle = "";
        if ($cellwidth)
            $griditemstyle .= "width:" . $cellwidth . ";";
        if ($cellheight)
            $griditemstyle .= "height:" .$cellheight . ";";
        if ($aligngrid == "autocenter")
            $griditemstyle .= "visibility:hidden";
        //Go filter the style & class
        $settings->griditemstyle = $this->getStyle('sfly_tbgrid_griditem_style', $griditemstyle);
        $settings->griditemclass = $this->getClass('sfly_tbgrid_griditem_class', 'griditemleft');
        //Get the Grid Container Style
        $gridstyle = $this->setGridStyle($gridwidth, $maxgridwidth, $aligngrid);
        $settings->gridstyle = $this->getStyle('sfly_tbgrid_grid_style', $gridstyle);
        //Filters for the grid
        $gridclass = "thumbnailgridcontainer tbdgrd_$this->postid";
        $gridclass .= ($aligngrid == 'autocenter') ? " tbgrd_autocenter" : "";
        $settings->gridclass = $this->getClass("sfly_tbgrid_grid_class", $gridclass);
        //Set up the title styles
        $titlestyle = $this->setTitleStyle($showcaption, $width, $captionheight);
        $settings->titlelinkstyle = "";
        if ($wraptext == 'TRUE' || $wraptext == "true")
                $settings->titlelinkstyle .= "white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap!important;word-wrap: break-word!important;";
        if ($captionwidth != '')
                $settings->titlelinkstyle .= "width:" . $captionwidth . ';margin:auto;';
        $settings->titlestyle = $this->getStyle('sfly_tbgrid_title_style', $titlestyle);
        //Filter the title class           
        $settings->titleclass = $this->getClass('sfly_tbgrid_title_class', 'postimage-title' );
        //Get the title link style
        $settings->titlelinkstyle = $this->getStyle('sfly_tbgrid_titlelink_style', $settings->titlelinkstyle);
        //Filter the title link class
        $settings->titlelinkclass = $this->getStyle('sfly_tbgrid_titlelink_class', '');
        //Set up styles & filter class for the image            
        
        $settings->imagestyle = $this->getStyleWithSize('sfly_tbgrid_image_style', $settings->height, $settings->width);
        $settings->imageclass = $this->getClass('sfly_tbgrid_image_class', '');
        //Get the Image div container Style
        $settings->postimgstyle =     $this->getStyleWithSize('sfly_tbgrid_postimagediv_style', $settings->height, $settings->width);
        $settings->postimgclass = $this->getClass('sfly_tbgrid_postimagediv_class', 'postimage');
           
        return $settings;
    }
function thumbnailgrid_function($atts) {
        
   
    $this->postid = get_the_ID();     //current post id
    $settings = new stdClass();
     
    //get the sttings 
    $settings = $this->getSettings($atts);    
      
         
    //add any filters for the query
    $this->thumbnailgrid_addqueryfilter($settings->debug);
    //run the query
     wp_reset_query();
    $the_query = new WP_Query($atts);
    //remove the filter
    $this->thumbnailgrid_removequeryfilter($settings->debug);
    //number of pages in the query
    $max = $the_query->max_num_pages;
    //navigate if the grid is paged   
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
        $id = get_the_ID();
        $thumb->title = apply_filters( 'sfly_tbgrd_title', $thumb->title, $id);
      
        //Returns content that displays under the title text
        $thumb->extra = apply_filters('sfly_tbgrid_extra_info', '');
        $thumb->target = ''; //only used for obsolete link thumbnails
        //get the image id
        $thumb->image_id = get_post_thumbnail_id(); 
        //get the url     
        $image_url = wp_get_attachment_image_src($thumb->image_id, $settings->imagesize, true);
        $thumb->image_url = $image_url[0] ;
        //display the thumbnail         
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
	    * Print the actual thumbnail
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

//Add query filters
function thumbnailgrid_addqueryfilter($debug_query = FALSE){
    add_filter('posts_join', array($this, 'new_join') );  //filter changes to the join section o the query
    add_filter('posts_orderby', array($this, 'new_order') ); //filter changes to the order by section of the query
    add_filter('posts_where', array($this, 'new_where')); //filter changes to the where section of the query
    add_filter('posts_fields', array($this, 'new_fields')); //filter changes to the fields section o the query
         
    if ($debug_query != FALSE)
        add_filter( 'posts_request', array($this, 'dump_request' )); //show the query
}

//Remove query filters
function thumbnailgrid_removequeryfilter($debug_query = FALSE){
    remove_filter('posts_join', array($this, 'new_join') );
    remove_filter('posts_orderby', array($this, 'new_order'));
    remove_filter('posts_where', array($this, 'new_where'));
    remove_filter('posts_fields', array($this, 'new_fields'));
    if ($debug_query != FALSE)
        remove_filter( 'posts_request', array($this, 'dump_request' ));
}

//modify fields in sql query
function new_fields($pfields)
{
    $pfields = apply_filters('shfly_tgrd_posts_fields', $pfields);
    return ($pfields);
}
//modify join in sql query
function new_join($pjoin){
    $pjoin = apply_filters('shfly_tgrd_posts_join', $pjoin);
    return ($pjoin);
}
//modify where in sql query
function new_where($pwhere){
    $pwhere = apply_filters('shfly_tgrd_posts_where', $pwhere);
	return ($pwhere);
}

//modify order by in sql query
function new_order( $oby ){
     
    $oby = apply_filters('shfly_tgrd_posts_orderby', $oby);
    return ($oby);
}
 
//Debug query - dumps the query 
function dump_request( $input ) {

    var_dump($input);
    return $input;
}



//Apply Class filters
function getClass($filtername, $class)
{   //add any additional classes 
    $newclass = apply_filters($filtername, $class); 
    $newclass = ($newclass != '') ? ' class="' .$newclass . '"': '';
    return $newclass;
}

//Apply Style Filters
function getStyle($filtername, $style)
{ //add any additional style
    $newstyle="";
    $newstyle = apply_filters( $filtername, $style );
    $newstyle = $newstyle != '' ? ' style="' .$newstyle . '"': '';
    return $newstyle;
        
}   
//Height & Width styles
function getStyleWithSize($filtername, $height, $width) 
{ //create a size style width & height
    $newstyle = "";
    if ($height || $width)
    {
        $newstyle .= ($height) ? 'height:' .$height . ';': '';
        $newstyle .=  ($width) ? 'width:' .$width . ';': '';
    } 
    //apply any filters
    $newstyle = apply_filters($filtername, $newstyle); 
    $newstyle = ($newstyle != '') ? ' style="' .$newstyle . '"': '';
    return $newstyle;
}
//Paging Navigation
function getGridNavContent($page, $location, $max)
{   
    //if $page is blank or null, grid is not paged and nothing should be returned
    //apply filters for grid navigation - used by the sfly-thumbnail-pages plugin
    $content = apply_filters('sfly_tbgrid_grid_nav_content', $page, $location, $max);

    return $content;
}
//Style for thumbnail titles
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

//Style for the Grid. Align grid left/right/center
function setGridStyle($width, $maxgridwidth, $aligngrid)
{
    $gridstyle  = "";
        if ($width != '')
        $gridstyle .= "width:$width;";
    if ($aligngrid == 'left')
        $gridstyle .= 'float:left;';
    else if ($aligngrid == 'right')
        $gridstyle .=  'float:right;';
    else if ($aligngrid == 'center')
        $gridstyle .=  'margin:auto;';
    if ($maxgridwidth)
        $gridstyle .= "max-width:$maxgridwidth;";
  
    return $gridstyle;
}
//Function to return an array for today's date - used by Wp_query
function todayArray()
{
    $today = getdate();
    return array(
            'year'  => $today['year'],
			'month' => $today['mon'],
			'day'   => $today['mday'],
            );
}
}?>