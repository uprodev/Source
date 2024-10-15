<?php
	$body_classes = '';
	$icons_dir = get_template_directory_uri() . '/assets/icons/';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $icons_dir; ?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $icons_dir; ?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $icons_dir; ?>favicon-16x16.png">
	<link rel="manifest" href="<?php echo $icons_dir; ?>site.webmanifest">
	<link rel="mask-icon" href="<?php echo $icons_dir; ?>safari-pinned-tab.svg" color="#dd588e">
	<link rel="shortcut icon" href="<?php echo $icons_dir; ?>favicon.ico">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-config" content="<?php echo $icons_dir; ?>browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class($body_classes); ?>>