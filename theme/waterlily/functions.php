<?php
add_action('wp_footer','print_footer');
if ( function_exists('register_sidebars') ) register_sidebar(array('id' => 'sidebar-1'));

function addTopBannerSettingsSection($wp_customize, $x, $default_title, $default_link, $default_image)
{
	$section_suffix = (string)$x;
	$section_identifier = 'top_banner_section' . $section_suffix;
	$section_title = 'Top Banner ' . $section_suffix;

    $title_setting_name = 'top_banner_title_setting' . $section_suffix;
    $link_setting_name = 'top_banner_link_setting' . $section_suffix;
    $image_setting_name = 'top_banner_image_setting' . $section_suffix;

    $title_setting_label = 'Title';
    $link_setting_label = 'Link';
    $image_setting_label = 'Image';

	//adding section in wordpress customizer
	$wp_customize->add_section($section_identifier, array(
		'title'          => $section_title,
	));

	// Title
	$wp_customize->add_setting($title_setting_name, array(
		'default'        => $default_title,
		'type' 			 => 'option',
	));
	$wp_customize->add_control($title_setting_name, array(
		'label'   => $title_setting_label,
		'section' => $section_identifier,
		'type'    => 'text',
	));

	// Link
	$wp_customize->add_setting($link_setting_name, array(
		'default'        => $default_link,
		'type' 			 => 'option',
	));
	$wp_customize->add_control($link_setting_name, array(
		'label'   => $link_setting_label,
		'section' => $section_identifier,
		'type'    => 'text',
	));

	// Image
	$wp_customize->add_setting($image_setting_name, array(
		'default'        => $default_image,
		'type' 			 => 'option',
	));
	$wp_customize->add_control($image_setting_name, array(
		'label'   => $image_setting_label,
		'section' => $section_identifier,
		'type'    => 'text',
));
}

// Since we don't plan on using any emojis. Provides a bit of a speed boost.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Stop wordpress from changing consecutive minus signs in post title to a single minus sign
remove_filter('the_content', 'wptexturize');
remove_filter('the_title', 'wptexturize');

?>