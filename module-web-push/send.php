<?php namespace h;
/*
  Hooks related to Admin pages
  // https://github.com/web-push-libs/web-push-php/tree/6e1b88c46351ea3850cb1e9ae9565b8c61a58396
  // https://rossta.net/blog/using-the-web-push-api-with-vapid.html
*/

use Minishlink\WebPush\WebPush;

class WebPush_Send {
  function __construct() {
  }

  /*
    Send Push Message

    @param $payload (string / array) - Data to send, format is free
    @param $target (string / int) - Limit where it sends to. Either Topic or User ID.
  */
  public function send( $payload, $target = null ) {
    $public_key = getenv( 'FCM_PUBLIC_KEY' );
    $private_key = getenv( 'FCM_PRIVATE_KEY' );

    // if any keys doesn't exist
    if( !($public_key && $private_key) ) {
      throw new \Exception("Keys haven't been set in Environment");
      return false;
    }

    $subs = $this->_get_subscribers( $target );
    $auth = array(
      'VAPID' => array(
        'subject' => $_SERVER['HTTP_HOST'],
        'publicKey' => $public_key,
        'privateKey' => $private_key,
      ),
    );

    // format message
    $notifs = array();
    foreach( $subs as $s ) {
      $notifs[] = array(
        'endpoint' => $s['endpoint'],
        'payload' => is_array( $payload ) ? json_encode( $payload ) : $payload,
        'userPublicKey' => $s['keys']['p256dh'],
        'userAuthToken' => $s['keys']['auth'],
      );
    }

    // send message
    $webPush = new WebPush( $auth );
    foreach( $notifs as $n ) {
      $webPush->sendNotification(
        $n['endpoint'],
        $n['payload'],
        $n['userPublicKey'],
        $n['userAuthToken']
      );
    }
    $webPush->flush();
  }

  ///

  /*
    Get subscribers from comments with post_ID = 0

    @param $target (string / int) - Limit where it sends to. Either Topic or User ID.
    @return array - Array of subscribers JSON data
  */
  function _get_subscribers( $target = null ) {
    $args = array(
      'author_url' => 'wp-edje-web-push',
    );

    // if User ID is given
    if( is_numeric( $target ) ) {
      $args['author__in'] = array( $target );
    }
    // if Topic is given
    elseif( is_string( $target ) ) {
      $args['type'] = $target;
    }

    $comments = get_comments( $args );

    return array_map( function( $c ) {
      return json_decode( $c->comment_content, true );
    }, $comments );


    // $sub_json = '{"endpoint":"https://fcm.googleapis.com/fcm/send/cwPHYbaPaYA:APA91bGhzpHWbaaqi6SCtJuTjFbQaJVd83VpnW9oSLuwZcMy2ApkRXrR6IqITaudpRwoR6b6S9_jGo6l4VzjtWzviqlxzj-CNn8_OmIxR1Bzg6j3jyaP8HCwuBBayH4aAbT1c-kfs44tnNlG_GZv0ziF99mQMW6uQA","expirationTime":null,"keys":{"p256dh":"BAUvpXoTSicrXpYaQgn7KIqYANLXX3CXist1dGC94UYFEwDF7X9rwdQ6BEKP0rahwpB8x_jvFczuLBy_AEgTPe8","auth":"WUg_rqcTmWkhUhwd2w_8nQ"}}';
    //
    // return json_decode( $sub_json, true );
  }
}
