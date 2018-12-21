<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

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
 * Conditionally load include functions 
 */
function jbr_load_includes() {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( ! is_checkout() && ! wp_doing_ajax() ) {
			require_once get_stylesheet_directory() . '/inc/header.php';
			require_once get_stylesheet_directory() . '/inc/footer.php';
		}
		if ( is_product_category() || is_front_page() || is_shop() || is_404() ) {
			require_once get_stylesheet_directory() . '/inc/product-loop.php';
			require_once get_stylesheet_directory() . '/inc/product.php';
		}
		if ( is_product() ) {
			require_once get_stylesheet_directory() . '/inc/product.php';
		}
		if ( is_cart() ) {
			require_once get_stylesheet_directory() . '/inc/cart.php';
		}
		if ( is_checkout() ) {
			require_once get_stylesheet_directory() . '/inc/checkout.php';
		}
	}
}
add_action( 'wp', 'jbr_load_includes' );

/**
 * Show all products
 */
function jbr_all_products_query( $q ){
	$q->set( 'posts_per_page', -1 );
}
add_action( 'woocommerce_product_query', 'jbr_all_products_query' );

/**
 * Add WCPOS plugin compatibility with Multisite
 */
function wc_pos_map_meta_cap( $caps, $cap, $user_id ) {
	if ( $cap == 'edit_users' && $user_id === 4 ) {
		$caps = array( 'edit_users' );
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'wc_pos_map_meta_cap', 10, 3 );