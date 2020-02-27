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

	if ( $args['maps'] != 1 ) {
		return;
	}

	$role_icons = array();
	foreach ( UM()->roles()->get_roles() as $slug => $title ) {
		$role_data = UM()->roles()->role_data( $slug );

		if ( isset( $role_data['maps_pin'] ) && $role_data['maps_pin'] != "" ) {
			$role_icons[ $slug ] = $role_data['maps_pin'];
		}
	}

	$users = get_users(
		array(
			'fields' => array('ID')
		)
	);
	foreach( $users as $user ) {
		$data['users'][] = $user->ID;
	}

	$members = pp_maps_get_members(
		$args['maps_info_width'],
		$args['maps_avatar'],
		$args['map_fields'],
		$data['users']
	);

	wp_enqueue_style( 'pp_maps_css' );

	wp_localize_script( 'pp_maps_display', "PP_MAPS", array(
		"members"      => $members,
		"role_icons"   => $role_icons,
		"type"         => $args['maps_type'],
		"max_zoom"     => $args['maps_max_zoom'],
		"icon"       	 => $args['maps_icon'],
		"scrollwheel"  => $args['maps_scrollwheel'],
		"initial_zoom" => $args['maps_initial_zoom'],
		"center_lat"   => isset( $args['maps_center_lat'] ) ? $args['maps_center_lat'] : "",
		"center_lng"   => isset( $args['maps_center_long'] ) ? $args['maps_center_long'] : "",
	) );

	wp_enqueue_script(
		'pp-maps-gmaps-init',
		UM_MAPS_PLUGIN_URI . 'assets/js/gmaps-init.js',
		array( 'jquery', 'pp_maps_cluster', 'pp_maps_display' ), 
		false,
		true
	);



	wp_enqueue_script( "pp_maps_spider" );
	wp_enqueue_script( "pp_maps_display" );

	echo '<div id="pp_map" style="height:' . $args['maps_height'] . 'px;width:100%;margin-bottom:25px;"></div>';


	$google_maps_js_api_key = trim( um_get_option( 'pp_maps_js_api_key' ) );

	wp_localize_script( 'pp-maps-gmaps-init', "UM_MAPS_API", array(
		"api_key" => empty( $google_maps_js_api_key ) ? '' : $google_maps_js_api_key,
	) );
}
