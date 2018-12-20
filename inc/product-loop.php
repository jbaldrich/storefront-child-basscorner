<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add product category image
 */
function jbr_add_category_image() {
	global $wp_query;
	$category = $wp_query->get_queried_object();
	$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
	$image = wp_get_attachment_url( $thumbnail_id );
	if ( $image ) {
		echo '<img src="' . $image . '" alt="' . $category->name . '" />';
	}
}
add_action( 'woocommerce_archive_description', 'jbr_add_category_image', 5 );

/*
 * Move category and product descriptions to the bottom
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
//remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 40 );
//add_action( 'woocommerce_after_shop_loop', 'woocommerce_product_archive_description', 40 );

/*
 * Show categories out of the loop
 */
function jbr_show_product_subcategories( $args = array() ) {
	$parentid = get_queried_object_id();
	$args = array( 'parent' => $parentid );
	$terms = get_terms( 'product_cat', $args );
	if ( $terms ) {
		echo '<nav class="product-cats"><ul>';
		foreach ( $terms as $term ) {
		echo '<li class="category">';
			echo '<h2>';
				echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="button ' . $term->slug . '">';
					echo $term->name;
				echo '</a>';
			echo '</h2>';
		echo '</li>';
		}
	echo '</ul></nav>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'jbr_show_product_subcategories', 5 );

/*
 * Remove unwanted sorting options
 */
function jbr_remove_sorting_options ( $orderby ) {
	unset( $orderby['popularity'] );
	unset( $orderby['rating'] );
	unset( $orderby['date'] );
	return $orderby;
}
add_filter ( 'woocommerce_catalog_orderby', 'jbr_remove_sorting_options', 20 );

/*
 * Remove result count from before the loop and all sorting and paging from after the loop
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );



 /*
  * Remove Add to Cart Button
  */
 remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );