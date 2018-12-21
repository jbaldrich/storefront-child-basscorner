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

/**
 * Remove related products
 **/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


/**
 * Add message above product thumbnails
 **/
function jbr_add_text_above_wc_product_thumbs() {
	global $product;
	$attachment_ids = $product->get_gallery_attachment_ids();
	if ( ! empty($attachment_ids ) ) {
		echo '<div class="slide-message"><span>';
		_e( 'Slide your finger to show more images', 'storefront' );
		echo '</span></div>';
	}
}
add_action( 'woocommerce_before_single_product_summary', 'jbr_add_text_above_wc_product_thumbs', 20 );

/**
 * Remove product description heading
 **/
add_filter( 'woocommerce_product_description_heading', '__return_null' );
