<?php
  require_once __DIR__ . '/functions_db.php';
  require_once __DIR__ . '/functions_util.php';

/**
 * PCW Board Builder Endpoints
 */

function _api_get_board ( WP_REST_Request $request ) {
  $board_id = $request->get_param( 'id' );
  $board_data = _db_getBoardData($board_id);

  if (!$board_data) {
    return new WP_Error( 'no_board_found', "Invalid board requested ({$board_id})", array( 'status' => 404 ) );
  }

  return json_decode($board_data->board_data);

  // return 'Getting a board.';
  // return _getDummyBoardData();
  // return var_export($request->get_params()['id'], true);
}

function _api_post_board ( WP_REST_Request $request ) {
  $board_data = $request->get_json_params();

  // TODO: Validate posted data is actually in proper format - JSON Schema?

  if (is_null($board_data)) {
    return new WP_Error( 'invalid_board_data', "Invalid board data submitted.", array( 'status' => 400 ) );
  }

  $new_board_id = _db_saveBoardData($board_data);

  if ($new_board_id) {
    return array(
      'board_id' => $new_board_id
    );
  } else {
    return new WP_Error( 'could_not_save_board', "Couldn't save the board.", array( 'status' => 500 ) );
  }

  // return 'Posting a board.';
}

function _api_get_order ( WP_REST_Request $request ) {
  $order_id = $request->get_param( 'id' );
  $order_data = _db_getOrderData($order_id);

  if (!$order_data) {
    return new WP_Error( 'no_order_found', "Invalid order requested ({$order_id})", array( 'status' => 404 ) );
  }

  return json_decode($order_data->order_data);

  // return 'Getting an order.';
}

function _api_post_order ( WP_REST_Request $request ) {
  $order_data = $request->get_json_params();

  // TODO: Validate posted data is actually in proper format - JSON Schema?

  if (is_null($order_data)) {
    return new WP_Error( 'invalid_order_data', "Invalid order data submitted.", array( 'status' => 400 ) );
  }


  if (!isset($order_data['board_id'])) {
    return new WP_Error( 'invalid_order_data', "Missing board id in order details.", array( 'status' => 400 ) );
  }
  $board_id = $order_data['board_id'];

  if (!isset($order_data['email'])) {
    return new WP_Error( 'invalid_order_data', "Missing email address in order details.", array( 'status' => 400 ) );
  }
  $email = $order_data['email'];

  if (!isset($order_data['name'])) {
    return new WP_Error( 'invalid_order_data', "Missing customer name in order details.", array( 'status' => 400 ) );
  }
  $name = $order_data['name'];


  $new_order_id = _db_saveOrderData($order_data);

  if ($new_order_id) {
    _mc_emailSendOrderConfirmation($email, $name, $board_id);

    return array(
      'order_id' => $new_order_id
    );
  } else {
    return new WP_Error( 'could_not_save_order', "Couldn't save the order.", array( 'status' => 500 ) );
  }

  // return 'Posting an order.';
}
