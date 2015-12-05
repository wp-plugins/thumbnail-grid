<?php
/*
Plugin Name: Featured Image Thumbnail Grid
Plugin URI: http://www.shooflysolutions.com/software/wordpress-plugins/featured-image-thumbnail-grid-for-wordpress/
Description: Display Thumbnail Grid using Featured Images
Version: 5.6
Author: A. R. Jones
Author URI: http://shooflysolutions.com
*/

/*
Copyright (C) 2013, 2014, 2015, 2016, Shoofly Solutions (NomadCoder)
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



require_once 'thumbnailgrid-admin.php';

add_shortcode("thumbnailgrid", "thumbnailgrid_handler");
add_shortcode("bkthumbnailgrid", "bkthumbnailgrid_handler");

//callable handle 
function thumbnailgrid_handler($atts) {
 
    $tg = new sfly_thumbnailgrid($atts);
    return $tg->thumbnailgrid_function();
    
}

$css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the load option
    
//If option to load css in header load it now by calling static function scripts_method
if ($css_load == "header")
{
    add_action( 'wp_enqueue_scripts',  array('sfly_thumbnailgrid' , 'scripts_method' ));
}
  

class sfly_thumbnailgrid
{
    private $theatts =  array();
    public function __construct($atts = NULL)
    {
 
        $css_load = (get_option('sfly_tbgrid_load_styles', 'header')); //get the options
       //If option to load css is set to footer, load it now.
        if ($css_load == "footer")
            sfly_thumbnailgrid::scripts_method();
        if (isset($atts))
        {
            $this->theatts = $atts;
            if (isset($atts['aligngrid']))
            { 
                if ($atts['aligngrid'] == "autocenter") //use javascript to center align the grid
                {
                   wp_enqueue_script( 'sfly-tbgrd-js', plugins_url( 'js/thumbnailgrid.js'  , __FILE__ ) );
                }
            }
        }      
       
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
       $atts= shortcode_atts( array(
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
            'target' => NULL,                 //Target
            'inclusive' => FALSE,           //Used with the before and after dates to make those dat
      //      'columns' => FALSE
                ), $atts  );
             return $atts;
    }

    //Get & filter settings
    function getSettings(&$atts)
    {
     
            $settings = new stdclass();
            if (!$atts)
            {
               $atts = $this->defaults($atts); //Get Default Settings & Attributes if nothing was sent
            }
        
            extract($this->defaults($atts)); //Extract Settings and Attributes
            //This filter used to extract alternate settings
	        $atts = apply_filters( 'sfly_tbgrd_settings', $atts);//do something with settings

            $this->postid = get_the_ID();
            $captions = $this->boolVal($showcaption);
            $wrap = $this->boolVal($wraptext);
   
  
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
            unset($atts['target']);
          //  this should never happen, left in because of old bug / failsafe
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
            $settings->target = $target;
           // $settings->columns = $columns;
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
            $gridclass = "thumbnailgridcontainer tbdgrd_{$this->postid}";
            $gridclass .= ($aligngrid == 'autocenter') ? " tbgrd_autocenter" : "";
            $gridclass .= " responsive-grid";
            $settings->gridclass = $this->getClass("sfly_tbgrid_grid_class", $gridclass);
            //Set up the title styles
            $titlestyle = $this->setTitleStyle($captions, $width, $captionheight);
            $settings->titlelinkstyle = "";
            if ($wraptext == 'TRUE' || $wraptext == "true" || $wraptext === TRUE)
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
    function boolVal($val)
    {
             if (!is_bool($val))         {
                return "true" == strtolower($val);
            }   else     {
                return $val;
            }    
    }
    function thumbnailgrid_function($atts=NULL) {
        
        if (!isset($atts))
            $atts = $this->theatts;

        $this->postid = get_the_ID();     //current post id
       
     
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

        $ret = "<div class='thumbnailblock'>$gridnavcontenttop<div id='thumbnailgridcontainer'{$settings->gridclass} {$settings->gridstyle}>";
             
        while ( $the_query->have_posts() ) :$the_query->the_post();
           
            $thumb = new stdClass;
            $thumb->permalink = get_permalink();
            $thumb->title = get_the_title();
            //get the post id
            $thumb->postid = get_the_ID();
            //this filter allows you to add to the title
            $thumb->title = apply_filters( 'sfly_tbgrd_title', $thumb->title, $thumb->postid);
            
            //this filter returns content that displays under the title text
            $thumb->extra = apply_filters('sfly_tbgrid_extra_info', '');

            $thumb->target = $settings->target;

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
        $ret .= "</div><!--thumbnailgridcontainer-->
        $gridnavcontentbottom
        </div><!--thumbnailblock-->";
        wp_reset_postdata();
        return $ret;
    }
  
 	/**
	* the actual thumbnail
    * 
	*/
    public function theThumbnail($settings, $thumb)
    {
        $target = '';
        if ($thumb->target)
       
            $target = "target='{$thumb->target}'";
     
        
        
        $link = "href='{$thumb->permalink}' title='{$thumb->title}' $target";
            
        if ($thumb->image_url)
            $thumbnail = "<img alt='{$thumb->title}' $settings->imageclass src='{$thumb->image_url}' {$settings->imagestyle}/>";
        else
            $thumbnail = "<div {$settings->imagestyle}></div>";
 
        $ret = "<div data-postid='{$thumb->postid}' {$settings->griditemclass} {$settings->griditemstyle}>
                <div {$settings->postimgclass} {$settings->postimgstyle}>
                    <a {$link}>$thumbnail</a>
	            </div>
                <div {$settings->titleclass} {$settings->titlestyle}>
                    <a {$settings->titlelinkstyle} $link>$thumb->title</a> 
	            </div>{$thumb->extra}                   
            </div>";
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
            $ret = "<div class='thumbnailblock'>
            <div {$settings->gridclass} {$settings->gridstyle}>";

   
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
        //This filter modifies query fields
        $pfields = apply_filters('shfly_tgrd_posts_fields', $pfields);
        return ($pfields);
    }
    //modify join in sql query
    function new_join($pjoin){
        //This filter modifies the query join sql
        $pjoin = apply_filters('shfly_tgrd_posts_join', $pjoin);
        return ($pjoin);
    }
    //modify where in sql query
    function new_where($pwhere){
        //This filter modifies the query where sql
        $pwhere = apply_filters('shfly_tgrd_posts_where', $pwhere);
	    return ($pwhere);
    }

    //modify order by in sql query
    function new_order( $oby ){
        //Thsi filter modifies the query orderby sql
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
        $style = ($newstyle != '') ? " style='$newstyle'": '';
        return $style;
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
    
            if (!$showcaption)
            {
          
                $titlestyle .=  "display:none";
            }
            else
            {
                if ($width != '' && $width != 'auto')
                    $titlestyle .= "width:$width;"; 
              
                if ($captionheight != '')
                    $titlestyle .= "height:$captionheight;overflow:hidden;";
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
}
//callable handler for bookmark thumbnails - this is obsolete functionality. There is no guarantee that it works
function bkthumbnailgrid_handler($atts) {
    //Include Stylesheet
    
    $tg = new sfly_thumbnailgrid();
    $output = $tg->bkthumbnailgrid_function($atts);
    return $output;
}

?>