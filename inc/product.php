<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
 * Replaces sale string with discount percentage
 */
function jbr_show_discount( $text, $post, $product ) {
	if ( $product->product_type == 'variable' ) {
		$regular_price = $product->min_variation_price;
		$sale_price = $product->min_variation_sale_price;
	} else {
		$regular_price = $product->regular_price;
		$sale_price = $product->sale_price;
	}
	$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
	$text = $percentage . '%';
	return '<span class="onsale">' . $text . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'jbr_show_discount', 10, 3 );

/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );