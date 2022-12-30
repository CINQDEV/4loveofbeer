<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SWK_license {
	public function __construct(){
		add_action( 'admin_init', array($this, 'swk_register_option') );
		add_action( 'admin_menu', array($this, 'swk_activate_license') );
		add_action( 'admin_menu', array($this, 'swk_deactivate_license') );
		add_action( 'admin_notices', array($this, 'swk_admin_notices') );
	}

	public function swk_register_option() {
		// creates our settings in the options table
		register_setting('swk_license', 'swiss_knife_license_key', 'swk_edd_sanitize_license' );
	}

	public function swk_edd_sanitize_license( $new ) {
		$old = get_option( 'swiss_knife_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'swiss_knife_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}

	/************************************
	* Activate a license key
	*************************************/
	public function swk_activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_license_activate'] ) ) {
			ob_start();
			// run a quick security check
		 	if( ! check_admin_referer( 'swk_nonce', 'swk_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( $_POST['swiss_knife_license_key'] );
			update_option( 'swiss_knife_license_key', $license );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_id'  => SWISS_KNIFE_ITEM_ID, // the id of our product in EDD
				'item_name'  => urlencode( SWISS_KNIFE_ITEM_NAME ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( SWISS_KNIFE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'disabled' :
						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), SWISS_KNIFE_ITEM_NAME );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}

				}

			}

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=swk_license');
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			update_option( 'swiss_knife_license_status', $license_data->license );
			wp_redirect( admin_url( 'plugins.php?page=' . SWISS_KNIFE_LICENSE_PAGE ) );
			exit();
		}
	}

	/***********************************************
	* Illustrates how to deactivate a license key.
	***********************************************/
	public function swk_deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['edd_license_deactivate'] ) ) {
			ob_start();
			// run a quick security check
		 	if( ! check_admin_referer( 'swk_nonce', 'swk_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'swiss_knife_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'  => SWISS_KNIFE_ITEM_ID, // the id of our product in EDD
				'item_name'  => urlencode( SWISS_KNIFE_ITEM_NAME ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( SWISS_KNIFE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			//echo "<pre>"; print_r($response); "</pre>"; exit;

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'plugins.php?page=' . SWISS_KNIFE_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( 'swiss_knife_license_key' );
				delete_option( 'swiss_knife_license_status' );
			}

			wp_redirect( admin_url( 'admin.php?page=swk_license') );
			exit();

		}
	}

	/************************************
	* Check if a license key is still valid
	*************************************/

	public function swk_check_license() {

		global $wp_version;

		$license = trim( get_option( 'swiss_knife_license_key' ) );

		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_name' => urlencode( SWISS_KNIFE_ITEM_NAME ),
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( SWISS_KNIFE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->license == 'valid' ) {
			echo 'valid'; exit;
			// this license is still valid
		} else {
			echo 'invalid'; exit;
			// this license is no longer valid
		}
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public function swk_admin_notices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

			switch( $_GET['sl_activation'] ) {

				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

			}
		}
	}
}

new SWK_license();
