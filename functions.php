<?php
/**
 * Store Pro Theme.
 *
 * @package      Store Pro
 * @link         https://seothemes.net/store-pro
 * @author       Seo Themes
 * @copyright    Copyright © 2017 Seo Themes
 * @license      GPL-2.0+
 */

// Child theme (do not remove).
include_once( get_template_directory() . '/lib/init.php' );

// Set Localization (do not remove).
load_child_theme_textdomain( 'store-pro', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'store-pro' ) );

// Theme constants.
define( 'CHILD_THEME_NAME', 'mujo-pro' );
define( 'CHILD_THEME_URL', 'http://mujo.ro/' );
define( 'CHILD_THEME_VERSION', '0.1.0' );

// Remove unused functionality.
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'header-right' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Enable support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'menu-primary',
	'menu-secondary',
	'site-inner',
	'footer-widgets',
	'footer',
) );

// Enable Accessibility support.
add_theme_support( 'genesis-accessibility', array(
	'404-page',
	'drop-down-menu',
	'headings',
	'rems',
	'search-form',
	'skip-links',
) );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus' , array(
	'primary' => __( 'Primary Menu', 'store-pro' ),
	'secondary' => __( 'Secondary Menu', 'store-pro' ),
) );

// Enable HTML5 markup structure.
add_theme_support( 'html5', array(
	'caption',
	'comment-form',
	'comment-list',
	'gallery',
	'search-form',
) );

// Add support for post formats.
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video',
) );

// Enable support for post thumbnails.
add_theme_support( 'post-thumbnails' );

// Enable automatic output of WordPress title tags.
add_theme_support( 'title-tag' );

// Enable selective refresh and Customizer edit icons.
add_theme_support( 'customize-selective-refresh-widgets' );

// Enable theme support for custom background image.
add_theme_support( 'custom-background' );

// Enable logo option in Customizer > Site Identity.
add_theme_support( 'custom-logo', array(
	'height'      => 60,
	'width'       => 200,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( '.site-title', '.site-description' ),
) );

// Enable support for custom header image or video.
add_theme_support( 'custom-header', array(
	'header-selector' 	=> '.front-page-1',
	'default_image'    	=> get_stylesheet_directory_uri() . '/assets/images/hero.jpg',
	'header-text'     	=> false,
	'width'           	=> 1920,
	'height'          	=> 1080,
	'flex-height'     	=> true,
	'flex-width'		=> true,
	'video'				=> true,
) );

// Register default header (just in case).
register_default_headers( array(
	'child' => array(
		'url'           => '%2$s/assets/images/hero.jpg',
		'thumbnail_url' => '%2$s/assets/images/hero.jpg',
		'description'   => __( 'Hero Image', 'store-pro' ),
	),
) );

/**
 * Load custom scripts and styles.
 */
function sp_enqueue_scripts_styles() {

	// Google fonts.
//	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700', array(), CHILD_THEME_VERSION );

	// Line awesome.
	wp_enqueue_style( 'line-awesome', 'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css' );

	// Theme scripts.
	wp_enqueue_script( 'store-pro', get_stylesheet_directory_uri() . '/assets/scripts/min/store-pro.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'sp_enqueue_scripts_styles' );

// Theme includes.
include_once( get_stylesheet_directory() . '/includes/theme-defaults.php' );
include_once( get_stylesheet_directory() . '/includes/helper-functions.php' );
include_once( get_stylesheet_directory() . '/includes/class-optimizations.php' );
include_once( get_stylesheet_directory() . '/includes/class-clean-gallery.php' );
include_once( get_stylesheet_directory() . '/includes/class-plugin-activation.php' );
include_once( get_stylesheet_directory() . '/includes/widget-areas.php' );
include_once( get_stylesheet_directory() . '/includes/woocommerce.php' );
include_once( get_stylesheet_directory() . '/includes/customizer-settings.php' );
include_once( get_stylesheet_directory() . '/includes/customizer-output.php' );


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

	wp_enqueue_script( 'masonry-init', get_stylesheet_directory_uri() . '/assets/scripts/masonry-init.js', array( 'jquery-masonry' ), CHILD_THEME_VERSION, true );
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