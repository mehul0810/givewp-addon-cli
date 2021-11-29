<?php
/**
 * Flutterwave for Give | Checkout - Payment Method
 *
 * @package WordPress
 * @subpackage Flutterwave for Give
 * @since 1.0.0
 */

namespace MG\GiveWP\Flutterwave\Includes\PaymentMethods;

use Error;
use MG\GiveWP\Flutterwave\Includes\Helpers as Helpers;
use Give\Helpers\Form\Utils as FormUtils;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Checkout {
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'give_gateway_flutterwave_checkout', [ $this, 'process_donation' ], 99 );
		add_action( 'give_flutterwave_checkout_cc_form', [ $this, 'render_fields' ] );
		add_action( 'give_flutterwave_checkout_cc_form', [ $this, 'show_address_fields' ] );
		add_action( 'init', [ $this, 'listen_to_backend_response' ] );
	}

	/**
	 * Render Fields.
	 *
	 * @param int $form_id Donation Form ID.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public function render_fields( $form_id ) {
		// Bailout, if legacy donation form is used.
		if ( FormUtils::isLegacyForm( $form_id ) ) {
			return false;
		}

		printf(
			'
			<fieldset class="no-fields">
				<div style="display: flex; justify-content: center; margin-top: 20px;">
					<svg height="34" viewBox="0 0 173 34" width="173" xmlns="http://www.w3.org/2000/svg" class="flw" data-v-0fd12934=""><path clip-rule="evenodd" d="M57.6032 7.93335H57.6704C58.0061 7.93335 58.2075 8.20002 58.2075 8.53335V24C58.2075 24.2667 58.0061 24.5333 57.6704 24.5333C57.4018 24.5333 57.1333 24.3334 57.1333 24V8.46668C57.1333 8.20002 57.3347 7.93335 57.6032 7.93335ZM42.9004 8.73335H53.038C53.3737 8.73335 53.5751 9.06668 53.5751 9.33335C53.5751 9.60002 53.3737 9.86668 53.038 9.86668H43.4375V16.3333H52.0981C52.3666 16.3333 52.6352 16.5333 52.6352 16.8667C52.6352 17.1333 52.4337 17.4 52.0981 17.4H43.5046V24.0667C43.4375 24.4 43.1689 24.6667 42.8332 24.6667C42.4975 24.6667 42.229 24.4 42.229 24.0667V9.33335H42.2961C42.2961 9.00002 42.5647 8.73335 42.9004 8.73335ZM72.3061 13.5333C72.3061 13.2 72.0376 13 71.769 13C71.4334 13 71.2319 13.2667 71.2319 13.5333V20C71.2319 22.2 69.4193 23.9333 67.2038 23.8667C64.854 23.8667 63.5113 22.3333 63.5113 19.9333V13.5333C63.5113 13.2 63.2427 13 62.9742 13C62.7056 13 62.4371 13.2667 62.4371 13.5333V20.1333C62.4371 22.8667 64.1826 24.8667 67.0695 24.8667C68.815 24.9333 70.4934 24 71.2991 22.4667V24.1334C71.2991 24.4667 71.5676 24.6667 71.8362 24.6667C72.1719 24.6667 72.3733 24.4 72.3733 24.1334H72.3061V13.5333ZM82.5109 13.6C82.5109 13.8667 82.2423 14.0667 81.9738 14.0667H78.2813V21.4667C78.2813 23.1333 79.2212 23.7333 80.631 23.7333C81.101 23.7333 81.571 23.6667 81.9738 23.5333C82.2423 23.5333 82.4437 23.7333 82.4437 24C82.4437 24.2 82.3095 24.4 82.108 24.4667C81.571 24.6667 80.9667 24.7333 80.4296 24.7333C78.6841 24.7333 77.2071 23.7333 77.2071 21.6V14.0667H75.9315C75.663 14.0667 75.3944 13.8 75.3944 13.5333C75.3944 13.2667 75.663 13.0667 75.9315 13.0667H77.2071V9.86668C77.2071 9.60002 77.4085 9.33335 77.677 9.33335H77.7442C78.0127 9.33335 78.2813 9.60002 78.2813 9.86668V13.0667H81.9738C82.2423 13.0667 82.5109 13.3333 82.5109 13.6ZM91.1043 14.0667C91.3729 14.0667 91.6414 13.8667 91.6414 13.6C91.6414 13.3333 91.3729 13.0667 91.1043 13.0667H87.4118V9.86668C87.4118 9.60002 87.1433 9.33335 86.8747 9.33335H86.8076C86.5391 9.33335 86.3376 9.60002 86.3376 9.86668V13.0667H85.062C84.7935 13.0667 84.525 13.2667 84.525 13.5333C84.525 13.8 84.7935 14.0667 85.062 14.0667H86.3376V21.6C86.3376 23.7333 87.8146 24.7333 89.5602 24.7333C90.0973 24.7333 90.7015 24.6667 91.2386 24.4667C91.44 24.4 91.5743 24.2 91.5743 24C91.5743 23.7333 91.3729 23.5333 91.1043 23.5333C90.7015 23.6667 90.2316 23.7333 89.7616 23.7333C88.3517 23.7333 87.4118 23.1333 87.4118 21.4667V14.0667H91.1043ZM93.6555 18.8C93.6555 15.4 96.0053 12.7333 99.1607 12.7333C102.45 12.7333 104.532 15.4 104.532 18.8C104.532 19.0667 104.263 19.3333 103.995 19.3333H94.864C95.0654 22.2 97.1466 23.8667 99.4292 23.8667C100.839 23.8667 102.249 23.2667 103.189 22.2667C103.256 22.2 103.39 22.1333 103.525 22.1333C103.793 22.1333 104.062 22.4 104.062 22.6667C104.062 22.8 103.995 22.9333 103.86 23.0667C102.719 24.3333 101.041 25 99.3621 24.9333C96.2738 24.9333 93.6555 22.5333 93.6555 18.8667V18.8ZM94.7968 18.2667C94.9982 15.7333 96.8109 13.8 99.0936 13.8C101.712 13.8 103.122 15.9333 103.256 18.2667H94.7968ZM108.895 16.4C109.634 14.4667 111.447 13.0667 113.528 12.9333C113.864 12.9333 114.132 13.2 114.132 13.6C114.132 13.8667 113.931 14.2 113.595 14.2H113.528C111.044 14.4667 108.895 16.2667 108.895 20V24.2667C108.828 24.6 108.627 24.8 108.291 24.8C108.023 24.8 107.754 24.5333 107.754 24.2667V13.6C107.821 13.2667 108.023 13.0667 108.358 13.0667C108.627 13.0667 108.895 13.3333 108.895 13.6V16.4ZM131.856 12.3333C131.051 12.3333 130.379 12.8667 130.178 13.6667L128.231 19.8667L126.284 13.6667C126.082 12.8667 125.344 12.2667 124.471 12.2667H124.27C123.397 12.2667 122.658 12.8 122.457 13.6667L120.51 19.8L118.63 13.6C118.429 12.8667 117.757 12.2667 116.952 12.2667H116.885C116.012 12.2667 115.341 13 115.341 13.8667C115.341 14.1333 115.408 14.4 115.475 14.6666L115.475 14.6667L118.496 23.2667C118.697 24.1334 119.436 24.7334 120.376 24.8H120.51C121.383 24.8 122.121 24.2 122.39 23.3333L124.337 17.2L126.284 23.3333C126.485 24.2 127.291 24.8 128.164 24.8H128.298C129.238 24.8 130.043 24.2 130.245 23.2667L133.266 14.6C133.333 14.4 133.4 14.1333 133.4 13.9333V13.8667C133.4 13 132.729 12.3333 131.856 12.3333ZM136.556 12.9333C137.831 12.5333 139.107 12.2667 140.45 12.3333C142.329 12.3333 143.672 12.8667 144.679 13.7333C145.619 14.8 146.089 16.2 146.022 17.6V23.0667C146.022 24 145.283 24.7333 144.344 24.7333C143.471 24.7333 142.732 24.1333 142.665 23.2667C141.725 24.2667 140.382 24.8667 138.973 24.8C136.757 24.8 134.81 23.4667 134.81 21.0667C134.81 18.4 136.824 17.1334 139.711 17.1334C140.718 17.1334 141.725 17.2667 142.665 17.6V17.4C142.665 15.9333 141.792 15.2 140.047 15.2C139.241 15.2 138.436 15.2667 137.63 15.5333C137.496 15.6 137.294 15.6 137.16 15.6C136.354 15.6667 135.683 15.0667 135.683 14.2667C135.683 13.6667 136.019 13.1333 136.556 12.9333ZM142.732 20.2667C142.732 21.6 141.591 22.4 139.98 22.3333C138.905 22.3333 138.1 21.8 138.1 20.8667V20.8C138.1 19.8 139.04 19.1333 140.517 19.1333C141.255 19.1333 142.061 19.3333 142.732 19.6V20.2667ZM156.361 13.4667C156.562 12.7333 157.234 12.2667 157.972 12.2667C158.912 12.2667 159.651 13 159.651 13.8667V13.9333C159.651 14.2 159.583 14.4667 159.449 14.7333L155.824 23.3333C155.488 24.2 154.683 24.7334 153.81 24.8H153.608C152.668 24.7334 151.93 24.1334 151.661 23.2667L147.902 14.6667C147.767 14.4 147.7 14.1333 147.7 13.8667C147.767 12.9333 148.506 12.2667 149.379 12.2667C150.184 12.2667 150.856 12.8 151.057 13.5333L153.675 20.5333L156.361 13.4667ZM160.993 19.0667C161.128 22.4 164.015 25 167.371 24.8C168.915 24.8 170.392 24.3333 171.601 23.3333C171.937 23.0667 172.071 22.7333 172.071 22.3333V22.2667C172.071 21.5333 171.467 20.9333 170.728 20.9333C170.46 20.9333 170.124 21 169.923 21.2C169.184 21.7333 168.311 22.0667 167.438 22C165.961 22.0667 164.686 21.0667 164.484 19.6H171.4C172.272 19.5333 172.944 18.8 172.877 17.9333V17.6667C172.877 14.6667 170.258 12.1333 167.036 12.2C163.477 12.2 160.993 15.0667 160.993 18.5333V19.0667ZM167.036 15C165.626 15 164.686 16 164.417 17.6H169.587C169.385 16.0667 168.446 15 167.036 15Z" fill-rule="evenodd" class="flw__type" data-v-0fd12934=""></path> <path fill="#f5a623" clip-rule="evenodd" d="M24.9077 0.266665C40.0134 -2 30.3457 10.8667 25.6462 14.4667C28.8687 16.9333 32.1584 20.4 33.5683 24.3333C36.1866 31.5333 29.7415 32.6 24.9077 30.8C19.6039 28.9333 14.9043 24.9333 11.8161 20.2667C10.9433 20.2667 10.0034 20.1333 9.13062 19.8667C10.8762 24.8 11.6147 29.8667 11.1447 34C11.1447 25.6667 7.18366 17.4 1.47706 10.5333C-0.537031 8.13333 1.5442 6.33333 3.35688 8.66667C4.57713 10.3565 5.69794 12.115 6.7137 13.9333C8.66066 7.13333 18.3283 1.2 24.9077 0.266665ZM22.7593 12.5333C25.7133 10.7333 34.7096 1.06667 26.3175 1.93333C21.4837 2.46667 15.6429 6.93333 13.2259 9.8C16.5828 9.4 20.0067 10.8667 22.7593 12.5333ZM13.1588 11.9333C15.4414 11.7333 17.9255 12.9333 19.8053 14.1333C17.9926 15 15.9785 15.5333 13.8973 15.6667C10.809 15.6667 10.2048 12.2 13.1588 11.9333ZM14.4344 20C17.1199 23 20.8124 25.9333 24.7734 27C27.056 27.6 29.6072 27.3333 28.6673 24.0667C27.7274 21.0667 25.3105 18.4 22.9607 16.4C22.2894 16.8667 21.5509 17.3333 20.8124 17.6667C18.7983 18.8 16.6499 19.6 14.4344 20Z" fill-rule="evenodd" class="flw__mark" data-v-0fd12934=""></path></svg>
				</div>
				<p style="text-align: center;"><b>%1$s</b></p>
				<p style="text-align: center;">
					<b>%2$s</b> %3$s
				</p>
			</fieldset>
			',
			esc_html__( 'Make your donations quickly and securely with Flutterwave.', 'mg-flutterwave-for-give' ),
			esc_html__( 'How it works:', 'mg-flutterwave-for-give' ),
			esc_html__( 'You will be redirected to an Flutterwave checkout page after you click the Donate Now button where you can securely make your donation. You will then be brought back to this page to view your receipt.', 'mg-flutterwave-for-give' )
		);

		return true;
	}

	/**
	 * Process donation.
	 *
	 * @param array $data List of data submitted.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function process_donation( $data ) {
		// Check for any stored errors.
		$errors = give_get_errors();

		if ( ! $errors ) {
			$form_id        = ! empty( $data['post_data']['give-form-id'] ) ? intval( $data['post_data']['give-form-id'] ) : false;
			$unique_session = $data['purchase_key'];
			$donor_email    = $data['user_email'];
			$donor_name     = ! empty( $data['give_last'] ) ? "{$data['give_first']} {$data['give_last']}" : $data['give_first'];
			$form_name      = $data['post_data']['give-form-title'];

			// Setup the donation details which need to send to PayFast.
			$data_to_send = [
				'price'           => $data['price'],
				'give_form_title' => $form_name,
				'give_form_id'    => $form_id,
				'give_price_id'   => isset( $data['post_data']['give-price-id'] ) ? $data['post_data']['give-price-id'] : '',
				'date'            => $data['date'],
				'user_email'      => $donor_email,
				'purchase_key'    => $unique_session,
				'currency'        => give_get_currency( $form_id ),
				'user_info'       => $data['user_info'],
				'status'          => 'pending',
				'gateway'         => $data['gateway'],
			];

			// Record the pending payment.
			$donation_id = give_insert_payment( $data_to_send );

			// Verify donation payment.
			if ( ! $donation_id ) {
				// Record the error.
				give_record_gateway_error(
					esc_html__( 'Payment Error', 'mg-flutterwave-for-give' ),
					sprintf(
					/* translators: %s: payment data */
						esc_html__( 'Payment creation failed before processing payment via Flutterwave. Payment data: %s', 'mg-flutterwave-for-give' ),
						wp_json_encode( $data )
					),
					$donation_id
				);

				// Problems? Send back.
				give_send_back_to_checkout( '?payment-mode=' . $data['post_data']['payment-mode'] );
			}

			// Auto set payment to abandoned in one hour if donor is not able to donate in that time.
			wp_schedule_single_event( time() + HOUR_IN_SECONDS, 'give_flutterwave_set_donation_abandoned', [ $donation_id ] );

			// Prepare required parameters for the API call.
			$secret_key = Helpers::get_secret_key();
			$url        = 'https://api.flutterwave.com/v3/payments';
			$body_args  = [
				'tx_ref'         => $unique_session,
				'amount'         => give_donation_amount( $donation_id ),
				'currency'       => give_get_currency( $form_id ),
				'redirect_url'   => site_url() . '?backend_listener=flutterwave_checkout',
				'customer'       => [
					'name'  => $donor_name,
					'email' => $donor_email,
				],
				'meta'           => [
					'donation_id' => $donation_id,
					'form_id'     => $form_id,
					'form_name'   => $form_name,
				],
				'customizations' => Helpers::get_checkout_information(),
			];

			// Safely call remote post to the Flutterwave API.
			$response = wp_safe_remote_post(
				$url,
				[
					'headers' => [
						'Authorization' => "Bearer {$secret_key}",
						'Content-Type'  => 'application/json',
					],
					'body'    => wp_json_encode( $body_args ),
				]
			);

			// Get response body and code.
			$response_body = json_decode( wp_remote_retrieve_body( $response ) );
			$response_code = wp_remote_retrieve_response_code( $response );

			// Success. Send donor to hosted checkout page.
			if ( 200 === $response_code && 'success' === $response_body->status ) {
				wp_redirect( $response_body->data->link );
				give_die();
			}

			// Problems? Send back to donation form.
			give_record_gateway_error(
				esc_html__( 'Payment Error', 'mg-flutterwave-for-give' ),
				sprintf(
					/* translators: %s: payment data */
					esc_html__( 'Payment creation failed before processing payment via Flutterwave. Payment data: %s', 'mg-flutterwave-for-give' ),
					print_r( $response, true )
				),
				$donation_id
			);
			give_set_error( 'processing-error', esc_html__( 'Unable to process donation. Please try again!', 'mg-flutterwave-for-give' ) );
			give_send_back_to_checkout( '?payment-mode=' . $data['post_data']['payment-mode'] );
		}
	}

	/**
	 * Print CC field in donation form conditionally.
	 *
	 * @param int $form_id Donation Form ID.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return bool
	 */
	public function show_address_fields( $form_id ) {
		if ( give_is_setting_enabled( give_get_option( 'mg_flutterwave_billing_details', 'disabled' ) ) ) {
			give_default_cc_address_fields( $form_id );
			return true;
		}

		return false;
	}

	/**
	 * Listen to backend response.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return void
	 */
	public function listen_to_backend_response():void {
		$get_data         = give_clean( $_GET );
		$backend_listener = ! empty( $get_data['backend_listener'] ) ? $get_data['backend_listener'] : '';

		// Bailout, if `backend listener` is not flutterwave checkout.
		if ( 'flutterwave_checkout' !== $backend_listener ) {
			return;
		}

		$unique_session = ! empty( $get_data['tx_ref'] ) ? $get_data['tx_ref'] : '';
		$transaction_id = ! empty( $get_data['transaction_id'] ) ? $get_data['transaction_id'] : false;

		// Bailout, if `unique session` is empty.
		if ( ! $unique_session ) {
			return;
		}

		$donation_id = give_get_donation_id_by_key( $unique_session );

		// Bailout, if `donation id` is empty.
		if ( ! $donation_id ) {
			return;
		}

		$redirect_url = FormUtils::getLegacyFailedPageURL();

		if ( ! $transaction_id ) {
			// Update donation status.
			give_update_payment_status( $donation_id, $get_data['status'] );

			$form_id = give_get_payment_form_id( $donation_id );

			if ( 'cancelled' === $get_data['status'] ) {
				$redirect_url = FormUtils::createFailedPageURL( get_permalink( $form_id ) );
			}
		} else {
			// Update donation status to `completed`.
			give_update_payment_status( $donation_id, 'publish' );

			give_set_payment_transaction_id( $donation_id, $transaction_id );

			$redirect_url = FormUtils::getSuccessPageURL();
		}

		wp_redirect( $redirect_url );
		give_die();
	}
}
