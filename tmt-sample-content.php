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

require_once 'tmt-sample-content-config.php';

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
		'post_title'	=> 'Macy\'s 20% off All Items coupon (+ Free Shipping on $99), 25% off Sitewide coupon (+ Clearance Sale, deals from $3)',
		'post_content'	=> 'Stock up on stylish essentials for a steal with the Warehouse Event at Land\'s End, down jackets, vests, shoes and more - plus free shipping on $50. Some picks:<ul><li>Women\'s Tops, Cardigans (from <b>$4</b>)</li><li>Snoes, Clogs, Mocs (from <b>$19</b>)</li><li>Men\'s Shirts (from <b>$6</b>)</li><li>Men\'s Pants (from <b>$12</b>)</li><li><b>View all items &gt;</b></li></ul>',
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
	update_field(ACF_KEY_COUPON_URL, "http://some.url.com", $postId);
	update_field(ACF_KEY_COUPON_CODE, "abc123", $postId);
	update_field(ACF_KEY_COUPON_EXPIRES, getDateNDaysFromNow($i), $postId);
	update_field(ACF_KEY_COUPON_OFFERID, "12345678", $postId);
}

function addDealPosts($n) {
	$output = "<div>Deal Posts<ol>";
	for ($i = 1; $i <= $n; $i++) {
		$postId = wp_insert_post( getPostContent("tmt-deal-posts") );
		addDealCustomFields($postId, $i);
		$output .= "<li><a href='http://localhost/development/wordpress/wp-admin/post.php?post=$postId&action=edit'>$postId</a></li>";
	}
	$output .= "</ol></div>";
	return $output;
}

function addCouponPosts($n) {
	$output = "<div>Coupon Posts<ol>";
	for ($i = 1; $i <= $n; $i++) {
		$postId = wp_insert_post( getPostContent("tmt-coupon-posts") );
		addCouponCustomFields($postId, $i);
		$output .= "<li><a href='http://localhost/development/wordpress/wp-admin/post.php?post=$postId&action=edit'>$postId</a></li>";
	}
	$output .= "</ol></div>";
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
			$output .= addCouponPosts(N);
		} elseif ($_GET["remove_all"] == true){;
			$output .= getMessage("removed");
        };
		$output .= getAddButton ();
		//$output .= getRemoveButton ();		
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