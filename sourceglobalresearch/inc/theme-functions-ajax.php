<?php

/**
 * Insights Load More
 * @return void
 */
function insights_load_more() {
	$total = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_NUMBER_INT);
	$offset = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
	$per_page = filter_input(INPUT_POST, 'perPage', FILTER_SANITIZE_NUMBER_INT);
	$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
	$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
	$search = filter_input(INPUT_POST, 'search');
	if ($offset >= $total) return;
	if ($offset && $per_page) {
		if ($search) {
			$args = [
				's' => $search,
				'post_type' => 'post',
				'relevanssi' => true,
				'numberposts' => $per_page,
				'offset' => $offset,
			];
			$query = new WP_Query($args);
			$posts = $query->posts;
		} else {
			$args = [
				'numberposts' => $per_page,
				'offset' => $offset,
			];
			if ($type) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'types',
						'field' => 'id',
						'terms' => [$type],
						'operator' => 'IN',
					]
				];
			}
			if ($category) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => [$category],
						'operator' => 'IN',
					]
				];
			}
			$posts = get_posts($args);
		}
		echo json_encode(insights_row(array_splice($posts, 0, $per_page), false, false));
	}
	exit;
}

add_action("sr_insights_load_more", "insights_load_more");
add_action("sr_nopriv_insights_load_more", "insights_load_more");