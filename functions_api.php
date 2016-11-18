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

  if (!isset($board_data['createdFromId'])) {
    return new WP_Error( 'invalid_board_data', "Missing created from board id in board details.", array( 'status' => 400 ) );
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
  $verify_hash = $request->get_param( 'hash' );
  // return array(
  //   'order' => $order_id,
  //   'hash' => $verify_hash
  // );
  $order_data = _db_getOrderData($order_id, $verify_hash);

  // return $order_data;

  if (!$order_data) {
    return new WP_Error( 'no_order_found', "Invalid order requested ({$order_id})", array( 'status' => 404 ) );
  }

  $return_order_data = json_decode($order_data['order_data']);

  // Make sure we include the board_id and verified state
  $return_order_data->board_id = $order_data['board_id'];
  $return_order_data->verified = (bool)$order_data['verified'];

  return $return_order_data;

  // return 'Getting an order.';
}

function _api_post_order ( WP_REST_Request $request ) {
  $order_data = $request->get_json_params();

  if (is_null($order_data)) {
    return new WP_Error( 'invalid_order_data', "Invalid order data submitted.", array( 'status' => 400 ) );
  }

  if (!isset($order_data['board_id'])) {
    return new WP_Error( 'invalid_order_data', "Missing board id in order details.", array( 'status' => 400 ) );
  }
  $board_id = $order_data['board_id'];

  $duplicate_board_order = _db_getOrderDataByBoardId($board_id);
  if ($duplicate_board_order) {
    return new WP_Error( 'invalid_order_data', "There is already an order for that board id ({$board_id}).", array( 'status' => 400 ) );
  }

  if (!isset($order_data['email'])) {
    return new WP_Error( 'invalid_order_data', "Missing email address in order details.", array( 'status' => 400 ) );
  }
  $email = $order_data['email'];

  if (!isset($order_data['name'])) {
    return new WP_Error( 'invalid_order_data', "Missing customer name in order details.", array( 'status' => 400 ) );
  }
  $name = $order_data['name'];


  $results = _db_saveOrderData($order_data);
  $new_order_id = $results['order_id'];
  $verify_hash = $results['verify_hash'];

  if ($new_order_id) {
    _mc_emailSendOrderVerification($email, $name, $new_order_id, $verify_hash);

    return array(
      'order_id' => $new_order_id
    );
  } else {
    return new WP_Error( 'could_not_save_order', "Couldn't save the order.", array( 'status' => 500 ) );
  }

  // return 'Posting an order.';
}

function _api_verify_order ( WP_REST_Request $request ) {
  $verify_data = $request->get_json_params();

  if (is_null($verify_data)) {
    return new WP_Error( 'invalid_order_verification', "Invalid order verification data submitted.", array( 'status' => 400 ) );
  }

  if (!isset($verify_data['order_id'])) {
    return new WP_Error( 'invalid_order_verification', "Missing order id in order verification.", array( 'status' => 400 ) );
  }
  $order_id = $verify_data['order_id'];

  if (!isset($verify_data['verify_hash'])) {
    return new WP_Error( 'invalid_order_verification', "Missing verify hash in order verification.", array( 'status' => 400 ) );
  }
  $verify_hash = $verify_data['verify_hash'];

  $resend = isset($verify_data['resend']);

  $verifiedResult = _db_verifyOrder($order_id, $verify_hash);
  $verified = $verifiedResult !== false;
  $already_sent = $verified && $verifiedResult == 0;

  // return array('verifiedResult' => $verifiedResult, 'verified' => $verified, 'already_sent' => $already_sent, 'resend' => $resend);

  // return _db_verifyOrder($order_id, $verify_hash);
  if ($verified) {
    $message = "Order verified. Sending confirmation.";
    $code = 'verified';
    $order_data = json_decode(_db_getOrderData($order_id)['order_data']);

    // return $order_data->email;
    if ($already_sent && !$resend) {
      $message = "Order already verified.";
      $code = 'already_verified';
    }
    else if ($already_sent && $resend) {
      $message = "Order already verified. Confirmation resent.";
      $code = 'already_verified_resent';
    }

    if (!$already_sent || $resend) {
      // return $order_data;
      _mc_emailSendOrderConfirmation( $order_data->email, $order_data->name, $order_id, $verify_hash );
      _mc_emailSendOrderDetails( $order_data->email, $order_data->name, $order_data->phone, $order_id, $verify_hash );
    }


    return array(
      'verified' => true,
      'code' => $code,
      'message' => $message
    );

  } else {
    return new WP_Error( 'invalid_order_verification', "Could not verify the provided order.", array( 'status' => 400 ) );
  }
}
