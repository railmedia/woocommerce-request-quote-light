<?php
class WRQ_REST {

    function __construct() {
        add_action( 'rest_api_init', array( $this, 'wrq_routes' ) );
    }

    function wrq_routes() {

        $namespace = 'wrq/v1';

        register_rest_route( $namespace, '/send-quote', array( // This one needs to be in the plugin
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'wrq_send_quote' ),
            'args' => array(
                'user_name' => array(
                    'sanitize_callback' => function( $value, $request, $param ) {
                        return sanitize_text_field( $request['user_name'] );
                    }
                ),
                'user_email' => array(
                    'sanitize_callback' => function( $value, $request, $param ) {
                        return sanitize_email( $request['user_email'] );
                    }
                ),
                'user_phone' => array(
                    'sanitize_callback' => function( $value, $request, $param ) {
                        return sanitize_text_field( $request['user_phone'] );
                    }
                )
            )
        ) );

    }

    function wrq_send_quote( $request ) {

        $params = $request->get_json_params();

        $name  = $params['user_name'];
        $email = $params['user_email'];
        $phone = $params['user_phone'];
        $product_id = $params['product_id'];
        $product_name = $params['product_name'];
        $admin_mail = get_bloginfo('admin_email');

        $subject = sprintf( __( 'You have received a new quote request for %s', 'wrq' ), $product_name );

        $admin_product_link = site_url() . '/wp-admin/post.php?post=' . $product_id . '&action=edit';

        $body = sprintf( __( '%sYou have received a new quote request for the product %s - %s%s%s%s', 'wrq' ), '<p>', $product_name, '<a href="' . $admin_product_link . '">', $admin_product_link, '</a>', '</p>' );
        $body .= sprintf( __( '%sName: %s%s', 'wrq' ), '<p>', $name, '</p>' );
        $body .= sprintf( __( '%sE-mail: %s%s', 'wrq' ), '<p>', $email, '</p>' );
        $body .= sprintf( __( '%sPhone: %s%s', 'wrq' ), '<p>', $phone, '</p>' );

        $headers = array( 'MIME-Version: 1.0', 'Content-type: text/html; charset=UTF-8', 'From: ' . get_bloginfo('name') . ' <' . $admin_mail . '>' );

        wp_mail( $admin_mail, $subject, $body, $headers );

        return new WP_REST_Response( array('status' => 'success', 'message' => 'Sent') );

    }

}
new WRQ_Rest;
?>
