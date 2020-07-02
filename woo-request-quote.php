<?php
/*
Plugin Name: Woo Request Quote
Plugin URI: https://www.tudorache.me
Description: Displays a Request Quote button and popup form on the WooCommerce product page and category overview page
Version: 1.0.0
Author: Adrian Emil Tudorache
Author URI: https://www.tudorache.me
License: GPL2
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    define( 'WRQPATH', plugin_dir_path( __FILE__ ) );
    define( 'WRQBASE', plugin_basename( __FILE__ ) );
    define( 'WRQURL', plugin_dir_url( __FILE__ ) );

    require_once( WRQPATH . 'lib/init.php' );

}
?>
