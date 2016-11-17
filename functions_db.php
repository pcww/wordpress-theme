<?php

require_once __DIR__ . '/functions_mandrill.php';
require_once __DIR__ . '/functions_util.php';

function _db_getBoardTemplates() {
  $board_rows = $GLOBALS['wpdb']->get_results( "SELECT * FROM pcw_boards WHERE template = 1" );
  if ($board_rows) return $board_rows; 

  return false;
}

function _db_getBoardData($id) {
  $board_row = $GLOBALS['wpdb']->get_row( "SELECT * FROM pcw_boards WHERE id = $id" );
  if ($board_row) return $board_row;

  return false;
}

function _db_saveBoardData($board_data) {
  $is_template = false;
  if (isset($board_data['template'])) {
    $is_template = ($board_data['template'] == 'true' ? true: false);
  }

  $json_board_data = json_encode($board_data);
  $result = $GLOBALS['wpdb']->insert('pcw_boards', array(
    'board_data' => $json_board_data,
    'name' => $board_data['name'],
    'date_created' => current_time('mysql'),
    'template' => $is_template,
    'created_from_id' => $board_data['createdFromId']
  ));
  $lastid = $GLOBALS['wpdb']->insert_id;

  if ($result) return $lastid;
  else return false;
}

function _db_getOrderData($id, $verify_hash = false) {
  if ($verify_hash) {
    $order_row = $GLOBALS['wpdb']->get_row( "SELECT * FROM pcw_orders WHERE id = $id AND verify_hash = '$verify_hash'", ARRAY_A);
  } else {
    $order_row = $GLOBALS['wpdb']->get_row( "SELECT * FROM pcw_orders WHERE id = $id", ARRAY_A);
  }

  if ($order_row) return $order_row;

  return false;
}

function _db_getOrderDataByBoardId($board_id) {
  $order_row = $GLOBALS['wpdb']->get_row( "SELECT * FROM pcw_orders WHERE board_id = $board_id", ARRAY_A);

  if ($order_row) return $order_row;

  return false;
}

function _db_saveOrderData($order_data) {
  $json_order_data = json_encode($order_data);
  $guid = GUID();
  $result = $GLOBALS['wpdb']->insert('pcw_orders', array(
    'order_data' => $json_order_data,
    'board_id' => $order_data['board_id'],
    'date_created' => current_time('mysql'),
    'verify_hash' => $guid
  ));
  $lastid = $GLOBALS['wpdb']->insert_id;

  if ($result) {
    return array(
      'order_id' => $lastid,
      'verify_hash' => $guid
    );
  }
  else
    return false;
}

function _db_verifyOrder($order_id, $verify_hash) {
  $result = $GLOBALS['wpdb']->update(
  	'pcw_orders',
  	array(
  		'verified' => true
  	),
  	array(
      'id' => $order_id,
      'verify_hash' => $verify_hash
    )
  );

  if ($result === false) return false;

  // Check if the order was actually verified
  $order_data = _db_getOrderData($order_id);
  if (!$order_data['verified']) return false; // hash probably didnt match the order_id's hash

  return $result;

}
