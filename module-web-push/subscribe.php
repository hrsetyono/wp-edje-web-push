<?php namespace h;

class WebPush_Subscribe {
  function __construct() {
    add_action( 'rest_api_init', array( $this, 'rest_api_init') );
  }

  /*
    @action rest_api_init
  */
  function rest_api_init() {
    $vendor = 'h/v0';

    register_rest_route( $vendor, '/subscribe', array(
      'methods' => 'POST',
      'callback' => array($this, 'subscribe_api'),
    ) );
  }

  /*
    @return int - The comment ID that's just created
  */
  function subscribe_api( $request ) {
    $data = $request->get_params();

    // check if already subscribed
    $already_subscribe = get_comments( array(
      'author_email' => $data['h_push_id'], 'number' => 1
    ) );

    if( $already_subscribe ) {
      return 'This user is already subscribed';
    }

    $comment_args = array(
      'comment_post_ID' => 0,
      'comment_author' => 'Edje',
      'comment_author_email' => $data['h_push_id'],
      'comment_author_url' => 'wp-edje-web-push',
      'comment_content' => $data['content'],
      'comment_type' => $data['topic'],
      'user_id' => $data['user_id'],
      'comment_parent' => 0,
    );

    return wp_insert_comment( $comment_args );
  }

}
