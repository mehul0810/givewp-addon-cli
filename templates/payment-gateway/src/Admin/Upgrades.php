<?php
/**
 * MG - Flutterwave for Give | Upgrades
 *
 * @since 1.0.0
 *
 * @package WordPress
 * @subpackage Flutterwave for Give
 * @since 1.0.0
 */

namespace MG\GiveWP\Flutterwave\Admin;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Upgrades {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'do_automatic_upgrades' ] );
	}

	/**
	 * Do Automatic Upgrades.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function do_automatic_upgrades() {
		$did_upgrade = false;
		$version     = get_option( 'mgffw_version', '1.0.0' );

		switch ( true ) {
			case version_compare( $version, '1.1.0', '<' ):
				$this->upgrade_to_110();
				$did_upgrade = true;
		}

		if ( $did_upgrade || version_compare( $version, MGFFW_VERSION, '<' ) ) {
			update_option( 'mgffw_version', MGFFW_VERSION, false );
		}
	}

	/**
	 * Upgrade routine for 1.1.0
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function upgrade_to_110() {
		$enabled_gateways = give_get_enabled_payment_gateways();
		$default_gateway  = give_get_option( 'default_gateway' );

		// Enable `Flutterwave Checkout`, if `Flutterwave` is enabled.
		if ( isset( $enabled_gateways['flutterwave'] ) ) {
			$enabled_gateways['flutterwave_checkout'] = $enabled_gateways['flutterwave'];
		}

		// Set `Flutterwave Checkout` as default gateway, if `Flutterwave` is set as default.
		if ( 'flutterwave' === $default_gateway ) {
			give_update_option( 'default_gateway', 'flutterwave_checkout' );
		}
	}
}
