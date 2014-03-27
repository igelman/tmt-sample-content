<?php /* 
Plugin Name: TMT Sample Content
Plugin URI: 
Description: Populate sample content for TMT post types.
Version: 0.1 
Author: Alan Igelman
Author URI: 

    Copyright 2014 Taste Media Technologies, Inc.

	Based on Hivemind Labs, Inc. Wp Example Content plug-in

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

// Start up the engine 
add_action('admin_menu', 'sample_posts_menu');

// Define new menu page parameters
function sample_posts_menu() {
	add_menu_page( 'TMT Sample Content', 'TMT Sample Content', 'activate_plugins', 'tmt-sample-content', 'sample_posts_options', '');
}

// Define new menu page content
function sample_posts_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} else {
		if ($_GET["add_bundle"] == true){
			echo '<div class="updated below-h2" id="message">
					<p>Sample Post Bundle Added!</p>
				</div>';
		} elseif ($_GET["remove_all"] == true){;
			echo '<div class="updated below-h2" id="message">
                <p>All Sample Posts Removed!</p>
            </div>';
        };
	
		echo '<a href="?page=tmt-sample-content&amp;add_bundle=true" class="button">Add Bundle of Sample Posts</a>';
		echo '<a href="?page=tmt-sample-content&amp;remove_all=true" class="button">Remove All Sample Posts</a>';
		
		
		$postContent = [
			'post_title'	=> 'Multiple Paragraph Post',
			'post_content'	=> '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
			'post_status'	=> 'publish',
			'post_type'		=> "tmt-deal-posts",
		];
		
		$postCustom = [
		];

		
		if ($_GET["add_bundle"] == true){
			global $wpdb;
			$postId = wp_insert_post( $postContent );
			// update_field($field_key, $value, $post_id)
			//http://www.advancedcustomfields.com/resources/functions/update_field/
		};

	}
}
		

	// ---------------------------------------

	//  Remove Posts -------------------------
/*
	if ($_GET["remove_all"] == true){
	    // Get content for all posts and pages, then remove them
	    include 'content.php';
	    foreach($remove_posts_array as $array){
	        $page_name = $array["post_title"];
			global $wpdb;
	        $page_name_id = $wpdb->get_results("SELECT ID FROM " . $wpdb->base_prefix . "posts WHERE post_title = '". $page_name ."'");
	        foreach($page_name_id as $page_name_id){
	        	$page_name_id = $page_name_id->ID;
	        	wp_delete_post( $page_name_id, true );
	        };
	    };
	};
*/
	// ---------------------------------------
	
?>