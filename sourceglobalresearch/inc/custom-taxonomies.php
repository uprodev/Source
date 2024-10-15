<?php

function custom_taxonomy_types() {
	$labels = array(
		'name' => 'Types',
		'singular_name' => 'Type',
		'menu_name' => 'Types',
		'all_items' => 'All Types',
		'parent_item' => 'Parent Type',
		'parent_item_colon' => 'Parent Type:',
		'new_item_name' => 'New Type Name',
		'add_new_item' => 'Add New Type',
		'edit_item' => 'Edit Type',
		'update_item' => 'Update Type',
		'separate_items_with_commas' => 'Separate Type with commas',
		'search_items' => 'Search Types',
		'add_or_remove_items' => 'Add or remove Types',
		'choose_from_most_used' => 'Choose from the most used Types',
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'rewrite' => array('slug' => 'type'), /* you can specify its url slug */
	);
	register_taxonomy('types', ['post'], $args);
}

add_action('init', 'custom_taxonomy_types');