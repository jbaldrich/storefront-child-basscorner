<?php
/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
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
			<?php echo '<a href="' . esc_url( get_the_permalink( '1587' ) ) . '" target="_blank" rel="nofollow">' . esc_html( get_the_title( '1587' ) ) . '</a>.'; ?>
		<?php } ?>
	</div><!-- .site-info -->
	<?php
}
remove_action( 'storefront_footer', 'storefront_credit', 20 );
add_action( 'storefront_footer', 'jbr_custom_credit', 20 );
