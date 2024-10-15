<?php
// Create the function for the custom type
function person() { 
	// creating (registering) the custom type 
	register_post_type( 'person', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array('labels' => array(
				'name' => __('People', 'jointswp'), /* This is the Title of the Group */
				'singular_name' => __('Person', 'jointswp'), /* This is the individual type */
				'all_items' => __('All People', 'jointswp'), /* the all items menu item */
				'add_new' => __('Add New', 'jointswp'), /* The add new menu item */
				'add_new_item' => __('Add New Person', 'jointswp'), /* Add New Display Title */
				'edit' => __( 'Edit', 'jointswp' ), /* Edit Dialog */
				'edit_item' => __('Edit Person', 'jointswp'), /* Edit Display Title */
				'new_item' => __('New Person', 'jointswp'), /* New Display Title */
				'view_item' => __('View Person', 'jointswp'), /* View Display Title */
				'search_items' => __('Search People', 'jointswp'), /* Search Custom Type Title */ 
				'not_found' =>  __('No People found.', 'jointswp'), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __('No People in Trash', 'jointswp'), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Person', 'jointswp' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 20, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-admin-page', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
			'rewrite' => array( 'slug' => 'profiles', 'with_front' => true ), /* you can specify its url slug */
			// 'has_archive' => 'spaces', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'custom-fields', 'thumbnail'),
		) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your custom post type */
	// register_taxonomy_for_object_type('category', 'projects');
}
// adding the function to the Wordpress init
add_action('init', 'person');