<?php
  require_once __DIR__ . '/functions_api_routes.php';

  add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

  function theme_enqueue_styles () {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
  }

  /**
   * Changes the redirect URL for the Return To Shop button in the cart.
   *
   * @return string
   */
  function wc_empty_cart_redirect_url () {
    return '/shop-coming-soon/';
  }
  add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );
