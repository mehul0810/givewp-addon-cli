<?php
/**
 * Flutterwave for Give | Filters
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

class Filters {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'give_payment_gateways', [ $this, 'register_gateway' ] );
	}

	/**
	 * Register Payment Gateway.
	 *
	 * @param array $gateways List of payment gateways.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function register_gateway( $gateways ) {
		$gateways['flutterwave_checkout'] = [
			'admin_label'    => esc_html__( 'Flutterwave - Checkout', 'mg-flutterwave-for-give' ),
			'checkout_label' => esc_html__( 'Checkout', 'mg-flutterwave-for-give' ),
		];

		return $gateways;
	}
}
