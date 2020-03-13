<?php

add_action( 'um_pre_directory_shortcode', 'pp_place_map' );

function pp_place_map( $args ) {
	if ( isset( $args['maps_location'] ) ) {
		$location = $args['maps_location'];
	}

	if ( $location == "below_search" ) {
		add_action( 'um_members_directory_head', 'pp_show_map' );
	} else if ( $location == "above_search" ) {
		add_action( 'um_members_directory_before_head', 'pp_show_map', 1 );
	} else if ( $location == "below_profiles" ) {
		add_action( 'um_members_directory_footer', 'pp_show_map' );
	}
}

function pp_show_map( $args ) {

	$google_maps_js_api_key = trim( um_get_option( 'pp_maps_js_api_key' ) );

	if ( $args['maps'] != 1 ) {
		return;
	}

	wp_enqueue_style( 'pp_maps_css' );

	wp_localize_script( 'pp_maps_display', "PP_MAPS", array(
		"type"         => $args['maps_type'],
		"max_zoom"     => $args['maps_max_zoom'],
		"icon"       	 => $args['maps_icon'],
		"scrollwheel"  => $args['maps_scrollwheel'],
		"initial_zoom" => $args['maps_initial_zoom'],
		"center_lat"   => isset( $args['maps_center_lat'] ) ? $args['maps_center_lat'] : "",
		"center_lng"   => isset( $args['maps_center_long'] ) ? $args['maps_center_long'] : "",
	) );

	wp_enqueue_script(
		'pp-maps-gmaps',
		'https://maps.google.com/maps/api/js?key=' . $google_maps_js_api_key,
		array(),
		false,
		true
	);

	wp_enqueue_script( "pp_maps_cluster" );
	wp_enqueue_script( "pp_maps_display" );

	echo '<div id="pp_map" style="height:' . $args['maps_height'] . 'px;width:100%;margin-bottom:25px;"></div>';

	wp_localize_script( 'pp_maps_display', "umMapsBaseUrl", UM_MAPS_PLUGIN_URI );

}
