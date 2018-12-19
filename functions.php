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

/*********************************************************************************************************/
/**
 * Enqueue Fonts on WordPress
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
//add_action( 'wp_enqueue_scripts', 'jbr_enqueue_fonts' );
/*********************************************************************************************************/

/**
 * Enqueue Google Fonts on Storefront (put $fonts asa a parameter to add, remove paramater to rewrite)
 */
function jbr_add_google_fonts() {
	$fonts['catamaran'] = 'Catamaran:200,700,900';
	$fonts['open-sans'] = 'Open+Sans:400,400i,700,700i';
	return $fonts;
}
add_filter( 'storefront_google_font_families', 'jbr_add_google_fonts' );

/**
 * Place product search bar before secondary navigation
 */
function jbr_change_search_secondary_nav_order() {
	remove_action('storefront_header','storefront_secondary_navigation',30);
	remove_action('storefront_header','storefront_product_search',40);
	add_action('storefront_header','storefront_secondary_navigation',40);
	add_action('storefront_header','storefront_product_search',30);
}
add_action('init','jbr_change_search_secondary_nav_order');

/**
 * Removes responsive menu toggle text
 */
add_filter( 'storefront_menu_toggle_text', '__return_empty_string' );

/**
 * Replaces original function storefront_cart_link() in order to remove text 'products'
 */
if ( ! function_exists( 'storefront_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function storefront_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'storefront' ); ?>">
				<?php
					$quantity = (int) WC()->cart->get_cart_contents_count();
					if ( $quantity > 0 ) :
				?>
						<span class="count"><?php echo wp_kses_data( $quantity ); ?></span>
				<?php
					endif;
				?>
			</a>
		<?php
	}
}

/**
 * Add product category image
 */
function jbr_add_category_image() {
	if ( is_product_category() ) {
		global $wp_query;
		$category = $wp_query->get_queried_object();
		$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id );
		if ( $image ) {
			echo '<img src="' . $image . '" alt="' . $category->name . '" />';
		}
	}
}
add_action( 'woocommerce_archive_description', 'jbr_add_category_image', 5 );

/*
 * Move category and product descriptions to the bottom
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 40 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_product_archive_description', 40 );

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
function jbr_customize_sorting() {
	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}
add_action( 'init', 'jbr_customize_sorting' );

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

/*
 * Remove image from cart table
 */
add_filter( 'woocommerce_cart_item_thumbnail', '__return_empty_string' );

/**
 * Create the distraction free checkout
 */
function jbr_distraction_free_checkout() {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_checkout() ) {
			// Remove all actions
			remove_all_actions( 'storefront_header' );
			remove_all_actions( 'storefront_footer' );
			remove_all_actions( 'storefront_sidebar' );
			remove_all_actions( 'storefront_before_content' );
			// Remove selected actions
			/*
			remove_action( 'storefront_header', 'storefront_product_search', 30 );
			remove_action( 'storefront_header', 'storefront_secondary_navigation', 40 );
			remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
			remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
			remove_action( 'storefront_header', 'storefront_header_cart', 60 );
			remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
			remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
			remove_action( 'storefront_footer', 'storefront_credit', 20 );
			remove_action( 'storefront_sidebar','storefront_get_sidebar', 10 );
			remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
			remove_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );
			*/
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		}
	}
}
add_action( 'wp', 'jbr_distraction_free_checkout' );

/*
 * Adds custom footer links
 */
function jbr_custom_credit() {
	?>
	<div class="site-info">
		<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ) ); ?>
		<?php if ( apply_filters( 'storefront_credit_link', true ) ) { ?>
		<br />
			<?php
			if ( apply_filters( 'storefront_privacy_policy_link', true ) && function_exists( 'the_privacy_policy_link' ) ) {
				the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
			}
			?>
			<?php echo '<a href="' . get_the_permalink( '1587' ) .'" target="_blank" rel="nofollow">' . get_the_title( '1587' ) . '</a>.'; ?>
		<?php } ?>
	</div><!-- .site-info -->
	<?php
}
function jbr_add_custom_credit() {
	remove_action( 'storefront_footer', 'storefront_credit', 20 );
	add_action( 'storefront_footer', 'jbr_custom_credit', 20 );
}
add_action( 'after_setup_theme', 'jbr_add_custom_credit' );