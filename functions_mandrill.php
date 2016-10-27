<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/secrets.php';

// -------------------------
// MAILCHIMP STUFF
// -------------------------
function _mc_getMandrillInstance() {
  return new Mandrill(PCW_MANDRILL_API_KEY);
}

function _mc_emailSendOrderVerification ( $to_email, $to_name, $order_id, $order_verification_hash ) {
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
                  'name' => 'VERIFY_HASH',
                  'content' => $order_verification_hash
              ),
              array(
                'name' => 'ORDER_ID',
                'content' => $order_id
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

function _mc_emailSendOrderConfirmation ( $to_email, $to_name, $board_id ) {

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
                  'name' => 'BOARD_ID',
                  'content' => $board_id
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
