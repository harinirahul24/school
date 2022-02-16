<?php
/*
Plugin Name: Gravity Forms Instamojo Gateway
Plugin URI: https://wpgateways.com/products/instamojo-gateway-gravity-forms/
Description: Extends Gravity Forms to process payments with Instamojo payment gateway
Version: 1.0.0
Author: WP Gateways
Author URI: https://wpgateways.com
Text Domain: gf-instamojo
Domain Path: /languages
*/

class GF_Instamojo_Bootstrap {

	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_payment_addon_framework' ) ) {
			return;
		}

		load_plugin_textdomain( 'gf-instamojo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once( 'updates/updates.php' );
		require_once( 'class-gf-instamojo.php' );

		GFAddOn::register( 'GF_Instamojo' );
	}
}
add_action( 'gform_loaded', array( 'GF_Instamojo_Bootstrap', 'load' ), 5 );

function gf_instamojo() {
	return GF_Instamojo::get_instance();
}

function gf_instamojo_add_inr_currency( $currencies ) {
	if( !isset( $currencies['INR'] ) ) {
		$currencies['INR'] = array(
			'name'               => __( 'Indian Rupee', 'gravityforms' ),
			'symbol_left'        => '&#8377;',
			'symbol_right'       => '',
			'symbol_padding'     => ' ',
			'thousand_separator' => ',',
			'decimal_separator'  => '.',
			'decimals'           => 2
		);
	}
	return $currencies;
}
add_filter( 'gform_currencies', 'gf_instamojo_add_inr_currency', 100 );
