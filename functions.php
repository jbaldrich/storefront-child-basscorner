<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */


/**
 * Dequeue the Storefront Parent theme core CSS
 */
function jbr_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}
//add_action( 'wp_enqueue_scripts', 'jbr_child_theme_dequeue_style', 999 );

/**
 * Enqueue Fonts
 */
function jbr_enqueue_fonts() {
	$fonts = array(
		'Catamaran' => 'https://fonts.googleapis.com/css?family=Catamaran:700,900',
	);
	foreach ($fonts as $font => $src) {
		wp_register_style( $font, $src, array(), null );
		wp_enqueue_style( $font, $src, array(), null );
	}
	
}
add_action( 'wp_enqueue_scripts', 'jbr_enqueue_fonts' );

/**
 * Place product search bar before secondary navigation
 */
function jbr_change_search_secondary_nav_order(){
	remove_action('storefront_header','storefront_secondary_navigation',30);
	remove_action('storefront_header','storefront_product_search',40);
	add_action('storefront_header','storefront_secondary_navigation',40);
	add_action('storefront_header','storefront_product_search',30);
}
add_action('init','jbr_change_search_secondary_nav_order');

