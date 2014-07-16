=== Plugin Name ===
Contributors: nomadcoder
Donate link: http://www.nomadcoder.com
Tags: featured image, thumbnail grid
Requires at least: 
Tested up to: 3.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


This plugin gives you access to two simple shortcodes that display posts and bookmark thumbnails in a grid. The plugin uses the featured image thumbnail if one exists. If one does not exist, the title will be displayed instead. Use the category feature to ensure that all of the posts that you want to display in this manner have thumbnails.

For quicker support, please visit the plugin web page. 
 
http://www.nomadcoder.com/thumbnail-grid-wordpress-plugin/. 

Please Comment & Rate. If you can, plese Donate. Everything helps! 


== Description ==
Version 2.1.2


This plugin gives you access to two simple shortcodes that display posts and bookmark thumbnails in a grid. The plugin uses the featured image thumbnail if one exists. If one does not exist, the title will be displayed instead. Use the category feature to ensure that all of the posts that you want to display in this manner have thumbnails.

This plugin is not designed to show several pages of thumbnails, rather it is designed to allow you to embed thumbnails into any page or to display a subset of thumbnails on a page. To show more thumbnails on a page than the default value Blog pages show at most, use a fixed value for posts_per_page or try setting the posts_per_page value to ’999′.

Your theme must be enabled for post thumbnails.

POSTS & PAGES

You can now use height & width to override the height & width of the thumbnail
[thumbnailgrid height="100px" width="100px"]

You can now change the width of the grid 
[thumbnailgrid gridwidth="500px"]

You can use any of the parameters listed in the wordpress codex.
Click here to see the WordPress Codex for Posts

Here are some of the most common paramaters:

cat = (*Use cat instead of category for posts if category does not work) category id. To view the category id, go to your post categories, edit the category and look in the browser address bar for the category id. If you don’t see it, click your mouse in the address bar.
It should look something like this. The category id is 7:

[[your site goes here]]/wp-admin/edit-tags.php?action=edit&taxonomy=category&tag_ID=7&post_type=post

posts_per_page - the number of posts to display. The default is -1. This causes the post count to default to the value in Blog pages show at most.

post_type – the type of posts to display. The default is ‘post”.  Enter any type that supports thumbnails. Obviously, you want to have featured images set for all of the posts that you are displaying in this grid. You can use ‘page’ here if your theme supports thumbnails on pages. You can enter custom post types, like ‘events’ in this section too.

orderby - Sort retrieved posts by parameter. The default is none.

order – ‘DESC’ or ‘ASC’

tag - use the tag name, not the tag id.

[thumbnailgrid cat='1' posts='5' type='post' order_by='author' order='ASC']”

Will display the thumbnails for the 5 most recent posts from the category with the id of 1 sorted by the author name in ascending order

[thumbnailgrid]
Will display the thumbnails using the default value in the Reading settings Blog pages show at most.

Bookmarks (Links)

Bookmark links can also be displayed clickable as thumbnails. If your installation of  Wordpress is missing Links, you can install the plugin.  Set the thumbnail image in the advanced section of the “Edit Link” screen where it says “Image Address”. Because there is no look-up feature on this administrative page. If you are using images from your own media library you may want to install this handy plugin

You can use any of the parameters listed on the WordPress Codex page for bookmarks. Note that for bookmarks, you should use category instead of cat.

Examples:

[bkthumbnailgrid category="4" limit="5"]
displays the thumbnails for the first 5 bookmarks where the link category is 4.

[bkthumbnailgrid orderby="owner" order="DESC"]
displays all thumbnails for all bookmarks ordered descending by the name of the owner.

 


== Installation ==

Download the Thumbnail Grid Plugin

How to install this plugin

Method 1:
Install this plugin using the built-in plugin installer:
Go to Plugins > Add New.
Under Search, type “Thumbnail Grid”
Click Install Now.
A popup window will ask you to confirm your wish to install the Plugin.
Click Proceed to continue with the installation. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

Method 2:
Download the plugin. In the WordPress admin panel, click on Plugins. Select Add New to get to the “Install Plugins” page. Click on browse and choose the downloaded file.



Click on the “Install Now” button to install the plugin. Once the plugin is installed, select “Activate Plugin”.

Method 3: (Advanced Users):

Unzip the file. Using FTP, upload the thumbnailgrid folder to the contents/plugins folder in your wordpress directory.



== Frequently Asked Questions ==
- How do I control the number of thumbnails that appear? -
This plugin is not designed to show several pages of thumbnails, rather it is designed to allow you to embed thumbnails into any page or to display a subset of thumbnails on a page. To show more thumbnails on a page than the default value "Blog pages show at most" (See the second screenshot on the screenshot tab), use a fixed value for posts_per_page or try setting the posts_per_page value to ’999′.

- I can't add a thumbnail to my posts.-
Your theme must be enabled for post thumbnails.

- How do I change the Height & Width on a single post or page?
You can now change the height and width in the short code, allowing you to override the style sheet width and height. Use auto to retain proportional sizing in the grid.

[thumbnailgrid height="200px" width="auto"]

- How do I change the Height & Width of the thumbnails for all posts and pages?

You can install a custom css plugin (do a search for the plugin Simple Custom CSS). Once installed, it should show up in your Appearance menu. Using the Custom CSS plugin:

Copy & Paste the styles below. Replace the width and height with the width and height that you would like to use. You can also change the space around the thumbnail by changing the padding. !important ensures that your custom style will have priority over the default style.

Start Copy & Paste Below this line

.postimage,
.postimage img {
width: 150px!important;
height: 150px!important;
}
.griditemleft {
width: 150px!important;
padding: 10px!important;
}

End Copy & Paste Above this line
Save this change. When you are testing your view, make sure that you refresh the page. If you mess it up, just delete it and start over.

- How do I center the grid on individual posts or pages?

You must set the width of the grid in order to center it. You can set the grid width in the shortcode by using the gridwidth in the shortcode.

[thumbnailgrid gridwidth="500px"]

How do I center the grid on all pages?

If your theme does not have a custom css module, you can install a custom css plugin (do a search for the plugin Simple Custom CSS). Once installed, it should show up in your Appearance menu. Using the Custom CSS plugin:

Using custom CSS, you must add a width to thumbnailgridcontainer. This can be a percentage.

Example 1:

.thumbnailgridcontainer {

width: 500px!important;
}

Example 2:
.thumbnailgridcontaner
{

width:80%!important;
}
End Copy & Paste Above this line
Save this change. When you are testing your view, make sure that you refresh the page. If you mess it up, just delete it and start over.

Visit the Web Site (and the Support page on Wordpress) for more tips: http://www.nomadcoder.com/thumbnail-grid-wordpress-plugin/

== Screenshots ==

1. This is a screenshot taken from the web site. Please visit http://www.nomadcoder.com/thumbnail-grid-wordpress-plugin/ to see the thumbnail demos.

2. To view or modify "Blog Pages Show at most, go to the reading section of your Wordpress Settings

== Changelog ==

= 1.0 =
* Original Release
= 1.1 =
* Remove extra quote after link title
= 1.2 =
* Fix incorrect thumbnail image loading
= 2.0 =
* Add functionality that enables users to center grid. See instructions.
* Add height & width attributes to shortcode for individual thumbnail grid.
= 2.1 =
* Fixes for height & width, minor modifications
* Add gridwidth attribute to shortcode
* = 2.1.1 - Fix to style sheet
* = 2.1.2 - Remove comments that (we think) wordpress is changing to <p>carriage returns.</p>
== Arbitrary section ==

Need more? Customization is available. Contact adrian@nomadcoder.com for more information.