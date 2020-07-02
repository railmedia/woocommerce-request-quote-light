<?php
class WRQ_Admin {

    var $views;

    function __construct() {
        $this->views = new WRQ_Admin_Views;
        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'admin_init', array( $this, 'display' ) );
    }

    function menu() {

        add_submenu_page(
            'woocommerce',
            __( 'Request Quote', 'wrq' ),
            __( 'Request Quote', 'wrq' ),
            'manage_options',
            'woo-request-quote-settings',
            array( $this, 'settings' )
        );

    }

    function settings() {
        echo $this->views->settings();
    }

    function display() {

        add_settings_section('wrq_settings', __('Request Quote', 'wrq'), array( $this, 'display_settings' ), 'wrq');

        add_settings_field(
            'display_on_single_product_page',
            'Display on single product page',
            array( $this, 'dsp_select' ),
            'wrq',
            'wrq_settings'
        );

        register_setting( 'wrq_settings', 'display_on_single_product_page' );

        add_settings_field(
            'display_on_categories_page',
            'Display on categories page',
            array( $this, 'dsc_select' ),
            'wrq',
            'wrq_settings'
        );

        register_setting( 'wrq_settings', 'display_on_categories_page' );

        add_settings_field(
            'display_on_product_price',
            'Display even if product has price',
            array( $this, 'dspr_select' ),
            'wrq',
            'wrq_settings'
        );

        register_setting( 'wrq_settings', 'display_on_product_price' );

    }

    function display_settings() {

    }

    function dsp_select( $args ) {

        $value = get_option('display_on_single_product_page');
        echo $this->views->markup( 'select', array(
            'id'        => 'display_on_single_product_page',
            'multiple'  => false,
            'value'     => $value,
            'options'   => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ) );

    }

    function dsc_select( $args ) {

        $value = get_option('display_on_categories_page');
        echo $this->views->markup( 'select', array(
            'id'        => 'display_on_categories_page',
            'multiple'  => false,
            'value'     => $value,
            'options'   => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ) );

    }

    function dspr_select( $args ) {

        $value = get_option('display_on_product_price');
        echo $this->views->markup( 'select', array(
            'id'        => 'display_on_product_price',
            'multiple'  => false,
            'value'     => $value,
            'options'   => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ) );

    }

}
new WRQ_Admin;
?>
