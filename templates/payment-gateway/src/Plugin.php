<?php
/**
 * Flutterwave for Give | Main File
 *
 * @package WordPress
 * @subpackage Flutterwave for Give
 * @since 1.0.0
 */

namespace MG\GiveWP\Flutterwave;

use MG\GiveWP\Flutterwave\Includes as Includes;
use MG\GiveWP\Flutterwave\Admin as Admin;
use MG\GiveWP\Flutterwave\Includes\PluginUpdater;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads and registers plugin functionality through WordPress hooks.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Registers functionality with WordPress hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register() {
		// Handle plugin activation and deactivation.
		register_activation_hook( MGFFW_PLUGIN_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( MGFFW_PLUGIN_FILE, [ $this, 'deactivate' ] );

		// Register services used throughout the plugin.
		add_action( 'plugins_loaded', [ $this, 'register_services' ] );

		// Load text domain.
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
	}

	/**
	 * Registers the individual services of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_services() {
		// Check for GiveWP plugin activation.
		if ( ! defined( 'GIVE_VERSION' ) ) {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-error is-dismissible">
						<p>
							<strong>
								<?php esc_html_e( 'Activation Error:', 'mg-flutterwave-for-give' ); ?>
							</strong>
							<?php esc_html_e( 'Please activate the GiveWP plugin to ensure that you can accept donations via Flutterwave payment gateway.', 'mg-flutterwave-for-give' ); ?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		// Load admin files.
		if ( is_admin() ) {
			new Admin\Actions();
			new Admin\Filters();
			new Admin\Upgrades();
		}

		// Load frontend files.
		new Includes\Filters();
		new Includes\Actions();
		new Includes\PaymentMethods\Checkout();

		$license_key = '';

		$this->can_update( $license_key );
	}

	/**
	 * Can update?
	 *
	 * @param string $license_key License key of the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function can_update( $license_key ) {
		if ( ! empty( $license_key ) ) {
			new PluginUpdater(
				'https://mehulgohil.com',
				MGFFW_PLUGIN_BASENAME,
				[
					'version'   => '1.0.0',
					'license'   => $license_key,
					'item_slug' => 'flutterwave-for-give',
					'author'    => 'Mehul Gohil',
					'url'       => home_url(),
					'beta'      => false,
				]
			);
		}
	}

	/**
	 * Loads the plugin's translated strings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'mg-flutterwave-for-give',
			false,
			dirname( plugin_basename( MGFFW_PLUGIN_FILE ) ) . '/languages/'
		);
	}

	/**
	 * Handles activation procedures during installation and updates.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param bool $network_wide Optional. Whether the plugin is being enabled on
	 *                           all network sites or a single site. Default false.
	 *
	 * @return void
	 */
	public function activate( $network_wide = false ) {}

	/**
	 * Handles deactivation procedures.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function deactivate() {}
}
