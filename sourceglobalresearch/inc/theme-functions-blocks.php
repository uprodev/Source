<?php

/**
 * Home Page Hero
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function home_page_hero($id, $block, $number)
{
	$bc = 'home-page-hero';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$subtitle = get_post_meta($id, 'flexible_blocks_' . $block . '_subtitle', true);
	$cta = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_link', true);
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$border_bottom = get_post_meta($id, 'flexible_blocks_' . $block . '_border_bottom', true);
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$o = '<section class="' . $bc . ($border_bottom ? ' ' . $bc . '--border-bottom' : '') . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__image-cell cell large-7 large-order-2 large-offset-0">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap">';
		$o .= '<div class="' . $bc . '__image fade-in-up">';
		$o .= $image;
		$o .= '</div>'; // $bc__image
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-6 large-order-1 large-offset-1">';
	if ($title || $cta) {
		$o .= '<div class="' . $bc . '__content-wrap">';
		$o .= '<div class="' . $bc . '__content fade-in-up">';
		$o .= $title ? '<h2 class="' . $bc . '__heading">' . $title . '</h2>' : '';
		$o .= $subtitle ? '<div class="' . $bc . '__subheading">' . $subtitle . '</div>' : '';
		if ($cta) {
			$o .= arrow_link($cta, $bc);
		}
		$o .= '</div>'; // $bc__content
		$o .= '</div>'; // $bc__content-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Latest Posts
 * @param int $id
 * @param int $block
 * @param int $number
 * @param string $flexible_content
 * @return string
 */
function latest_posts($id, $block, $number, $flexible_content = true)
{
	$bc = 'latest-posts';
	if (!$flexible_content) {
		$title = get_post_meta($id, 'latest_posts_title', true);
		$use_latest = get_post_meta($id, 'latest_posts_use_latest', true);
	} else {
		$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
		$use_latest = get_post_meta($id, 'flexible_blocks_' . $block . '_use_latest', true);
        $use_cta_block = get_post_meta($id, 'flexible_blocks_' . $block . '_use_cta_block', true);
	}
	$posts = [];
	if ($use_latest) {
		$numberposts = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_to_show', true);
		$fetched_posts = get_posts([
			'numberposts' => $numberposts,
		]);
		foreach ($fetched_posts as $p) {
			$posts[] = $p->ID;
		}
	} else {
		if (!$flexible_content) {
			$fetched_posts_count = get_post_meta($id, 'latest_posts_posts', true);
		} else {
			$fetched_posts_count = get_post_meta($id, 'flexible_blocks_' . $block . '_posts', true);
		}
		for ($i = 0; $i < $fetched_posts_count; $i++) {
			if (!$flexible_content) {
				$posts[] = get_post_meta($id, 'latest_posts_posts_' . $i . '_post', true);
			} else {
				$posts[] = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_post', true);
			}
		}
	}
	if (empty($posts)) return;
	$slides_count = count($posts);
	$autoplay = false;
	$autoplay_speed = 5000;
	$slides = '';

	foreach ($posts as $post_id) {
		$slides .= '<div class="' . $bc . '__slide' . ($slides_count > 1 ? ' swiper-slide' : '') . '">';
		$slides .= latest_posts_card($post_id);
		$slides .= '</div>'; // $bc__slide
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__head-cell cell large-offset-1 large-2">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	if ($slides_count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-10 fade-in-up">';
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($slides_count > 1 ? ' swiper-container' : '') . '"' . ($slides_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($slides_count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // $bc__swiper-wrap
	$o .= '</div>'; // .cell

    if($use_cta_block){
        $title_cta = get_post_meta($id, 'flexible_blocks_' . $block . '_title_cta', true);
        $link_cta = get_post_meta($id, 'flexible_blocks_' . $block . '_link_cta', true);

        $o .= '<div class="cell large-12">';
		$o .= '<div class="content">';
        $o .= $title_cta ? '<h2 class="title">' . esc_html($title_cta) . '</h2>' : '';
		if($link_cta) {
            $link_url = $link_cta['url'];
            $link_title = $link_cta['title'];
            $link_target = $link_cta['target'] ? $link_cta['target'] : '_self';

            $o .= '<div class="btn-wrap">';
            $o .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="arrow-link subscribe-call-out__cta-link">';
            $o .= '<span class="arrow-link__inner">';
            $o .= '<span class="arrow-link__text subscribe-call-out__cta-link-text">' . esc_html($link_title) . '</span>';
            $o .= '<span class="arrow-link__arrow subscribe-call-out__cta-link-arrow">';
            $o .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 178 14.728">';
            $o .= '<path id="arrow" class="arrow" d="M177.707 8.071a.999.999 0 0 0 0-1.414L171.343.293a.999.999 0 1 0-1.414 1.414l5.657 5.657-5.657 5.657a.999.999 0 1 0 1.414 1.414l6.364-6.364Z" fill="#fff"></path>';
            $o .= '<path id="line" class="line" d="M0 6.364h177v2H0z" fill="#fill"></path>';
            $o .= '</svg>';
            $o .= '</span>';
            $o .= '</span>';
            $o .= '</a>';
            $o .= '</div>'; // .btn-wrap
        }
		$o .= '</div>'; // .content
        $o .= '</div>'; // .cell .large-12
    }

    $o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Latest Posts Card
 * @param int $id
 * @return string
 */
function latest_posts_card($id)
{
	$bc = 'latest-posts-card';
	$title = get_the_title($id);
	// $image_id = get_post_thumbnail_id($id);
	$image_id = get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($id);
	$types = get_the_terms($id, 'types');
	$url = get_the_permalink($id);
	$o = '<aside class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__image-wrap">';
	$o .= $image ? $image : '';
	if (isset($types[0]) && $types[0]->name === 'Podcast') {
		$o .= '<span class="' . $bc . '__image-podcast-indicator">';
		$o .= get_svg('podcast');
		$o .= '</span>';
	}
	$o .= '</div>'; // $bc__image-wrap
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title">';
	$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= esc_html($title);
	$o .= '</a>';
	$o .= '</h3>';
	$o .= '</aside>';
	return $o;
}

/**
 * Page Intro
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function page_intro($id, $block, $number)
{
	$bc = 'page-intro';
	$page_title = get_the_title($id);
	$ancestor_list = get_post_ancestors($id);
	$ancestors = '';
	foreach ($ancestor_list as $ancestor_id) {
		$ancestors .= '<a href="' . get_the_permalink($ancestor_id) . '" class="' . $bc . '__crumbs-ancestor">' . get_the_title($ancestor_id) . '</a>';
		$ancestors .= '<span class="' . $bc . '__crumbs-divider"></span>';
	}
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
	$image_fill = get_post_meta($id, 'flexible_blocks_' . $block . '_image_fill', true);
	$image_class = $image_fill === 'contain' ? 'img-background-contain' : 'img-background';
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => $image_class]);
	$show_page_title = get_post_meta($id, 'flexible_blocks_' . $block . '_show_page_title', true);
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$o = '<section class="' . $bc . ' ' . $bc . '--' . $highlight . ($image ? ' ' . $bc . '--has-image' : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-10 large-offset-1' . ($image ? '' : ' xlarge-8') . '">';
	$o .= '<div class="' . $bc . '__heading-wrap fade-in-up">';
	$o .= '<div class="' . $bc . '__crumbs">';
	$o .= $ancestors;
	$o .= '<span class="' . $bc . '__crumbs-current">' . esc_html($page_title) . '</span>';
	$o .= '</div>';
	if ($title) {
		$o .= $title ? '<h2 class="' . $bc . '__heading testbyhimanshi">' . $title . '</h2>' : '';
	}
	$o .= '</div>'; // $bc__heading-wrap
	$o .= '</div>'; // .cell
	if ($intro) {
		$o .= '<div class="cell large-' . ($image ? '6' : '9') . ' large-offset-1">';
		$o .= '<div class="' . $bc . '__intro-wrap fade-in-up">';
		$o .= '<div class="' . $bc . '__intro">';
		$o .= wpautop($intro);
		$o .= '</div>'; // $bc__intro
		$o .= '</div>'; // $bc__intro-wrap
		$o .= '</div>'; // .cell
	}
	if ($image) {
		$o .= '<div class="cell large-6 large-offset-1 xlarge-7 xlarge-offset-0">';
		$o .= '<div class="' . $bc . '__image-wrap fade-in-up">';
		$o .= $image;
		$o .= '</div>'; // $bc__image-wrap
		$o .= '</div>'; // .cell
	}
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}


/**
 * About CTAs
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function about_ctas($id, $block, $number)
{
	$bc = 'about-ctas';
	$cta_1_text = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_text', true);
	$cta_1_link = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_link', true);
	$cta_1_image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_image', true);
	$cta_1 = '';
	if ($cta_1_text || $cta_1_link || $cta_1_image_id) {
		$image = $cta_1_image_id ? wp_get_attachment_image($cta_1_image_id, 'size', false, ['class' => 'img-background']) : false;
		$cta_1 .= '<div class="' . $bc . '__cta-1">';
		$cta_1 .= '<div class="' . $bc . '__cta-1-content-wrap">';
		$cta_1 .= $cta_1_text ? '<p class="' . $bc . '__cta-1-text">' . remove_widow($cta_1_text) . '</p>' : '';
		$cta_1 .= $cta_1_link ? arrow_link($cta_1_link, $bc, true) : '';
		$cta_1 .= '</div>'; // $bc__cta-1-content-wrap
		$cta_1 .= $image ? '<div class="' . $bc . '__cta-1-image">' . $image . '</div>' : '';
		$cta_1 .= '</div>'; // $bc__cta-1
	}
	$cta_2_text = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_text', true);
	$cta_2_link = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_link', true);
	$cta_2_image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_image', true);
	$cta_2 = '';
	if ($cta_2_text || $cta_2_link || $cta_2_image_id) {
		$image = $cta_2_image_id ? wp_get_attachment_image($cta_2_image_id, 'size', false, ['class' => 'img-background']) : false;
		$cta_2 .= '<div class="' . $bc . '__cta-2">';
		$cta_2 .= $image ? '<div class="' . $bc . '__cta-2-image">' . $image . '</div>' : '';
		$cta_2 .= '<div class="' . $bc . '__cta-2-content-wrap">';
		$cta_2 .= $cta_2_text ? '<p class="' . $bc . '__cta-2-text">' . remove_widow($cta_2_text) . '</p>' : '';
		$cta_2 .= $cta_2_link ? arrow_link($cta_2_link, $bc, true) : '';
		$cta_2 .= '</div>'; // $bc__cta-2-content-wrap
		$cta_2 .= '</div>'; // $bc__cta-2
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-2 large-11 fade-in-up">';
	$o .= $cta_1;
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-11 fade-in-up">';
	$o .= $cta_2;
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Hot Topics
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function hot_topics($id, $block, $number)
{
	$bc = 'hot-topics';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$section_sub_title = get_post_meta($id, 'flexible_blocks_' . $block . '_sub_title', true);
	$posts_count = get_post_meta($id, 'flexible_blocks_' . $block . '_posts', true);
	$slides = '';
	for ($i = 0; $i < $posts_count; $i++) {
		$post = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_post', true);
		$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_title_override', true);
		$sub_title = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_sub_title', true);
		$slides .= '<div class="' . $bc . '__slide' . ($posts_count > 1 ? ' swiper-slide' : '') . '">';
		$slides .= hot_topics_card($post, $title_override, $sub_title);
		$slides .= '</div>'; // $bc__slide
	}
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12 fade-in-up">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $section_sub_title ? '<p class="' . $bc . '__sub-title">' . esc_html($section_sub_title) . '</p>' : '';
	if ($posts_count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls hide-for-large">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__swiper-wrap fade-in-up">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($posts_count > 1 ? ' swiper-container' : '') . '"' . ($posts_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($posts_count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	if ($posts_count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls-wrap show-for-large">';
		$o .= '<div class="' . $bc . '__swiper-controls">';
		$o .= '<button class="' . $bc . '__swiper-controls-control-desktop ' . $bc . '__swiper-controls-control-desktop--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-desktop-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control-desktop
		$o .= '<button class="' . $bc . '__swiper-controls-control-desktop ' . $bc . '__swiper-controls-control-desktop--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-desktop-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control-desktop
		$o .= '</div>'; // $bc__swiper-controls
		$o .= '<div class="' . $bc . '__swiper-pagination"></div>';
		$o .= '</div>'; // $bc__swiper-controls-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Hot Topics Card
 * @param int $id
 * @param string $title_override
 * @param string $sub_title
 * @return string
 */
function hot_topics_card($id, $title_override = false, $sub_title = false)
{
	$bc = 'hot-topics-card';
	$title = get_the_title($id);
	if ($title_override) {
		$title = $title_override;
	}
	if ($sub_title) {
		$title = $title_override;
	}
	$title_length = strlen($title);
	$title_classes = '';
	if ($title_length > 110) {
		$title_classes .= ' ' . $bc . '__title--extremely-long';
	} else if ($title_length > 90) {
		$title_classes .= ' ' . $bc . '__title--very-long';
	} else if ($title_length > 70) {
		$title_classes .= ' ' . $bc . '__title--long';
	}
	// $image_id = get_post_thumbnail_id($id);
	$image_id = get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($id);
	$types = get_the_terms($id, 'types');
	$url = get_the_permalink($id);
	$o = '<aside class="' . $bc . '">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap">';
		$o .= $image;
		if (isset($types[0]) && $types[0]->name === 'Podcast') {
			$o .= '<span class="' . $bc . '__image-podcast-indicator">';
			$o .= get_svg('podcast');
			$o .= '</span>';
		}
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '<div class="' . $bc . '__content-wrap">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title ' . $title_classes . '">';
	$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= $title;
	$o .= '</a>';
	$o .= '</h3>';
	$o .= $sub_title ? '<p class="' . $bc . '__sub-title">' . esc_html($sub_title) . '</p>' : '';
	$o .= '</div>';
	$o .= '</aside>';
	return $o;
}

/**
 * People Carousel
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function people_carousel($id, $block, $number)
{
	$bc = 'people-carousel';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_people', true);
	$dark = get_post_meta($id, 'flexible_blocks_' . $block . '_dark_theme', true);
	$contact_page_id = get_option('options_contact_page');
	$cta_link = false;
	if ($contact_page_id) {
		$contact_page_url = get_the_permalink($contact_page_id);
		$cta_link = [
			"title" => "Get in touch",
			"url" => $contact_page_url,
			"target" => "_self",
		];
	}
	$slides = '';
	for ($i = 0; $i < $count; $i++) {
		$person = get_post_meta($id, 'flexible_blocks_' . $block . '_people_' . $i . '_person', true);
		$cta = $cta_link;
		$person_link = get_post_meta($id, 'flexible_blocks_' . $block . '_people_' . $i . '_include_person_link', true);
		$cta_override = get_post_meta($id, 'flexible_blocks_' . $block . '_people_' . $i . '_cta_override', true);
		if ($cta_override) {
			$cta = $cta_override;
		}
		$slides .= '<div class="' . $bc . '__slide' . ($count > 1 ? ' swiper-slide' : '') . '">';
		$slides .= people_carousel_card($person, $cta, $person_link, $dark);
		$slides .= '</div>'; // $bc__slide
	}
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . ($dark ? ' ' . $bc . '--dark' : '') . '">';
	$o .= '<div class="grid-container fade-in-up">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($count > 1 ? ' swiper-container' : '') . '"' . ($count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	if ($count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls-wrap">';
		$o .= '<div class="' . $bc . '__swiper-controls">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
		$o .= '</div>'; // $bc__swiper-controls-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * People Carousel Card
 * @param int $id
 * @param array $cta
 * @param bool $person_link
 * @param bool $dark
 * @return string
 */
function people_carousel_card($id, $cta = false, $person_link = false, $dark = true)
{
	$bc = 'people-carousel-card';
	$title = get_the_title($id);
	$role = get_post_meta($id, 'role', true);
	$image_id = get_post_thumbnail_id($id);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$url = get_the_permalink($id);
	$o = '<aside class="' . $bc . ($dark ? ' ' . $bc . '--dark' : '') . '">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap">';
		$o .= $image;
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '<div class="' . $bc . '__content-wrap">';
	$o .= '<h3 class="' . $bc . '__title">';
	if ($person_link) {
		$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	}
	$o .= $title;
	if ($person_link) {
		$o .= '</a>';
	}
	$o .= '</h3>';
	$o .= $role ? '<p class="' . $bc . '__role">' . esc_html($role) . '</p>' : '';
	$dark = $dark ? false : true;
	if ($cta) {
		$o .= arrow_link($cta, $bc, $dark);
	}
	$o .= '</div>'; // $bc__content-wrap
	$o .= '</aside>';
	return $o;
}

/**
 * Two Column Text
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function two_column_text($id, $block, $number)
{
	$bc = 'two-column-text';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$text = get_post_meta($id, 'flexible_blocks_' . $block . '_text', true);
	if (!$title && !$text) return;
	$cta = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_link', true);
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-3 large-offset-1">';
	if ($title) {
		$o .= '<h2 class="' . $bc . '__heading">' . remove_widow($title, false) . '</h2>';
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-8 large-offset-1">';
	if ($text || $cta) {
		$o .= '<div class="' . $bc . '__content">';
		$o .= $text ? wpautop($text) : '';
		$o .= '</div>'; // $bc__content
		$o .= $cta ? arrow_link($cta, $bc, true) : '';
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Scrolling Services
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function scrolling_services($id, $block, $number)
{
	$bc = 'scrolling-services';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$services_count = get_post_meta($id, 'flexible_blocks_' . $block . '_services', true);
	$slides = '';
	$scrollers = '';
	$spacers = '';
	for ($i = 0; $i < $services_count; $i++) {
		$service = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service', true);
		$service_description = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_description', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_link', true);
		$highlight = fetchServiceHighlight($link);
		// $slides .= '<div class="' . $bc . '__slide' . ($services_count > 1 ? ' swiper-slide' : '') . '">';
		// $slides .= '<div class="' . $bc . '__slide-inner' . ($highlight ? ' ' . $bc . '__slide-inner--' . $highlight : '') . '">';
		// $slides .= $service ? '<h3 class="' . $bc . '__slide-title">' . esc_html($service) . '</h3>' : '';
		// $slides .= '<div class="' . $bc . '__slide-description-wrap">';
		// $slides .= $service_description ? '<p class="' . $bc . '__slide-description">' . esc_html($service_description) . '</p>' : '';
		// if ($link) {
		// 	$slides .= arrow_link($link, $bc, false, true);
		// }
		// $slides .= '</div>'; // $bc__description-wrap
		// $slides .= '</div>'; // $bc__slide-inner
		// $slides .= '</div>'; // $bc__slide
		$scrollers .= '<div class="' . $bc . '__scroller" data-scroller="' . $i . '">';
		$scrollers .= '<div class="' . $bc . '__scroller-inner' . ($highlight ? ' ' . $bc . '__scroller-inner--' . $highlight : '') . '">';
		$scrollers .= $service ? '<h3 class="' . $bc . '__scroller-title' . ($highlight ? ' ' . $bc . '__scroller-title--' . $highlight : '') . '">' . esc_html($service) . '</h3>' : '';
		$scrollers .= '<div class="' . $bc . '__scroller-description-wrap">';
		$scrollers .= $service_description ? '<p class="' . $bc . '__scroller-description">' . remove_widow(esc_html($service_description)) . '</p>' : '';
		if ($link) {
			$scrollers .= arrow_link($link, $bc, false, true);
		}
		$scrollers .= '</div>'; // $bc__description-wrap
		$scrollers .= '</div>'; // $bc__scroller-inner
		$scrollers .= '</div>'; // $bc__scroller
		$spacers .= '<div class="' . $bc . '__scroller-spacer" data-spacer="' . $i . '"></div>';
	}
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1 fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '<div class="' . $bc . '__scrollers fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__scrollers-cell cell large-11 large-offset-2">';
	$o .= $scrollers;
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__scrollers
	$o .= '</div>'; // $bc__head
	$o .= '<div class="show-for-large">';
	$o .= $spacers;
	$o .= '</div>'; // .show-for-large
	// $o .= '<div class="grid-container hide-for-large">';
	// $o .= '<div class="grid-x grid-padding-x">';
	// $o .= '<div class="cell large-offset-2 large-11">';
	// if ($services_count > 1) {
	// 	$o .= '<div class="' . $bc . '__swiper-controls hide-for-large">';
	// 	$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
	// 	$o .= '<span class="show-for-sr">Previous</span>';
	// 	$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
	// 	$o .= '</button>'; // $bc__swiper-controls-control
	// 	$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
	// 	$o .= '<span class="show-for-sr">Next</span>';
	// 	$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
	// 	$o .= '</button>'; // $bc__swiper-controls-control
	// 	$o .= '</div>'; // $bc__swiper-controls
	// }
	// $o .= '<div class="' . $bc . '__swiper-wrap hide-for-large">';
	// $o .= '<div class="' . $bc . '__swiper-container' . ($services_count > 1 ? ' swiper-container' : '') . '"' . ($services_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	// $o .= '<div class="' . $bc . '__swiper-wrapper' . ($services_count > 1 ? ' swiper-wrapper' : '') . '">';
	// $o .= $slides;
	// $o .= '</div>'; // $bc__swiper-wrapper
	// $o .= '</div>'; // $bc__swiper-container
	// $o .= '</div>'; // .cell
	// $o .= '</div>'; // .grid-padding-x
	// $o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Compressed Services
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function compressed_services($id, $block, $number)
{
	$bc = 'compressed-services';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$services_count = get_post_meta($id, 'flexible_blocks_' . $block . '_services', true);
	$slides = '';
	$cells = '';
	for ($i = 0; $i < $services_count; $i++) {
		$service = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service', true);
		$service_description = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_description', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_link', true);
		$link_highlight = fetchServiceHighlight($link);
		// $slides .= '<div class="' . $bc . '__slide' . ($services_count > 1 ? ' swiper-slide' : '') . '">';
		// $slides .= '<div class="' . $bc . '__slide-inner">';
		// $slides .= $service ? '<h3 class="' . $bc . '__slide-title">' . esc_html($service) . '</h3>' : '';
		// $slides .= '<div class="' . $bc . '__slide-description-wrap">';
		// $slides .= $service_description ? '<p class="' . $bc . '__slide-description">' . esc_html($service_description) . '</p>' : '';
		// if ($link) {
		// 	$slides .= arrow_link($link, $bc, false, true, $link_highlight);
		// }
		// $slides .= '</div>'; // $bc__description-wrap
		// $slides .= '</div>'; // $bc__slide-inner
		// $slides .= '</div>'; // $bc__slide
		$cells .= '<div class="' . $bc . '__cell cell medium-7 large-5 xlarge-4 large-offset-' . ($i % 2 === 0 ? '2' : '1') . '">';
		$cells .= '<div class="' . $bc . '__card fade-in-up">';
		$cells .= $service ? '<h3 class="' . $bc . '__card-title">' . esc_html($service) . '</h3>' : '';
		$cells .= '<div class="' . $bc . '__card-description-wrap">';
		$cells .= $service_description ? '<p class="' . $bc . '__card-description">' . esc_html($service_description) . '</p>' : '';
		if ($link) {
			$cells .= arrow_link($link, $bc, false, true, $link_highlight);
		}
		$cells .= '</div>'; // $bc__card-description-wrap
		$cells .= '</div>'; // $bc__card
		$cells .= '</div>'; // $bc__cell
	}
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-10 large-offset-1 xlarge-8 fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '<div class="' . $bc . '__cards">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= $cells;
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__cells
	// $o .= '<div class="grid-container hide-for-large">';
	// $o .= '<div class="grid-x grid-padding-x">';
	// $o .= '<div class="cell large-offset-2 large-11">';
	// if ($services_count > 1) {
	// 	$o .= '<div class="' . $bc . '__swiper-controls hide-for-large">';
	// 	$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
	// 	$o .= '<span class="show-for-sr">Previous</span>';
	// 	$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
	// 	$o .= '</button>'; // $bc__swiper-controls-control
	// 	$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
	// 	$o .= '<span class="show-for-sr">Next</span>';
	// 	$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
	// 	$o .= '</button>'; // $bc__swiper-controls-control
	// 	$o .= '</div>'; // $bc__swiper-controls
	// }
	// $o .= '<div class="' . $bc . '__swiper-wrap hide-for-large fade-in-up">';
	// $o .= '<div class="' . $bc . '__swiper-container' . ($services_count > 1 ? ' swiper-container' : '') . '"' . ($services_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	// $o .= '<div class="' . $bc . '__swiper-wrapper' . ($services_count > 1 ? ' swiper-wrapper' : '') . '">';
	// $o .= $slides;
	// $o .= '</div>'; // $bc__swiper-wrapper
	// $o .= '</div>'; // $bc__swiper-container
	// $o .= '</div>'; // .cell
	// $o .= '</div>'; // .grid-padding-x
	// $o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Scrolling Questions
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function scrolling_questions($id, $block, $number)
{
	$bc = 'scrolling-questions';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$question_count = get_post_meta($id, 'flexible_blocks_' . $block . '_questions', true);
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$slides = '';
	$scrollers = '';
	$spacers = '';
	for ($i = 0; $i < $question_count; $i++) {
		$question = get_post_meta($id, 'flexible_blocks_' . $block . '_questions_' . $i . '_question', true);
		if ($question) {
			$slides .= '<div class="' . $bc . '__slide' . ($question_count > 1 ? ' swiper-slide' : '') . '">';
			$slides .= '<div class="' . $bc . '__slide-inner">';
			$slides .= '<span class="' . $bc . '__slide-number">' . str_pad(($i + 1), 2, '0', STR_PAD_LEFT) . '</span>';
			$slides .= '<h3 class="' . $bc . '__slide-title">' . esc_html($question) . '</h3>';
			$slides .= '</div>'; // $bc__slide-inner
			$slides .= '</div>'; // $bc__slide
			$scrollers .= '<div class="' . $bc . '__scroller" data-scroller="' . $i . '">';
			$scrollers .= '<div class="' . $bc . '__scroller-inner">';
			$scrollers .= '<span class="' . $bc . '__scroller-number">' . str_pad(($i + 1), 2, '0', STR_PAD_LEFT) . '</span>';
			$scrollers .= '<span class="' . $bc . '__scroller-divider"></span>';
			$scrollers .= $question ? '<h3 class="' . $bc . '__scroller-title">' . esc_html($question) . '</h3>' : '';
			$scrollers .= '</div>'; // $bc__scroller-inner
			$scrollers .= '</div>'; // $bc__scroller
			$spacers .= '<div class="' . $bc . '__scroller-spacer" data-spacer="' . $i . '"></div>';
		}
	}
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1 fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '<div class="' . $bc . '__scrollers show-for-large">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__scrollers-cell cell show-for-large large-12 large-offset-1 xlarge-11">';
	$o .= $scrollers;
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__scrollers
	$o .= '</div>'; // $bc__head
	$o .= '<div class="show-for-large">';
	$o .= $spacers;
	$o .= '</div>'; // .show-for-large
	$o .= '<div class="grid-container hide-for-large">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-2 large-11">';
	if ($question_count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls hide-for-large">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '<div class="' . $bc . '__swiper-wrap hide-for-large">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($question_count > 1 ? ' swiper-container' : '') . '"' . ($question_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($question_count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Assets
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function assets($id, $block, $number)
{
	$bc = 'assets';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_assets', true);
	$show_login_cta = get_post_meta($id, 'flexible_blocks_' . $block . '_show_login_cta', true);
	$login_cta = '';
	if ($show_login_cta === "1") {
		$login_text = get_post_meta($id, 'flexible_blocks_' . $block . '_login_text', true);
		$login_link = get_post_meta($id, 'flexible_blocks_' . $block . '_login_link', true);
		$signup_label = get_post_meta($id, 'flexible_blocks_' . $block . '_sign_up_label', true);
		$signup_link = get_post_meta($id, 'flexible_blocks_' . $block . '_sign_up_link', true);
		$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
		$login_cta .= '<div class="' . $bc . '__login-cta fade-in-up">';
		$login_cta .= '<div class="grid-container">';
		$login_cta .= '<div class="grid-x grid-padding-x">';
		$login_cta .= '<div class="cell">';
		$login_cta .= '<div class="' . $bc . '__login-cta-inner' . ($highlight ? ' ' . $bc . '__login-cta-inner--' . $highlight : '') . '">';
		$login_cta .= $login_text ? '<p class="' . $bc . '__login-cta-text">' . esc_html($login_text) . '</p>' : '';
		$login_cta .= '<div class="' . $bc . '__login-cta-link">';
		$login_cta .= $login_link ? arrow_link($login_link, $bc, true) : '';
		$login_cta .= '</div>'; // $bc__login-cta-link
		if ($signup_label && $signup_link) {
			$login_cta .= '<div class="' . $bc . '__login-cta-signup-wrap">';
			$login_cta .= '<p class="' . $bc . '__login-cta-signup-label">' . esc_html($signup_label) . '</p>';
			$login_cta .= '<a href="' . esc_url($signup_link["url"]) . '"' . ($signup_link["target"] === '_blank' ? ' target="_blank"' : '') . ' class="' . $bc . '__login-cta-signup-link">' . esc_html($signup_link["title"]) . '</a>';
			$login_cta .= '</div>'; // $bc__login-cta-signup-wrap
		}
		$login_cta .= '</div>'; // $bc__login-cta-inner
		$login_cta .= '</div>'; // .cell
		$login_cta .= '</div>'; // .grid-padding-x
		$login_cta .= '</div>'; // .grid-container
		$login_cta .= '</div>'; // $bc__login-cta
	}
	$assets = '';
	for ($i = 0; $i < $count; $i++) {
		$service = get_post_meta($id, 'flexible_blocks_' . $block . '_assets_' . $i . '_title', true);
		$asset_description = get_post_meta($id, 'flexible_blocks_' . $block . '_assets_' . $i . '_description', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_assets_' . $i . '_link', true);
		$assets .= '<div class="' . $bc . '__row  fade-in-up">';
		$assets .= '<div class="' . $bc . '__row-title-wrap">';
		$assets .= $service ? '<h3 class="' . $bc . '__row-title">' . esc_html($service) . '</h3>' : '';
		$assets .= $asset_description ? '<p class="' . $bc . '__row-description">' . esc_html($asset_description) . '</p>' : '';
		$assets .= '</div>'; // $bc__title-wrap
		if ($link) {
			$assets .= '<div class="' . $bc . '__row-link-wrap">';
			$assets .= arrow_link($link, $bc, true);
			$assets .= '</div>'; // . $bc__link-wrap
		}
		$assets .= '</div>'; // $bc__row
	}
	$o = '<section class="' . $bc . ($login_cta ? ' ' . $bc . '--has-login-cta' : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-12 large-9 large-offset-1 xlarge-8">';
	$o .= '<div class="' . $bc . '__head fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-offset-1 medium-12 xlarge-11 large-offset-2">';
	$o .= $assets;
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= $login_cta;
	$o .= '</section>';
	return $o;
}

/**
 * Subscribe Call Out
 * @param int $id
 * @param int $block
 * @param int $number
 * @param bool $flexible_content
 * @param mixed $fields Array of field values or false
 * @return string
 */
function subscribe_call_out($id, $block, $number, $flexible_content = true, $fields = false)
{
	$bc = 'subscribe-call-out';
	$popup = '';
	if (!$flexible_content) {
		if ($fields) {
			$text = $fields["text"];
			$link = $fields["link"];
			$highlight = $fields["highlight"];
			//$popup = $fields["popup"];
		} else {
			$text = get_post_meta($id, 'subscribe_call_out_text', true);
			$link = get_post_meta($id, 'subscribe_call_out_link', true);
			$highlight = get_post_meta($id, 'subscribe_call_out_highlight_colour', true);
			//$popup = get_post_meta($id, 'subscribe_call_out_popup', true);
		}
	} else {
		$text = get_post_meta($id, 'flexible_blocks_' . $block . '_text', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
		$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
		$popup = get_post_meta($id, 'flexible_blocks_' . $block . '_popup', true);
		
	}
	if (!$text && !$link) return;
	$o = '<section class="' . $bc . ' ' . $bc . '--' . $highlight . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__inner fade-in-up">';
	$o .= $text ? '<h2 class="' . $bc . '__title">' . esc_html($text) . '</h2>' : '';
	if($popup == 'yes'){
			$o .= $link ? '<div class="' . $bc . '__link"><a id="mypopup2" href="javascript:void(0)" class="arrow-link  ' . $bc . '__cta-link"><span class="arrow-link__inner"><span class="arrow-link__text ' . $bc . '__cta-link-text">Request a demo</span><span class="arrow-link__arrow what-is-white-space__cta-link-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 178 14.728">
	  <path id="arrow" class="arrow" d="M177.707 8.071a.999.999 0 0 0 0-1.414L171.343.293a.999.999 0 1 0-1.414 1.414l5.657 5.657-5.657 5.657a.999.999 0 1 0 1.414 1.414l6.364-6.364Z" fill="#fff"></path><path id="line" class="line" d="M0 6.364h177v2H0z" fill="#fill"></path>
	</svg></span></span></a></div>' : '';


	}else{
		$o .= $link ? '<div class="' . $bc . '__link">' . arrow_link($link, $bc) . '</div>' : '';
	}
	
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Single Featured Post
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function single_featured_post($id, $block, $number)
{
	$bc = 'single-featured-post';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$post = get_post_meta($id, 'flexible_blocks_' . $block . '_post', true);
	$image_override = get_post_meta($id, 'flexible_blocks_' . $block . '_post_image_override', true);
	$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_post_title_override', true);
	$description_override = get_post_meta($id, 'flexible_blocks_' . $block . '_post_description_override', true);
	if (!$post) return;
	$post_title = $title_override ? $title_override : get_the_title($post);
	// $image_id = $image_override ? $image_override : get_post_thumbnail_id($post);
	$image_id = $image_override ? $image_override : get_post_meta($post, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($post);
	$types = get_the_terms($post, 'types');
	$url = get_the_permalink($post);
	$description = $description_override ? $description_override : 'Listen now';
	$o = '<section class="' . $bc . '">';
	if ($title) {
		$o .= '<div class="grid-container">';
		$o .= '<div class="grid-x grid-padding-x">';
		$o .= '<div class="cell large-12 large-offset-1 fade-in-up">';
		$o .= '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>';
		$o .= '</div>'; // .cell
		$o .= '</div>'; // .grid-padding-x
		$o .= '</div>'; // .grid-container
	}
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x fade-in-up">';
	$o .= '<div class="' . $bc . '__image-cell cell medium-6 large-4 large-offset-1">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap">';
		$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__image-link">';
		$o .= $image;
		if (isset($types[0]) && $types[0]->name === 'Podcast') {
			$o .= '<span class="' . $bc . '__image-podcast-indicator">';
			$o .= get_svg('podcast');
			$o .= '</span>';
		}
		$o .= '</a>';
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="' . $bc . '__details-cell cell medium-8">';
	$o .= '<div class="' . $bc . '__details">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__post-title">';
	$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= esc_html($post_title);
	$o .= '</a>';
	$o .= '</h3>';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	$o .= '</div>'; // $bc__details
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Meet our people
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function meet_our_people($id, $block, $number)
{
	$bc = 'meet-our-people';
    $style = get_post_meta($id, 'flexible_blocks_' . $block . '_use_new_style', true);
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$image_1 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_1', true);
	$image_2 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_2', true);
	$image_3 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_3', true);
	$primary_image = wp_get_attachment_image($image_1, 'size', false, ['class' => 'img-background']);
	$secondary_image = wp_get_attachment_image($image_2, 'size', false, ['class' => 'img-background']);
	$tertiary_image = wp_get_attachment_image($image_3, 'size', false, ['class' => 'img-background']);

	if($style){
	    $ns = ' meet-our-people-new';
    }else{
	    $ns = '';
    }
	$o = '<section class="' . $bc . $ns . '">';
	$o .= '<div class="' . $bc . '__intro grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1 fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<p class="' . $bc . '__description">' . remove_widow($description) . '</p>' : '';
	$o .= $link ? '<div class="' . $bc . '__link">' . arrow_link($link, $bc, true) . '</div>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	if ($secondary_image || $primary_image || $tertiary_image) {
		$o .= '<div class="' . $bc . '__images fade-in-up">';
		if ($secondary_image) {
			$o .= '<div class="' . $bc . '__image-secondary">';
			$o .= '<div class="grid-container">';
			$o .= '<div class="grid-x grid-padding-x">';
			$o .= '<div class="cell large-6 large-offset-7">';
			$o .= '<div class="' . $bc . '__image-secondary-wrap">';
			$o .= $secondary_image;
			$o .= '</div>'; // $bc__image-secondary-wrap
			$o .= '</div>'; // .cell
			$o .= '</div>'; // .grid-padding-x
			$o .= '</div>'; // .grid-container
			$o .= '</div>'; // $bc__image-secondary
		}
		if ($primary_image) {
			$o .= '<div class="' . $bc . '__image-primary">';
			$o .= '<div class="grid-container">';
			$o .= '<div class="grid-x grid-padding-x">';
			$o .= '<div class="cell large-8 large-offset-1">';
			$o .= '<div class="' . $bc . '__image-primary-wrap">';
			$o .= $primary_image;
			$o .= '</div>'; // $bc__image-primary-wrap
			$o .= '</div>'; // .cell
			$o .= '</div>'; // .grid-padding-x
			$o .= '</div>'; // .grid-container
			$o .= '</div>'; // $bc__image-primary
		}
		if ($tertiary_image) {
			$o .= '<div class="' . $bc . '__image-tertiary">';
			$o .= '<div class="grid-container">';
			$o .= '<div class="grid-x grid-padding-x">';
			$o .= '<div class="cell large-6 large-offset-6">';
			$o .= '<div class="' . $bc . '__image-tertiary-wrap">';
			$o .= $tertiary_image;
			$o .= '</div>'; // $bc__image-tertiary-wrap
			$o .= '</div>'; // .cell
			$o .= '</div>'; // .grid-padding-x
			$o .= '</div>'; // .grid-container
			$o .= '</div>'; // $bc__image-tertiary
		}
		$o .= '</div>'; // $bc__images
	}
	$o .= '</section>';
	return $o;
}

/**
 * Image Group
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function image_group($id, $block, $number)
{
	$bc = 'image-group';
	$image_1 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_1', true);
	$image_2 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_2', true);
	$image_3 = get_post_meta($id, 'flexible_blocks_' . $block . '_image_3', true);
	$primary_image = wp_get_attachment_image($image_1, 'size', false, ['class' => 'img-background']);
	$secondary_image = wp_get_attachment_image($image_2, 'size', false, ['class' => 'img-background']);
	$tertiary_image = wp_get_attachment_image($image_3, 'size', false, ['class' => 'img-background']);
	if (!$secondary_image || !$primary_image || !$tertiary_image) return;
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__image-wrap fade-in-up">';
	$o .= '<div class="' . $bc . '__image-secondary">';
	$o .= $secondary_image;
	$o .= '</div>'; // $bc__image-secondary
	$o .= '<div class="' . $bc . '__image-primary">';
	$o .= $primary_image;
	$o .= '</div>'; // $bc__image-primary
	$o .= '<div class="' . $bc . '__image-tertiary">';
	$o .= $tertiary_image;
	$o .= '</div>'; // $bc__image-tertiary
	$o .= '</div>'; // $bc__image-wrap
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * How can we help
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function how_can_we_help($id, $block, $number)
{
	$bc = 'how-can-we-help';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$end_link = get_post_meta($id, 'flexible_blocks_' . $block . '_end_cta_link', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_content', true);
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$mobile_content_cols = '';
	$right_col_content = '';
	$left_col_content = '';
	$desktop_content_cols = '';
	for ($i = 0; $i < $count; $i++) {
		$content_title = get_post_meta($id, 'flexible_blocks_' . $block . '_content_' . $i . '_title', true);
		$content_description = get_post_meta($id, 'flexible_blocks_' . $block . '_content_' . $i . '_description', true);
		$content_cta = get_post_meta($id, 'flexible_blocks_' . $block . '_content_' . $i . '_cta_link', true);
		$column_content = '<div class="' . $bc . '__content fade-in-up">';
		$column_content .= $content_title ? '<h3 class="' . $bc . '__content-title">' . $content_title . '</h3>' : '';
		$column_content .= $content_description ? '<div class="' . $bc . '__content-description">' . wpautop($content_description) . '</div>' : '';
		$column_content .= $content_cta ? arrow_link($content_cta, $bc, true) : '';
		$column_content .= '</div>'; // $bc__content
		if ($i % 2 === 0) {
			$right_col_content .= $column_content;
		} else {
			$left_col_content .= $column_content;
		}
		$desktop_content_cols = '<div class="' . $bc . '__content-cell ' . $bc . '__content-cell--left cell show-for-large large-5 large-offset-1">';
		$desktop_content_cols .= $right_col_content;
		$desktop_content_cols .= '</div>'; // $bc__content-cell
		$desktop_content_cols .= '<div class="' . $bc . '__content-cell ' . $bc . '__content-cell--right cell show-for-large large-5 large-offset-2">';
		$desktop_content_cols .= $left_col_content;
		$desktop_content_cols .= '</div>'; // $bc__content-cell

		$mobile_content_cols .= '<div class="' . $bc . '__content-cell cell hide-for-large">';
		$mobile_content_cols .= $column_content;
		$mobile_content_cols .= '</div>'; // $bc__content-cell
	}
	switch ($highlight) {
		case 'red':
			$highlight_colour = '#FF004C';
			break;
		case 'purple':
			$highlight_colour = '#A600FF';
			break;
		case 'yellow':
			$highlight_colour = '#FFBB00';
			break;
		case 'blue':
			$highlight_colour = '#00DEDE';
			break;
		case 'green':
			$highlight_colour = '#00F07C';
			break;

		default:
			$highlight_colour = false;
			break;
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . ($count >= 4 ? ' ' . $bc . '--dt-parallax' : '') . '"' . ($highlight_colour ? ' data-highlight="' . $highlight_colour . '"' : '') . '>';
	$o .= '<div class="' . $bc . '__head fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-5 large-offset-1">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-7">';
	$o .= $description ?  $description  : '';
	//$o .= $description ? '<p class="' . $bc . '__description">' . $description . '</p>' : '';
	$o .= $link ? '<div class="' . $bc . '__link">' . arrow_link($link, $bc, true) . '</div>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__head
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= $mobile_content_cols;
	$o .= $desktop_content_cols;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	// if ($end_link) {
	// 	$o .= '<div class="' . $bc . '__end-link grid-container fade-in-up">';
	// 	$o .= '<div class="grid-x grid-padding-x">';
	// 	$o .= '<div class="cell large-offset-1 large-12">';
	// 	$o .= arrow_link($end_link, $bc, true);
	// 	$o .= '</div>'; // .cell
	// 	$o .= '</div>'; // .grid-padding-x
	// 	$o .= '</div>'; // .grid-container
	// }
	$o .= '</section>';
	return $o;
}

/**
 * What's new
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function whats_new($id, $block, $number)
{
	$bc = 'whats-new';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$sub_title = get_post_meta($id, 'flexible_blocks_' . $block . '_sub_title', true);
	$posts_count = get_post_meta($id, 'flexible_blocks_' . $block . '_posts', true);
	$dark = get_post_meta($id, 'flexible_blocks_' . $block . '_dark_theme', true);
	$arrow_cta = get_post_meta($id, 'flexible_blocks_' . $block . '_arrow_style_cta', true);
	$slides = '';
	$cells = '';
	for ($i = 0; $i < $posts_count; $i++) {
		$post = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_post', true);
		$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_title_override', true);
		$description_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_description_override', true);
		$card = whats_new_card($post, $title_override, $description_override, $dark, $arrow_cta);
		$slides .= '<div class="' . $bc . '__slide' . ($posts_count > 1 ? ' swiper-slide' : '') . '">';
		$slides .= $card;
		$slides .= '</div>'; // $bc__slide
		$cell_classes = '';
		if ($posts_count === '1') {
			$cell_classes .= 'large-6';
		} else if ($posts_count === '2') {
			if ($i === 0) {
				$cell_classes .= 'large-6 ' . $bc . '__card-cell-one';
			} else {
				$cell_classes .= 'large-5 ' . $bc . '__card-cell-two';
			}
		} else if ($posts_count === '3') {
			if ($i === 0) {
				$cell_classes .= 'large-4 ' . $bc . '__card-cell-one';
			} else if ($i === 1) {
				$cell_classes .= 'large-4 ' . $bc . '__card-cell-two';
			} else {
				$cell_classes .= 'large-2 ' . $bc . '__card-cell-three';
			}
		}
		$cells .= '<div class="' . $bc . '__card-cell cell show-for-large ' . $cell_classes . ' large-offset-1">';
		$cells .= $card;
		$cells .= '</div>';
	}
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . ($dark ? ' ' . $bc . '--dark' : '') . '">';
	$o .= '<div class="grid-container ' . $bc . '__post-count-' . $posts_count . ' fade-in-up">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $sub_title ? '<p class="' . $bc . '__sub-title">' . esc_html($sub_title) . '</p>' : '';
	if ($posts_count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls hide-for-large">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= '<div class="' . $bc . '__swiper-cell cell hide-for-large large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($posts_count > 1 ? ' swiper-container' : '') . '"' . ($posts_count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($posts_count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // $bc__swiper-wrap
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * What's new card
 * @param int $id
 * @param string $title_override
 * @param string $description_override
 * @param string $dark
 * @return string
 */
function whats_new_card($id, $title_override = false, $description_override = false, $dark = false, $arrow_cta = false)
{
	$bc = 'whats-new-card';
	$title = get_the_title($id);
	if ($title_override) {
		$title = $title_override;
	}
	$description = '';
	if ($description_override) {
		$description = $description_override;
	}
	$title_length = strlen($title);
	$title_classes = '';
	if ($title_length > 110) {
		$title_classes .= ' ' . $bc . '__title--extremely-long';
	} else if ($title_length > 90) {
		$title_classes .= ' ' . $bc . '__title--very-long';
	} else if ($title_length > 70) {
		$title_classes .= ' ' . $bc . '__title--long';
	}
	// $image_id = get_post_thumbnail_id($id);
	$image_id = get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($id);
	$types = get_the_terms($id, 'types');
	$is_podcast = false;
	$podcast_indicator = '';
	if (isset($types[0]) && $types[0]->name === 'Podcast') {
		$podcast_indicator .= '<span class="' . $bc . '__image-podcast-indicator">';
		$podcast_indicator .= get_svg('podcast');
		$podcast_indicator .= '</span>';
		$is_podcast = true;
	}
	$url = get_the_permalink($id);
	$dark_arrow = $dark ? false : true;
	$o = '<aside class="' . $bc . ($dark ? ' ' . $bc . '--dark' : '') . '">';
	$o .= '<div class="' . $bc . '__image-wrap-outer">';
	$o .= '<div class="' . $bc . '__image-wrap">';
	$o .= $image ? $image : '';
	$o .= $podcast_indicator;
	$o .= '</div>'; // $bc__image-wrap
	$o .= '</div>'; // $bc__image-wrap-outer
	$o .= '<div class="' . $bc . '__content-wrap">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title ' . $title_classes . '">';
	$o .= $arrow_cta ? '' : '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= $title;
	$o .= $arrow_cta ? '' : '</a>';
	$o .= '</h3>';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	if ($arrow_cta) {
		$o .= '<div class="' . $bc . '__cta">';
		$o .= arrow_link(["url" => $url, "title" => "Find out more", "target" => "_self"], $bc, $dark_arrow);
		$o .= '</div>'; // $bc__cta
	} else {
		$o .= '<div class="' . $bc . '__details">';
		$o .= '<span class="' . $bc . '__details-type">';
		$o .= $is_podcast ? 'Podcast' : 'Article';
		$o .= '</span>';
		$o .= '<span class="' . $bc . '__details-separator"> • </span>';
		$o .= '<span class="' . $bc . '__details-length">';
		$o .= $is_podcast ? '40 minute listen' : estimateReadingTime(get_the_content(null, false, $id))["minutes"] . ' min read';
		$o .= '</span>';
		if (!$is_podcast) {
			$author_id = get_post_field('post_author', $id);
			$o .= '<span class="' . $bc . '__details-separator"> • </span>';
			$o .= '<span class="' . $bc . '__details-author">';
			$o .= get_the_author_meta('display_name', intval($author_id));
			$o .= '</span>';
		}
		$o .= '</div>'; // $bc__details
	}
	$o .= '</div>'; // $bc__content-wrap
	$o .= '</aside>';
	return $o;
}

/**
 * Brands insight
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function brand_insights($id, $block, $number)
{
	$bc = 'brand-insights';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$posts_count = get_post_meta($id, 'flexible_blocks_' . $block . '_posts', true);
	$cells = '';
	for ($i = 0; $i < $posts_count; $i++) {
		$post = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_post', true);
		$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_title_override', true);
		$description_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_description_override', true);
		$cell_classes = '';
		if ($posts_count === '1') {
			$cell_classes .= 'large-6';
		} else if ($posts_count === '2') {
			if ($i === 0) {
				$cell_classes .= 'large-7 ' . $bc . '__card-cell-one';
			} else {
				$cell_classes .= 'large-4 ' . $bc . '__card-cell-two';
			}
		}
		$cells .= '<div class="' . $bc . '__card-cell cell ' . $cell_classes . ' large-offset-1">';
		$cells .= brand_insights_card($post, $title_override, $description_override);
		$cells .= '</div>';
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Brand Insights card
 * @param int $id
 * @param string $title_override
 * @param string $description_override
 * @return string
 */
function brand_insights_card($id, $title_override = false, $description_override = false)
{
	$bc = 'brand-insights-card';
	$title = get_the_title($id);
	if ($title_override) {
		$title = $title_override;
	}
	$description = '';
	if ($description_override) {
		$description = $description_override;
	}
	// $image_id = get_post_thumbnail_id($id);
	$image_id = get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($id);
	$types = get_the_terms($id, 'types');
	$podcast_indicator = '';
	if (isset($types[0]) && $types[0]->name === 'Podcast') {
		$podcast_indicator .= '<span class="' . $bc . '__image-podcast-indicator">';
		$podcast_indicator .= get_svg('podcast');
		$podcast_indicator .= '</span>';
		$is_podcast = true;
	}
	$url = get_the_permalink($id);
	$o = '<aside class="' . $bc . '">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap">';
		$o .= $image;
		$o .= $podcast_indicator;
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '<div class="' . $bc . '__content-wrap">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title">';
	$o .= $title;
	$o .= '</h3>';
	$o .= $description ? '<p class="' . $bc . '__description">' . esc_html($description) . '</p>' : '';
	$o .= '<div class="' . $bc . '__cta">';
	$o .= arrow_link(["url" => $url, "title" => "Find out more", "target" => "_self"], $bc, true);
	$o .= '</div>'; // $bc__cta
	$o .= '</div>'; // $bc__content-wrap
	$o .= '</aside>';
	return $o;
}

/**
 * Other Services
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function other_services($id, $block, $number)
{
	$bc = 'other-services';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_services', true);
	$services_page_id = intval(get_option('options_services_page'));
	$cells = '';
	for ($i = 0; $i < $count; $i++) {
		$service_title = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service_title', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service_link', true);
		$link_highlight = fetchServiceHighlight($link);
		if (!$service_title || !$link) continue;
		$cells .= '<div class="' . $bc . '__cell ' . $bc . '__cell--' . $i . ' ' . 'cell medium-7 large-3' . ($i === 0 ? ' large-offset-1' : '') . '">';
		$cells .= '<div class="' . $bc . '__service">';
		$cells .= '<h3 class="' . $bc . '__service-title">' . esc_html($service_title) . '</h3>';
		$cells .= arrow_link($link, $bc, false, false, $link_highlight);
		$cells .= '</div>'; // $bc__service
		$cells .= '</div>'; // .cell
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Our Values
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function our_values($id, $block, $number)
{
	$bc = 'our-values';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_values', true);
	$cells = '';
	for ($i = 0; $i < $count; $i++) {
		$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_values_' . $i . '_image', true);
		$value_title = get_post_meta($id, 'flexible_blocks_' . $block . '_values_' . $i . '_title', true);
		$value_description = get_post_meta($id, 'flexible_blocks_' . $block . '_values_' . $i . '_description', true);
		if (!$value_title || !$image_id) continue;
		$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
		$cells .= '<div class="' . $bc . '__cell cell large-3 ' . ($i % 3 === 0 ? 'large-offset-2' : 'large-offset-1') . '">';
		$cells .= '<div class="' . $bc . '__value">';
		$cells .= '<div class="' . $bc . '__value-image">';
		$cells .= $image;
		$cells .= '</div>'; // $bc__value_image
		$cells .= '<div class="' . $bc . '__value-content">';
		$cells .= '<h3 class="' . $bc . '__value-title">' . esc_html($value_title) . '</h3>';
		$cells .= $value_description ? '<p class="' . $bc . '__value-description">' . esc_html($value_description) . '</p>' : '';
		$cells .= '</div>'; // $bc__value-content
		$cells .= '</div>'; // $bc__value
		$cells .= '</div>'; // .cell
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container fade-in-up">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Text & Image
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function text_and_image($id, $block, $number)
{
	$bc = 'text-and-image';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$content = get_post_meta($id, 'flexible_blocks_' . $block . '_content', true);
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => '']);
	$dark = get_post_meta($id, 'flexible_blocks_' . $block . '_dark_theme', true);
	$o = '<section class="' . $bc . ($dark ? ' ' . $bc . '--dark' : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-6 large-6 large-offset-1 xlarge-6 medium-order-2">';
	$o .= '<div class="' . $bc . '__image-wrap fade-in-up">';
	$o .= $image;
	$o .= '</div>'; // $bc__image-wrap
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-8 large-offset-1 large-6 xlarge-6">';
	$o .= '<div class="' . $bc . '__content-wrap fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . remove_widow(esc_html($title)) . '</h2>' : '';
	$o .= $content ? '<div class="' . $bc . '__content">' . wpautop($content) . '</div>' : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Full height image & large text
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function full_height_image_and_large_text($id, $block, $number)
{
	$bc = 'fh-img-and-lg-txt';
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$text = get_post_meta($id, 'flexible_blocks_' . $block . '_text', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__image-wrap fade-in-up">';
	$o .= $image;
	$o .= '</div>'; // $bc__image-wrap
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x grid-full-height">';
	$o .= '<div class="cell medium-6 large-5 large-offset-1 xlarge-4">';
	$o .= '<div class="' . $bc . '__content fade-in-up">';
	$o .= $text ? '<h2 class="' . $bc . '__content-text">' . esc_html($text) . '</h2>' : '';
	if ($link) {
		$o .= arrow_link($link, $bc);
	}
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Text with logo carousel
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function text_with_logo_carousel($id, $block, $number)
{
	$bc = 'text-with-logo-carousel';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_logos', true);
	$logos = '';
	for ($i = 0; $i < $count; $i++) {
		$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_logos_' . $i . '_logo_image', true);
		if ($image_id) {
			$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background-nah']);
			$logo_link = get_post_meta($id, 'flexible_blocks_' . $block . '_logos_' . $i . '_link', true);
			$logos .= '<div class="' . $bc . '__logos-item">';
			if ($logo_link) {
				$logos .= '<a href="' . esc_url($logo_link["url"]) . '" target="_blank" class="' . $bc . '__logos-item-link">';
			}
			$logos .= $image;
			if ($logo_link) {
				$logos .= '</a>';
			}
			$logos .= '</div>';
		}
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x grid-full-height">';
	$o .= '<div class="cell large-offset-1 large-9">';
	$o .= '<div class="' . $bc . '__content fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__content-title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<p class="' . $bc . '__content-description">' . esc_html($description) . '</p>' : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	if ($logos) {
		$o .= '<div class="' . $bc . '__logos fade-in-up">';
		$o .= '<div class="marquee3k" data-speed="1" data-reverse="false" data-pausable="true">';
		$o .= '<div class="' . $bc . '__marquee">';
		$o .= '<div class="' . $bc . '__logos-wrapper">';
		$o .= $logos;
		$o .= '</div>'; // $bc__logos-wrapper
		$o .= '</div>'; // $bc__marquee
		$o .= '</div>'; // .marquee3k
		$o .= '</div>'; // $bc__logos
	}
	if ($link) {
		$o .= '<div class="grid-container">';
		$o .= '<div class="grid-x grid-padding-x grid-full-height">';
		$o .= '<div class="cell large-offset-1 large-9">';
		$o .= '<div class="' . $bc . '__link fade-in-up">';
		$o .= arrow_link($link, $bc, true);
		$o .= '</div>'; // $bc__link
		$o .= '</div>'; // .cell
		$o .= '</div>'; // .grid-padding-x
		$o .= '</div>'; // .grid-container
	}
	$o .= '</section>';
	return $o;
}

/**
 * Quotes
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function quotes($id, $block, $number)
{
	$bc = 'quotes';
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_quotes', true);
	if ($count < 1) return;
	$slides = '';
	for ($i = 0; $i < $count; $i++) {
		$quote = get_post_meta($id, 'flexible_blocks_' . $block . '_quotes_' . $i . '_quote', true);
		if ($quote) {
			$attribution = get_post_meta($id, 'flexible_blocks_' . $block . '_quotes_' . $i . '_attribution', true);
			$slides .= '<div class="' . $bc . '__slide' . ($count > 1 ? ' swiper-slide' : '') . '">';
			$slides .= '<div class="' . $bc . '__slide-quote-marks">' . get_svg('quote-marks') . '</div>';
			$slides .= '<figure class="' . $bc . '__slide-fig">';
			$slides .= '<blockquote class="' . $bc . '__slide-quote">' . $quote . '</blockquote>';
			$slides .= $attribution ? '<figcaption class="' . $bc . '__slide-attribution">' . $attribution . '</figcaption>' : '';
			$slides .= '</figure>';
			$slides .= '</div>'; // $bc__slide
		}
	}
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$autoplay = false;
	$autoplay_speed = 5000;
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-12 medium-offset-1 large-10 large-offset-2">';
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($count > 1 ? ' swiper-container' : '') . '"' . ($count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // $bc__swiper-wrap
	$o .= '</div>'; // .cell
	$o .= '<div class="' . $bc . '__controls-cell cell medium-12 medium-offset-1 large-10 large-offset-2">';
	if ($count > 1) {
		$o .= '<div class="' . $bc . '__swiper-pagination show-for-large"></div>';
		$o .= '<div class="' . $bc . '__swiper-controls">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Single Case Study
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function single_case_study($id, $block, $number)
{
	$bc = 'single-case-study';
	$post_id = get_post_meta($id, 'flexible_blocks_' . $block . '_post', true);
	$post = get_post($post_id);
	$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_title_override', true);
	$image_override = get_post_meta($id, 'flexible_blocks_' . $block . '_image_override', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link_label = get_post_meta($id, 'flexible_blocks_' . $block . '_link_label', true);
	$link = false;
	if ($link_label) {
		$link = arrow_link([
			"url" => get_the_permalink($post_id),
			"title" => $link_label,
			"target" => "_self",
		], $bc, true);
	}
	$image_id = $image_override ? $image_override : get_post_thumbnail_id($post_id);
	$image_id = $image_override ? $image_override : get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$title = $title_override ? $title_override : $post->post_title;
	$categories = get_the_category($post->ID);
	$types = get_the_terms($post->ID, 'types');
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-offset-1 medium-11 large-6">';
	$o .= '<div class="' . $bc . '__image">';
	$o .= $image;
	$o .= '</div>'; // $bc__image
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-offset-1 medium-11 large-5">';
	$o .= '<div class="' . $bc . '__content">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__content-meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__content-meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__content-meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__content-meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__content-meta
	}
	$o .= '<h2 class="' . $bc . '__content-title">' . esc_html($title) . '</h2>';
	$o .= '<p class="' . $bc . '__content-description">' . esc_html($description) . '</p>';
	$o .= $link ? $link : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Featured People List
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function featured_people_list($id, $block, $number)
{
	$bc = 'featured-people-list';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_people', true);
	$cells = '';
	for ($i = 0; $i < $count; $i++) {
		$post_id = get_post_meta($id, 'flexible_blocks_' . $block . '_people_' . $i . '_person', true);
		if ($post_id) {
			$large_indexes = [0, 5, 7, 9, 14, 16, 18, 22, 24, 26];
			$cell_classes = '';
			if (in_array($i, $large_indexes)) {
				$cell_classes .= 'large-4';
			} else {
				$cell_classes .= 'large-3';
			}
			$cells .= '<div class="cell medium-7 large-offset-1 ' . $cell_classes . '">';
			$cells .= person_card($post_id);
			$cells .= '</div>';
		}
	}
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__content">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '<div class="' . $bc . '__cards">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__cards
	$o .= '</section>';
	return $o;
}

/**
 * Person Card
 * @param int $post_id
 * @return string
 */
function person_card($post_id)
{
	$bc = 'person-card';
	$image_id = get_post_thumbnail_id($post_id);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$role = get_post_meta($post_id, 'role', true);
	$title = get_the_title($post_id);
	$link = get_the_permalink($post_id);
	$o = '<div class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__image">';
	$o .= $image;
	$o .= '</div>'; // $bc__image
	$o .= '<div class="' . $bc . '__content">';
	$o .= $role ? '<span class="' . $bc . '__role">' . esc_html($role) . '</span>' : '';
	$o .= $title ? '<h3 class="' . $bc . '__name"><a href="' . esc_url($link) . '" class="' . $bc . '__link">' . esc_html($title) . '</a></h3>' : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // $bc
	return $o;
}

/**
 * People List
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function people_list($id, $block, $number)
{
	$bc = 'people-list';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_people', true);
	$cells = '';
	for ($i = 0; $i < $count; $i++) {
		$post_id = get_post_meta($id, 'flexible_blocks_' . $block . '_people_' . $i . '_person', true);
		if ($post_id) {
			$cells .= '<div class="cell medium-7 large-3 large-offset-1 xlarge-2">';
			$cells .= person_card($post_id);
			$cells .= '</div>';
		}
	}
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__content">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__content
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '<div class="' . $bc . '__cards">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__cards
	$o .= '</section>';
	return $o;
}

/**
 * Text and Link
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function text_and_link($id, $block, $number)
{
	$bc = 'text-and-link';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x fade-in-up">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $description ? '<div class="' . $bc . '__description">' . wpautop($description) . '</div>' : '';
	$o .= $link ? '<div class="' . $bc . '__link">' . arrow_link($link, $bc, true) . '</div>' : '';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Full Bleed Image
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function full_bleed_image($id, $block, $number)
{
	$bc = 'full-bleed-image';
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
	$select_for_trends_page = get_post_meta($id, 'flexible_blocks_' . $block . '_select_for_trends_page', true);
	if(empty($select_for_trends_page)){
		$new_class=' ';
		$extra_class=' ';
	}else{
        $new_class='full-image';
        $extra_class='extra-img-background';
	}
	
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background '.$extra_class]);

	
	$o = '<section class="' . $bc . ' fade-in-up '.$new_class.'">';
	
	$o .= '<div class="' . $bc . '__image">';
	$o .= $image;
	$o .= '</div>'; // $bc__image
	$o .= '</section>';
	return $o;
}

/**
 * Careers Accordion List
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function careers_accordian_list($id, $block, $number)
{
	$bc = 'careers-accordion-list';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_roles', true);
	if ($count < 1) return;
	$roles = '';
	for ($i = 0; $i < $count; $i++) {
		$role_title = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_title', true);
		$type = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_type', true);
		$location = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_location', true);
		$description = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_description', true);
		$apply_url = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_application_link', true);
		$apply_text = get_post_meta($id, 'flexible_blocks_' . $block . '_roles_' . $i . '_application_link_text', true);
		if ($title && $apply_url && $apply_text) {
			$roles .= '<li class="' . $bc . '__accordion-item' . ($description ? ' has-details' : '') . ' fade-in-up">';
			$roles .= '<div class="' . $bc . '__accordion-item-head">';
			$roles .= '<' . ($description ? 'button' : 'span') . ' class="' . $bc . '__accordion-item-head-control"';
			$roles .= $description ? ' aria-controls="careers-accordion-' . $block . '-' . $i . '"' : '';
			$roles .= $description ? ' aria-expanded="false"' : '';
			$roles .= '>';
			$roles .= '<span class="' . $bc . '__accordion-item-head-control-title">' . esc_html($role_title) . '</span>';
			if ($type || $location) {
				$roles .= '<span class="' . $bc . '__accordion-item-head-control-meta">';
				$roles .= ' – ';
				$roles .= $type ? $type : '';
				$roles .= $type && $location ? ' / ' : '';
				$roles .= $location ? $location : '';
				$roles .= '</span>';
			}
			$roles .= '</' . ($description ? 'button' : 'span') . '>';
			$roles .= '<a href="' . esc_url($apply_url) . '" class="' . $bc . '__accordion-item-apply-link show-for-large" target="_blank">';
			$roles .= '<span class="' . $bc . '__accordion-item-apply-link-text">' . esc_html($apply_text) . '</span>';
			$roles .= '<span class="' . $bc . '__accordion-item-apply-link-icon">' . get_svg('up-right-arrow') . '</span>';
			$roles .= '</a>';
			$roles .= '</div>'; // $bc__accordion-item-head
			if ($description) {
				$roles .= '<div id="careers-accordion-' . $block . '-' . $i . '" class="' . $bc . '__accordion-item-body" aria-hidden="true">';
				$roles .= '<div class="' . $bc . '__accordion-item-body-inner">';
				$roles .= wpautop($description);
				$roles .= '<a href="' . esc_url($apply_url) . '" class="' . $bc . '__accordion-item-apply-link ' . $bc . '__accordion-item-apply-link--body show-for-large" target="_blank">';
				$roles .= '<span class="' . $bc . '__accordion-item-apply-link-text">' . esc_html($apply_text) . '</span>';
				$roles .= '<span class="' . $bc . '__accordion-item-apply-link-icon">' . get_svg('up-right-arrow') . '</span>';
				$roles .= '</a>';
				$roles .= '</div>'; // $bc__accordion-item-body-inner
				$roles .= '</div>'; // $bc__accordion-item-body
			}
			$roles .= '<a href="' . esc_url($apply_url) . '" class="' . $bc . '__accordion-item-apply-link hide-for-large" target="_blank">';
			$roles .= '<span class="' . $bc . '__accordion-item-apply-link-text">' . esc_html($apply_text) . '</span>';
			$roles .= '<span class="' . $bc . '__accordion-item-apply-link-icon">' . get_svg('up-right-arrow') . '</span>';
			$roles .= '</a>';
			$roles .= '</li>';
		}
	}
	if (!$roles) return;
	$o = '<section id="available-positions" class="' . $bc . ' js-scroll-to-smoothly">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head fade-in-up">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '<ul class="' . $bc . '__accordion">';
	$o .= $roles;
	$o .= '</ul>'; // $bc__accordion
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Quote with picture
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function quote_with_picture($id, $block, $number)
{
	$bc = 'quote-with-picture';
	$quote = get_post_meta($id, 'flexible_blocks_' . $block . '_quote', true);
	if (!$quote) return;
	$attribution = get_post_meta($id, 'flexible_blocks_' . $block . '_quote_attribution', true);
	$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_picture', true);
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	if ($image) {
		$o .= '<div class="cell large-order-2 large-5">';
		$o .= '<div class="' . $bc . '__image fade-in-up">';
		$o .= $image;
		$o .= '</div>'; // $bc__image
		$o .= '</div>'; // .cell
	}
	$o .= '<div class="cell large-order-1 large-8 large-offset-1 xlarge-7 xlarge-offset-2">';
	$o .= '<div class="' . $bc . '__quote">';
	$o .= '<div class="' . $bc . '__quote-marks fade-in-up">' . get_svg('quote-marks') . '</div>';
	$o .= '<figure class="' . $bc . '__quote-fig fade-in-up">';
	$o .= '<blockquote class="' . $bc . '__quote-quote">' . $quote . '</blockquote>';
	$o .= $attribution ? '<figcaption class="' . $bc . '__quote-attribution">' . $attribution . '</figcaption>' : '';
	$o .= '</figure>';
	$o .= '</div>'; // $bc__quote
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Benefits carousel
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function benefits_carousel($id, $block, $number)
{
	$bc = 'benefits-carousel';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_slides', true);
	$autoplay = false;
	$autoplay_speed = 5000;
	$slides = '';
	for ($i = 0; $i < $count; $i++) {
		$image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_slides_' . $i . '_image', true);
		$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
		$slide_title = get_post_meta($id, 'flexible_blocks_' . $block . '_slides_' . $i . '_title', true);
		$description = get_post_meta($id, 'flexible_blocks_' . $block . '_slides_' . $i . '_description', true);
		$slides .= '<div class="' . $bc . '__slide' . ($count > 1 ? ' swiper-slide' : '') . '">';
		if ($image) {
			$slides .= '<div class="' . $bc . '__slide-image">';
			$slides .= $image ? $image : '';
			$slides .= '</div>'; // $bc__slide-image
		}
		$slides .= '<div class="' . $bc . '__slide-content">';
		$slides .= '<div class="' . $bc . '__slide-content-number">';
		$slides .= $i + 1;
		$slides .= '</div>'; // $bc__slide-number
		$slides .= '<div class="' . $bc . '__slide-content-body">';
		if ($slide_title) {
			$slides .= '<h3 class="' . $bc . '__slide-content-title">';
			$slides .= esc_html($slide_title);
			$slides .= '</h3>'; // $bc__slide-title
		}
		if ($description) {
			$slides .= '<p class="' . $bc . '__slide-content-description">';
			$slides .= esc_html($description);
			$slides .= '</p>'; // $bc__slide-description
		}
		$slides .= '</div>'; // $bc__slide-content-body
		$slides .= '</div>'; // $bc__slide-content
		$slides .= '</div>'; // $bc__slide
	}
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="' . $bc . '__head-cell cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	if ($count > 1) {
		$o .= '<div class="' . $bc . '__swiper-controls">';
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--prev">';
		$o .= '<span class="show-for-sr">Previous</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('prev-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '<button class="' . $bc . '__swiper-controls-control ' . $bc . '__swiper-controls-control--next">';
		$o .= '<span class="show-for-sr">Next</span>';
		$o .= '<span class="' . $bc . '__swiper-controls-control-icon hide-for-sr">' . get_svg('next-arrow') . '</span>';
		$o .= '</button>'; // $bc__swiper-controls-control
		$o .= '</div>'; // $bc__swiper-controls
	}
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-13">';
	$o .= '<div class="' . $bc . '__swiper-wrap">';
	$o .= '<div class="' . $bc . '__swiper-container' . ($count > 1 ? ' swiper-container' : '') . '"' . ($count > 1 && $autoplay && $autoplay_speed ? ' data-autoplay="' . $autoplay_speed . '"' : '')  . '>';
	$o .= '<div class="' . $bc . '__swiper-wrapper' . ($count > 1 ? ' swiper-wrapper' : '') . '">';
	$o .= $slides;
	$o .= '</div>'; // $bc__swiper-wrapper
	$o .= '</div>'; // $bc__swiper-container
	$o .= '</div>'; // $bc__swiper-wrap
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Contact Form
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function contact_form($id, $block, $number)
{
	$bc = 'contact-form';
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
	$required_fields_text = get_post_meta($id, 'flexible_blocks_' . $block . '_required_fields_text', true);
	$form_id = get_post_meta($id, 'flexible_blocks_' . $block . '_form_id', true);
	$o = '<section class="' . $bc . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-10 large-offset-1 xlarge-6">';
	$o .= '<div class="' . $bc . '__inner">';
	$o .= $intro ? '<p class="' . $bc . '__intro">' . esc_html($intro) . '</p>' : '';
	$o .= $required_fields_text ? '<p class="' . $bc . '__required-fields-text">' . esc_html($required_fields_text) . '</p>' : '';
	if ($form_id) {
		$o .= '<div class="' . $bc . '__form fade-in-up">';
		$o .= do_shortcode('[gravityform id="' . $form_id . '" title="false"]');
		$o .= '</div>'; // $bc__form
	}
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * SourceGravity Form
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function sgravity_form($id, $block, $number)
{
	$bc = 'sgravity-form';
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
	$form_id = get_post_meta($id, 'flexible_blocks_' . $block . '_form_id', true);
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__inner">';
	$o .= $intro ? '<p class="' . $bc . '__intro">' . esc_html($intro) . '</p>' : '';
	if ($form_id) {
		$o .= '<div class="' . $bc . '__form">';
		$o .= do_shortcode('[gravityform id="' . $form_id . '" title="false"]');
		$o .= '</div>'; // $bc__form
	}
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * White Paper gateway Form
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function whitepaper_gravity_form($id, $block, $number)
{
	$bc = 'sgravity-form';
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
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
		"url" => get_post_meta($id, 'flexible_blocks_' . $block . '_download_link', true),
		"target" => "_self"
	];

	$callout_fields["highlight"] = 'blue';

	$o .= subscribe_call_out(0, 0, 0, false, $callout_fields);


	return $o;
}

/**
 * Report Extract Form
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function report_extract_gravity_form($id, $block, $number)
{
	$bc = 'sgravity-form';
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
	$o = '<section class="' . $bc . ' report_extract_gravity_form" role="dialog">';
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

	}
	$http_field_values = http_build_query($field_values);
	$o .= '<pre style="display:none">' . var_export([$field_values, $http_field_values], true) . '</pre>';
	$o .= do_shortcode('[gravityform id="5" ajax="true" field_values="' . $http_field_values . '"  title="false"]');
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
		"url" => get_post_meta($id, 'flexible_blocks_' . $block . '_download_link', true),
		"target" => "_self"
	];

	$callout_fields["highlight"] = 'blue';

	$o .= subscribe_call_out(0, 0, 0, false, $callout_fields);


	return $o;
}

/**
 * Report Notification Form
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function report_notification_gravity_form($id, $block, $number)
{
	$bc = 'sgravity-form';
	$intro = get_post_meta($id, 'flexible_blocks_' . $block . '_intro_text', true);
	$o = '<section class="' . $bc . ' report_notification_gravity_form" role="dialog">';
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

	}
	$http_field_values = http_build_query($field_values);
	$o .= '<pre style="display:none">' . var_export([$field_values, $http_field_values], true) . '</pre>';
	$o .= do_shortcode('[gravityform id="6" ajax="true" field_values="' . $http_field_values . '"  title="false"]');
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
		"url" => get_post_meta($id, 'flexible_blocks_' . $block . '_download_link', true),
		"target" => "_self"
	];

	$callout_fields["highlight"] = 'blue';

	$o .= subscribe_call_out(0, 0, 0, false, $callout_fields);


	return $o;
}

/**
 * Map
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function map($id, $block, $number)
{
	$bc = 'map';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$map = get_post_meta($id, 'flexible_blocks_' . $block . '_map', true);
	$address = get_post_meta($id, 'flexible_blocks_' . $block . '_address', true);
	$directions_link = get_post_meta($id, 'flexible_blocks_' . $block . '_directions_link', true);
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-8 large-offset-1">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	if ($map) {
		$o .= '<div class="' . $bc . '__map-container" data-address="' . $map["address"] . '" data-lat="' . $map["lat"] . '" data-lng="' . $map["lng"] . '" data-zoom="' . $map["zoom"] . '" data-place="' . $map["place_id"] . '" data-street-number="' . $map["street_number"] . '" data-street-name="' . $map["street_name"] . '" data-city="' . $map["city"] . '" data-state="' . $map["state"] . '" data-post-code="' . $map["post_code"] . '" data-country="' . $map["country"] . '" data-country-short="' . $map["country_short"] . '"></div>';
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-3 large-offset-1">';
	$o .= '<div class="' . $bc . '__address-wrap">';
	$o .= $address ? '<div class="' . $bc . '__address">' . wpautop($address) . '</div>' : '';
	$o .= $directions_link ? '<div class="' . $bc . '__directions">' . arrow_link($directions_link, $bc) . '</div>' : '';
	$o .= '</div>'; // $bc__address-wrap
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insights
 * @param array $args
 * @return string
 */
function insights($args)
{
	$use_latest = get_post_meta($args["id"], 'latest_posts_use_latest', true);
	$latest_posts_ids = [];
	if ($use_latest) {
		$fetched_posts = get_posts([
			'numberposts' => 3,
		]);
		foreach ($fetched_posts as $p) {
			$latest_posts_ids[] = $p->ID;
		}
	} else {
		$fetched_posts_count = get_post_meta($args["id"], 'latest_posts_posts', true);
		for ($i = 0; $i < $fetched_posts_count; $i++) {
			$latest_posts_ids[] = get_post_meta($args["id"], 'latest_posts_posts_' . $i . '_post', true);
		}
	}
	$posts = get_posts([
		'numberposts' => 30,
		'exclude' => $latest_posts_ids
	]);
	$initial_post_count = count($posts);
	$rows = latest_posts($args["id"], false, false, false);
	$rows .= insights_row(array_splice($posts, 0, 6), 'Latest insights');
	$rows .= subscribe_call_out($args["id"], false, false, false);
	$rows .= insights_row(array_splice($posts, 0, 9), false, false);
	$rows .= insights_row(array_splice($posts, 0, 6));
	$rows .= insights_row(array_splice($posts, 0, 9), false, false);
	$total = wp_count_posts()->publish;
	$post_count = ($initial_post_count - count($posts)) + count($latest_posts_ids);
	$o = insights_hero($args);
	$o .= $rows;
	$o .= insights_footer($post_count, $total);
	return $o;
}

/**
 * Insights tax and search
 * @param array $args
 * @return string
 */
function insights_tax_and_search($args)
{
	$insights_page_id = get_option('options_insights_page');
	$queried_object = get_queried_object();
	$taxonomy = false;
	$term_id = false;
	$term_name = false;
	$search = false;
	$posts = [];
	if ($queried_object) {
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;
		$term_name = $queried_object->name;
		$posts = get_posts([
			'numberposts' => -1,
			'tax_query' => [
				[
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => [$term_id],
					'operator' => 'IN',
				]
			],
		]);
	}
	$search = get_search_query();
	if ($search) {
		$args = [
			's' => $search,
			'relevanssi' => true,
			'numberposts' => -1,
			'post_type' => 'post',
		];
		$query = new WP_Query($args);
		$posts = $query->posts;
	}
	$initial_post_count = count($posts);
	$category_id = $type_id = false;
	if ($taxonomy === 'category') {
		$category_id = $term_id;
	} else if ($taxonomy === 'types') {
		$type_id = $term_id;
	}
	$o = insights_hero([
		"id" => $insights_page_id,
		"taxonomy" => $taxonomy,
		"term_id" => $term_id,
		"term_name" => $term_name,
		"search" => $search,
		"animate" => false,
	]);
	$title = false;
	if ($taxonomy) {
		$title = ucfirst($taxonomy) . ' | ' . $term_name;
		if ($taxonomy === 'types') {
			$title = 'Type | ' . $term_name;
		}
	} else if ($search) {
		$title = 'Search results for: "' . esc_html($search) . '"';
	}
	$o .= insights_row(array_splice($posts, 0, 18), $title, true, false);
	$post_count = $initial_post_count - count($posts);
	$o .= insights_footer($post_count, $initial_post_count, $category_id, $type_id, $search);
	return $o;
}

/**
 * Insights Hero
 * @param array $args
 * @return string
 */
function insights_hero($args)
{
	$bc = 'insights-hero';
	$id = $args["id"];
	$page_title = get_the_title($id);
	$title = get_post_meta($id, 'hero_title', true);
	$highlight = get_post_meta($id, 'highlight_colour', true);
	$image_id = get_post_meta($id, 'hero_image', true);
	$image_fill = get_post_meta($id, 'hero_image_fill', true);
	$image_class = $image_fill === 'contain' ? 'img-background-contain' : 'img-background';
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => $image_class]);
	$categories = get_terms([
		'taxonomy' => 'category',
		'hide_empty' => false,
	]);
	$types = get_terms([
		'taxonomy' => 'types',
		'hide_empty' => true,
	]);
	// Categories
	$category_links = '';
	$category_options = '';
	$current_category_id = false;
	if (isset($args["taxonomy"]) && $args["taxonomy"] === 'category') {
		$current_category_id = $args["term_id"];
	}
	foreach ($categories as $category) {
		$category_url = get_term_link($category);
		if ($category->name !== 'Uncategorised') {
			$active = $category->term_id === $current_category_id ? true : false;
			$category_links .= '<li class="' . $bc . '__filters-taxonomies-taxonomy-terms-list-item">';
			$category_links .= '<a href="' . $category_url . '#filters" class="' . $bc . '__filters-taxonomies-taxonomy-terms-list-item-link' . ($active ? ' active' : '') . '">' . $category->name . '</a>';
			$category_links .= '</li>';
			$category_options .= '<option value="' . $category->name . '" data-url="' . $category_url . '"' . ($active ? ' selected' : '') . '>' . $category->name . '</option>';
		}
	}
	// Types
	$types_links = '';
	$types_options = '';
	$current_type_id = false;
	$fade_in_up = isset($args["animate"]) && $args["animate"] === false ? false : true;
	if (isset($args["taxonomy"]) && $args["taxonomy"] === 'types') {
		$current_type_id = $args["term_id"];
	}
	foreach ($types as $type) {
		$type_url = get_term_link($type);
		if ($type->name !== 'Uncategorised') {
			$active = $type->term_id === $current_type_id ? true : false;
			$types_links .= '<li class="' . $bc . '__filters-taxonomies-taxonomy-terms-list-item">';
			$types_links .= '<a href="' . $type_url . '#filters" class="' . $bc . '__filters-taxonomies-taxonomy-terms-list-item-link">' . $type->name . '</a>';
			$types_links .= '</li>';
			$types_options .= '<option value="' . $type->name . '" data-url="' . $type_url . '"' . ($active ? ' selected' : '') . '>' . $type->name . '</option>';
		}
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . ($current_type_id || $current_category_id || (isset($args["search"]) && $args["search"]) ? ' ' . $bc . '--taxonomy' : '') . '">';
	$o .= '<div id="filters" class="' . $bc . '__filter-anchor"></div>';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-7 large-6 large-offset-1">';
	if ($title) {
		$o .= '<div class="' . $bc . '__content' . ($fade_in_up ? ' fade-in-up' : '') . '">';
		$o .= '<div class="' . $bc . '__crumbs">';
		if (isset($args["taxonomy"]) && $args["taxonomy"] === 'category' && isset($args["term_id"])) {
			$o .= '<a href="' . get_the_permalink($args["id"]) . '" class="' . $bc . '__crumbs-ancestor">' . esc_html($page_title) . '</a>';
			$o .= '<span class="' . $bc . '__crumbs-divider"></span>';
			$o .= '<span class="' . $bc . '__crumbs-current">' . esc_html($args["term_name"]) . '</span>';
		} else {
			$o .= '<span class="' . $bc . '__crumbs-current">' . esc_html($page_title) . '</span>';
		}
		$o .= '</div>'; // $bc__crumbs
		$o .= '<h1 class="' . $bc . '__heading">' . $title . '</h1>';
		$o .= '</div>'; // $bc__content
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-7 large-offset-0">';
	if ($image) {
		$o .= '<div class="' . $bc . '__image-wrap' . ($fade_in_up ? ' fade-in-up' : '') . '">';
		$o .= '<div class="' . $bc . '__image">';
		$o .= $image;
		$o .= '</div>'; // $bc__image
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__filters">';
	$o .= '</div>'; // $bc__filters
	$o .= '</div>'; // .cell
	// Types
	$o .= '<div class="cell small-order-2 small-7 medium-4 medium-order-1 large-3 large-offset-1">';
	$o .= '<div class="' . $bc . '__filters-taxonomies-taxonomy">';
	$o .= '<button class="' . $bc . '__filters-taxonomies-taxonomy-control show-for-large" aria-controls="filters-types" aria-expanded="false">';
	$o .= '<span class="' . $bc  . '__filters-taxonomies-taxonomy-control-text">Type</span>';
	$o .= '<span class="' . $bc  . '__filters-taxonomies-taxonomy-control-icon">' . get_svg('select-arrow') . '</span>';
	$o .= '</button>';
	if ($current_type_id) {
		$o .= '<span class="' . $bc . '__filters-taxonomies-taxonomy-current show-for-large">' . esc_html($args["term_name"]) . '</span>';
	}
	if ($types_links) {
		$o .= '<div id="filters-types" class="' . $bc . '__filters-taxonomies-taxonomy-terms ' . $bc . '__filters-taxonomies-taxonomy-terms--types show-for-large" aria-hidden="true">';
		$o .= '<span class="' . $bc . '__filters-taxonomies-taxonomy-terms-title">Type ></span>';
		$o .= '<ul class="' . $bc . '__filters-taxonomies-taxonomy-terms-list">';
		$o .= $types_links;
		$o .= '</ul>'; // $bc__filters-taxonomies-taxonomy-terms-list
		$o .= '</div>'; // $bc__filters-taxonomies-taxonomy-terms
	}
	$o .= '<select class="' . $bc . '__filters-taxonomies-taxonomy-field ' . $bc . '__filters-taxonomies-taxonomy-field--type hide-for-large">';
	$o .= '<option>Type</option>';
	$o .= $types_options ? $types_options : '';
	$o .= '</select>';
	$o .= '</div>'; // $bc__filters-taxonomies-taxonomy
	$o .= '</div>'; // .cell
	// Categories
	$o .= '<div class="cell small-order-2 small-7 medium-4 medium-order-1 large-3 xlarge-4">';
	$o .= '<div class="' . $bc . '__filters-taxonomies-taxonomy">';
	$o .= '<button class="' . $bc . '__filters-taxonomies-taxonomy-control show-for-large" aria-controls="filters-categories" aria-expanded="false">';
	$o .= '<span class="' . $bc  . '__filters-taxonomies-taxonomy-control-text">Category</span>';
	$o .= '<span class="' . $bc  . '__filters-taxonomies-taxonomy-control-icon">' . get_svg('select-arrow') . '</span>';
	$o .= '</button>';
	if ($current_category_id) {
		$o .= '<span class="' . $bc . '__filters-taxonomies-taxonomy-current show-for-large">' . esc_html($args["term_name"]) . '</span>';
	}
	if ($category_links) {
		$o .= '<div id="filters-categories" class="' . $bc . '__filters-taxonomies-taxonomy-terms ' . $bc . '__filters-taxonomies-taxonomy-terms--categories show-for-large" aria-hidden="true">';
		$o .= '<span class="' . $bc . '__filters-taxonomies-taxonomy-terms-title">Category ></span>';
		$o .= '<ul class="' . $bc . '__filters-taxonomies-taxonomy-terms-list">';
		$o .= $category_links;
		$o .= '</ul>'; // $bc__filters-taxonomies-taxonomy-terms-list
		$o .= '</div>'; // $bc__filters-taxonomies-taxonomy-terms
	}
	$o .= '<select class="' . $bc . '__filters-taxonomies-taxonomy-field ' . $bc . '__filters-taxonomies-taxonomy-field--category hide-for-large">';
	$o .= '<option>Category</option>';
	$o .= $category_options ? $category_options : '';
	$o .= '</select>';
	$o .= '</div>'; // $bc__filters-taxonomies-taxonomy
	$o .= '</div>'; // .cell
	// Search
	$o .= '<div class="cell medium-order-1 medium-6 large-4 large-offset-2 xlarge-offset-1">';
	$o .= '<form action="/#filters" class="' . $bc . '__filters-search">';
	$o .= '<div class="' . $bc . '__filters-search-inner">';
	$o .= '<input type="search" name="s" class="' . $bc . '__filters-search-input" placeholder="Search"' . (isset($args["search"]) && $args["search"] ? ' value="' . $args["search"] . '"' : '') . '>';
	$o .= '<button type="submit" class="' . $bc . '__filters-search-submit">';
	$o .= '<span class="show-for-sr">Search</span>';
	$o .= '<span class="' . $bc . '__filters-search-submit-icon hide-for-sr">' . get_svg('search') . '</span>';
	$o .= '</button>';
	$o .= '</div>'; // $bc__filters-search-inner
	$o .= '</form>';
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insights Row
 * @param array $posts
 * @param string $title
 * @param string $card_images
 * @param bool $fade_in_up
 * @return string
 */
function insights_row($posts, $title = false, $card_images = true, $fade_in_up = true)
{
	$bc = 'insights-row';
	$cells = '';
	foreach ($posts as $post) {
		$cells .= '<div class="cell medium-7 large-3 large-offset-1">';
		$cells .= insights_row_card($post, $card_images);
		$cells .= '</div>'; // .cell
	}
	$o = '<section class="' . $bc . ($fade_in_up ? ' fade-in-up' : '') . '">';
	if ($title) {
		$o .= '<div class="' . $bc . '__title">';
		$o .= '<div class="grid-container">';
		$o .= '<div class="grid-x grid-padding-x">';
		$o .= '<div class="cell large-12 large-offset-1">';
		$o .= '<h2 class="' . $bc . '__title-heading">' . esc_html($title) . '</h2>';
		$o .= '</div>'; // .cell
		$o .= '</div>'; // .grid-padding-x
		$o .= '</div>'; // .grid-container
		$o .= '</div>'; // $bc__title
	}
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insights Row Card
 * @param array $post
 * @param bool $show_image
 * @return string
 */
function insights_row_card(object $post, bool $show_image = true)
{
	$bc = 'insights-row-card';
	$title = $post->post_title;
	$categories = get_the_category($post->ID);
	$types = get_the_terms($post->ID, 'types');
	$image_markup = '';
	if ($show_image) {
		// $image_id = get_post_thumbnail_id($post->ID);
		$image_id = get_post_meta($post->ID, 'thumbnail_image', true);
		$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
		$image_markup .= '<div class="' . $bc . '__image-wrap">';
		$image_markup .= $image ? $image : '';
		if (isset($types[0]) && $types[0]->name === 'Podcast') {
			$image_markup .= '<span class="' . $bc . '__image-podcast-indicator">';
			$image_markup .= get_svg('podcast');
			$image_markup .= '</span>';
		}
		$image_markup .= '</div>'; // $bc__image-wrap
	}
	$url = get_the_permalink($post->ID);
	$o = '<aside class="' . $bc . (!$show_image ? ' ' . $bc . '--no-image' : '') . '">';
	$o .= $image_markup;
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title">';
	$o .= esc_html($title);
	$o .= '</h3>';
	$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= '<span class="' . $bc . '__link-text">Read more</span>';
	$o .= '<span class="' . $bc . '__link-arrow">' . get_svg('read-more-arrow') . '</span>';
	$o .= '</a>';
	$o .= '</aside>';
	return $o;
}

/**
 * Insights Footer
 * @param int $showing
 * @param int $total
 * @param string $category
 * @param string $type
 * @param string $search
 * @return string
 */
function insights_footer($showing, $total, $category = false, $type = false, $search = false)
{
	$bc = 'insights-footer';
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell">';
	$o .= '<div class="' . $bc . '__inner fade-in-up">';
	$o .= '<div class="' . $bc . '__showing">';
	$o .= '<span class="' . $bc . '__showing-inner">Showing <span class="' . $bc . '__showing-inner-count">' . $showing . '</span> of <span class="' . $bc . '__showing-inner-total">' . $total . '</span> insights</span>';
	$o .= '</div>'; // $bc__showing
	if ($showing < $total) {
		$o .= '<span class="' . $bc . '__divider"></span>';
		$o .= '<div class="' . $bc . '__more">';
		$o .= '<button class="' . $bc . '__more-control"';
		$o .= ' data-offset="' . esc_attr($showing) . '"';
		$o .= ' data-total="' . esc_attr($total) . '"';
		$o .= ' data-per-page="9"';
		$o .= $category ? ' data-category="' . $category . '"' : '';
		$o .= $type ? ' data-type="' . $type . '"' : '';
		$o .= $search ? ' data-search="' . $search . '"' : '';
		$o .= '>';
		$o .= 'Load more';
		$o .= '</button>';
		$o .= '</div>'; // $bc__more
	}
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insight
 * @param array $args
 * @return string
 */
function insight($args)
{
	$categories = get_the_terms($args["id"], 'category');
	$types = get_the_terms($args["id"], 'types');

	$redirect = get_post_meta($args["id"], 'redirect', true);
	if($redirect){
		wp_redirect($redirect);
		exit;
	}


	if ($types && $types[0] && $types[0]->name === 'Podcast') {
		return podcast($args, $categories, $types);
	}
	$show_subscribe = get_post_meta($args["id"], 'show_subscribe_call_out', true);
	$subscribe_call_out = '';
	if ($show_subscribe) {
		$subscribe_use_global_settings = get_post_meta($args["id"], 'subscribe_call_out_use_global_settings', true);
		$fields = [];
		if ($subscribe_use_global_settings) {
			$fields["text"] = get_option('options_subscribe_call_out_text');
			$fields["link"] = get_option('options_subscribe_call_out_link');
			$fields["highlight"] = get_option('options_subscribe_call_out_highlight_colour');
		} else {
			$fields["text"] = get_post_meta($args["id"], 'subscribe_call_out_text', true);
			$fields["link"] = get_post_meta($args["id"], 'subscribe_call_out_link', true);
			$fields["highlight"] = get_post_meta($args["id"], 'subscribe_call_out_highlight_colour', true);
		}
		$subscribe_call_out = subscribe_call_out($args["id"], false, false, false, $fields);
	}
	$o = insight_hero($args, $categories, $types);
	$o .= insight_content($args, $categories, $types);
	$o .= $subscribe_call_out;
	$o .= insight_related_posts($args, $categories, $types);
	return $o;
}

/**
 * Insight Hero
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function insight_hero($args, $categories, $types)
{
	$bc = 'insight-hero';
	$id = $args["id"];
	$page_title = get_the_title($id);
	$title_override = get_post_meta($id, 'title_override', true);
	$title = $title_override ? $title_override : $page_title;
	$sub_title = get_post_meta($id, 'sub_title', true);
	$highlight = get_post_meta($id, 'highlight_colour', true);
	$taxonomies = '';
	if ($categories && $categories[0]) {
		$taxonomies .= '<a href="' . get_term_link($categories[0], 'category') . '" class="' . $bc . '__crumbs-ancestor">' . $categories[0]->name . '</a>';
	}
	if ($categories && $categories[0] && $types && $types[0]) {
		$taxonomies .= '<span class="' . $bc . '__crumbs-divider"></span>';
	}
	if ($types && $types[0]) {
		$taxonomies .= '<a href="' . get_term_link($types[0], 'types') . '" class="' . $bc . '__crumbs-ancestor">' . $types[0]->name . '</a>';
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-12 large-10 large-offset-1 xlarge-9">';
	if ($title) {
		$o .= '<div class="' . $bc . '__content fade-in-up">';
		$o .= '<div class="' . $bc . '__crumbs">';
		$o .= $taxonomies;
		$o .= '</div>'; // $bc__crumbs
		$o .= '<h1 class="' . $bc . '__heading">' . remove_widow($title, false) . '</h1>';
		$o .= $sub_title ? '<span class="' . $bc . '__sub-title">' . $sub_title . '</span>' : '';
		$o .= '</div>'; // $bc__content
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insight Content
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function insight_content($args, $categories, $types)
{
	$bc = 'insight-content';
	$id = $args["id"];
	$post = get_post($id);
	$url = get_the_permalink($id);
	$post_date = date('F j Y', strtotime($post->post_date));
	$person_id = get_post_meta($id, 'author', true);
	$author_name = $person_id ? get_the_title($person_id) : '';
	$content = get_post_meta($id, 'content', true);
	$htmlcontent = apply_filters('the_content', $content);
	$htmlcontent = wpautop($htmlcontent);
	$intro_paragraph = substr($htmlcontent, 0, strpos($htmlcontent, '</p>') + 4);
	$main_article = substr($htmlcontent, strlen($intro_paragraph));
	$reading_time = estimateReadingTime($content, 200, false);
	$highlight = get_post_meta($id, 'highlight_colour', true);
	$taxonomies = '';
	if ($categories && $categories[0]) {
		$taxonomies .= '<a href="' . get_term_link($categories[0], 'category') . '" class="' . $bc . '__meta-taxonomies-link">' . $categories[0]->name . '</a>';
	}
	if ($types && $types[0]) {
		$taxonomies .= '<a href="' . get_term_link($types[0], 'types') . '" class="' . $bc . '__meta-taxonomies-link">' . $types[0]->name . '</a>';
	}
	$download_count = get_post_meta($id, 'downloads', true);
	$downloads = '';
	for ($i = 0; $i < $download_count; $i++) {
		$download_title = get_post_meta($id, 'downloads_' . $i . '_title', true);
		$download_description = get_post_meta($id, 'downloads_' . $i . '_description', true);
		$download_document_id = get_post_meta($id, 'downloads_' . $i . '_document', true);
		$download_document_url = wp_get_attachment_url($download_document_id);
		if ($download_title && $download_document_id) {
			$downloads .= '<div class="' . $bc . '__documents-download">';
			$downloads .= '<div class="' . $bc . '__documents-download-icon">';
			$downloads .= get_svg('download');
			$downloads .= '</div>'; // $bc__documents-download-icon
			$downloads .= $download_title ? '<h4 class="' . $bc . '__documents-download-title">' . esc_html($download_title) . '</h4>' : '';
			$downloads .= $download_description ? '<p class="' . $bc . '__documents-download-description">' . esc_html($download_description) . '</p>' : '';
			$downloads .= '<a href="' . esc_url($download_document_url) . '" class="' . $bc . '__documents-download-link" target="_blank" download>Download</a>';
			$downloads .= '</div>'; // $bc__documents-download
		}
	}
	$featured_figure_number = get_post_meta($id, 'featured_figure', true);
	$featured_figure = '';
	if ($featured_figure_number) {
		$thousands_separator = get_post_meta($id, 'featured_figure_thousands_separator', true);
		$featured_figure_number_length = mb_strlen($featured_figure_number);
		if ($thousands_separator) {
			$featured_figure_number = number_format($featured_figure_number, 2, '.', $thousands_separator);
			$featured_figure_number = rtrim(rtrim($featured_figure_number, 0), '.');
		}
		if (strpos($featured_figure_number, '.')) {
			$featured_figure_number_length = $featured_figure_number_length - 1;
		}
		$featured_figure_before = get_post_meta($id, 'featured_figure_before', true);
		$featured_figure_before_length = mb_strlen($featured_figure_before);
		$featured_figure_after = get_post_meta($id, 'featured_figure_after', true);
		$featured_figure_after_length = mb_strlen($featured_figure_after);
		$featured_figure_supset_length = $featured_figure_before_length + $featured_figure_after_length;
		$featured_figure_caption = get_post_meta($id, 'featured_figure_caption', true);
		$featured_figure .= '<div class="' . $bc . '__featured-figure fade-in-up">';
		$featured_figure .= '<div class="' . $bc . '__featured-figure-number ' . $bc . '__featured-figure-number--' . $featured_figure_number_length . ' ' . $bc . '__featured-figure-number-sup--' . $featured_figure_supset_length . '">';
		if ($featured_figure_before) {
			$featured_figure .= '<span class="' . $bc . '__featured-figure-number-sup">' . esc_html($featured_figure_before) . '</span>';
		}
		$featured_figure .= '<span class="' . $bc . '__featured-figure-number-amount">' . esc_html($featured_figure_number) . '</span>';
		if ($featured_figure_after) {
			$featured_figure .= '<span class="' . $bc . '__featured-figure-number-sup">' . esc_html($featured_figure_after) . '</span>';
		}
		$featured_figure .= '</div>'; // $bc__featured-figure-number
		$featured_figure .= $featured_figure_caption ? '<span class="' . $bc . '__featured-figure-caption">' . remove_widow(esc_html($featured_figure_caption)) . '</span>' : '';
		$featured_figure .= '</div>'; // $bc__featured-figure
	}
	$related_service = '';
	$related_service_page_id = get_post_meta($id, 'related_service', true);
	if ($related_service_page_id) {
		$related_service_title = get_post_meta($id, 'related_service_title', true);
		$related_service_cta_text = get_post_meta($id, 'related_service_cta_text', true);
		$related_service_cta_text ? $related_service_cta_text : 'Find out more';
		$related_service_link = [
			"title" => $related_service_cta_text,
			"url" => get_the_permalink($related_service_page_id),
			"target" => "_self"
		];
		$related_service .= $related_service_title ? '<h4 class="' . $bc . '__related-service-title">' . esc_html($related_service_title) . '</h4>' : '';
		$related_service .= arrow_link($related_service_link, $bc, true);
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-3">';
	$o .= '<div class="' . $bc . '__sidebar">';
	$o .= '<div class="' . $bc . '__meta fade-in-up">';
	$o .= '<div class="' . $bc . '__meta-date">';
	$o .= $post_date;
	$o .= '</div>'; // $bc__meta-date
	$o .= '<div class="' . $bc . '__meta-author">';
	$o .= $author_name ?: 'Source';
	$o .= '</div>'; // $bc__meta-author
	$o .= '<div class="' . $bc . '__meta-estimated-reading-time">';
	$o .= $reading_time . ' min read';
	$o .= '</div>'; // $bc__meta-author
	$o .= '<div class="' . $bc . '__meta-taxonomies">';
	$o .= $taxonomies;
	$o .= '</div>'; // $bc__meta-taxonomies
	$o .= '<div class="' . $bc . '__meta-sharing">';
	$o .= '<a href="https://www.facebook.com/sharer.php?u=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to FaceBook</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-facebook') . '</span>';
	$o .= '</a>';
	$o .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to LinkedIn</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-linkedin') . '</span>';
	$o .= '</a>';
	$o .= '<a href="https://twitter.com/share?url=' . esc_url($url) . '&text=' . esc_html($post->post_title) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to Twitter</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-twitter') . '</span>';
	$o .= '</a>';
	$o .= '<a href="mailto:?Subject=' . esc_html($post->post_title) . '&Body=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share via email</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon ' . $bc . '__meta-sharing-link-icon--email hide-for-sr">' . get_svg('share-email') . '</span>';
	$o .= '</a>';
	$o .= '</div>'; // $bc__meta-sharing
	$o .= '</div>'; // $bc__meta
	if ($featured_figure) {
		$o .= '<div class="show-for-large">';
		$o .= $featured_figure;
		$o .= '</div>';
	}
	if ($downloads) {
		$o .= '<div class="' . $bc . '__documents fade-in-up show-for-large">';
		$o .= $downloads;
		$o .= '</div>'; // $bc__documents
	}
	if ($related_service) {
		$o .= '<div class="' . $bc . '__related-service fade-in-up show-for-large">';
		$o .= $related_service;
		$o .= '</div>'; // $bc__related-service
	}
	$o .= '</div>'; // $bc__sidebar
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-7">';
	$o .= '<div class="' . $bc . '__intro fade-in-up">';
	$o .= $intro_paragraph;
	$o .= '</div>'; // $bc__intro
	if ($featured_figure) {
		$o .= '<div class="hide-for-large">';
		$o .= $featured_figure;
		$o .= '</div>';
	}
	$o .= '<div class="' . $bc . '__content fade-in-up">';
	$o .= $main_article;
	$o .= '</div>'; // $bc__content
	if ($downloads) {
		$o .= '<div class="' . $bc . '__documents fade-in-up hide-for-large">';
		$o .= $downloads;
		$o .= '</div>'; // $bc__documents
	}
	if ($related_service) {
		$o .= '<div class="' . $bc . '__related-service fade-in-up hide-for-large">';
		$o .= $related_service;
		$o .= '</div>'; // $bc__related-service
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insight Related Posts
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function insight_related_posts($args, $categories, $types)
{
	$bc = 'insight-related-posts';
	$id = $args["id"];
	$title = get_post_meta($id, 'related_posts_title', true);
	$category_id = false;
	if ($categories && isset($categories[0])) {
		if ($categories[0]->slug !== 'uncategorised') {
			$category_id = $categories[0]->term_id;
		}
	}
	$type_id = false;
	if ($types && isset($types[0])) {
		$type_id = $types[0]->term_id;
	}
	$posts = [];
	$numberposts = isset($args["related_posts_count"]) ? $args["related_posts_count"] : 3;
	$manually_chosen_posts = get_post_meta($id, 'override_automatic_related_posts', true);
	if ($manually_chosen_posts) {
		$fetched_posts_count = get_post_meta($id, 'related_posts', true);
		for ($i = 0; $i < $fetched_posts_count; $i++) {
			$posts[] = get_post_meta($id, 'related_posts_' . $i . '_post', true);
		}
	} else {
		$tax_query = [];
		if ($category_id) {
			$tax_query[] = [
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => [$category_id]
			];
		}
		if ($type_id) {
			if (!empty($tax_query)) {
				$relation = ["relation" => 'AND'];
				$tax_query = $relation + $tax_query;
			}
			$get_posts_args["type"] = $type_id;
			$tax_query[] = [
				'taxonomy' => 'types',
				'field' => 'term_id',
				'terms' => [$type_id]
			];
		}
		$get_posts_args = [
			'numberposts' => $numberposts,
			'exclude' => [$args["id"]],
			'orderby' => 'rand',
			'order' => 'ASC'
		];
		if (!empty($tax_query)) {
			$get_posts_args["tax_query"] = $tax_query;
		}
		$fetched_posts = get_posts($get_posts_args);
		foreach ($fetched_posts as $p) {
			$posts[] = $p->ID;
		}
	}
	if (empty($posts)) return;
	$cells = '';
	for ($i = 0; $i < count($posts); $i++) {
		if ($i === 0) {
			$cells .= '<div class="cell large-offset-1 large-4">';
			$cells .= insight_related_post_card($posts[$i]);
			$cells .= '</div>'; // .cell
		} else if ($i === 4) {
			$cells .= '<div class="cell large-offset-1 large-4">';
			$cells .= insight_related_post_card($posts[$i]);
			$cells .= '</div>'; // .cell
		} else {
			$cells .= '<div class="cell large-3 large-offset-1">';
			$cells .= insight_related_post_card($posts[$i]);
			$cells .= '</div>'; // .cell
		}
	}
	$o = '<section class="' . $bc . (isset($args["classes"]) ? ' ' . $args["classes"] : '') . ' fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	if ($title) {
		$o .= '<h2 class="' . $bc . '__heading">' . $title . '</h2>';
	}
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Insight Related Post Card
 * @param array $id
 * @return string
 */
function insight_related_post_card($id)
{
	$bc = 'insight-related-post-card';
	$title = get_the_title($id);
	// $image_id = get_post_thumbnail_id($id);
	$image_id = get_post_meta($id, 'thumbnail_image', true);
	$image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background']);
	$categories = get_the_category($id);
	$types = get_the_terms($id, 'types');
	$url = get_the_permalink($id);
	$o = '<aside class="' . $bc . '">';
	$o .= '<div class="' . $bc . '__image-wrap">';
	$o .= $image ? $image : '';
	if (isset($types[0]) && $types[0]->name === 'Podcast') {
		$o .= '<span class="' . $bc . '__image-podcast-indicator">';
		$o .= get_svg('podcast');
		$o .= '</span>';
	}
	$o .= '</div>'; // $bc__image-wrap
	$o .= '<div class="' . $bc . '__content-wrap">';
	if ($categories || $types) {
		$o .= '<div class="' . $bc . '__meta">';
		$o .= isset($categories[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($categories[0]->name) . '</span>' : '';
		$o .= isset($categories[0]) && isset($types[0]) ? '<span class="' . $bc . '__meta-separator">|</span>' : '';
		$o .= isset($types[0]) ? '<span class="' . $bc . '__meta-category">' . esc_html($types[0]->name) . '</span>' : '';
		$o .= '</div>'; // $bc__meta
	}
	$o .= '<h3 class="' . $bc . '__title">';
	$o .= '<a href="' . esc_url($url) . '" class="' . $bc . '__link">';
	$o .= esc_html($title);
	$o .= '</a>';
	$o .= '</h3>';
	$o .= '</div>'; // $bc__content-wrap
	$o .= '</aside>';
	return $o;
}

/**
 * Podcast
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function podcast($args, $categories, $types)
{
	$args["related_posts_count"] = 6;
	$args["classes"] = 'podcast-related-insights';
	$show_subscribe = get_post_meta($args["id"], 'show_subscribe_call_out', true);
	$subscribe_call_out = '';
	if ($show_subscribe) {
		$subscribe_use_global_settings = get_post_meta($args["id"], 'subscribe_call_out_use_global_settings', true);
		$fields = [];
		if ($subscribe_use_global_settings) {
			$fields["text"] = get_option('options_subscribe_call_out_text');
			$fields["link"] = get_option('options_subscribe_call_out_link');
			$fields["highlight"] = get_option('options_subscribe_call_out_highlight_colour');
		} else {
			$fields["text"] = get_post_meta($args["id"], 'subscribe_call_out_text', true);
			$fields["link"] = get_post_meta($args["id"], 'subscribe_call_out_link', true);
			$fields["highlight"] = get_post_meta($args["id"], 'subscribe_call_out_highlight_colour', true);
		}
		$subscribe_call_out = subscribe_call_out($args["id"], false, false, false, $fields);
	}
	$o = podcast_hero($args, $categories, $types);
	$o .= podcast_content($args, $categories, $types);
	$o .= $subscribe_call_out;
	$o .= insight_related_posts($args, $categories, $types);
	return $o;
}

/**
 * Podcast Hero
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function podcast_hero($args, $categories, $types)
{
	$bc = 'podcast-hero';
	$id = $args["id"];
	$page_title = get_the_title($id);
	$title_override = get_post_meta($id, 'title_override', true);
	$title = $title_override ? $title_override : $page_title;
	$sub_title = get_post_meta($id, 'sub_title', true);
	$highlight = get_post_meta($id, 'highlight_colour', true);
	$hero_image_id = get_post_meta($id, 'hero_image', true);
	$hero_image = wp_get_attachment_image($hero_image_id, 'size', false, ['class' => 'img-background show-for-large']);
	$taxonomies = '';
	if ($categories && $categories[0]) {
		$taxonomies .= '<a href="' . get_term_link($categories[0], 'category') . '" class="' . $bc . '__crumbs-ancestor">' . $categories[0]->name . '</a>';
	}
	if ($categories && $categories[0] && $types && $types[0]) {
		$taxonomies .= '<span class="' . $bc . '__crumbs-divider"></span>';
	}
	if ($types && $types[0]) {
		$taxonomies .= '<a href="' . get_term_link($types[0], 'types') . '" class="' . $bc . '__crumbs-ancestor">' . $types[0]->name . '</a>';
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1">';
	$o .= '<div class="' . $bc . '__inner">';
	$o .= $hero_image;
	if ($title) {
		$o .= '<div class="' . $bc . '__content fade-in-up">';
		$o .= '<div class="' . $bc . '__content-text">';
		$o .= '<div class="' . $bc . '__crumbs">';
		$o .= $taxonomies;
		$o .= '</div>'; // $bc__crumbs
		$o .= '<div class="' . $bc . '__heading-wrap">';
		$o .= '<h1 class="' . $bc . '__heading">' . $title . '</h1>';
		$o .= '</div>'; // $bc__heading-wrap
		$o .= $sub_title ? '<div class="' . $bc . '__sub-title-wrap"><span class="' . $bc . '__sub-title">' . $sub_title . '</span></div>' : '';
		$o .= '</div>'; // $bc__content-text
		$o .= '</div>'; // $bc__content
	}
	$o .= podcast_player($id);
	$o .= '</div>'; // $bc__inner
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Podcast Player
 * @param int $id
 * @return string
 */
function podcast_player($id)
{
	$bc = 'podcast-player';
	$podcast_url = get_post_meta($id, 'podcast_url', true);
	if (!$podcast_url) return;
	$show_art_id = get_post_meta($id, 'podcast_show_art', true);
	if (!$show_art_id) {
		$show_art_id = get_option('options_podcast_default_show_art', true);
	}
	$show_art = '';
	if ($show_art_id) {
		$show_art = wp_get_attachment_image($show_art_id, 'sm', false, ['class' => 'img-background']);
	}
	$o = '<aside class="' . $bc . ' fade-in-up" data-post="' . $id . '">';
	$o .= '<div class="' . $bc . '__loading"><div class="loader"><span></span><span></span><span></span><span></span></div></div>';
	$o .= '<audio src="' . esc_url($podcast_url) . '" class="' . $bc . '__audio" preload="metadata"></audio>';
	$o .= '<div class="' . $bc . '__show-art">';
	$o .= $show_art ? $show_art : '';
	$o .= '</div>'; // $bc__show-art
	$o .= '<button class="' . $bc . '__play-pause-toggle">';
	$o .= '<span class="show-for-sr ' . $bc . '__play-pause-toggle-text">Play</span>';
	$o .= '<span class="' . $bc . '__play-pause-toggle-icon-play">' . get_svg('podcast-play') . '</span>';
	$o .= '<span class="' . $bc . '__play-pause-toggle-icon-pause">' . get_svg('podcast-pause') . '</span>';
	$o .= '</button>';
	$o .= '<div class="' . $bc . '__progress-wrap">';
	$o .= '<div class="' . $bc . '__progress">';
	$o .= '<span class="' . $bc . '__progress-bar"></span>';
	$o .= '</div>'; // $bc__progress
	$o .= '<div class="' . $bc . '__controls">';
	$o .= '<button class="' . $bc . '__controls-seek ' . $bc . '__controls-seek--reverse" title="Skip Back 30 Seconds">';
	$o .= '<span class="show-for-sr">Skip Back 30 Seconds</span>';
	$o .= '<span class="' . $bc . '__controls-seek-number hide-for-sr">30</span>';
	$o .= '<span class="' . $bc . '__controls-seek-icon hide-for-sr">' . get_svg('podcast-reverse') . '</span>';
	$o .= '</button>'; // $bc__controls-seek
	$o .= '<div class="' . $bc . '__controls-progress">';
	$o .= '<div class="' . $bc . '__controls-progress-current" aria-label="Current time">00:00:00</div>';
	$o .= '<span>/</span>';
	$o .= '<div class="' . $bc . '__controls-progress-duration" aria-label="Duration">00:00:00</div>';
	$o .= '</div>'; // $bc__controls-progress
	$o .= '<button class="' . $bc . '__controls-seek ' . $bc . '__controls-seek--forward" title="Skip Forward 30 Seconds">';
	$o .= '<span class="show-for-sr">Skip Forward 30 Seconds</span>';
	$o .= '<span class="' . $bc . '__controls-seek-number hide-for-sr">30</span>';
	$o .= '<span class="' . $bc . '__controls-seek-icon hide-for-sr">' . get_svg('podcast-forward') . '</span>';
	$o .= '</button>'; // $bc__controls-seek
	$o .= '</div>'; // $bc__controls
	$o .= '</div>'; // $bc__progress-wrap
	$o .= '</aside>'; // $bc
	return $o;
}

/**
 * Insight Content
 * @param array $args
 * @param array $categories
 * @param array $types
 * @return string
 */
function podcast_content($args, $categories, $types)
{
	$bc = 'insight-content';
	$id = $args["id"];
	$post = get_post($id);
	$url = get_the_permalink($id);
	$post_date = date('F j Y', strtotime($post->post_date));
	$hosts = get_post_meta($id, 'podcast_hosts', true);
	$duration = get_post_meta($id, 'podcast_duration', true);
	$apple_podcasts_url = get_post_meta($id, 'podcast_listen_on_apple_podcasts_url', true);
	$google_podcasts_url = get_post_meta($id, 'podcast_listen_on_google_podcasts_url', true);
	$spotify_podcasts_url = get_post_meta($id, 'podcast_listen_on_spotify_url', true);
	$content = get_post_meta($id, 'content', true);
	$htmlcontent = apply_filters('the_content', $content);
	$htmlcontent = wpautop($htmlcontent);
	$intro_paragraph = substr($htmlcontent, 0, strpos($htmlcontent, '</p>') + 4);
	$main_article = substr($htmlcontent, strlen($intro_paragraph));
	$highlight = get_post_meta($id, 'highlight_colour', true);
	$taxonomies = '';
	if ($categories && $categories[0]) {
		$taxonomies .= '<a href="' . get_term_link($categories[0], 'category') . '" class="' . $bc . '__meta-taxonomies-link">' . $categories[0]->name . '</a>';
	}
	if ($types && $types[0]) {
		$taxonomies .= '<a href="' . get_term_link($types[0], 'types') . '" class="' . $bc . '__meta-taxonomies-link">' . $types[0]->name . '</a>';
	}
	$o = '<section class="' . $bc . ' ' . $bc . '--podcast' . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-3 xlarge-2">';
	$o .= '<div class="' . $bc . '__sidebar">';
	$o .= '<div class="' . $bc . '__meta fade-in-up">';
	$o .= '<div class="' . $bc . '__meta-date">';
	$o .= $post_date;
	$o .= '</div>'; // $bc__meta-date
	if ($hosts) {
		$o .= '<div class="' . $bc . '__meta-author">';
		$o .= $hosts;
		$o .= '</div>'; // $bc__meta-author
	}
	if ($duration) {
		$o .= '<div class="' . $bc . '__meta-estimated-reading-time">';
		$o .= $duration . ' min listen';
		$o .= '</div>'; // $bc__meta-estimated-reading-time
	}
	$o .= '<div class="' . $bc . '__meta-sharing">';
	$o .= '<a href="https://www.facebook.com/sharer.php?u=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to FaceBook</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-facebook') . '</span>';
	$o .= '</a>';
	$o .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to LinkedIn</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-linkedin') . '</span>';
	$o .= '</a>';
	$o .= '<a href="https://twitter.com/share?url=' . esc_url($url) . '&text=' . esc_html($post->post_title) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share to Twitter</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon hide-for-sr">' . get_svg('share-twitter') . '</span>';
	$o .= '</a>';
	$o .= '<a href="mailto:?Subject=' . esc_html($post->post_title) . '&Body=' . esc_url($url) . '" target="_blank" class="' . $bc . '__meta-sharing-link">';
	$o .= '<span class="show-for-sr">Share via email</span>';
	$o .= '<span class="' . $bc . '__meta-sharing-link-icon ' . $bc . '__meta-sharing-link-icon--email hide-for-sr">' . get_svg('share-email') . '</span>';
	$o .= '</a>';
	$o .= '</div>'; // $bc__meta-sharing
	$o .= '<div class="' . $bc . '__meta-taxonomies">';
	$o .= $taxonomies;
	$o .= '</div>'; // $bc__meta-taxonomies
	if ($apple_podcasts_url || $google_podcasts_url || $spotify_podcasts_url) {
		$o .= '<div class="' . $bc . '__meta-listen-on">';
		$o .= '<span class="' . $bc . '__meta-listen-on-title">Listen on</span>';
		$o .= '<div class="' . $bc . '__meta-listen-on-links">';
		if ($apple_podcasts_url) {
			$o .= '<a href="' . esc_url($apple_podcasts_url) . '" target="_blank" class="' . $bc . '__meta-listen-on-link" title="Listen on Apple Podcasts">';
			$o .= get_svg('apple-podcasts');
			$o .= '</a>';
		}
		if ($google_podcasts_url) {
			$o .= '<a href="' . esc_url($google_podcasts_url) . '" target="_blank" class="' . $bc . '__meta-listen-on-link" title="Listen on Google Podcasts">';
			$o .= get_svg('google-podcasts');
			$o .= '</a>';
		}
		if ($spotify_podcasts_url) {
			$o .= '<a href="' . esc_url($spotify_podcasts_url) . '" target="_blank" class="' . $bc . '__meta-listen-on-link" title="Listen on Spotify">';
			$o .= get_svg('spotify');
			$o .= '</a>';
		}
		$o .= '</div>'; // $bc__meta-list-on-links
		$o .= '</div>'; // $bc__meta-list-on
	}
	$o .= '</div>'; // $bc__meta

	$o .= '</div>'; // $bc__sidebar
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-7 xlarge-offset-2 fade-in-up">';
	$o .= '<div class="' . $bc . '__intro">';
	$o .= $intro_paragraph;
	$o .= '</div>'; // $bc__intro
	if (trim($main_article)) {
		$o .= '<div class="' . $bc . '__content">';
		$o .= $main_article;
		$o .= '</div>'; // $bc__content
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Person Page
 * @param array $args
 * @return string
 */
function person_page($args)
{
	$user = get_post_meta($args["id"], 'user', true);
	$highlight = get_post_meta($args["id"], 'highlight_colour', true);
	$args["highlight"] = $highlight;
	$args["user_id"] = $user;
	$o = person_hero($args);
	$o .= person_about($args);
	$o .= person_quote($args);
	$o .= person_insights($args);
	return $o;
}

/**
 * Person Hero
 * @param array $args
 * @return string
 */
function person_hero($args)
{
	$bc = 'person-hero';
	$id = $args["id"];
	$title = get_the_title($id);
	$role = get_post_meta($id, 'role', true);
	$hero_image_id = get_post_meta($id, 'hero_image', true);
	$hero_image_id = $hero_image_id ? $hero_image_id : get_post_thumbnail_id($id);
	$hero_image = wp_get_attachment_image($hero_image_id, 'size', false, ['class' => 'img-background']);
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-6 xlarge-5">';
	if ($title) {
		$o .= '<div class="' . $bc . '__content fade-in-up">';
		$o .= '<div class="' . $bc . '__crumbs">';
		$o .= $role;
		$o .= '</div>'; // $bc__crumbs
		$o .= '<h1 class="' . $bc . '__heading">' . $title . '</h1>';
		$o .= '</div>'; // $bc__content
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-10 medium-offset-4 large-7 large-offset-0 xlarge-8">';
	if ($hero_image) {
		$o .= '<div class="' . $bc . '__image-wrap fade-in-up">';
		$o .= $hero_image;
		$o .= '</div>'; // $bc__image-wrap
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Person about
 * @param array $args
 * @return string
 */
function person_about($args)
{
	$bc = 'person-about';
	$id = $args["id"];
	$highlight = $args["highlight"];
	$first_name = $args["user_id"] ? get_user_meta($args["user_id"], 'first_name', true) : '';
	$title = 'About';
	$title = $first_name ? $title . ' ' . $first_name : $title;
	$about = get_post_meta($id, 'about', true);
	$expertise_count = get_post_meta($id, 'expertise', true);
	$expertise_title = $first_name ? $first_name . '&rsquo;s Expertise' : 'Expertise';
	$expertise_items = '';
	for ($i = 0; $i < $expertise_count; $i++) {
		$expertise_row = get_post_meta($id, 'expertise_' . $i . '_expertise', true);
		$expertise_items .= '<li class="' . $bc . '__expertise-list-item">' . esc_html($expertise_row) . '</li>';
	}
	$links = '';
	$facebook = get_post_meta($id, 'facebook', true);
	if ($facebook) {
		$links .= '<li class="' . $bc . '__expertise-links-item ' . $bc . '__expertise-links-item--facebook">';
		$links .= '<a href="' . esc_url($facebook["url"]) . '" target="_blank" class="' . $bc . '__links-item-link">';
		$links .= '<span class="show-for-sr">' . esc_html($facebook["title"]) . '</span>';
		$links .= '<span class="' . $bc . '__expertise-links-item-link-icon">' . get_svg('share-facebook') . '</span>';
		$links .= '</a>';
		$links .= '</li>';
	}
	$linkedin = get_post_meta($id, 'linkedin', true);
	if ($linkedin) {
		$links .= '<li class="' . $bc . '__expertise-links-item ' . $bc . '__expertise-links-item--linkedin">';
		$links .= '<a href="' . esc_url($linkedin["url"]) . '" target="_blank" class="' . $bc . '__links-item-link">';
		$links .= '<span class="show-for-sr">' . esc_html($linkedin["title"]) . '</span>';
		$links .= '<span class="' . $bc . '__expertise-links-item-link-icon">' . get_svg('share-linkedin') . '</span>';
		$links .= '</a>';
		$links .= '</li>';
	}
	$twitter = get_post_meta($id, 'twiter', true);
	if ($twitter) {
		$links .= '<li class="' . $bc . '__expertise-links-item ' . $bc . '__expertise-links-item--twitter">';
		$links .= '<a href="' . esc_url($twitter["url"]) . '" target="_blank" class="' . $bc . '__links-item-link">';
		$links .= '<span class="show-for-sr">' . esc_html($twitter["title"]) . '</span>';
		$links .= '<span class="' . $bc . '__expertise-links-item-link-icon">' . get_svg('share-twitter') . '</span>';
		$links .= '</a>';
		$links .= '</li>';
	}
	$email = get_post_meta($id, 'contact_email', true);
	if ($email) {
		$links .= '<li class="' . $bc . '__expertise-links-item ' . $bc . '__expertise-links-item--email">';
		$links .= '<a href="' . esc_url($email["url"]) . '" target="_blank" class="' . $bc . '__links-item-link">';
		$links .= '<span class="show-for-sr">' . esc_html($email["title"]) . '</span>';
		$links .= '<span class="' . $bc . '__expertise-links-item-link-icon">' . get_svg('share-email') . '</span>';
		$links .= '</a>';
		$links .= '</li>';
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-8 large-offset-1 fade-in-up">';
	if ($about) {
		$o .= '<h2 class="' . $bc . '__about-title">' . esc_html($title) . '</h2>';
		$o .= '<div class="' . $bc . '__about-content">' . wpautop($about) . '</div>';
	}
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-4 large-offset-1 fade-in-up">';
	$o .= '<div class="' . $bc . '__expertise' . ($expertise_items ? ' ' . $bc . '__expertise--border' : '') . '">';
	if ($expertise_items) {
		$o .= '<h3 class="' . $bc . '__expertise-title">' . esc_html($expertise_title) . '</h3>';
		$o .= '<ul class="' . $bc . '__expertise-list">';
		$o .= $expertise_items;
		$o .= '</ul>';
	}
	if ($links) {
		$o .= '<ul class="' . $bc . '__expertise-links">';
		$o .= $links;
		$o .= '</ul>';
	}
	$o .= '</div>'; // $bc__expertise
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Person quote
 * @param array $args
 * @return string
 */
function person_quote($args)
{
	$bc = 'person-quote';
	$id = $args["id"];
	$highlight = $args["highlight"];
	$pullquote = get_post_meta($id, 'pullquote', true);
	if (!$pullquote) return;
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-12 large-offset-1 xlarge-10 xlarge-offset-2 fade-in-up">';
	$o .= '<div class="' . $bc . '__wrap">';
	$o .= '<div class="' . $bc . '__quote-marks">' . get_svg('quote-marks') . '</div>';
	$o .= '<figure class="' . $bc . '__fig">';
	$o .= '<blockquote class="' . $bc . '__quote">' . $pullquote . '</blockquote>';
	$o .= '</figure>';
	$o .= '</div>'; // $bc__wrap
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Person insights
 * @param array $args
 * @return string
 */
function person_insights($args)
{
	$bc = 'person-insights';
	$id = $args["id"];
	$user_id = $args["user_id"];
	// $first_name = $args["user_id"] ? get_user_meta($args["user_id"], 'first_name', true) : '';
	$name = get_the_title($id);
	$title = $name ? $name . '&rsquo;s latest insights' : 'Latest insights';
	$posts = get_posts([
		'numberposts' => 3,
		'meta_key' => 'author',
		'meta_value' => $id,
	]);
	$o = '<div class="' . $bc . '">';
	if ($posts) {
		$o .= insights_row($posts, $title);
	}
	$o .= '</div>';
	return $o;
}

/**
 * Simple
 * @param array $args
 * @return string
 */
// function simple($args) {
// 	$bc = 'simple';
// 	$title = get_the_title($args["id"]);
// 	$content = get_the_content($args["id"]);
// 	$o = '';
// 	$o .= '<section class="' . $bc . ' slide-in">';
// 	$o .= '<div class="grid-container">';
// 	$o .= '<div class="grid-x grid-padding-x">';
// 	$o .= '<div class="cell medium-2">';
// 	$o .= $title ? '<h1 class="' . $bc . '__title primary-title">' . esc_html($title) . '</h1>' : '';
// 	$o .= '</div>'; // .cell
// 	$o .= '<div class="cell medium-2">';
// 	if ($content) {
// 		$o .= '<div class="' . $bc . '__content body-copy">';
// 		$o .= wpautop($content);
// 		$o .= '</div>';
// 	}
// 	$o .= '</div>'; // .cell
// 	$o .= '</div>'; // .grid-x
// 	$o .= '</div>'; // .grid-container
// 	$o .= '</section>';
// 	return $o;
// }

/**
 * Policy
 * @param object $post
 * @return string
 */
// function policy($id) {
// 	$bc = 'policy';
// 	$title = get_post_meta($id, 'title', true);
// 	$intro = get_post_meta($id, 'intro', true);
// 	$sections_count = get_post_meta($id, 'sections', true);
// 	$sections = '';
// 	for ($i = 0; $i < $sections_count; $i++) {
// 		$section_title = get_post_meta($id, 'sections_' . $i . '_section_title', true);
// 		$section_content = get_post_meta($id, 'sections_' . $i . '_section_title_copy', true);
// 		if ($section_content) {
// 			$sections .= '<div class="' . $bc . '__section">';
// 			$sections .= '<div class="grid-container">';
// 			$sections .= '<div class="grid-x grid-padding-x">';
// 			$sections .= '<div class="cell show-for-xlarge">';
// 			$sections .= '<span class="hr"></span>';
// 			$sections .= '</div>'; // .cell
// 			$sections .= '<div class="cell xlarge-4">';
// 			$sections .= '<div class="' . $bc . '__section-title-wrap">';
// 			$sections .= $section_title ? '<h2 class="' . $bc . '__section-title small-caps">' . esc_html($section_title) . '</h2>' : '';
// 			$sections .= '</div>'; // $bc__section-title-wrap
// 			$sections .= '</div>'; // .cell
// 			$sections .= '<div class="cell xlarge-8 xxlarge-7">';
// 			if ($section_content) {
// 				$sections .= '<div class="' . $bc . '__section-content">';
// 				$sections .= wpautop($section_content);
// 				$sections .= '</div>'; // $bc__title-wrap
// 			}
// 			$sections .= '</div>'; // .cell
// 			$sections .= '</div>'; // .grid-x
// 			$sections .= '</div>'; // .grid-container
// 			$sections .= '</div>'; // $bc__section
// 		}
// 	}
// 	$o = '';
// 	$o .= '<section class="' . $bc . '">';
// 	if ($title || $intro) {
// 		$o .= '<div class="grid-container">';
// 		$o .= '<div class="grid-x grid-padding-x">';
// 		$o .= '<div class="cell show-for-xlarge">';
// 		$o .= '<span class="hr"></span>';
// 		$o .= '</div>'; // .cell
// 		$o .= '<div class="cell xlarge-4">';
// 		$o .= '<div class="' . $bc . '__title-wrap">';
// 		$o .= $title ? '<h1 class="' . $bc . '__title small-caps">' . esc_html($title) . '</h1>' : '';
// 		$o .= '</div>'; // $bc__title-wrap
// 		$o .= '</div>'; // .cell
// 		$o .= '<div class="cell xlarge-8 xxlarge-7">';
// 		if ($intro) {
// 			$o .= '<div class="' . $bc . '__intro-wrap lead-text">';
// 			$o .= wpautop($intro);
// 			$o .= '</div>'; // $bc__title-wrap
// 		}
// 		$o .= '</div>'; // .cell
// 		$o .= '</div>'; // .grid-x
// 		$o .= '</div>'; // .grid-container
// 	}
// 	$o .= $sections;
// 	$o .= '</section>';
// 	return $o;
// }

/**
 * Error 404
 * @return string
 */
function error_404()
{
	$bc = 'simple';
	$title = 'Error 404';
	$content = 'Error 404: Page not found. The requested page could not be found.';
	$o = '';
	$o .= '<section class="' . $bc . ' slide-in">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell medium-2">';
	$o .= $title ? '<h1 class="' . $bc . '__title primary-title">' . esc_html($title) . '</h1>' : '';
	$o .= '</div>'; // .cell
	$o .= '<div class="cell medium-2">';
	if ($content) {
		$o .= '<div class="' . $bc . '__content body-copy">';
		$o .= wpautop($content);
		$o .= '</div>';
	}
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/*
New Developement Customization Code
*/
/**
 * Dev Testing
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function dev_testing($id, $block, $number)
{
	$bc = 'dev-testing';
	$dev_title = get_post_meta($id, 'flexible_blocks_' . $block . '_dev_title', true);
	$dev_dic = get_post_meta($id, 'flexible_blocks_' . $block . '_dev_dic', true);
	
	$o = '<p class="' . $bc . '">'.$dev_title.'</p>';
	
	
	$o .= '<p>'.$dev_dic.'</p>';
	return $o;
	
}

/**
 * Dev White Space
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function dev_white_space($id, $block, $number)
{
	$bc = 'dev-white-space';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	
	$o = '<section class="' . $bc . '">';
	$o .= '<h1>'.$title.'</h1>';
	$o .= 'Your Code is hear';
	
	$o .= '<section>';
	return $o;
}

/**
 * Dev Trends
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function dev_trends($id, $block, $number)
{
	$bc = 'dev-trends';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	
	$o = '<section class="' . $bc . '">';
	$o .= '<h1>'.$title.'</h1>';
	$o .= 'Your Code is hear';
	
	$o .= '<section>';
	return $o;
}

/**
 * what is white space
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function what_is_white_space($id, $block, $number)
{
	
	$bc = 'what-is-white-space';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$description = get_post_meta($id, 'flexible_blocks_' . $block . '_description', true);
	$link = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
	$end_link = get_post_meta($id, 'flexible_blocks_' . $block . '_end_cta_link', true);
	$button_text = get_post_meta($id, 'flexible_blocks_' . $block . '_button_text', true);
	
	$highlight = get_post_meta($id, 'flexible_blocks_' . $block . '_highlight_colour', true);
	$mobile_content_cols = '';
	$right_col_content = '';
	$left_col_content = '';
	$desktop_content_cols = '';
	
	switch ($highlight) {
		case 'red':
			$highlight_colour = '#FF004C';
			break;
		case 'purple':
			$highlight_colour = '#A600FF';
			break;
		case 'yellow':
			$highlight_colour = '#FFBB00';
			break;
		case 'blue':
			$highlight_colour = '#00DEDE';
			break;
		case 'green':
			$highlight_colour = '#00F07C';
			break;

		default:
			$highlight_colour = false;
			break;
	}
	$o = '<section class="' . $bc . ($highlight ? ' ' . $bc . '--' . $highlight : '') . '"' . ($highlight_colour ? ' data-highlight="' . $highlight_colour . '"' : '') . '>';
	$o .= '<div class="' . $bc . '__head fade-in-up">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-5 large-offset-1">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-7">';
	$o .= $description ?  $description  : '';
	//$o .= $description ? '<p class="' . $bc . '__description">' . $description . '</p>' : '';
	//$o .= $link ? '<div class="' . $bc . '__link">' . arrow_link($link, $bc, true) . '</div>' : '';
	$o .= $button_text ? '<div class="' . $bc . '__link">'.'<a id="mypopup" href="javascript:void(0)" class="arrow-link  arrow-link--dark ' . $bc . '__cta-link"><span class="arrow-link__inner"><span class="arrow-link__text ' . $bc . '__cta-link-text">' . $button_text . '</span><span class="arrow-link__arrow what-is-white-space__cta-link-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 178 14.728">
  <path id="arrow" class="arrow" d="M177.707 8.071a.999.999 0 0 0 0-1.414L171.343.293a.999.999 0 1 0-1.414 1.414l5.657 5.657-5.657 5.657a.999.999 0 1 0 1.414 1.414l6.364-6.364Z" fill="#fff"></path><path id="line" class="line" d="M0 6.364h177v2H0z" fill="#fill"></path>
</svg></span></span></a>'.'</div>' : '';
	
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</div>'; // $bc__head
	
	
	$o .= '</section>';
	return $o;
}

/**
 * White Space Services
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function white_space_services($id, $block, $number)
{
	$bc = 'white-space-services';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);

	$count = get_post_meta($id, 'flexible_blocks_' . $block . '_services', true);
	$services_page_id = intval(get_option('options_services_page'));
	$cells = '';
	for ($i = 0; $i < $count; $i++) {
		$service_title = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service_title', true);
		$service_subtitle = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service_subtitle', true);
		$link = get_post_meta($id, 'flexible_blocks_' . $block . '_services_' . $i . '_service_link', true);
		$link_highlight = fetchServiceHighlight($link);
		if (!$service_title || !$link) continue;
		$cells .= '<div class="' . $bc . '__cell ' . $bc . '__cell--' . $i . ' ' . 'cell medium-7 large-3' . ($i === 0 ? ' large-offset-1' : '') . '">';
		$cells .= '<div class="' . $bc . '__service">';
		$cells .= '<h5 class="' . $bc . '__service-title">' . esc_html($service_title) . '</h3>';
		$cells .= '<h3 class="' . $bc . '__service-subtitle">' . esc_html($service_subtitle) . '</h3>';
		$cells .= arrow_link($link, $bc, true, false, $link_highlight);
		$cells .= '</div>'; // $bc__service
		$cells .= '</div>'; // .cell
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2 class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}


/**
 * Latest Post for White Space
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function latest_posts_white_space($id, $block, $number)
{

  
  $bc='latest-posts-white-space';
  $title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
  $cta = get_post_meta($id, 'flexible_blocks_' . $block . '_link', true);
  $featured_items_count = get_post_meta($id, 'flexible_blocks_' . $block . '_featured_items', true);

  $featured_items = [];

  for ($i = 0; $i < $featured_items_count; $i++) {
    $featured_item = ['featured-item-image' => 0, 'featured-item-image-img' => ''];
    $featured_post_id = get_post_meta($id, 'flexible_blocks_' . $block . '_featured_items_' . $i . '_post', true);

    if ($featured_post_id) {
      //$featured_post = get_post($featured_post_id);
      $featured_item['title'] = get_the_title($featured_post_id);
      $featured_item['link'] = get_the_permalink($featured_post_id);
      $featured_item['featured-item-image'] = intval(get_post_meta($featured_post_id, 'thumbnail_image', true));
    }
    foreach (['link', 'title', 'featured-item-image', 'featured-item-blurb'] as $k) {
      $v = get_post_meta($id, 'flexible_blocks_' . $block . '_featured_items_' . $i . '_' . $k, true);
      if ($v) {
        if ($k == 'featured-item-image') {
          $v = intval($v);
        }
        $featured_item[$k] = $v;
      }
    }
    if ($featured_item['featured-item-image']) {
      $featured_item['featured-item-image-img'] = wp_get_attachment_image($featured_item['featured-item-image'], 'medium', false, ['class' => 'img-background  latest-posts-white-space__image', 'alt' => $featured_item['title']]);
    }
    $featured_items[] = $featured_item;
  }
  $type_filter = $_REQUEST['type'] ?? false;
  ob_start(); 

  $lastest_post_data = '';
	foreach ($featured_items as $featured_item) {
		$post_link=$featured_item['link'];
		$post_image=$featured_item['featured-item-image-img'];
		$post_title=$featured_item['title'];
		$post_blurb=$featured_item['featured-item-blurb'];
		$lastest_post_data .= '<div class=" cell medium-4 medium-offset-1">
                            <div class="' . $bc . '__item">';

        $lastest_post_data .= '<a class="' . $bc . '__link" href="'.$post_link.'">'.$post_image.'</a>';  
        $lastest_post_data .= '<a href="'.$post_link.'"><h4 class="' . $bc . '-card__title insights-row-card__title">'.$post_title.'</h4></a>'; 
        $lastest_post_data .='<p class="' . $bc . '__blurb">'.$post_blurb.'</p>';

		$lastest_post_data .= '  </div>
          				  </div>';


  }

  $o = '';
  $o .= '<section class="' . $bc .'">';
  $o .= '<div class="grid-container">';
  $o .= '<div class="grid-x grid-padding-x">';
  $o .= '<div class="cell medium-2 medium-offset-1">';
  $o .= '<h2 class="' . $bc . '__title">'.$title.'</h2>';
  if ($cta) {
      $o .= arrow_link($cta, $bc);
  }
  $o .= '</div>';
  $o .= $lastest_post_data;
  $o .= '</div>';
  $o .= '</div>';
  $o .= '</section>';
  return $o;
  
}



/**
 * Staggered Text Columns White Space
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function staggered_text_columns_white_space($id, $block, $number)
{
	$bc = 'about-ctas';
	$bc2 = 'staggered-text-columns-white-space';
	$cta_1_title = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_title', true);
	$cta_1_text = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_text', true);
	$cta_1_link = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_link', true);
	$cta_1_image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_1_image', true);
	$cta_1 = '';
	if ($cta_1_text || $cta_1_link || $cta_1_image_id) {
		$image = $cta_1_image_id ? wp_get_attachment_image($cta_1_image_id, 'size', false, ['class' => 'img-background']) : false;
		$cta_1 .= '<div class="' . $bc . '__cta-1  ' . $bc2 . '__cta-1">';
		$cta_1 .= '<div class="' . $bc . '__cta-1-content-wrap ' . $bc2 . '__cta-1-content-wrap">';
		$cta_1 .= $cta_1_title ? '<h2 class="' . $bc . '__cta-1-title ' . $bc2 . '__cta-1-title">'.$cta_1_title.'</h2>' : '';
		$cta_1 .= $cta_1_text ? '<p class="' . $bc . '__cta-1-text ' . $bc2 . '__cta-1-text">' . remove_widow($cta_1_text) . '</p>' : '';
		$cta_1 .= $cta_1_link ? arrow_link($cta_1_link, $bc, true) : '';
		$cta_1 .= '</div>'; // $bc__cta-1-content-wrap
		$cta_1 .= $image ? '<div class="' . $bc . '__cta-1-image ' . $bc2 . '__cta-1-image">' . $image . '</div>' : '';
		$cta_1 .= '</div>'; // $bc__cta-1
	}
	$cta_2_title = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_title', true);
	$cta_2_text = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_text', true);
	$cta_2_link = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_link', true);
	$cta_2_image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_cta_2_image', true);
	$cta_2 = '';
	if ($cta_2_text || $cta_2_link || $cta_2_image_id) {
		$image = $cta_2_image_id ? wp_get_attachment_image($cta_2_image_id, 'size', false, ['class' => 'img-background']) : false;
		$cta_2 .= '<div class="' . $bc . '__cta-2  ' . $bc2 . '__cta-2">';
		$cta_2 .= $image ? '<div class="' . $bc . '__cta-2-image  ' . $bc2 . '__cta-2-image">' . $image . '</div>' : '';
		$cta_2 .= '<div class="' . $bc . '__cta-2-content-wrap  ' . $bc2 . '__cta-2-content-wrap">';
		$cta_2 .= $cta_2_title ? '<h2 class="' . $bc . '__cta-2-title ' . $bc2 . '__cta-2-title">'.$cta_2_title.'</h2>' : '';
		$cta_2 .= $cta_2_text ? '<p class="' . $bc . '__cta-2-text  ' . $bc2 . '__cta-2-text">' . remove_widow($cta_2_text) . '</p>' : '';
		$cta_2 .= $cta_2_link ? arrow_link($cta_2_link, $bc, true) : '';
		$cta_2 .= '</div>'; // $bc__cta-2-content-wrap
		$cta_2 .= '</div>'; // $bc__cta-2
	}
	$o = '<section class="' . $bc .' '.$bc2 .' ">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-2 large-11 fade-in-up">';
	$o .= $cta_1;
	$o .= '</div>'; // .cell
	$o .= '<div class="cell large-offset-1 large-11 fade-in-up">';
	$o .= $cta_2;
	$o .= '</div>'; // .cell
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}

/**
 * Brand Insights White Space
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function brand_insights_white_space($id, $block, $number)
{
	$bc = 'brand-insights';
	$title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
	$more_link = get_post_meta($id, 'flexible_blocks_' . $block . '_more_link', true);
	$posts_count = get_post_meta($id, 'flexible_blocks_' . $block . '_posts', true);
	$cells = '';
	for ($i = 0; $i < $posts_count; $i++) {
		$post = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_post', true);
		$title_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_title_override', true);
		$description_override = get_post_meta($id, 'flexible_blocks_' . $block . '_posts_' . $i . '_description_override', true);
		$cell_classes = '';
		if ($posts_count === '1') {
			$cell_classes .= 'large-3';
		} else if ($posts_count === '2') {
			if ($i === 0) {
				$cell_classes .= 'large-4 ' . $bc . '__card-cell-one';
			} else {
				$cell_classes .= 'large-3 ' . $bc . '__card-cell-two';
			}
		}else if ($posts_count === '3') {
			if ($i === 0) {
				$cell_classes .= 'large-4 ' . $bc . '__card-cell-one';
			} else {
				$cell_classes .= 'large-3 ' . $bc . '__card-cell-two';
			}
		}
		$cells .= '<div class="' . $bc . '__card-cell cell ' . $cell_classes . ' large-offset-1">';
		$cells .= brand_insights_card($post, $title_override, $description_override);
		$cells .= '</div>';
	}
	$o = '<section class="' . $bc . '">';
	$o .= '<div class="grid-container">';
	$o .= '<div class="grid-x grid-padding-x">';
	$o .= '<div class="cell large-offset-1 large-12">';
	$o .= '<div class="' . $bc . '__head">';
	$o .= $title ? '<h2  class="' . $bc . '__title">' . esc_html($title) . '</h2>' : '';
	$o .= $more_link ? arrow_link($more_link, $bc, true) : '';
	$o .= '</div>'; // $bc__head
	$o .= '</div>'; // .cell
	$o .= $cells;
	$o .= '</div>'; // .grid-padding-x
	$o .= '</div>'; // .grid-container
	$o .= '</section>';
	return $o;
}


/**
 * About
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function about($id, $block, $number){

    $flexible_content = get_field('flexible_blocks', $id);

    foreach ($flexible_content as $layout) {
        if ($layout['acf_fc_layout'] === 'about') {
            $list = $layout['about_items'];

            if($list):

                $o = '<section class="about-ctas about-new">';
                $o .= '<div class="grid-container">';
                $o .= '<div class="grid-x grid-padding-x">';
                $o .= '<div class="cell large-12">';
                $o .= '<div class="content">';
                    foreach($list as $li):
                        $link = $li["link"];
                        $image = $li["image"];

                        $o .= '<div class="item">';
                        $o .= '<div class="text">';

                        if($li['title']){
                            $o .= '<h2 class="title">'. $li['title'].'</h2>';
                        }
                        if($li['text']) {
                            $o .= '<p>' . $li['text'] . '</p>';
                        }

                        if( $link ){
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';

                            $o .= '<a class="arrow-link  arrow-link--dark about-ctas__cta-link" href="'. esc_url($link_url).'" target="' . esc_attr($link_target).'">
                                <span class="arrow-link__inner">
                                    <span class="arrow-link__text about-ctas__cta-link-text">'. esc_html($link_title).'</span>
                                    <span class="arrow-link__arrow about-ctas__cta-link-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 178 14.728">
                                            <path id="arrow" class="arrow" d="M177.707 8.071a.999.999 0 0 0 0-1.414L171.343.293a.999.999 0 1 0-1.414 1.414l5.657 5.657-5.657 5.657a.999.999 0 1 0 1.414 1.414l6.364-6.364Z" fill="#fff"></path><path id="line" class="line" d="M0 6.364h177v2H0z" fill="#fill"></path>
                                        </svg>
                                    </span>
                                </span>
                            </a>';
                        }
                    $o .= '</div>';

                    if($image) {
                        $o .= '<figure>';
                        $o .= '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '">';
                        $o .= '</figure>';
                    }
                    $o .= '</div>';

                    endforeach;
                $o .= '</div>';
                $o .= '</div>';
                $o .= '</div>';
                $o .= '</div>';
                $o .= '</section>';

            endif;

    return $o;
        }
    }
}


/**
 * Page Hero
 * @param int $id
 * @param int $block
 * @param int $number
 * @return string
 */
function page_hero($id, $block, $number)
{
    $bc = 'page-hero';
    $title = get_post_meta($id, 'flexible_blocks_' . $block . '_title', true);
    $text = get_post_meta($id, 'flexible_blocks_' . $block . '_text', true);
    $subtitle = get_post_meta($id, 'flexible_blocks_' . $block . '_subtitle', true);
    $image_id = get_post_meta($id, 'flexible_blocks_' . $block . '_image', true);
    $image = wp_get_attachment_image($image_id, 'size', false, ['class' => 'img-background-contain']);

    $o = '<section class="insights-hero insights-hero--purple hero-page-4x">';
    $o .= '<div class="grid-container">';
    $o .= '<div class="grid-x grid-padding-x content">';
    $o .= '<div class="cell text">';
    $o .= '<div class="insights-hero__content">';

    if ($subtitle) {
        $o .= '<div class="insights-hero__crumbs">';
        $o .= '<span class="insights-hero__crumbs-current">' . $subtitle . '</span>';
        $o .= '</div>';
    }

    if ($title) {
        $o .= '<h1 class="insights-hero__heading">' . $title . '</h1>';
    }

    if ($text) {
        $o .= wpautop($text);
    }

    $o .= '</div>';
    $o .= '</div>';
    $o .= '<div class="cell img-wrap">';
    $o .= '<div class="insights-hero__image-wrap">';

    if ($image){
        $o .= '<figure class="insights-hero__image">';
        $o .= $image;
        $o .= '</figure>';
    }
    $o .= '</div>';
    $o .= '</div>';
    $o .= '</div>';
    $o .= '</div>';
    $o .= '</section>';

    return $o;
}