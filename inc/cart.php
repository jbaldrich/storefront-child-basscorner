<?php
/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Remove image from cart table
 */
add_filter( 'woocommerce_cart_item_thumbnail', '__return_empty_string' );
