<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/secrets.php';

// -------------------------
// MAILCHIMP STUFF
// -------------------------
function _mc_getMandrillInstance() {
  return new Mandrill(PCW_MANDRILL_API_KEY);
}

function _mc_emailSendOrderVerification ( $to_email, $to_name, $order_id, $verify_hash ) {
  try {
      $mandrill = _mc_getMandrillInstance();
      $template_name = 'pine-cliff-woodworks-order-verification';
      $template_content = array();
      $message = array(
          'to' => array(
              array(
                  'email' => $to_email,
                  'name' => $to_name,
                  'type' => 'to'
              )
          ),
          'important' => false,
          'track_opens' => null,
          'track_clicks' => null,
          'auto_text' => null,
          'auto_html' => null,
          'inline_css' => null,
          'url_strip_qs' => null,
          'preserve_recipients' => null,
          'view_content_link' => null,
          'tracking_domain' => null,
          'signing_domain' => null,
          'return_path_domain' => null,
          'merge' => true,
          'merge_language' => 'mailchimp',
          'global_merge_vars' => array(
              array(
                'name' => 'ORDER_ID',
                'content' => $order_id
              ),
              array(
                  'name' => 'VERIFY_HASH',
                  'content' => $verify_hash
              )
          )
      );
      $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message);

      return ($result[0][status] == 'sent') ? true : false;
      // return print_r($result[0][status], true);
  } catch(Mandrill_Error $e) {
      echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
      throw $e;
  }
}

function _mc_emailSendOrderConfirmation ( $to_email, $to_name, $order_id, $verify_hash ) {

  try {
      $mandrill = _mc_getMandrillInstance();
      $template_name = 'pine-cliff-woodworks-order-confirmation';
      $template_content = array();
      $message = array(
          'to' => array(
              array(
                  'email' => $to_email,
                  'name' => $to_name,
                  'type' => 'to'
              )
          ),
          'important' => false,
          'track_opens' => null,
          'track_clicks' => null,
          'auto_text' => null,
          'auto_html' => null,
          'inline_css' => null,
          'url_strip_qs' => null,
          'preserve_recipients' => null,
          'view_content_link' => null,
          'tracking_domain' => null,
          'signing_domain' => null,
          'return_path_domain' => null,
          'merge' => true,
          'merge_language' => 'mailchimp',
          'global_merge_vars' => array(
              array(
                  'name' => 'ORDER_ID',
                  'content' => $order_id
              ),
              array(
                  'name' => 'VERIFY_HASH',
                  'content' => $verify_hash
              )
          )
      );
      $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message);

      return ($result[0][status] == 'sent') ? true : false;
      // return print_r($result[0][status], true);
  } catch(Mandrill_Error $e) {
      echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
      throw $e;
  }

}

function _mc_emailSendOrderDetails ( $to_email, $to_name, $phone, $order_id, $verify_hash ) {

  try {
      $mandrill = _mc_getMandrillInstance();
      $template_name = 'pine-cliff-woodworks-order-details';
      $template_content = array();
      $message = array(
          'to' => array(
              array(
                  'email' => PCW_ORDERS_EMAIL,
                  'name' => PCW_ORDERS_NAME,
                  'type' => 'to'
              )
          ),
          'important' => false,
          'track_opens' => null,
          'track_clicks' => null,
          'auto_text' => null,
          'auto_html' => null,
          'inline_css' => null,
          'url_strip_qs' => null,
          'preserve_recipients' => null,
          'view_content_link' => null,
          'tracking_domain' => null,
          'signing_domain' => null,
          'return_path_domain' => null,
          'merge' => true,
          'merge_language' => 'mailchimp',
          'global_merge_vars' => array(
              array(
                  'name' => 'CUSTOMER_NAME',
                  'content' => $to_name
              ),
              array(
                  'name' => 'CUSTOMER_PHONE',
                  'content' => $phone
              ),
              array(
                  'name' => 'CUSTOMER_EMAIL',
                  'content' => $to_email
              ),
              array(
                  'name' => 'ORDER_ID',
                  'content' => $order_id
              ),
              array(
                  'name' => 'VERIFY_HASH',
                  'content' => $verify_hash
              )
          )
      );
      $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message);

      return ($result[0][status] == 'sent') ? true : false;
      // return print_r($result[0][status], true);
  } catch(Mandrill_Error $e) {
      echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
      throw $e;
  }

}
