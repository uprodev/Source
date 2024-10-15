<?php
	// MD Custom ajax handler

	define('DOING_AJAX', true);

	if (!isset($_REQUEST['action'])) {
		die('-1');
	}

	// Make sure you update this line to the relative location of the wp-load.php
	require_once('../../../../wp-load.php');
	require_once('../../../../wp-config.php');
	require_once('../../../../wp-includes/post.php');
	require_once('../../../../wp-includes/formatting.php');
	require_once('../../../../wp-includes/query.php');
	require_once('../../../../wp-includes/taxonomy.php');
	require_once('../../../../wp-includes/meta.php');
	require_once('../../../../wp-includes/user.php');
	require_once('../../../../wp-includes/functions.php');

	// Typical headers
	header('Content-Type: text/html');
	send_nosniff_header();

	// Disable caching
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	$action = esc_attr(trim($_REQUEST['action']));

	// Declare all actions (function names) that you will use this ajax handler for, as an added security measure
	$allowed_actions = [
		'insights_load_more',
	];

	if (in_array($action, $allowed_actions)) {
		if (is_user_logged_in()) {
			do_action('sr_' . $action);
		} else {
			do_action('sr_nopriv_' . $action);
		}
	} else {
		die('-1');
	}