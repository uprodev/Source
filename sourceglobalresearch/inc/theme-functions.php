<?php

/**
 * Get SVG
 * @param string $svg
 * @return string
 */
function get_svg($svg)
{
	return file_get_contents(get_template_directory() . '/assets/images/' . $svg . '.svg');
}

/**
 * Prevent Widows
 * by adding &nbsp; between final two words
 * @param string $text
 * @param bool $strip_html
 * @return string
 */
function remove_widow($text, $strip_html = true)
{
	$text = trim($text);
	$text = $strip_html ? esc_html($text) : $text;
	$words = explode(' ', $text);
	$count = count($words);
	$return = $text;
	if ($count > 3) {
		$modified_text = '';
		for ($i = 0; $i < $count; $i++) {
			if ($i == $count - 2) {
				$modified_text .= $words[$i] . '&nbsp;';
			} else {
				$modified_text .= $words[$i] . ' ';
			}
		}
		$return = $modified_text;
	}
	return $return;
}

/**
 * Shorten to given length
 * Add ellipsis if longer than length
 * @param string $text
 * @param int $length
 * @return string
 */
function shorten($text, $length = 200)
{
	$shortened = substr($text, 0, $length);
	$shortened = trim($shortened);
	// Remove 1 or 2 char 'orphans'
	if (substr($shortened, -2, 1) == " ") {
		$shortened = substr($shortened, 0, -2);
	} elseif (substr($shortened, -3, 1) == " ") {
		$shortened = substr($shortened, 0, -3);
	}
	$shortened = strlen($text) > $length ? rtrim($shortened, " ,.;:-=()") : $shortened;
	$shortened .= strlen($text) > $length ? '&hellip;' : '';
	return $shortened;
}

function custom_number_format($n, $precision = 3)
{
	if ($n < 1000000) {
		// Anything less than a million
		$n_format = '£' . number_format($n);
	} else if ($n < 1000000000) {
		// Anything less than a billion
		$n_format = '£' . number_format($n / 1000000, $precision) . ' Million';
	} else {
		// At least a billion
		$n_format = '£' . number_format($n / 1000000000, $precision) . 'B';
	}

	return $n_format;
}

/**
 * Get title of page
 * @param int $id
 * @return string
 */
function get_page_title($id = false)
{
	$default = get_bloginfo('name');
	if ($id) {
		$page_title = get_the_title($id) . ' - ' . $default;
	} else {
		$id = get_the_ID();
		$page_title = get_the_title($id) . ' - ' . $default;
	}
	return $page_title;
}

/**
 * Get description of page
 * @return string
 */
function get_page_description()
{
	$description = get_option('options_company_description');
	return $description;
}

/**
 * Get type of page
 * @return string
 */
function get_page_type()
{
	$type = 'Website';
	return $type;
}

/**
 * Get image for page
 * @return string
 */
function get_page_image()
{
	$image = get_template_directory_uri() . '/screenshot.png';
	return $image;
}

/**
 * Open Graph
 * @return string
 */
function open_graph()
{
	$title = get_page_title();
	$description = get_page_description();
	$image = get_page_image();
	$o = '<meta name="description" content="' . $description . '">';
	$o .= '<meta name="twitter:card" content="summary">';
	$o .= '<meta name="twitter:title" content="' . $title . '">';
	$o .= '<meta name="twitter:description" content="' . $description . '">';
	$o .= '<meta name="twitter:site" content="' . get_option('options_twitter_handle') . '">';
	$o .= '<meta name="twitter:image" content="' . $image . '">';
	$o .= '<meta property="og:title" content="' . $title . '">';
	$o .= '<meta property="og:type" content="' . get_page_type() . '">';
	$o .= '<meta property="og:url" content="' . get_permalink() . '">';
	$o .= '<meta property="og:image" content="' . $image . '">';
	$o .= '<meta property="og:site_name" content="' . get_option('blogname') . '">';
	$o .= '<meta property="og:description" content="' . $description . '">';
	return $o;
}

/**
 * Display open graph tags
 * @return string
 */
function get_open_graph()
{
	echo open_graph();
}

/**
 * Background Video
 * @param int $id
 * @return string
 */
function background_video($url)
{
	$bc = 'background-video';
	$o = '<div class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__video-wrap">';
	$o .= '<video playsinline muted loop autoplay';
	$o .= ' class="' . $bc . '__video">';
	$o .= '<source src="' . esc_url($url) . '" type="video/mp4">';
	$o .= '</video>';
	$o .= '</div>'; // $bc__video-wrap
	$o .= '</div>';
	return $o;
}

/**
 * Function to calculate the estimated reading time of the given text.
 * @param string $text The text to calculate the reading time for.
 * @param string $wpm The rate of words per minute to use.
 * @param bool $detailed Option to show minutes and seconds or just minutes
 * @return Array
 */
function estimateReadingTime($text, $wpm = 200, $detailed = true)
{
	$totalWords = str_word_count(strip_tags($text));
	if ($detailed) {
		$minutes = floor($totalWords / $wpm);
		$seconds = floor($totalWords % $wpm / ($wpm / 60));
		return [
			'minutes' => $minutes,
			'seconds' => $seconds
		];
	} else {
		return ceil($totalWords / $wpm);
	}
}

/**
 * Fetch service highlight
 * @param string $link
 * @return mixed $link_highlight
 */
function fetchServiceHighlight($link)
{
	$services_page_id = intval(get_option('options_services_page'));
	$link_highlight = false;
	if (isset($link["url"])) {
		$link_page_id = url_to_postid($link["url"]);
		$link_page_parent_id = wp_get_post_parent_id($link_page_id);

		if ($link_page_parent_id === $services_page_id) {
			$link_highlight = get_post_meta($link_page_id, 'page_highlight_colour', true);
		}
	}
	return $link_highlight;
}

/**
 * Site Header
 * @param array $args
 * @return string
 */
function site_header($args)
{
	$bc = 'header';
	$id = $args["id"];
	$current_url = $args["current_url"];
	$is_home = $args["template_home"];
	$insights_page_id = intval($args["insights_page_id"]);
	$items_1_count = get_option('options_menu_items_1');
	$items_1 = '';
	$page_ancestors = get_post_ancestors($id);
	$top_level_parent_url = false;
	if (!empty($page_ancestors)) {
		$top_level_parent_url = get_the_permalink($page_ancestors[0]);
	}
	$is_post = false;
	if (is_singular('post')) {
		$is_post = true;
	}
	$links = [];
	for ($i = 0; $i <= $items_1_count; $i++) {
		$link = get_option('options_menu_items_1_' . $i . '_link');
		if ($link) {
			$links[] = $link;
		}
	}
	$items_2_count = get_option('options_menu_items_2');
	$items_2 = '';
	for ($i = 0; $i <= $items_2_count; $i++) {
		$link = get_option('options_menu_items_2_' . $i . '_link');
		if ($link) {
			$links[] = $link;
		}
	}
	$i = 0;
	foreach ($links as $link) {
		$is_active = $link["url"] === $current_url;
		$link_page_id = url_to_postid($link["url"]);
		if (!$is_active) {
			$is_active = $link["url"] === $top_level_parent_url;
		}
		if (!$is_active) {
			$is_active = $link_page_id === $insights_page_id && $args["single_post"];
		}
		if ($is_active) {
			$links[$i]["active"] = true;
		} else {
			$links[$i]["active"] = false;
		}
		$i++;
	}
	$items_1_links = array_slice($links, 0, 3);
	$items_2_links = array_slice($links, 3, 2);
	foreach ($items_1_links as $link) {
		$items_1 .= '<li class="' . $bc . '__nav-item">';
		$items_1 .= '<a href="' . esc_url($link["url"]) . '"' . ($link["target"] === '_blank' ? ' target="_blank"' : '') . ' class="' . $bc . '__nav-item-link' . ($link["active"] ? ' ' . $bc . '__nav-item-link--active' : '') . '">' . esc_html($link["title"]) . '</a>';
		$items_1 .= '</li>';
	}
	foreach ($items_2_links as $link) {
		$items_2 .= '<li class="' . $bc . '__nav-item">';
		$items_2 .= '<a href="' . esc_url($link["url"]) . '"' . ($link["target"] === '_blank' ? ' target="_blank"' : '') . ' class="' . $bc . '__nav-item-link' . ($link["active"] ? ' ' . $bc . '__nav-item-link--active' : '') . '">' . esc_html($link["title"]) . '</a>';
		$items_2 .= '</li>';
	}
	$o = '<header class="' . $bc . ($is_home ? ' ' . $bc . '--white' : '') . ' active">';
	$o .= '<div class="' . $bc . '__pre">';
	$o .= '<div class="grid-container">';

	$o .= '<div class="grid-x grid-padding-x">';
	//$o .= '<div  class="cell  large-offset-1 large-6"><div id="offline-notice">Our website will be offline on April 15th from 9:00 to 12:00 UTC to run some security tests.</div></div>';
	$o .= '<div class="cell small-14 large-12 large-offset-1">';

	$o .= '<div class="' . $bc . '__pre-inner">';
	$o .= '<a href="https://id.sourceglobalresearch.com/user/login?bd=https%3A//reports.sourceglobalresearch.com/" class="' . $bc . '__login">Login</a>';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // $bc__pre-inner
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__pre
	$o .= '<div class="' . $bc . '__inner">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell show-for-large large-offset-1 large-5">';
	if ($items_1) {
		$o .= '<div class="' . $bc . '__nav-wrap ' . $bc . '__nav-wrap--left">';
		$o .= '<ul class="' . $bc . '__nav ' . $bc . '__nav--left">';
		$o .= $items_1;
		$o .= '</ul>';
		$o .= '</div>'; // $bc__nav-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-2">';
	$o .= '<div class="' . $bc . '__logo-column">';
	$o .= '<a href="' . get_home_url() . '" class="' . $bc . '__logo-link">';
	$o .= '<span class="show-for-sr">Home</span>';
	$o .= '<span class="' . $bc . '__logo-image">' . get_svg('logo') . '</span>';
	$o .= '</a>';
	// $o .= '<button class="' . $bc . '__search-control hide-for-large" aria-controls="search" aria-expanded="false">';
	// $o .= '<span class="show-for-sr">Search</span>';
	// $o .= '<span class="' . $bc . '__search-control-icon hide-for-sr">' . get_svg('search') . '</span>';
	// $o .= '</button>';
	$o .= '<button class="' . $bc . '__menu-control hide-for-large" aria-controls="menu" aria-expanded="false">';
	$o .= '<span class="show-for-sr">Menu</span>';
	$o .= '<span class="' . $bc . '__menu-control-bar ' . $bc . '__menu-control-bar--top"></span>';
	$o .= '<span class="' . $bc . '__menu-control-bar ' . $bc . '__menu-control-bar--middle"></span>';
	$o .= '<span class="' . $bc . '__menu-control-bar ' . $bc . '__menu-control-bar--bottom"></span>';
	$o .= '</button>';
	$o .= '</div>'; // $bc__logo-column
	$o .= '</div>'; // .cell
	$o .= '<div class="cell show-for-large large-5">';
	if ($items_2) {
		$o .= '<div class="' . $bc . '__nav-wrap ' . $bc . '__nav-wrap--right">';
		$o .= '<ul class="' . $bc . '__nav ' . $bc . '__nav--right">';
		$o .= $items_2;
		$o .= '</ul>';
		// $o .= '<form class="' . $bc . '__search">';
		// $o .= '<input type="search" name="q" class="' . $bc . '__search-input" placeholder="Search">';
		// $o .= '<button type="submit" class="' . $bc . '__search-submit">';
		// $o .= '<span class="show-for-sr">Search</span>';
		// $o .= '<span class="' . $bc . '__search-submit-icon hide-for-sr">' . get_svg('search') . '</span>';
		// $o .= '</button>';
		// $o .= '</form>';
		$o .= '</div>'; // $bc__nav-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	// $o .= search();
	$o .= '</div>'; // $bc__inner
	$o .= menu($id, $current_url);
	$o .= '</header>';
	return $o;
}

/**
 * Menu
 * @param int $id
 * @param string $current_url
 * @return string
 */
function menu($id, $current_url)
{
	$bc = 'menu';
	$items_1_count = get_option('options_menu_items_1');
	$links = [];
	for ($i = 0; $i <= $items_1_count; $i++) {
		$link = get_option('options_menu_items_1_' . $i . '_link');
		$links[] = $link;
	}
	$items_2_count = get_option('options_menu_items_2');
	for ($i = 0; $i <= $items_2_count; $i++) {
		$link = get_option('options_menu_items_2_' . $i . '_link');
		$links[] = $link;
	}
	$items = '';
	foreach ($links as $link) {
		if ($link) {
			$items .= '<li class="' . $bc . '__list-item">';
			$items .= '<a href="' . esc_url($link["url"]) . '"' . ($link["target"] === '_blank' ? ' target="_blank"' : '') . ' class="' . $bc . '__list-item-link' . ($link["url"] === $current_url ? ' ' . $bc . '__list-item-link--active' : '') . '">';
			$items .= '<span class="' . $bc . '__list-item-link-text">' . esc_html($link["title"]) . '</span>';
			$items .= '<span class="' . $bc . '__list-item-link-arrow">' . get_svg('menu-item-arrow') . '</span>';
			$items .= '</a>';
			$items .= '</li>';
		}
	}
	$o = '<nav id="menu" class="' . $bc . '" aria-hidden="true">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell">';
	$o .= '<div class="' . $bc . '__inner">';
	if ($items) {
		$o .= '<ul class="' . $bc . '__list">';
		$o .= $items;
		$o .= '</ul>';
	}
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</nav>';
	return $o;
}

/**
 * Search
 * @return string
 */
function search()
{
	$bc = 'search-form';
	$o = '<div id="search" class="' . $bc . '" aria-hidden="true">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell">';
	$o .= '<form>';
	$o .= '<div class="' . $bc . '__inner">';
	$o .= '<input type="search" name="q" class="' . $bc . '__input" placeholder="Search">';
	$o .= '<button type="submit" class="' . $bc . '__submit">';
	$o .= '<span class="show-for-sr">Search</span>';
	$o .= '<span class="' . $bc . '__submit-icon hide-for-sr">' . get_svg('search') . '</span>';
	$o .= '</button>';
	$o .= '<button type="button" class="' . $bc . '__close" aria-controls="search" aria-expanded="false">';
	$o .= '<span class="show-for-sr">Close</span>';
	$o .= '<span class="' . $bc . '__close-bar ' . $bc . '__close-bar--top"></span>';
	$o .= '<span class="' . $bc . '__close-bar ' . $bc . '__close-bar--bottom"></span>';
	$o .= '</button>';
	$o .= '</div>'; // $bc__inner
	$o .= '</form>';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc
	return $o;
}

/**
 * Site Footer
 * @return string
 */
function site_footer()
{
	$bc = 'footer';
	$links_count = get_option('options_footer_links');
	$items = '';
	for ($i = 0; $i < $links_count; $i++) {
		$link = get_option('options_footer_links_' . $i . '_link');
		if ($link) {
			$items .= '<li class="' . $bc . '__links-item">';
			$items .= '<a href="' . esc_url($link["url"]) . '"' . ($link["target"] === '_blank' ? ' target="_blank"' : '') . ' class="' . $bc . '__links-item-link">' . esc_html($link["title"]) . '</a>';
			$items .= '</li>';
		}
	}
	$subscribe_text = get_option('options_footer_subscribe_text');
	$subscribe_link = get_option('options_footer_subscribe_link');
	$twitter_link = get_option('options_footer_twitter_link');
	$linkedin_link = get_option('options_footer_linkedin_link');
	$instagram_link = get_option('options_footer_instagram_link');
	$youtube_link = get_option('options_footer_youtube_link');
	$social = '';
	if ($twitter_link || $linkedin_link || $instagram_link || $youtube_link) {
		$social .= '<ul class="' . $bc . '__social">';
		if ($instagram_link) {
			$social .= '<li class="' . $bc . '__social-item ' . $bc . '__social-item--instagram">';
			$social .= '<a href="' . esc_url($instagram_link["url"]) . '" class="' . $bc . '__social-item-link"';
			$social .= $instagram_link["target"] === "_blank" ? ' target="_blank"' : '';
			$social .= '>';
			$social .= '<span class="show-for-sr">' . esc_html($instagram_link["title"]) . '</span>';
			$social .= '<span class="' . $bc . '__social-item-link-icon hide-for-sr">' . get_svg('instagram') . '</span>';
			$social .= '</a>';
			$social .= '</li>';
		}
		if ($twitter_link) {
			$social .= '<li class="' . $bc . '__social-item ' . $bc . '__social-item--twitter">';
			$social .= '<a href="' . esc_url($twitter_link["url"]) . '" class="' . $bc . '__social-item-link"';
			$social .= $twitter_link["target"] === "_blank" ? ' target="_blank"' : '';
			$social .= '>';
			$social .= '<span class="show-for-sr">' . esc_html($twitter_link["title"]) . '</span>';
			$social .= '<span class="' . $bc . '__social-item-link-icon hide-for-sr">' . get_svg('twitter') . '</span>';
			$social .= '</a>';
			$social .= '</li>';
		}
		if ($linkedin_link) {
			$social .= '<li class="' . $bc . '__social-item ' . $bc . '__social-item--linkedin">';
			$social .= '<a href="' . esc_url($linkedin_link["url"]) . '" class="' . $bc . '__social-item-link"';
			$social .= $linkedin_link["target"] === "_blank" ? ' target="_blank"' : '';
			$social .= '>';
			$social .= '<span class="show-for-sr">' . esc_html($linkedin_link["title"]) . '</span>';
			$social .= '<span class="' . $bc . '__social-item-link-icon hide-for-sr">' . get_svg('linkedin') . '</span>';
			$social .= '</a>';
			$social .= '</li>';
		}
		if ($youtube_link) {
			$social .= '<li class="' . $bc . '__social-item ' . $bc . '__social-item--youtube">';
			$social .= '<a href="' . esc_url($youtube_link["url"]) . '" class="' . $bc . '__social-item-link"';
			$social .= $youtube_link["target"] === "_blank" ? ' target="_blank"' : '';
			$social .= '>';
			$social .= '<span class="show-for-sr">' . esc_html($youtube_link["title"]) . '</span>';
			$social .= '<span class="' . $bc . '__social-item-link-icon hide-for-sr">' . get_svg('youtube') . '</span>';
			$social .= '</a>';
			$social .= '</li>';
		}
		$social .= '</ul>';
	}
	$privacy_link = get_option('options_footer_privacy_policy_link');
	$terms_link = get_option('options_footer_terms_link');
	$o = '<footer class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__logo-cell cell large-2 large-offset-1">';
	$o .= '<a href="' . get_home_url() . '" class="' . $bc . '__logo-link">';
	$o .= '<span class="show-for-sr">Home</span>';
	$o .= '<span class="' . $bc . '__logo-image">' . get_svg('logo') . '</span>';
	$o .= '</a>';
	$o .= '</div>'; // $bc__logo-cell
	$o .= '<div class="' . $bc . '__links-cell cell large-2 large-offset-1">';
	if ($items) {
		$o .= '<ul class="' . $bc . '__links">';
		$o .= $items;
		$o .= '</ul>'; // $bc__links
	}
	$o .= '</div>'; // $bc__links-cell
	$o .= '<div class="' . $bc . '__social-cell cell show-for-large large-1">';
	$o .= $social ? $social : '';
	$o .= '</div>'; // $bc__social-cell
	$o .= '<div class="' . $bc . '__subscribe-cell cell large-4 large-offset-2">';
	if ($subscribe_text && $subscribe_link) {
		$o .= '<div class="' . $bc . '__subscribe">';
		$o .= '<p class="' . $bc . '__subscribe-text">';
		$o .= $subscribe_text;
		$o .= '</p>';
		$o .= arrow_link($subscribe_link, $bc);
		$o .= '</div>';
		if ($social) {
			$o .= '<div class="hide-for-large">';
			$o .= $social;
			$o .= '</div>';
		}
	}
	if ($terms_link || $privacy_link) {
		$o .= '<div class="' . $bc . '__policy">';
		if ($terms_link) {
			$o .= '<a href="' . esc_url($terms_link["url"]) . '" class="' . $bc . '__policy-link"';
			$o .= $terms_link["target"] === "_blank" ? ' target="_blank"' : '';
			$o .= '>';
			$o .= esc_html($terms_link["title"]);
			$o .= '</a>';
		}
		if ($privacy_link) {
			$o .= '<a href="' . esc_url($privacy_link["url"]) . '" class="' . $bc . '__policy-link"';
			$o .= $privacy_link["target"] === "_blank" ? ' target="_blank"' : '';
			$o .= '>';
			$o .= esc_html($privacy_link["title"]);
			$o .= '</a>';
		}
		$o .= '<span class="' . $bc . '__copyright">&copy;' . date('Y') . ' ' . get_bloginfo('site_title') . '</span>';
		$o .= '</div>'; // $bc__policy
	}
	$o .= '</div>'; // $bc__subscribe-cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</footer>';
	return $o;
}

/**
 * Arrow Link
 * @param array $cta
 * @param string $bc
 * @param bool $dark_arrow
 * @param bool $hide_text
 * @param bool $arrow_highlight_colour
 * @return string
 */
function arrow_link($cta, $bc, $dark_arrow = false, $hide_text = false, $arrow_highlight_colour = false)
{
	if (!isset($cta["url"])) return "";
	$o = '<a href="' . esc_url($cta["url"]) . '"' . ($cta["target"] === '_blank' ? ' target="_blank"' : '') . ' class="arrow-link ';
	$o .= $dark_arrow && !$arrow_highlight_colour ? ' arrow-link--dark ' : '';
	$o .= !$dark_arrow && $arrow_highlight_colour ? ' arrow-link--' . $arrow_highlight_colour . ' ' : '';
	$o .= $hide_text ? ' arrow-link--no-text ' : '';
	$o .= $bc . '__cta-link">';
	$o .= '<span class="arrow-link__inner">';
	$o .= '<span class="arrow-link__text ' . $bc . '__cta-link-text">' . esc_html($cta["title"]) . '</span>';
	$o .= '<span class="arrow-link__arrow ' . $bc . '__cta-link-arrow">' . get_svg('cta-arrow') . '</span>';
	$o .= '</span>'; // .arrow-link__inner
	$o .= '</a>';
	return $o;
}

/**
 * Output block
 * @param string $block
 * @param int $page_id
 * @param int $block_id
 * @param int $block_number
 * @return void
 */
function display_block($block, $page_id, $block_id, $block_number)
{
	if (function_exists($block)) {
		return $block($page_id, $block_id, $block_number);
	}
}

/**
 * Cookie Notice
 * @param int $id
 * @return string
 */
function cookie_notice()
{
	$bc = 'cookie-notice';
	$heading = get_option('options_cookie_notice_heading');
	$description = get_option('options_cookie_notice_description');
	$name = get_option('options_cookie_notice_cookie_name');
	if (!$name) {
		$name = 'cookies-accept';
	}
	$hasCookieNoticeCookie = isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
	if ($hasCookieNoticeCookie) return;
	$o = '<div class="' . $bc . '" data-name="' . $name . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-2">';
	$o .= '<h3 class="' . $bc . '__heading">' . esc_html($heading) . '</h3>';
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-2">';
	$o .= '<div class="' . $bc . '__description">' . wpautop($description) . '</div>';
	$o .= '</div>'; // .cell
	$o .= '<button type="button" class="' . $bc . '__close">';
	$o .= '<span class="show-for-sr">Close</span>';
	$o .= '<span class="' . $bc . '__close-icon">' . get_svg('close') . '</span>';
	$o .= '</button>';
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>';
	return $o;
}

/**
 * Page
 * Output Page
 * @param array $ags
 * @return string
 */
function page($args)
{
	$id = $args["id"];

	// Page Templates
	$is_home = $args["template_home"];
	$is_insights = $args["template_insights"];
	$is_insight = $args["single_post"];
	$is_person = $args["single_person"];
	$is_simple = $args["template_simple"];
	$is_flexible_blocks_template = $args["template_flexible_blocks"];
	$is_policy = $args["template_policy"];

	// Content
	$content_classes = $args["content_classes"] ?? '';
	$content = $args["content"] ?? '';

	// Flexible Blocks
	if ($is_flexible_blocks_template) {
		$flexible_content = get_post_meta($id, 'flexible_blocks', true);
		if ($flexible_content) {
			$i = 0;
			foreach ($flexible_content as $key => $block) {
				$content .= display_block($block, $id, $key, $i);
				$i++;
			}
		}
	}

	// Insights Category/Type Search Archive
	if (is_category() || is_tax() || is_search()) {
		$content .= insights_tax_and_search($args);
	} else if ($is_insights) { 	// Insights
		$content .= insights($args);
	}

	// Insight (Singular)
	if ($is_insight) {
		$types = get_the_terms($args["id"], 'types');
		if ($types && $types[0] && $types[0]->slug) {
			$content_classes .= ' post_type_' . $types[0]->slug;
		}
		$content .= insight($args);
	}

	// Person (Singular)
	if ($is_person) {
		$content .= person_page($args);
	}

	// Simple
	// if ($is_simple) {
	// 	$content .= simple($args);
	// }

	// Policy
	// if ($is_policy) {
	// 	$content .= policy($id);
	// }

	// 404
	if (is_404()) {
		$content .= error_404();
	}

	echo '<a href="#main" class="skip-to-content">Skip to content</a>';
	echo site_header($args);
	echo '<main id="main" class="content' . ($content_classes) . '">';
	echo '<div class="content-cover"></div>';
	echo $content;
	echo '</main>';
	echo site_footer();
	// echo cookie_notice();
}
add_action('display_page', 'page');



/**
 * Quote Shortcode
 * Output content HTML for Quote
 * @param mixed $atts
 */
function shortcode_embed_quote($atts, $quote = null)
{
	$v = shortcode_atts(array(
		'attribution' => false,
		'highlightcolour' => false,
	), $atts);

	$slides = '';
	$bc = 'quotes';

	if(!$v['highlightcolour']){
		$this_post_id = get_the_ID();
		if($this_post_id && $highlight = get_post_meta($this_post_id,  'highlight_colour', true)){
			$v['highlightcolour'] = $highlight;
		}
	}


	$attribution = $v['attribution'];
	$slides .= '<div class="' . $bc . '__slide' . '">';
	$slides .= '<div class="' . $bc . '__slide-quote-marks">' . get_svg('quote-marks') . '</div>';
	$slides .= '<figure class="' . $bc . '__slide-fig">';
	$slides .= '<blockquote class="' . $bc . '__slide-quote">' . $quote . '</blockquote>';
	$slides .= $attribution ? '<figcaption class="' . $bc . '__slide-attribution">' . $attribution . '</figcaption>' : '';
	$slides .= '</figure>';
	$slides .= '</div>'; // $bc__slide

	$classes = [$bc, 'fade-in-up'];
	if ($v['highlightcolour']) {
		$classes[] = $bc . '--' . preg_replace("/[^a-z]+/", "", strtolower($v['highlightcolour']));
	}


	$o = '<div class="' . implode(" ", $classes) . '"';
	/*
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-12 medium-offset-1 large-10 large-offset-2">';
*/
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . '"' . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' .  '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // $bc__swiper-wrap


	/*
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
*/
	$o .= '</div>'; // .$bc

	return $o;
}


/**
 * Dark section Shortcode
 * Output content HTML in white on black
 * @param mixed $atts
 */
function shortcode_dark_section($atts, $content = null)
{
	$v = shortcode_atts(array(
			'highlightcolour' => false,
	), $atts);

	$o = '<div class="dark-section">';
	$o .= do_shortcode($content);
	$o .= '</div>';

	return $o;
}



/**
 * Arrowlink Shortcode
 * Output content HTML in white on black
 * @param mixed $atts
 */
function shortcode_arrowlink($atts, $content = null)
{
	$cta = shortcode_atts(array(
		'url' => false,
		'title' => false,
		'target' => false,
		'darkarrow' => false,
		'arrow_highlight_colour' => false,
		'hide_text' => false,
	), $atts);

	$bc = 'shortcode_arrowlink';

	if (!isset($cta["url"])) return "";
	$o = '<a href="' . esc_url($cta["url"]) . '"' . ($cta["target"] === '_blank' ? ' target="_blank"' : '') . ' class="arrow-link ';
	$o .= $cta['dark_arrow'] && !$cta['arrow_highlight_colour'] ? ' arrow-link--dark ' : '';
	$o .= !$cta['dark_arrow'] && $cta['arrow_highlight_colour'] ? ' arrow-link--' . $cta['arrow_highlight_colour'] . ' ' : '';
	$o .= $cta['hide_text'] ? ' arrow-link--no-text ' : '';
	$o .= $bc . '__cta-link">';
	$o .= '<span class="arrow-link__inner">';
	$o .= '<span class="arrow-link__text ' . $bc . '__cta-link-text">' . esc_html($cta["title"]) . '</span>';
	$o .= '<span class="arrow-link__arrow ' . $bc . '__cta-link-arrow">' . get_svg('cta-arrow') . '</span>';
	$o .= '</span>'; // .arrow-link__inner
	$o .= '</a>';
	return $o;

}


/**
 * White Paper gateway Form Shortcode
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function shortcode_whitepaper_gravity_form($atts, $intro = "")
{
	$bc = 'sgravity-form';
	$cta = shortcode_atts(array(
		'url' => false,
		'title' => "Download",
		'target' => "_self",
		"highlightcolour" => false
	), $atts);


	$o = '<section class="' . $bc . ' whitepaper_gravity_form" role="dialog">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__inner">';
	$o .= $intro ? '<p class="' . $bc . '__intro">' . esc_html($intro) . '</p>' : '';
	$me = false;
	global $wp;
	$hostpage = add_query_arg($wp->query_vars, home_url($wp->request));
	if (function_exists('swp_reports_getMe')) {
		$me = swp_reports_getMe(0);
	}
	$o .= '<pre style="display:none">' . var_export($me, true) . '</pre>';
	$o .= '<div class="' . $bc . '__form">';
	$field_values = [
		'hostpage' => $hostpage
	];
	if ($me) {
		$me = json_decode(json_encode($me), false);
		if ($me->user->mail ?? 0) {
			$field_values['mail'] = $me->user->mail;
			$field_values['recap_override'] = "Skip";
		}
		if ($me->user->firstname ?? 0) {
			$field_values['firstname'] = $me->user->firstname;
		}
		if ($me->user->lastname ?? 0) {
			$field_values['lastname'] = $me->user->lastname;
		}
		if (!$field_values['firstname'] && !$field_values['lastname'] && ($me->user->fullname ?? 0)) {
			$fullname_parts = explode(" ", $me->user->fullname);
			$field_values['firstname'] = array_shift($fullname_parts);
			$field_values['lastname'] = implode(" ", $fullname_parts);
		}

		if ($me->user->company ?? 0) {
			$field_values['organization'] = $me->user->company;
		}
		if ($me->user->jobtitle ?? 0) {
			$field_values['jobtitle'] = $me->user->jobtitle;
		}
	}
	$http_field_values = http_build_query($field_values);
	$o .= '<pre style="display:none">' . var_export([$field_values, $http_field_values], true) . '</pre>';
	$o .= do_shortcode('[gravityform id="4" ajax="true" field_values="' . $http_field_values . '"  title="false"]');
	$o .= '</div>'; // $bc__form

	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';

	$callout_fields = [];
	$callout_fields["text"] = wp_title("", false);
	$callout_fields["link"] = 	[
		"title" => "Download",
		"url" => $cta['url'],
		"target" => "_self"
	];

	if (!$cta['highlightcolour']) {
		$this_post_id = get_the_ID();
		if ($this_post_id && $highlight = get_post_meta($this_post_id,  'highlight_colour', true)) {
			$cta['highlightcolour'] = $highlight;
		} else {
			$cta['highlightcolour'] = 'blue';
		}
	}

	$callout_fields["highlight"] = $cta['highlightcolour'];



	$o .= subscribe_call_out(0, 0, 0, false, $callout_fields);


	return $o;
}


add_shortcode('quote', 'shortcode_embed_quote');
add_shortcode('darksection', 'shortcode_dark_section');
add_shortcode('arrowlink', 'shortcode_arrowlink');
add_shortcode('whitepaper_gravity_form', 'shortcode_whitepaper_gravity_form');
