<?php

class PP_Geocode {
	function __construct() {

		add_action( 'wp_ajax_bulk_geocode', array( $this, 'pp_bulk_geocode' ) );
		add_filter( 'um_user_pre_updating_profile_array', array( $this, 'check_update_array' ) );

	}

	function check_update_array( $to_update ) {

		$metas = $this->get_metas();

		$flag = false;

		foreach ( $metas as $meta ) {
			if ( isset( $to_update[ $meta ] ) ) {
				if ( $to_update[ $meta ] != get_user_meta( get_current_user_id(), $meta, true ) ) {
					$flag = true;
				}
			}
		}

		if ( $flag ) {
			add_action( 'um_after_user_updated', array( $this, 'pp_geocode' ) );
		}

		return $to_update;

	}

	function get_metas() {

		$metas = array( "pp_address" );

		$custom_meta = trim( um_get_option( 'pp_maps_geocode_meta' ) );

		if ( $custom_meta != "" ) {
			$metas = array_map( "trim", explode( ",", $custom_meta ) );
		}

		return $metas;

	}

	function get_address( $user_id ) {

		$metas  = $this->get_metas();
		$fields = array();
		foreach ( $metas as $meta ) {
			$value = get_user_meta( $user_id, $meta, true );
			if ( $value ) {
				$fields[] = $value;
			}
		}

		$address = implode( ",", $fields );

		return $address;

	}

	function pp_geocode( $user_id ) {

		$address = $this->get_address( $user_id );
		if ( $address == "" ) {

			delete_user_meta( $user_id, 'geo_latitude' );
			delete_user_meta( $user_id, 'geo_longitude' );

			return "no_address";
		}

		$base_url = "https://maps.googleapis.com/maps/api/geocode/json?address=";

		$url = $base_url . trim( str_replace( ' ', '+', $address ) );

		$google_maps_geocode_api_key = trim( um_get_option( 'pp_maps_geocode_api_key' ) );

		if ( ! empty( $google_maps_geocode_api_key ) ) {
			$url .= '&key=' . $google_maps_geocode_api_key;
		}

		$response = wp_remote_get( $url );

		if ( is_array( $response ) ) {
			$header = $response['headers'];
			$body   = $response['body'];
			$code   = wp_remote_retrieve_response_code( $response );

			if ( 200 == $code ) {

				try {

					$json = json_decode( $response['body'], true );

					if ( count( $json['results'] ) > 0 ) {

						$lat = $json['results'][0]['geometry']['location']['lat'];
						$lng = $json['results'][0]['geometry']['location']['lng'];

						update_user_meta( $user_id, 'geo_latitude', $lat );
						update_user_meta( $user_id, 'geo_longitude', $lng );

						return "success";

					}

				} catch ( Exception $ex ) {
					return "geocoding_failed";
				}
			} else {
				return "geocoding_failed";
			}
		}

		return "unknown";
	}

	function pp_bulk_geocode() {

		$limit = 9999;

		$res_no_address       = 0;
		$res_geocoding_failed = 0;
		$res_success          = 0;
		$res_unknown          = 0;
		$res_skipped          = 0;

		if ( isset( $_POST['pp-geocode-limit'] ) ) {
			if ( $_POST['pp-geocode-limit'] != "" ) {

				$limit = intval( $_POST['pp-geocode-limit'] );
			}

		}

		$users  = get_users();
		$failed = 0;

		$time_limit = ini_get( 'max_execution_time' );
		if ( empty( $time_limit ) || ! is_numeric( $time_limit ) ) {
			$time_limit = 270;
		} else {
			$time_limit -= ( $time_limit / 10 );
		}
		$delay = 100000; // one tenth of a second

		$counter = 0;

		foreach ( $users as $user ) {

			if ( $counter >= $limit ) {
				break;
			}

			$meta = get_userdata( $user->ID );
			$lat  = $meta->geo_latitude;
			$lng  = $meta->geo_longitude;

			if ( $lat == '' || $lng == '' ) {

				$result = $this->pp_geocode( $user->ID );

				if ( $result == "success" ) {
					$res_success += 1;
				} elseif ( $result == "geocoding_failed" ) {
					$res_geocoding_failed += 1;
				} elseif ( $result == "no_address" ) {
					$res_no_address += 1;
				} else {
					$res_unknown += 1;
				}

				$counter += 1;
				usleep( $delay );
			} else {
				$res_skipped += 1;
			}

		}

		wp_send_json_success( array(
			"result" => "Done ({$counter} users, {$res_skipped} skipped as they have been geocoded previously, {$res_success} successful, ${res_no_address} had no address, {$res_geocoding_failed} could not be geocoded by Google, {$res_unknown} failed for unknown reasons)",
		) );

		die();
	}
}
