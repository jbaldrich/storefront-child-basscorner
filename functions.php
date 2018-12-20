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
 * Conditionally load layout functions 
 */
function jbr_load_layouts() {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( ! is_checkout() && ! wp_doing_ajax() ) {
			//require_once get_stylesheet_directory_uri() . '/inc/header.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/header.php';
			//require_once get_stylesheet_directory_uri() . '/inc/footer.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/footer.php';
		}
	}
}
add_action( 'wp', 'jbr_load_layouts' );

/**
 * Conditionally load WooCommerce layout functions 
 */
function jbr_load_wc_layouts() {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_product_category() || is_front_page() || is_shop() ) {
			//require_once get_stylesheet_directory_uri() . '/inc/product-loop.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/product-loop.php';
			//require_once get_stylesheet_directory_uri() . '/inc/product.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/product.php';
		}
		if ( is_product() ) {
			//require_once get_stylesheet_directory_uri() . '/inc/product.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/product.php';
		}
		if ( is_cart() ) {
			//require_once get_stylesheet_directory_uri() . '/inc/cart.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/cart.php';
		}
		if ( is_checkout() ) {
			//require_once get_stylesheet_directory_uri() . '/inc/checkout.php';
			require_once  '/app/public/wp-content/themes/storefront-child-basscorner/inc/checkout.php';
		}
	}
}
add_action( 'wp', 'jbr_load_wc_layouts' );

