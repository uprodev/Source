<?php
$args = $args ?? [];
$args["id"] = get_the_ID();
$args["current_url"] = get_the_permalink();
$args["template_home"] = is_page_template('templates/home.php') ? true : false;
$args["template_insights"] = is_page_template('templates/insights.php') ? true : false;
$args["single_post"] = is_singular('post') ? true : false;
$args["single_person"] = is_singular('person') ? true : false;
$args["template_simple"] = is_page_template('templates/simple.php') ? true : false;
$args["template_policy"] = is_page_template('templates/policy.php') ? true : false;
$args["template_flexible_blocks"] = is_page_template('templates/flexible-blocks.php') ? true : false;
$args["insights_page_id"] = get_option('options_insights_page');
get_header(null, $args);
do_action('display_page', $args);
get_footer();