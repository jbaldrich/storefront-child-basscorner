<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Create the distraction free checkout
 */
remove_all_actions( 'storefront_header' );
remove_all_actions( 'storefront_footer' );
remove_all_actions( 'storefront_sidebar' );
remove_all_actions( 'storefront_before_content' );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
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