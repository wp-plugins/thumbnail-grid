<?php 
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