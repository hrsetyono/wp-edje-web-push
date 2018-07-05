<?php
/*
  Web Push Service Handler
*/

add_action( 'init', '_run_h_webpush' );
add_action( 'admin_init', '_run_admin_h_webpush' );

function _run_h_webpush() {
  require_once H_WEBPUSH_DIR . '/module-web-push/send.php';
  require_once H_WEBPUSH_DIR . '/module-web-push/subscribe.php';

  new \h\WebPush_Subscribe();
}

function _run_admin_h_webpush() {

}
