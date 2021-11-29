<?php
/**
 * MG - Flutterwave for Give | Helpers
 *
 * @since 1.0.0
 *
 * @package WordPress
 * @subpackage Flutterwave for Give
 * @since 1.0.0
 */

namespace MG\GiveWP\Flutterwave\Includes;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helpers {
	/**
	 * Get Public Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_public_key() {
		$public_key = give_get_option( 'mg_flutterwave_live_public_key' );

		// Use `public_key` of test mode.
		if ( give_is_test_mode() ) {
			$public_key = give_get_option( 'mg_flutterwave_test_public_key' );
		}

		return $public_key;
	}

	/**
	 * Get Secret Key.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_secret_key() {
		$secret_key = give_get_option( 'mg_flutterwave_live_secret_key' );

		// Use `secret_key` of test mode.
		if ( give_is_test_mode() ) {
			$secret_key = give_get_option( 'mg_flutterwave_test_secret_key' );
		}

		return $secret_key;
	}

	/**
	 * Get Endpoint URL.
	 *
	 * @since  1.0.3
	 * @access public
	 *
	 * @return string
	 */
	public static function get_endpoint_url() {
		return 'https://checkout.flutterwave.com';
	}

	/**
	 * Has Checkout Information?
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public static function has_checkout_information():bool {
		if (
			! empty( give_get_option( 'mg_flutterwave_checkout_title' ) ) &&
			! empty( give_get_option( 'mg_flutterwave_checkout_title' ) ) &&
			! empty( give_get_option( 'mg_flutterwave_checkout_title' ) )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Get Checkout Information.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_checkout_information():array {
		// Return checkout information, if exists.
		if ( self::has_checkout_information() ) {
			return [
				'title'       => give_get_option( 'mg_flutterwave_checkout_title' ),
				'description' => give_get_option( 'mg_flutterwave_checkout_description' ),
				'logo'        => give_get_option( 'mg_flutterwave_checkout_logo' ),
			];
		}

		return [
			'title'       => '',
			'description' => '',
			'logo'        => '',
		];
	}
}
