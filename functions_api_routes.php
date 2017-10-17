<?php
require_once __DIR__ . '/functions_api.php';

// ----------------------------
// ROUTES
// ----------------------------
add_action('rest_api_init', function () {
  // Boards
  register_rest_route('board-builder/v1', '/board/(?P<id>.*)', array(
    'methods' => 'GET',
    'callback' => '_api_get_board',
    'args' => array(
      'id' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_numeric( $param );
        }
      )
    )
  ));

  register_rest_route('board-builder/v1', '/board', array(
    'methods' => 'POST',
    'callback' => '_api_post_board'
  ));

  // Orders
  register_rest_route('board-builder/v1', '/order/(?P<id>.+)/(?P<hash>.*)', array(
    'methods' => 'GET',
    'callback' => '_api_get_order',
    'args' => array(
      'id' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_numeric( $param );
        }
      ),
      'hash' => array(
        'validate_callback' => function($param, $request, $key) {
          return !empty( $param );
        }
      )
    )
  ));

  register_rest_route('board-builder/v1', '/order', array(
    'methods' => 'POST',
    'callback' => '_api_post_order'
  ));

  register_rest_route('board-builder/v1', '/order-verify', array(
    'methods' => 'POST',
    'callback' => '_api_verify_order'
  ));

  // Boards
  register_rest_route('board-builder/v1', '/designs', array(
    'methods' => 'GET',
    'callback' => '_api_get_designs'
  ));

});
