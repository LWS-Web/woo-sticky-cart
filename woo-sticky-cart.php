<?php
/*
Plugin Name: Woo Sticky Cart
Plugin URI: -
Description: A sticky cart for WooCommerce
Author: Mo
Version: 0.0.1
Author URI: -
License: GPLv2
Text Domain: woo-sc
Domain Path: /lang
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
     * Enqueue the stylesheet, js (in wp_footer)
     **/
    function woo_sc_scripts() {
    	if ( ! is_admin() ) {
    		wp_enqueue_style( 'dashicons' );
        	wp_enqueue_style( 'woo-sc', plugins_url('/css/style.css', __FILE__) );
        	wp_enqueue_script( 'woo-sc', plugins_url('/js/functions.js', __FILE__), array('jquery'), '', true);
        }  
    }
    add_action('wp_enqueue_scripts', 'woo_sc_scripts');


    /**
     * Inserts and displays the template in wp_footer
     **/
    function woo_sc_template() {
        	global $woocommerce;

        	// Dont display if we are on the Cart or Checkout page
			if ( ! is_cart() && ! is_checkout() ) {

        		echo    '<div id="woo-sc-container">';
        		echo 		'<div id="woo-sc-handle">';
                 	           woo_sc_handle_template(); // Get the button template from the function
            	echo 		'</div>';
        		echo 		'<div id="woo-sc-content" class="cf">';
                 	           // Check for WooCommerce 2.0 and display the cart widget
                    	        if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
                        	        the_widget( 'WC_Widget_Cart', 'title=' );
                            	} else {
                                	the_widget( 'WooCommerce_Widget_Cart', 'title=' );
                         	   } // END WooCommerce 2.0 check
        		echo  		'</div>';
        		echo 	'</div>';

        	}//END Dont display if we are on the Cart or Checkout page

    }
    add_action('wp_footer', 'woo_sc_template', 100);


    /**
     * The button template, called in the template and ajax function, this is also the slide-handle
     **/
    function woo_sc_handle_template() {
        global $woocommerce;

	        echo 	'<button class="woo-sc-button">';
	        echo 		'<span class="label">'.__("Cart", "woocommerce").'</span>';
	        echo 		'<span class="count">(' . sprintf( _n( '%d', '%d', intval( $woocommerce->cart->get_cart_contents_count() ) ), intval( $woocommerce->cart->get_cart_contents_count() ) ) . ')</span> ';
	        echo 		wp_kses_post( $woocommerce->cart->get_cart_total() );
	        echo 	'</button>';
    }// END woo_sc_handle_template


    /**
     * Update the button via ajax, when products are added to cart
     **/
    function woo_sc_fragments( $fragments ) {
        global $woocommerce;

        ob_start();
        woo_sc_handle_template(); // Get the button template from the function
        $fragments['.woo-sc-button'] = ob_get_clean();
        return $fragments;
    }
    add_filter('add_to_cart_fragments', 'woo_sc_fragments');


}// END Check if WooCommerce is active