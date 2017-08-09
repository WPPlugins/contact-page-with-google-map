<?php
/*
 Plugin Name: Contact Page With Google Map
 Description: This plugin will let users quickly and easily create a contact page with the company hours, address, phone number, and an optional Google Map.
 Version:     1.2
 Author:      Corporate Zen
 Author URI:  http://www.corporatezen.com/
 License:     GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: zen-contact-page
 Domain Path: /languages
 
Contact Page With Google Map is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Contact Page With Google Map is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Contact Page With Google Map. If not, see https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Error: Direct access to this code is not allowed.' );

// de-activate hook
function cpwgm_deactivate_plugin() {
	// clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cpwgm_deactivate_plugin' );


// activation hook
function cpwgm_active_plugin() {
	// trigger our function that registers the custom post type
	cpwgm_setup_post_types();
	
	// clear the permalinks after the post type has been registered
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cpwgm_active_plugin' );


function cpwgm_setup_post_types() {
	$labels = array(
			'name'                => 'Contact Page',
			'singular_name'       => 'Contact Page',
			'menu_name'           => 'Contact Pages',
			'all_items'           => 'All Contact Pages',
			'view_item'           => 'View Contact Page',
			'add_new_item'        => 'Add New Contact Page',
			'add_new'             => 'Add New',
			'edit_item'           => 'Edit Contact Page',
			'update_item'         => 'Update Contact Page',
			'search_items'        => 'Search Contact Pages',
			'not_found'           => 'Not Found',
			'not_found_in_trash'  => 'Not found in Trash'
	);
	
	$args = array(
			'labels' => $labels,
			'menu_icon' => 'dashicons-id-alt',
			'description' => 'Contact Pages are created automatically! All you have to do is enter some information such as address and hours of operation',
			'public' => true,
			'publicly_queryable' => true,
			'show_in_nav_menus' => true,
			//'_builtin' => true, /* internal use only. don't use this when registering your own post type. */
			//'_edit_link' => 'post.php?post=%d', /* internal use only. don't use this when registering your own post type. */
			'capability_type' => 'page',
			'map_meta_cap' => true,
			'menu_position' => 20,
			'hierarchical' => false,
			'rewrite' => false,
			'query_var' => false,
			'delete_with_user' => false,
			'supports' => array( 'title', 'editor', 'revisions' ),
			'show_in_rest' => true,
			'rest_base' => 'pages',
			'rest_controller_class' => 'WP_REST_Posts_Controller'
	);
	
	register_post_type( 'contact_page_cpt', $args );
}
add_action( 'init', 'cpwgm_setup_post_types' );

// register meta boxes
function cpwgm_add_contact_metaboxes() {
	add_meta_box('contact_page_details', 'Details', 'cpwgm_fill_contact_details_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('contact_page_hours', 'Hours of Operation', 'cpwgm_fill_contact_hours_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('contact_page_google_api_key', 'Google Maps', 'cpwgm_fill_map_box', 'contact_page_cpt', 'normal', 'high');
}
add_action( 'add_meta_boxes', 'cpwgm_add_contact_metaboxes' );

// fill map metabox
function cpwgm_fill_map_box() {
	global $post;
	$key = get_post_meta($post->ID, 'google_api_key', true);
	
	echo 'If you are unfamiliar with Google Maps or do not know what an API Key is, you can find out more information here: <a target="_blank" href="https://developers.google.com/maps/faq">FAQ</a><br><br>';
	echo 'Your Google Maps API Key: <input type="text" name="google_api_key" value="' . $key . '" class="widefat" />';
}

// fill contact details metabox
function cpwgm_fill_contact_details_box() {
	global $post;
	
	$biz_name = get_post_meta( $post->ID, 'contact_page_name', true );
	$email    = get_post_meta( $post->ID, 'contact_page_email', true );
	$phone    = get_post_meta( $post->ID, 'contact_page_phone', true );
	$address  = get_post_meta( $post->ID, 'contact_page_street', true );
	$city     = get_post_meta( $post->ID, 'contact_page_city', true );
	$state    = get_post_meta( $post->ID, 'contact_page_state', true );
	$zip      = get_post_meta( $post->ID, 'contact_page_zip', true );
	
	// Echo out the field
	echo 'Business Name (to display): <input type="text" name="contact_page_name" value="' . $biz_name. '" class="widefat" />';
	echo 'Street Address: <input type="text" name="contact_page_street" value="' . $address. '" class="widefat" />';
	echo 'City: <input type="text" name="contact_page_city" value="' . $city. '" class="widefat" />';
	echo 'State: <input type="text" name="contact_page_state" value="' . $state . '" class="widefat" />';
	echo 'Zip: <input type="text" pattern="[0-9]{5}" name="contact_page_zip" value="' . $zip. '" class="widefat" />';
	echo 'Phone (xxx-xxx-xxxx): <input type="text" pattern="^\d{3}-\d{3}-\d{4}$" name="contact_page_phone" value="' . $phone. '" class="widefat" />';
	echo 'Email: <input type="email" name="contact_page_email" value="' . $email. '" class="widefat" />';
}

// fill hours metabox
function cpwgm_fill_contact_hours_box() {
	global $post;
	
	// is open
	$mday_is_open     = get_post_meta( $post->ID, 'mday_is_open', true );
	$tuesday_is_open  = get_post_meta( $post->ID, 'tuesday_is_open', true );
	$wday_is_open     = get_post_meta( $post->ID, 'wday_is_open', true );
	$thursday_is_open = get_post_meta( $post->ID, 'thursday_is_open', true );
	$fday_is_open     = get_post_meta( $post->ID, 'fday_is_open', true );
	$saturday_is_open = get_post_meta( $post->ID, 'saturday_is_open', true );
	$sunday_is_open   = get_post_meta( $post->ID, 'sunday_is_open', true );
	
	$mday_open_checked     = ( $mday_is_open == 1 ? 'checked' : '' );
	$tuesday_open_checked  = ( $tuesday_is_open == 1 ? 'checked' : '' );
	$wday_open_checked     = ( $wday_is_open == 1 ? 'checked' : '' );
	$thursday_open_checked = ( $thursday_is_open == 1 ? 'checked' : '' );
	$fday_open_checked     = ( $fday_is_open == 1 ? 'checked' : '' );
	$saturday_open_checked = ( $saturday_is_open == 1 ? 'checked' : '' );
	$sunday_open_checked   = ( $sunday_is_open == 1 ? 'checked' : '' );
	
	// all day
	$mday_allday     = get_post_meta( $post->ID, 'mday_allday', true );
	$tuesday_allday  = get_post_meta( $post->ID, 'tuesday_allday', true );
	$wday_allday     = get_post_meta( $post->ID, 'wday_allday', true );
	$thursday_allday = get_post_meta( $post->ID, 'thursday_allday', true );
	$fday_allday     = get_post_meta( $post->ID, 'fday_allday', true );
	$saturday_allday = get_post_meta( $post->ID, 'saturday_allday', true );
	$sunday_allday   = get_post_meta( $post->ID, 'sunday_allday', true );
	
	$mday_allday_checked     = ( $mday_allday == 1 ? 'checked' : '' );
	$tuesday_allday_checked  = ( $tuesday_allday == 1 ? 'checked' : '' );
	$wday_allday_checked     = ( $wday_allday == 1 ? 'checked' : '' );
	$thursday_allday_checked = ( $thursday_allday == 1 ? 'checked' : '' );
	$fday_allday_checked     = ( $fday_allday == 1 ? 'checked' : '' );
	$saturday_allday_checked = ( $saturday_allday == 1 ? 'checked' : '' );
	$sunday_allday_checked   = ( $sunday_allday == 1 ? 'checked' : '' );
	
	// hours
	$mday_start	    = get_post_meta( $post->ID, 'mday_start', true );
	$mday_end       = get_post_meta( $post->ID, 'mday_end', true );
	$tuesday_start	= get_post_meta( $post->ID, 'tuesday_start', true );
	$tuesday_end    = get_post_meta( $post->ID, 'tuesday_end', true );
	$wday_start	    = get_post_meta( $post->ID, 'wday_start', true );
	$wday_end       = get_post_meta( $post->ID, 'wday_end', true );
	$thursday_start	= get_post_meta( $post->ID, 'thursday_start', true );
	$thursday_end   = get_post_meta( $post->ID, 'thursday_end', true );
	$fday_start	    = get_post_meta( $post->ID, 'fday_start', true );
	$fday_end       = get_post_meta( $post->ID, 'fday_end', true );
	$saturday_start	= get_post_meta( $post->ID, 'saturday_start', true );
	$saturday_end   = get_post_meta( $post->ID, 'saturday_end', true );
	$sunday_start	= get_post_meta( $post->ID, 'sunday_start', true );
	$sunday_end     = get_post_meta( $post->ID, 'sunday_end', true );
	
	echo '<style>
.hours_div {
	min-width:10%;
	display: inline-block;
}
			
.allday {
	margin-left: 5%;
}
</style>';
	
	echo '<p>Check the days you are open. For those days, enter when you open and when you close. If you are open 24 hours, select the "All Day" option.';
	echo '<div class="day" id="monday"><div class="hours_div"><input class="is_open" name="mday_is_open" id="mday_is_open" type="checkbox" ' . $mday_open_checked . '/> Monday: </div>From <input value="' . $mday_start . '" maxlength="10" class="start" name="mday_start" id="mday_start" type="text"/> To <input value="' . $mday_end . '" maxlength="10" class="end" name="mday_end" id="mday_end" type="text" /> All Day: <input class="allday" name="mday_allday" id="mday_allday" type="checkbox" ' . $mday_allday_checked . ' /></div>';
	echo '<div class="day" id="tuesday"><div class="hours_div"><input class="is_open" name="tuesday_is_open" id="tuesday_is_open" type="checkbox" ' . $tuesday_open_checked. ' /> Tuesday: </div>From <input value="' . $tuesday_start . '" maxlength="10" class="start" name="tuesday_start" id="tuesday_start" type="text" /> To <input value="' . $tuesday_end . '" maxlength="10" class="end" name="tuesday_end" id="tuesday_end" type="text" /> All Day: <input class="allday" name="tuesday_allday" id="tuesday_allday" type="checkbox" ' . $tuesday_allday_checked . ' /></div>';
	echo '<div class="day" id="wednesday"><div class="hours_div"><input class="is_open" name="wday_is_open" id="wday_is_open" type="checkbox" ' . $wday_open_checked. ' /> Wednesday: </div>From <input value="' . $wday_start . '" maxlength="10" class="start" name="wday_start" id="wday_start" type="text" /> To <input value="' . $wday_end . '" maxlength="10" class="end" name="wday_end" id="wday_end" type="text" /> All Day: <input class="allday" name="wday_allday" id="wday_allday" type="checkbox" ' . $wday_allday_checked . ' /></div>';
	echo '<div class="day" id="thursday"><div class="hours_div"><input class="is_open" name="thursday_is_open" id="thursday_is_open" type="checkbox" ' . $thursday_open_checked. ' /> Thursday: </div>From <input value="' . $thursday_start . '" maxlength="10" class="start" name="thursday_start" id="thursday_start" type="text" /> To <input value="' . $thursday_end . '" maxlength="10" class="end" name="thursday_end" id="thursday_end" type="text" /> All Day: <input class="allday" name="thursday_allday" id="thursday_allday" type="checkbox" ' . $thursday_allday_checked . ' /></div>';
	echo '<div class="day" id="friday"><div class="hours_div"><input class="is_open" name="fday_is_open" id="fday_is_open" type="checkbox" ' . $fday_open_checked. ' /> Friday: </div>From <input value="' . $fday_start . '" maxlength="10" class="start" name="fday_start" id="fday_start" type="text" /> To <input value="' . $fday_end . '" maxlength="10" class="end" name="fday_end" id="fday_end" type="text" /> All Day: <input class="allday" name="fday_allday" id="fday_allday" type="checkbox" ' . $fday_allday_checked . ' /></div>';
	echo '<div class="day" id="saturday"><div class="hours_div"><input class="is_open" name="saturday_is_open" id="saturday_is_open" ' . $saturday_open_checked. ' type="checkbox" /> Saturday: </div>From <input value="' . $saturday_start . '" maxlength="10" class="start" name="saturday_start" id="saturday_start" type="text" /> To <input value="' . $saturday_end . '" maxlength="10" class="end" name="saturday_end" id="saturday_end" type="text" /> All Day: <input class="allday" name="saturday_allday" id="saturday_allday" type="checkbox" ' . $saturday_allday_checked . ' /></div>';
	echo '<div class="day" id="sunday"><div class="hours_div"><input class="is_open" name="sunday_is_open" id="sunday_is_open"  ' . $sunday_open_checked. ' type="checkbox" /> Sunday: </div>From <input value="' . $sunday_start . '" maxlength="10" class="start" name="sunday_start" id="sunday_start" type="text" /> To <input value="' . $sunday_end . '" maxlength="10" class="end" name="sunday_end" id="sunday_end" type="text" /> All Day: <input class="allday" name="sunday_allday" id="sunday_allday" type="checkbox" ' . $sunday_allday_checked . ' /></div>';
	
	echo '<script>
jQuery(document).ready(function() {
	jQuery(".day").each(function() {
		if (jQuery(this).find(".is_open").is(":checked") != true) {
	        jQuery(this).find(".start").val("Closed").prop("disabled", true);
			jQuery(this).find(".end").val("Closed").prop("disabled", true);
			jQuery(this).find(".allday").prop("disabled", true);
		}
			
		if (jQuery(this).find(".allday").is(":checked") == true) {
			jQuery(this).find(".start").val("All Day").prop("disabled", true);
			jQuery(this).find(".end").val("All Day").prop("disabled", true);
		}
	});
			
	jQuery(".is_open").change(function(){
		var thisdiv = jQuery(this).closest(".day");
		if (jQuery(this).is(":checked") == true){
	        thisdiv.find(".start").val("").prop("disabled", false);
			thisdiv.find(".end").val("").prop("disabled", false);
			thisdiv.find(".allday").prop("disabled", false);
	    } else {
	        thisdiv.find(".start").val("Closed").prop("disabled", true);
			thisdiv.find(".end").val("Closed").prop("disabled", true);
			thisdiv.find(".allday").prop("disabled", true);
			thisdiv.find(".allday").attr("checked", false);
	    }
	});
			
	jQuery(".allday").change(function(){
		var thisdiv = jQuery(this).closest(".day");
		if (jQuery(this).is(":checked") == false){
	        thisdiv.find(".start").val("").prop("disabled", false);
			thisdiv.find(".end").val("").prop("disabled", false);
	    } else {
	        thisdiv.find(".start").val("All Day").prop("disabled", true);
			thisdiv.find(".end").val("All Day").prop("disabled", true);
	    }
	});
});
</script>';
}

// handles saving the data
function cpwgm_save_contact_page_cpt($post_id, $post) {
	
	/*
	if ( !isset( $_POST['cpwgm_noncename'] ) || !wp_verify_nonce( $_POST['cpwgm_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	*/
	
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
		
	// map api key
	$meta['google_api_key'] = $_POST['google_api_key'];
		
	// details
	$meta['contact_page_name']   = sanitize_text_field( $_POST['contact_page_name'] );
	$meta['contact_page_email']  = sanitize_email( $_POST['contact_page_email'] );
	$meta['contact_page_phone']  = sanitize_text_field( $_POST['contact_page_phone'] );
	$meta['contact_page_street'] = sanitize_text_field( $_POST['contact_page_street'] );
	$meta['contact_page_city']   = sanitize_text_field( $_POST['contact_page_city'] );
	$meta['contact_page_state']  = sanitize_text_field( $_POST['contact_page_state'] );
	$meta['contact_page_zip']    = sanitize_text_field( $_POST['contact_page_zip'] );
		
	// is open checkbox
	$meta['mday_is_open']     = ( isset($_POST['mday_is_open']) ? 1 : 0);
	$meta['tuesday_is_open']  = ( isset($_POST['tuesday_is_open']) ? 1 : 0);
	$meta['wday_is_open']     = ( isset($_POST['wday_is_open']) ? 1 : 0);
	$meta['thursday_is_open'] = ( isset($_POST['thursday_is_open']) ? 1 : 0);
	$meta['fday_is_open']     = ( isset($_POST['fday_is_open']) ? 1 : 0);
	$meta['saturday_is_open'] = ( isset($_POST['saturday_is_open']) ? 1 : 0);
	$meta['sunday_is_open']   = ( isset($_POST['sunday_is_open']) ? 1 : 0);
		
	// hours
	$meta['mday_start']	    = ( isset($_POST['mday_start']) ? sanitize_text_field( $_POST['mday_start'] ) : '');
	$meta['mday_end']       = ( isset($_POST['mday_end']) ? sanitize_text_field( $_POST['mday_end'] ) : '');
	$meta['tuesday_start']	= ( isset($_POST['tuesday_start']) ? sanitize_text_field( $_POST['tuesday_start'] ) : '');
	$meta['tuesday_end']    = ( isset($_POST['tuesday_end']) ? sanitize_text_field( $_POST['tuesday_end'] ) : '');
	$meta['wday_start']	    = ( isset($_POST['wday_start']) ? sanitize_text_field( $_POST['wday_start'] ) : '');
	$meta['wday_end']       = ( isset($_POST['wday_end']) ? sanitize_text_field( $_POST['wday_end'] ) : '');
	$meta['thursday_start']	= ( isset($_POST['thursday_start']) ? sanitize_text_field( $_POST['thursday_start'] ) : '');
	$meta['thursday_end']   = ( isset($_POST['thursday_end']) ? sanitisanitize_text_fieldze_title( $_POST['thursday_end'] ) : '');
	$meta['fday_start']	    = ( isset($_POST['fday_start']) ? sanitize_text_field( $_POST['fday_start'] ) : '');
	$meta['fday_end']       = ( isset($_POST['fday_end']) ? sanitize_text_field( $_POST['fday_end'] ) : '');
	$meta['saturday_start']	= ( isset($_POST['saturday_start']) ? sanitize_text_field( $_POST['saturday_start'] ) : '');
	$meta['saturday_end']   = ( isset($_POST['saturday_end']) ? sanitize_text_field( $_POST['saturday_end'] ) : '');
	$meta['sunday_start']	= ( isset($_POST['sunday_start']) ? sanitize_text_field( $_POST['sunday_start'] ) : '');
	$meta['sunday_end']     = ( isset($_POST['sunday_end']) ? sanitize_text_field( $_POST['sunday_end'] ) : '');
		
	// all day checkboxes
	$meta['mday_allday']     = ( isset($_POST['mday_allday']) ? 1 : 0 );
	$meta['tuesday_allday']  = ( isset($_POST['tuesday_allday']) ? 1 : 0);
	$meta['wday_allday']     = ( isset($_POST['wday_allday']) ? 1 : 0);
	$meta['thursday_allday'] = ( isset($_POST['thursday_allday']) ? 1 : 0);
	$meta['fday_allday']     = ( isset($_POST['fday_allday']) ? 1 : 0);
	$meta['saturday_allday'] = ( isset($_POST['saturday_allday']) ? 1 : 0);
	$meta['sunday_allday']   = ( isset($_POST['sunday_allday']) ? 1 : 0);
		
	foreach ($meta as $key => $value) {
		if ($post->post_type == 'revision')
			return;
				
		$value = implode(',', (array)$value);
				
		if (get_post_meta($post->ID, $key, FALSE)) {
			update_post_meta($post->ID, $key, $value);
		} else {
				add_post_meta($post->ID, $key, $value);
		}
				
		if (!$value)
			delete_post_meta($post->ID, $key);
	}
}
add_action('save_post', 'cpwgm_save_contact_page_cpt', 1, 2);

// load our cpt template
add_filter( 'single_template', 'cpwgm_custom_post_type_template' );
function cpwgm_custom_post_type_template($single_template) {
	global $post;
	
	if ($post->post_type == 'contact_page_cpt' ) {
		$single_template = dirname( __FILE__ ) . '/single-zen-contact-page.php';
	}
	return $single_template;
	wp_reset_postdata();
}

// remove ?post_type= and &p= from url
function cpwgm_remove_cpt_slug( $post_link, $post, $leavename ) {
	
	if ( 'contact_page_cpt' != $post->post_type || 'publish' != $post->post_status ) {
		return $post_link;
	}
	
	$post_link = str_replace( '/?post_type=' . $post->post_type . '&p=' . $post->ID, '/' . $post->post_name, $post_link );
	
	return $post_link;
}
add_filter( 'post_type_link', 'cpwgm_remove_cpt_slug', 10, 3 );

function cpwgm_parse_request_trick( $query ) {
	
	// Only noop the main query
	if ( ! $query->is_main_query() )
		return;
		
		// Only noop our very specific rewrite rule match
		if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
			return;
		}
		
		// 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
		if ( ! empty( $query->query['name'] ) ) {
			$query->set( 'post_type', array( 'post', 'page', 'contact_page_cpt' ) );
		}
}
add_action( 'pre_get_posts', 'cpwgm_parse_request_trick' );


/////////////////////////////// SIGN UP ////////////////////////////
add_action('wp_dashboard_setup', 'cpwgm_custom_dashboard_widgets');
function cpwgm_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('corporatezen_newsletter', 'CZ Newsletter', 'cpwgm_mailchimp_signup_widget');
}

function cpwgm_mailchimp_signup_widget() {
	$user    = wp_get_current_user();
	$email   = (string) $user->user_email;
	$fname   = (string) $user->user_firstname;
	$lname   = (string) $user->user_lastname;
	?>
	
<!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup">
	<form action="//corporatezen.us13.list-manage.com/subscribe/post?u=e9426a399ea81798a865c10a7&amp;id=9c1dcdaf0e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
	    <div id="mc_embed_signup_scroll">
	    
			<h2>Don't miss important updates!</h2>
			<p>Don't worry, we hate spam too. We send a max of 2 emails a month, and we will never share your email for any reason. Sign up to ensure you don't miss any important updates or information about this plugin or theme. </p>
		
			<div class="mc-field-group">
				<!--<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span></label>-->
				<input type="email" value="<?php echo $email; ?>" name="EMAIL" class="fat_wide required email" id="mce-EMAIL" style="width: 75%;">
				<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-primary">
			</div>
		
			<div class="mc-field-group">
				<input type="hidden" value="<?php echo $fname; ?>" name="FNAME" class="" id="mce-FNAME">
			</div>
			<div class="mc-field-group">
				<input type="hidden" value="<?php echo $lname; ?>" name="LNAME" class="" id="mce-LNAME">
			</div>
			
		
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none;color: red;font-weight: 500;margin-top: 20px; margin-bottom: 20px;"></div>
				<div class="response" id="mce-success-response" style="display:none;color: green;font-weight: 500;margin-top: 20px; margin-bottom: 20px;"></div>
			</div>    
			
			<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
		    <div style="position: absolute; left: -5000px;" aria-hidden="true">
		    	<input type="text" name="b_e9426a399ea81798a865c10a7_9c1dcdaf0e" tabindex="-1" value="">
		    </div>
	
	    </div>
	</form>
</div>

<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
	
	<?php
}
?>