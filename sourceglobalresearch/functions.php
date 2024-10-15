<?php
// Main theme functions file
require_once(get_template_directory() . '/inc/theme-functions.php');
require_once(get_template_directory() . '/inc/theme-functions-blocks.php');
require_once(get_template_directory() . '/inc/cpt-person.php');
// require_once(get_template_directory() . '/inc/cpt-project.php');
require_once(get_template_directory() . '/inc/theme-functions-ajax.php');
require_once(get_template_directory() . '/inc/custom-taxonomies.php');

// Theme support options
require_once(get_template_directory() . '/inc/theme-support.php');

// WP Head and other cleanup functions
require_once(get_template_directory() . '/inc/cleanup.php');

// Register scripts and stylesheets
require_once(get_template_directory() . '/inc/enqueue-scripts.php');

// Adds support for multiple languages
require_once(get_template_directory() . '/assets/translation/translation.php');

// Remove 4.2 Emoji Support
require_once(get_template_directory() . '/inc/disable-emoji.php');

//-----------------------------------------------
// ACF init
//-----------------------------------------------

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyBfyPL5LRNoLmfVVsnbWm3EhKsmc49v1WE');
}

add_action('acf/init', 'my_acf_init');

//-----------------------------------------------
// ACF Options Pages
//-----------------------------------------------

if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' => 'Site Settings',
		'menu_title' => 'Site Settings',
		'menu_slug' => 'site-settings',
		'position' => 10,
		'capability' => 'edit_posts',
		'icon_url' => 'dashicons-admin-settings',
		'redirect' => true
	));
}

//-----------------------------------------------
// Page Slug Body Class
//-----------------------------------------------

function add_slug_body_class($classes) {
	global $post;
	if (isset($post)) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter('body_class', 'add_slug_body_class');

//-----------------------------------------------
// Images Sizes
//-----------------------------------------------

if (function_exists('add_image_size')) {
	add_image_size('xl', 2600);
	add_image_size('lg', 1800);
	add_image_size('md', 1250);
	add_image_size('sm', 850);
	add_image_size('xs', 400);
}

//-----------------------------------------------
// Disable unneeded scripts and styles
//-----------------------------------------------

// JP: Unregister the awful default WordPress scripts and styles
function wps_deregister_styles() {
  	wp_dequeue_style('wp-block-library');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );

// Remove redundant bits from dashboard menu
add_action('admin_menu', function() {
	// remove_menu_page('edit.php');
	remove_menu_page('edit-comments.php');
	// remove_menu_page('edit.php?post_type=acf-field-group');
});

// Remove redundant navigation links from the admin bar.
add_action('wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('search');
});

//-----------------------------------------------
// Disable RSS Feeds
//-----------------------------------------------

function wpb_disable_feed() {
	wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}
 
add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );

//-----------------------------------------------
// vCard support
//-----------------------------------------------

// add_filter('upload_mimes', function ($mime_types) {
// 	$mime_types['vcf'] = 'text/x-vcard';

// 	return $mime_types;
// });

// add_filter('wp_check_filetype_and_ext', function($types, $file, $filename, $mimes) {
// 	if (strpos($filename, '.vcf') !== false) {
// 		$types['ext'] = 'vcf';
// 		$types['type'] = 'text/x-vcard';
// 	}

// 	return $types;
// }, 10, 4);

//-----------------------------------------------
// Hide dashboard bar
//-----------------------------------------------

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

//-----------------------------------------------
// Restrict admin access to administrators
//-----------------------------------------------

// add_action('init', 'blockusers_init');

// function blockusers_init() {
// 	if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
// 		wp_redirect(home_url());
// 		exit;
// 	}
// }

//-----------------------------------------------
// Move Yoast to bottom
//-----------------------------------------------

function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation($confirmation, $form, $entry, $ajax)
{
	$base_url = home_url();
  if ($form['id'] == '5') {
    $id = get_the_ID();
    $page_title = get_the_title($id);
    $nid = intval(get_post_meta($id, 'report_nid', true));
    $response = NULL;
	$post_url = "https://reports.sourceglobalresearch.com/report/download/{$nid}/extract/" . urlencode(str_replace("/", " ", $page_title));
	
    if ($nid > 0) {
		$data = array("post_data" =>  array(
			'first_name' => rgar( $entry, '1' ),
			'last_name'  => rgar( $entry, '3' ),
			'email'    => rgar( $entry, '4' ),
			'report_url'    => rgar( $entry, '5' ),
			'report_nid' => $nid,
		));
		
		$args = array(	
			'method' => 'POST',
			'headers'  => array(
				'Content-type: application/x-www-form-urlencoded'
			),
			'timeout'     => 600,
			'sslverify'   => false,
			'body' => $data
		);
		
		
		GFCommon::log_debug( 'gform_confirmation: body => ' . print_r( $args, true ) );
		
		$url = $base_url.$_SERVER['REQUEST_URI'];
		
		
		$response = wp_remote_post( $post_url, $args);
   		GFCommon::log_debug( 'gform_confirmation: headers => ' . print_r( $response['headers'], true ) );
		$confirmation = 'Thanks for contacting us! We will get in touch with you shortly.';


		if(strpos($response['headers']['content-type'],"application/pdf") !== "false"){
			$filename = preg_replace('/[^A-Za-z0-9\s]/', '', $page_title)." EXTRACT.pdf";
			$response_body = wp_remote_retrieve_body($response);
			file_put_contents($filename,$response_body);
			
					
			

			// header('Content-type: application/pdf');
			// header('Content-disposition: attachment;filename= ' . $filename . ';');	

			// readfile($filename);

			//exit;
			// wp_delete_file($filename);
			$confirmation .= "<script type=\"text/javascript\">setTimeout(function () { window.location.assign('$url') }, 2000);window.open('$base_url/report-extracts-download?q=$page_title', '_blank').focus();</script>";
		}
	
    }
  }
  if ($form['id'] == '7') {
    $id = get_the_ID();
    $page_title = get_the_title($id);
    $nid = intval(get_post_meta($id, 'report_nid', true));
	$response = NULL;
	if ($nid > 0) {

		$data = array('user_info' =>  array(

			'first_name' => rgar( $entry, '1' ),
			'last_name'  => rgar( $entry, '3' ),
			'email'    => rgar( $entry, '5' )),		
			'report_nid' => $nid,
		);

		GFCommon::log_debug( 'report_notf_gform_confirmation: data received => ' . print_r( $data, true ) );
		$post_url = "https://reports.sourceglobalresearch.com/notifyusersonpublish";
		$args = array(	
			'method' => 'POST',
			'headers'  => array(
				'Content-type: application/x-www-form-urlencoded'
			),
			'timeout'     => 600,
			'sslverify'   => false,
			'body' => $data
		);
		$response = wp_remote_post( $post_url, $args);
		GFCommon::log_debug( 'report_notf_gform_confirmation: response headers => ' . print_r( $response['headers'], true ) );
		$confirmation .= "<script type=\"text/javascript\">jQuery('.report-notf-success').show();jQuery('.report-notf-link').hide();</script>";
	}

  }
  return $confirmation;
}
