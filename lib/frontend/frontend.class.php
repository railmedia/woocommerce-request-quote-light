<?php
class WRQ_Frontend {

    var $settings;

    function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'wrq_scripts' ) );
        $this->settings = array(
            'dsp' => get_option('display_on_single_product_page'),
            'dsc' => get_option('display_on_categories_page'),
            'dspr' => get_option('display_on_product_price')
        );
        add_action('wp', array($this, 'wrq_load'));
    }

    function wrq_load() {

        if( $this->settings['dsp'] ) {
            if( $this->settings['dspr'] ) {
                add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'wrq_add_form_button' ) );
            } else {
                global $post;
                $prod = new WC_Product($post->ID);
                $prod_price = $prod->get_price();
                if( ! $prod_price ) {
                    add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'wrq_add_form_button' ) );
                }
            }
        }
        if( $this->settings['dsc'] ) {
            if( $this->settings['dspr'] ) {
                add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wrq_add_form_button' ) );
            } else {
                global $post;
                $prod = new WC_Product($post->ID);
                $prod_price = $prod->get_price();
                if( ! $prod_price ) {
                    add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wrq_add_form_button' ) );
                }
            }
        }
    }

    function wrq_scripts() {

        wp_enqueue_style( 'wrq-css', WRQURL . 'assets/css/wrq.css' );
        wp_enqueue_script( 'wrq-js', WRQURL . 'assets/js/wrq.js', array('jquery'), null, true );
        wp_localize_script( 'wrq-js', 'wrq', array(
            'nonce' => wp_create_nonce('wp_rest')
        ) );

    }

    function wrq_add_form_button() {

        global $post;
    ?>
        <form action="" method="post" class="wrq-form wrq-form-<?php echo $post->ID; ?>" data-prodid="<?php echo $post->ID; ?>">
            <h3>
                <?php printf( __( 'Request price quote for <strong>%s</strong> product', 'wrq' ), $post->post_title ); ?>
            </h3>
            <p>
                <input type="text" id="user_name_<?php echo $post->ID; ?>" placeholder="<?php _e( 'First Name/Last Name', 'wrq' ); ?>*" />
            </p>
            <p>
                <input type="email" id="user_email_<?php echo $post->ID; ?>" placeholder="<?php _e( 'E-mail', 'wrq' ); ?>*" />
            </p>
            <p>
                <input type="text" id="user_phone_<?php echo $post->ID; ?>" placeholder="<?php _e( 'Phone', 'wrq' ); ?>" />
            </p>
            <p>
                <button class="submit-wrq" data-prodid="<?php echo $post->ID; ?>" data-prodtitle="<?php echo $post->post_title; ?>"><?php _e( 'Send', 'wrq' ); ?></button>
            </p>
            <div class="wrq-preloader wrq-preloader-<?php echo $post->ID; ?>">
                <div class="wrq-loader"></div>
            </div>
            <div class="wrq-success-msg wrq-success-msg-<?php echo $post->ID; ?>"></div>
        </form>

        <p>
            <button class="open-submit-wrq" data-prodid="<?php echo $post->ID; ?>"><?php _e( 'Request price quote', 'wrq' ); ?></button>
        </p>

    <?php

    }

}
new WRQ_Frontend;
?>
