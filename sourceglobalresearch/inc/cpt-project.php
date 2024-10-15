<?php
// Create the function for the custom type
function project() { 
	// creating (registering) the custom type 
	register_post_type( 'project', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array('labels' => array(
				'name' => __('Projects', 'jointswp'), /* This is the Title of the Group */
				'singular_name' => __('Project', 'jointswp'), /* This is the individual type */
				'all_items' => __('All Projects', 'jointswp'), /* the all items menu item */
				'add_new' => __('Add New', 'jointswp'), /* The add new menu item */
				'add_new_item' => __('Add New Project', 'jointswp'), /* Add New Display Title */
				'edit' => __( 'Edit', 'jointswp' ), /* Edit Dialog */
				'edit_item' => __('Edit Project', 'jointswp'), /* Edit Display Title */
				'new_item' => __('New Project', 'jointswp'), /* New Display Title */
				'view_item' => __('View Project', 'jointswp'), /* View Display Title */
				'search_items' => __('Search Projects', 'jointswp'), /* Search Custom Type Title */ 
				'not_found' =>  __('No Projects found.', 'jointswp'), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __('No Projects in Trash', 'jointswp'), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Project', 'jointswp' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 20, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-admin-page', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
			'rewrite' => array( 'slug' => 'work', 'with_front' => true ), /* you can specify its url slug */
			// 'has_archive' => 'spaces', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'custom-fields'),
		) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your custom post type */
	// register_taxonomy_for_object_type('category', 'projects');
}
// adding the function to the Wordpress init
add_action('init', 'project');