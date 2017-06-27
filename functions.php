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

  // Board Template Shortcode

  function pcw_board_templates_shortcode( $atts, $content = null ) {
    ob_start();
    ?>
    <div id="pcw-boards" class="">
      <?php
        $board_templates = _db_getBoardTemplates();

        // Sort boards by complexity
        usort($board_templates, function ($a, $b) {
          return ($a->template > $b->template) ? 1 : -1;
        });

        foreach ( $board_templates as $board )
        {
          ?>
          <div class="board">
            <div class="logo">
              <img src="<?= $board->logo_url ?>" onerror="this.onerror=null; this.src='<?= $board->logo_url ?>'">
              <span><?= $board->name ?></span>
            </div>
            <div class="photo">
              <img src="<?= $board->photo_url ?>"/>

              <div class="mnky_button flat-teal build-button">
                <a style="background-color:#7f8c8d; color:#ffffff;" href="http://builder.pinecliffwoodworks.com/#?id=<?= $board->id ?>" title="Build this board">
                  <span>Build this board <i class="fa fa-cog" aria-hidden="true"></i></span>
                </a>
              </div>
            </div>
          </div>
          <?php
        }
      ?>
    </div>
    <?php
    return ob_get_clean();
  }
  add_shortcode( 'pcw_board_templates', 'pcw_board_templates_shortcode' );


  // Required - Chrome was reporting invald "Content-Type" in preflight response (OPTIONS CORS check) - this just forces it to true
  // TODO: Make sure this is secure!!!
  add_filter('rest_post_dispatch', function (\WP_REST_Response $result) {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      $result->header('Access-Control-Allow-Headers', 'Authorization, Content-Type', true);
    }
    return $result;
  });
