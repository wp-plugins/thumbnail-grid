<?php
/*
Plugin Name: Featured Image Thumbnail Grid
Plugin URI: http://www.nomadcoder.com/thumbnail-grid-wordpress-plugin/
Description: Display Thumbnail Grid using Featured Images
Version: 2.1.2
Author: A. R. Jones
Author URI: http://www.nomadcoder.com
*/

/*
Copyright (C) 2013 Nomad Coder
Contact me at http://www.nomadcoder.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

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
add_shortcode("thumbnailgrid", "thumbnailgrid_handler");
add_shortcode("bkthumbnailgrid", "bkthumbnailgrid_handler");

add_filter('query_vars', 'tbpage_vars');

function tbpage_vars($qvars)
{
    $qvars[] = 'tg_page';
    return $qvars;
}
	 /**
	 * Handler for Shortcode.
	 * $atts = array of options
     * 
	 */
 add_action( 'wp_enqueue_scripts', 'thumbnailgrid_scripts_method' );
 function thumbnailgrid_scripts_method()
 {
      wp_enqueue_style('thumbnailgrid', plugins_url('css/thumbnailgrid.css', __FILE__));
 }
function bkthumbnailgrid_handler($atts) {
    //Include Stylesheet
    
     $tg = new thumbnailgrid();
    $output = $tg->bkthumbnailgrid_function($atts);
  
    return $output;
}
function thumbnailgrid_handler($atts) {
 
     $tg = new thumbnailgrid();
    $output = $tg->thumbnailgrid_function($atts);
  
    return $output;
}
	 /**
	 * Function for Shortcode.
	 * $atts = array of options
     * 
	 */

class thumbnailgrid
{
   

    function thumbnailgrid_function($atts) {
        wp_reset_query();
        if ($atts)
        {
              extract( shortcode_atts( array(
                'height' => '',                
                'width' => '',
                'gridwidth' =>''
	        ), $atts ) );
           unset($atts["height"]);
           unset($atts["width"]);
           unset($atts["gridwidth"]);
          
           $the_query = new WP_Query($atts);
        }
        else
        {
            $the_query = new WP_Query('posts_per_page  = -1');
        }
        // The Loop
        $style = "";
        if ($gridwidth)
            $style = "style=width:$gridwidth";
        $ret = '<div class="thumbnailblock"><div class="thumbnailgridcontainer"' . $style. '>';
        $style = "";
        if ($height || $width)
        {
            $style = ' style="';
            if ($height)
                $style .= 'height:' .$height . ';';
            
            if ($width)
                $style .= 'width:' .$width . ';';
            $style .= '"';
         } 
     
          while ( $the_query->have_posts() ) :$the_query->the_post();
            $titlelength = 20;
            $permalink = get_permalink();
            $title = get_the_title();
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_src($image_id,'thumbnail', true);
            if ($image_id)
                $thumbnail = '<img src="' .$image_url[0] .'"' .$style . '/>';
            else
                $thumbnail = '';
            $tt = $title; 
            $im = '<div class="postimage"' .$style .'>
                <a href="'. $permalink .'" title="'.$title.'">'. $thumbnail .'</a> 
	            </div>';
                $ret .=
                '<div class="griditemleft">'
                . $im ;
	            $ret .= '<div class="postimage-title">
		            <a href="'. $permalink .'" title="'. $title .'">'.$tt .'</a>
	            </div>
            </div>';
        endwhile;
        wp_reset_postdata();
        $ret .=  '</div></div>';
        return $ret;
    }
  
 
	     /**
	     * Function for Shortcode.
	     * $atts = array of options
         * 
	     */
    function bkthumbnailgrid_function($atts) {
        if ($atts)
        {
           extract( shortcode_atts( array(
                'height' => '',                
                'width' => '',
                'gridwidth' => ''
 	        ), $atts ) );
           unset($atts["height"]);
           unset($atts["width"]);
           unset($atts["gridwidth"]);
           $the_query = new WP_Query($atts);
        }
        $style = "";
        if ($height || $width)
        {
            $style = ' style="';
            if ($height)
                $style .= 'height:' .$height . ';';
            
            if ($width)
                $style .= 'width:' .$width . ';';
            $style .= '"';
         } 
        $titlelength = 20; // Length of the post titles shown below the thumbnails
   
        $bookmarks = get_bookmarks( $atts );

    // Loop through each bookmark and print formatted output
       
        $gstyle = "";
        if ($gridwidth)
            $gstyle = "style=width:$gridwidth";
        $ret = '<div class="thumbnailblock"><div class="thumbnailgridcontainer"' . $gstyle. '>';
       // $titlelength = 20;
        foreach ( $bookmarks as $bookmark ) { 
            $permalink = $bookmark->link_url;
            $title = $bookmark->link_name;
            $target = $bookmark->link_target;
            $thumbnail = $bookmark->link_image;
            if ($target != '')
            {
                $target = ' target="' .$target .'"';
            }
            
           if (strlen($title) > $titlelength)
                $tt = mb_substr($title, 0, $titlelength) . ' ...';
            else 
                 $tt = $title; 
                $im = '<div class="postimage"' .$style .'>
                    <a href="'. $permalink .'" title="'.$title.'"'. $target .'><img src="'. $thumbnail .'"' . $style .'/></a> 
	            </div>';
                $ret .=
                '<div class="griditemleft">'
                . $im .
	            '<div class="postimage-title">
		            <a href="'. $permalink .'" title="'. $title .'">'.$tt .'</a>
	            </div>
            </div>';
        }
        wp_reset_postdata();
         $ret .=  '</div></div>';
        return $ret;
    }
}
?>
