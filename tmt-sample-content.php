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
    
    
    
    TODO
    + Add coupon sample content
    + Loop to add lots of content
    + Compute expiration date
*/ 

define("N", 10);

define("ACF_KEY_DEAL_URL", "field_526440b01e452");
define("ACF_KEY_DEAL_DEALEXP", "field_526c28fddb7cc");
define("ACF_KEY_DEAL_CODES", "field_527bb9710e6cb");

// Start up the engine 
add_action('admin_menu', 'sample_posts_menu');

// Define new menu page parameters
function sample_posts_menu() {
	add_menu_page( 'TMT Sample Content', 'TMT Sample Content', 'activate_plugins', 'tmt-sample-content', 'sample_posts_options', '');
}

function getMessage($state) {
	$message['added'] = '<div class="updated below-h2" id="message"><p>Sample Post Bundle Added!</p></div>';
	$message['removed'] = '<div class="updated below-h2" id="message"><p>All Sample Posts Removed!</p></div>';
	return $message[$state];
}

function getAddButton () {
	return '<a href="?page=tmt-sample-content&amp;add_bundle=true" class="button">Add ' . N . ' Sample Posts</a>';
}

function getRemoveButton () {
	return '<a href="?page=tmt-sample-content&amp;remove_all=true" class="button">Remove All Sample Posts</a>';
}

function getDateNDaysFromNow($n) {
	return date("Ymd", strtotime("+$n day"));
}

function getPostContent($postType) {
	return [
		'post_title'	=> 'A sample deal post',
		'post_content'	=> '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
		'post_status'	=> 'publish',
		'post_type'		=> $postType,
	];
}

function addDealCustomFields($postId, $i) {
	//http://www.advancedcustomfields.com/resources/functions/update_field/
	update_field(ACF_KEY_DEAL_URL, "http://some.url.com", $postId);
	update_field(ACF_KEY_DEAL_DEALEXP, "20141231", $postId);
	
	$codes[] = [
		"code"				=> "code1",
		"description"		=> "code1 description",
		"expiration_date"	=> getDateNDaysFromNow($i),//"20141231",
		"acf_fc_layout"		=> "codes",
	];
	$codes[] = [
		"code"				=> "code2",
		"description"		=> "code2 description",
		"expiration_date"	=> getDateNDaysFromNow($i),//"20141231",
		"acf_fc_layout"		=> "codes",
	];
	update_field(ACF_KEY_DEAL_CODES, $codes, $postId);
}

function addCouponCustomFields($postId, $i) {
	
}

function addDealPosts($n) {
	$output = "<ol>";
	for ($i = 1; $i <= $n; $i++) {
		$postId = wp_insert_post( getPostContent("tmt-deal-posts") );
		addDealCustomFields($postId, $i);
		$output .= "<li><a href='http://localhost/development/wordpress/wp-admin/post.php?post=$postId&action=edit'>$postId</a></li>";
	}
	$output .= "</ol>";
	return $output;
}

// Define new menu page content
function sample_posts_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} else {
		$output = "";
		
		// If user clicked action, do it
		if ($_GET["add_bundle"] == true){
			$output .= getMessage("added");
			global $wpdb;
			$output .= addDealPosts(N);
		} elseif ($_GET["remove_all"] == true){;
			$output .= getMessage("removed");
        };
		$output .= getAddButton ();
		$output .= getRemoveButton ();		
	}
	echo $output;
}
		

	//  Remove Posts -------------------------
/*
			global $wpdb;
	        $page_name_id = $wpdb->get_results("SELECT ID FROM " . $wpdb->base_prefix . "posts WHERE post_title = '". $page_name ."'");
	        foreach($page_name_id as $page_name_id){
	        	$page_name_id = $page_name_id->ID;
	        	wp_delete_post( $page_name_id, true );
	        };
*/
	
?>