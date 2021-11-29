<?php
/**
 * MG - Flutterwave for Give | Admin Filters
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
		add_filter( 'give_get_sections_gateways', [ $this, 'register_sections' ] );
		add_filter( 'give_get_settings_gateways', [ $this, 'register_settings' ] );
		add_filter( 'plugin_action_links_' . MGFFW_PLUGIN_BASENAME, [ $this, 'add_plugin_links' ] );
	}

	/**
	 * Register Admin Section.
	 *
	 * @param array $sections List of sections.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function register_sections( $sections ) {
		$sections['flutterwave'] = esc_html__( 'Flutterwave', 'mg-flutterwave-for-give' );

		return $sections;
	}

	/**
	 * Register Admin Settings.
	 *
	 * @param array $settings List of settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function register_settings( $settings ) {

		$current_section = give_get_current_setting_section();

		switch ( $current_section ) {
			case 'flutterwave':
				$settings = [
					[
						'type' => 'title',
						'id'   => 'mgffw_title_start',
					],
					[
						'name' => esc_html__( 'Manage License', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the license key to receive automatic updates and support.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_license_key',
						'type' => 'manage_license',
					],
					[
						'name' => esc_html__( 'Checkout - Title', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the title to be displayed on the hosted payment page or modal.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_checkout_title',
						'type' => 'text',
					],
					[
						'name' => esc_html__( 'Checkout - Description', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the description to be displayed on the hosted payment page or modal.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_checkout_description',
						'type' => 'text',
					],
					[
						'name' => esc_html__( 'Checkout - Logo', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please add the logo to be displayed on the hosted payment page or modal.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_checkout_logo',
						'type' => 'file',
					],
					[
						'name' => esc_html__( 'Test - Public Key', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the Public Key of your Flutterwave Test account.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_test_public_key',
						'type' => 'text',
					],
					[
						'name' => esc_html__( 'Live - Public Key', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the Public Key of your Flutterwave Live account.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_live_public_key',
						'type' => 'text',
					],
					[
						'name' => esc_html__( 'Test - Secret Key', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the Secret Key of your Flutterwave Test account.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_test_secret_key',
						'type' => 'api_key',
					],
					[
						'name' => esc_html__( 'Live - Secret Key', 'mg-flutterwave-for-give' ),
						'desc' => esc_html__( 'Please enter the Secret Key of your Flutterwave Live account.', 'mg-flutterwave-for-give' ),
						'id'   => 'mg_flutterwave_live_secret_key',
						'type' => 'api_key',
					],
					[
						'type' => 'sectionend',
						'id'   => 'mgffw_title_end',
					],
				];
				break;
		}
		return $settings;
	}

	/**
	 * This function is used to add settings page link on plugins page.
	 *
	 * @param array $links List of links on plugin page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function add_plugin_links( $links ) {

		$links['settings'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url_raw( admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=gateways&section=flutterwave' ) ),
			__( 'Settings', 'mg-flutterwave-for-give' )
		);

		asort( $links );

		return $links;
	}
}
