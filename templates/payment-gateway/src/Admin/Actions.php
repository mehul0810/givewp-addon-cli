<?php
/**
 * MG - Flutterwave for Give | Admin Actions
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

class Actions {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'give_admin_field_manage_license', [ $this, 'render_manage_license_field' ], 10, 2 );
		add_action( 'wp_ajax_mgffw_activate_license', [ $this, 'activate_license' ] );
		add_action( 'wp_ajax_mgffw_deactivate_license', [ $this, 'deactivate_license' ] );
	}

	/**
	 * Register Assets.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_assets() {
		wp_enqueue_style( 'mgffw-admin', MGFFW_PLUGIN_URL . '/assets/dist/css/mgffw-admin.css', '', MGFFW_VERSION );
		wp_enqueue_script( 'mgffw-admin', MGFFW_PLUGIN_URL . '/assets/dist/js/mgffw-admin.js', '', MGFFW_VERSION, true );
	}

	/**
	 * Render Manage License Field.
	 *
	 * @param array  $value        List of settings parameters.
	 * @param string $option_value Option value.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return mixed
	 */
	public function render_manage_license_field( $value, $option_value ) {
		$license_key   = get_option( 'mgffw_license_key' );
		$wrapper_class = ! empty( $value['wrapper_class'] ) ? 'class="' . $value['wrapper_class'] . '"' : '';
		?>
		<tr valign="top" <?php echo esc_html( $wrapper_class ); ?>>
			<td class="give-cta-notice-wrap" style="padding: 0;" colspan="2">
				<div class="mgffw-manage-license-wrap">
					<h3>
						<?php echo esc_attr( $value['title'] ); ?>
					</h3>
					<div class="mgffw-manage-license">
						<div><?php esc_html_e( 'License Key', 'mg-flutterwave-for-give' ); ?></div>
						<div>
							<input id="mgffw-license-key" type="text" name="mgffw_license_key" value="<?php echo $license_key ? $license_key : ''; ?>"/>
						</div>
						<div>
							<button
								disabled="disabled"
								id="mgffw-activate-btn"
								class="button button-primary <?php echo $license_key ? 'mgffw-hidden' : ''; ?>"
								data-processing-text="<?php esc_html_e( 'Activating...', 'mg-flutterwave-for-give' ); ?>"
								data-default-text="<?php esc_html_e( 'Activate License', 'mg-flutterwave-for-give' ); ?>"
							>
								<?php esc_html_e( 'Activate License', 'mg-flutterwave-for-give' ); ?>
							</button>
							<button
								id="mgffw-deactivate-btn"
								class="button button-secondary <?php echo ! $license_key ? 'mgffw-hidden' : ''; ?>"
								data-processing-text="<?php esc_html_e( 'Deactivating...', 'mg-flutterwave-for-give' ); ?>"
								data-default-text="<?php esc_html_e( 'Deactivate License', 'mg-flutterwave-for-give' ); ?>"
							>
								<?php esc_html_e( 'Deactivate License', 'mg-flutterwave-for-give' ); ?>
							</button>
						</div>
					</div>
					<p class="give-field-description">
						<?php echo esc_attr( $value['desc'] ); ?>
					</p>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Activate License.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function activate_license() {
		$post_data   = give_clean( $_POST );
		$license_key = ! empty( $post_data['license_key'] ) ? $post_data['license_key'] : false;
		$url         = add_query_arg(
			[
				'edd_action' => 'activate_license',
				'item_name'  => 'Flutterwave for Give',
				'license'    => $license_key,
				'url'        => site_url(),
			],
			'https://mehulgohil.com'
		);

		$response      = wp_remote_get( $url );
		$response_body = wp_remote_retrieve_body( $response );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			$response_data = json_decode( $response_body );

			if (
				isset( $response_data->success ) &&
				$response_data->success &&
				'valid' === $response_data->license
			) {
				update_option( 'mgffw_license_key', $license_key );
				update_option( 'mgffw_license_information', (array) $response_data );
				wp_send_json_success( $response_data );
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Deactivate License.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function deactivate_license() {
		$post_data   = give_clean( $_POST );
		$license_key = ! empty( $post_data['license_key'] ) ? $post_data['license_key'] : false;
		$url         = add_query_arg(
			[
				'edd_action' => 'deactivate_license',
				'item_name'  => 'Flutterwave for Give',
				'license'    => $license_key,
				'url'        => site_url(),
			],
			'https://mehulgohil.com'
		);

		$response      = wp_remote_get( $url );
		$response_body = wp_remote_retrieve_body( $response );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			$response_data = json_decode( $response_body );

			if (
				isset( $response_data->success ) &&
				$response_data->success
			) {
				update_option( 'mgffw_license_key', '' );
				update_option( 'mgffw_license_information', (array) $response_data );
				wp_send_json_success( $response_data );
			} else {
				wp_send_json_error();
			}
		} else {
			wp_send_json_error();
		}
	}
}
