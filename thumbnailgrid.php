<?php
/*
Plugin Name: Featured Image Thumbnail Grid
Plugin URI: http://www.shooflysolutions.com/premium-thumbnail-grid-wordpress-plugin/
Description: This is the new version of the Featured Image Thumbnail Grid. Display Thumbnail Grid using Featured Images
Version: 3.1
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
    add_action( 'wp_enqueue_scripts',  'sfly_thumbnailgrid_scripts_method' );
    function sfly_thumbnailgrid_scripts_method()
    {
         wp_enqueue_style( 'sfly-tbgrd-css', plugins_url( 'css/thumbnailgrid.css' , __FILE__ ) );
    }

class sfly_thumbnailgrid
{

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
    function getSettings(&$atts)
    {
         $atts = apply_filters( 'sfly_tbgrd_settings', $atts);//do something with settings
         $settings = new stdClass;
         extract( shortcode_atts( array(
                'height' => '',                
                'width' => '',
                'gridwidth' =>'',
                'showcaption' => 'TRUE',
                'captionheight' => '',
                'captionwidth' => '',
                'wraptext' => 'FALSE',
                'aligngrid' => '',
                'imagesize' => 'thumbnail'

	        ), $atts ) );
         
           unset($atts["height"]);
           unset($atts["width"]);
           unset($atts["gridwidth"]);
           unset($atts['showcaption']);
           unset($atts['captionheight']);
           unset($atts['captionwidth']);
           unset($atts['wraptext']);
           unset($atts['aligngrid']);
           unset($atts['imagesize']);
           $settings->height = $height;
           $settings->width = $width;
           $settings->gridwidth = $gridwidth;
           $settings->imagesize = $imagesize;
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
           $settings->griditemstyle = $this->getStyle('sfly_tbgrid_griditem_style', '');
           $settings->griditemclass = $this->getClass('sfly_tbgrid_griditem_class', 'griditemleft');
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
        $this->thumbnailgrid_addqueryfilter();
         $settings = new stdClass();
        
        if ($atts)
        {
           $settings = $this->getSettings($atts);    
        }
        if ($atts)
        {
           $the_query = new WP_Query($atts);
        }
        else
        {
         
           $the_query = new WP_Query('posts_per_page  = -1');
        }
        // The Loop
        
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
            $thumb->image_url = $image_url[0];
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
            if ($thumb->image_id)
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
    function thumbnailgrid_addqueryfilter(){
        add_filter('posts_join', array($this, 'new_join') );
        add_filter('posts_orderby', array($this, 'new_order') );
        add_filter('posts_where', array($this, 'new_where'));
        add_filter('posts_fields', array($this, 'new_fields'));
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
}?>
