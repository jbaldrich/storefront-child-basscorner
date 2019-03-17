<?php
/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Place product search bar before secondary navigation
 */
remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
remove_action( 'storefront_header', 'storefront_product_search', 40 );
add_action( 'storefront_header', 'storefront_secondary_navigation', 40 );
add_action( 'storefront_header', 'storefront_product_search', 30 );

/**
 * Removes responsive menu toggle text
 */
add_filter( 'storefront_menu_toggle_text', '__return_empty_string' );

