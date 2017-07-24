<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
function genesis_sample_localization_setup(){
	load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );
}

// Add the helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the required WooCommerce styles and Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Add the Genesis Connect WooCommerce notice.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Mujo Pro' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.3.0' );

// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style( 'genesis-sample-fonts', '//fonts.googleapis.com/css?family=Old+Standard+TT|Source+Sans+Pro:400,600,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'genesis-sample-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

}

// Define our responsive menu settings.
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( 'Menu', 'genesis-sample' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add Image Sizes.
add_image_size( 'featured-image', 720, 400, TRUE );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Header Menu', 'genesis-sample' ), 'secondary' => __( 'Footer Menu', 'genesis-sample' ) ) );

// Reposition the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

// Reduce the secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
function genesis_sample_author_box_gravatar( $size ) {
	return 90;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

// =================================================================
// = Pinterest-like Masonry layout for all Archives =
// =================================================================

add_action( 'pre_get_posts', 'sk_change_archives_posts_per_page' );
/**
 * Change Posts Per Page for all archives.
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/customize-the-wordpress-query/
 * @param object $query data
 *
 */
function sk_change_archives_posts_per_page( $query ) {

	if( $query->is_main_query() && !is_admin() && !is_singular() && !is_search() ) {
		$query->set( 'posts_per_page', '9' );
	}

}

// Enqueue and initialize jQuery Masonry script.
add_action( 'wp_enqueue_scripts', 'sk_masonry_script' );
function sk_masonry_script() {
	if ( is_singular() || is_search() ) {
		return;
	}

	wp_enqueue_script( 'masonry-init', get_stylesheet_directory_uri() . '/js/masonry-init.js', array( 'jquery-masonry' ), CHILD_THEME_VERSION, true );
}


// Add custom body class to the head.
add_filter( 'body_class', 'sk_body_class' );
function sk_body_class( $classes ) {
	if ( !is_singular() && !is_search() ) {
		$classes[] = 'masonry-page';
	}

	return $classes;
}

// Helper function to add custom divs for grid size and gap.
function sk_grid_sizer() { ?>
	<!-- width of .grid-sizer used for columnWidth -->
	<div class="grid-sizer"></div>
	<!-- width of .gutter-width used for hoirzontal gap between the blocks -->
	<div class="gutter-width"></div>
<?php }

// Helper function to display Post title and Post content/excerpt wrapped in a custom div.
function sk_masonry_title_content() {
	echo '<div class="title-content">';
		genesis_do_post_title();
		genesis_do_post_content();
	echo '</div>';
}

// Helper function to display Post info and Post meta.
function sk_masonry_entry_footer() {
	genesis_post_info();
	genesis_post_meta();
}

// Customize entry meta in the entry header.
function sp_post_info_filter( $post_info ) {
	$post_info = '[post_date]';
	return $post_info;
}

// Display Post thumbnail, Post title, Post content/excerpt, Post info and Post meta in masonry brick.
add_action( 'genesis_meta','sk_masonry_layout' );
function sk_masonry_layout() {
	if ( is_singular() || is_search() ) {
		return;
	}

	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	add_action( 'loop_start', 'sk_grid_sizer' );

	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

	add_action( 'genesis_entry_content', 'sk_masonry_block_post_image', 8 ) ;
	add_action( 'genesis_entry_content', 'sk_masonry_title_content', 9 );

	add_action( 'genesis_entry_footer', 'sk_masonry_entry_footer' );
	add_filter( 'genesis_post_info', 'sp_post_info_filter' );

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

	remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
	add_action( 'genesis_before_content', 'genesis_do_taxonomy_title_description', 15 );

	remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
	add_action( 'genesis_before_content', 'genesis_do_author_title_description', 15 );

	remove_action( 'genesis_before_loop', 'genesis_do_author_box_archive', 15 );
	add_action( 'genesis_before_content', 'genesis_do_author_box_archive', 15 );

	remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
	add_action( 'genesis_before_content', 'genesis_do_cpt_archive_title_description' );

	remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
	remove_action( 'genesis_before_content', 'genesis_do_search_title' );

	remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
	add_action( 'genesis_after_content', 'genesis_posts_nav' );
}

// Set the second parameter to width of your masonry brick (.home .entry, .archive .entry).
add_image_size( 'masonry-image', 400, 0, true );

// Helper function to display featured image with a fallback.
function sk_masonry_block_post_image() {
	if ( has_post_thumbnail() ) {
		$img = genesis_get_image( array( 'format' => 'url', 'size' => 'masonry-image', 'attr' => array( 'class' => 'post-image' ) ) );
	} else {
		$img = 'http://lorempixel.com/400/300/';
	}

	printf( '<a href="%s" title="%s"><img src="%s" /></a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );
}

add_filter( 'excerpt_more', 'sk_excerpt_more' );
/**
 * Add more link when using excerpts.
 */
function sk_excerpt_more( $more ) {
	return ' <a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

add_filter( 'excerpt_length', 'sk_excerpt_length' );
/**
 * Modify the length of post excerpts.
 *
 * @param integer $length current excerpt length.
 * @return modified excerpt length.
 */
function sk_excerpt_length( $length ) {
	return 20; // pull first 20 words.
}

// Remove Footer Widgets.
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );