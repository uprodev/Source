<?php
function site_scripts() {
	global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

	$js_dir = get_template_directory_uri() . '/assets/js/';

	// Libraries (Gsap, scrolltrigger, scrollto, swiper)
	wp_enqueue_script( 'libraries', $js_dir . 'libraries.min.js', [], '', true );

	// Google Maps
	wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDC4IKdjcZ1vdB3risyk3-B-41M1hh2KHM', [], null, null );

	// Vimeo
	// wp_enqueue_script( 'vimeo', 'https://player.vimeo.com/api/player.js', [], false, true );

	// YouTube
	// wp_enqueue_script( 'youtube', 'https://www.youtube.com/player_api', [], false, true );

	// Module specific script loading
	// $id = get_the_ID();
	// $modules = get_post_meta($id, 'modules', true);
	// if ($modules) {
	// 	foreach ($modules as $key => $module) {
	// 		switch ($module) {
	// 			case 'video':
	// 				$video_service = get_post_meta($id, 'modules_' . $key . '_video_service', true);
	// 				if ($video_service == 'vimeo') {
	// 					wp_enqueue_script( 'vimeo', 'https://player.vimeo.com/api/player.js', [], false, true );
	// 				} elseif ($video_service == 'youtube') {
	// 					wp_enqueue_script( 'youtube', 'https://www.youtube.com/player_api', [], false, true );
	// 				}
	// 				break;
	// 			default:
	// 				break;
	// 		}
	// 	}
	// }

	$cb = '?v=87';

	// Main scripts
	wp_enqueue_script( 'site', $js_dir . 'site.js' . $cb, [], '', true );
	// wp_enqueue_script( 'site', $js_dir . 'site.min.js', [], '', true );
	// Extra scripts
	wp_enqueue_script('extra', $js_dir . 'extra.js' . $cb, [], '', true);

	// Localize the main scripts file so that PHP variables can be accessed in the main script.js file
	wp_localize_script('site', 'php_data', [
		'custom_ajaxurl' => get_template_directory_uri() . '/inc/ajax-handler.php',
		// 'security' => wp_create_nonce( 'string_funcname' ),
		// 'images_path' => get_template_directory_uri() . '/images/',
		// 'home_url' => home_url()
	]);

	// Main stylesheet
	wp_enqueue_style( 'styles', get_template_directory_uri() . '/assets/css/style.css' . $cb, [], '', 'all' );

    wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/styles.css' . $cb, [], '', 'all' );
	// Override stylesheet
	wp_enqueue_style( 'styles_override', get_template_directory_uri() . '/style.css' . $cb, [], '', 'all' );
}

add_action('wp_enqueue_scripts', 'site_scripts', 999);
