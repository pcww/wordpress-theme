<?php

require_once __DIR__ . '/functions_mandrill.php';
require_once __DIR__ . '/functions_util.php';

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
    'template' => $is_template
  ));
  $lastid = $GLOBALS['wpdb']->insert_id;

  if ($result) return $lastid;
  else return false;
}

function _db_getOrderData($id) {
  $order_row = $GLOBALS['wpdb']->get_row( "SELECT * FROM pcw_orders WHERE id = $id" );
  if ($order_row) return $order_row;

  return false;
}

function _db_saveOrderData($order_data) {
  $json_order_data = json_encode($order_data);
  $result = $GLOBALS['wpdb']->insert('pcw_orders', array(
    'order_data' => $json_order_data,
    'board_id' => $order_data['board_id'],
    'date_created' => current_time('mysql'),
    'verify_hash' => GUID()
  ));
  $lastid = $GLOBALS['wpdb']->insert_id;

  if ($result) return $lastid;
  else return false;
}
