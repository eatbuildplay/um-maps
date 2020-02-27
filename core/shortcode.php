<?php

function pp_maps_shortcode( $atts ) {

	$atts = shortcode_atts( array(
		'roles'       => 'member',
		'height'      => '300',
		'type'        => 'road',
		'icon'        => '',
		'initialzoom' => '',
		'maxzoom'     => '',
		'showavatar'  => '1',
		'fields'      => 'description',
		'scroll'      => '1',
		'lat'         => '',
		'long'        => '',
	), $atts, 'pp_maps' );

	$role_icons = array();

	foreach ( UM()->roles()->get_roles() as $slug => $title ) {

		$role_data = UM()->roles()->role_data( $slug );

		if ( isset( $role_data['maps_pin'] ) && $role_data['maps_pin'] != "" ) {
			$role_icons[ $slug ] = $role_data['maps_pin'];
		}

	}

	$member_roles = array_map( 'trim', explode( ',', $atts['roles'] ) );

	$members_args = array(
		'meta_query' => array(
			array(
				'key'     => 'role',
				'value'   => $member_roles,
				'compare' => 'IN',
			),
		),
		'fields'     => 'id',
	);

	$members_ids = get_users( $members_args );

	$fields = array_map( 'trim', explode( ',', $atts['fields'] ) );

	$members = pp_maps_get_members( 200, $atts['showavatar'], $fields, $members_ids );

	wp_enqueue_style( 'pp_maps_css' );

	wp_localize_script( 'pp_maps_display', "PP_MAPS", array(
		"members"      => $members,
		"role_icons"   => $role_icons,
		"type"         => $atts['type'],
		"max_zoom"     => $atts['maxzoom'],
		"icon"         => $atts['icon'],
		"scrollwheel"  => $atts['scroll'],
		"initial_zoom" => $atts['initialzoom'],
		"center_lat"   => $atts['lat'],
		"center_lng"   => $atts['long'],
	) );

	wp_enqueue_script( "pp_maps_display" );

	wp_enqueue_script( 'pp-maps-gmaps-init', UM_MAPS_PLUGIN_URI . 'assets/js/gmaps-init.js', array( 'jquery' ), false, true );

	$google_maps_js_api_key = trim( um_get_option( 'pp_maps_js_api_key' ) );

	wp_localize_script( 'pp-maps-gmaps-init', "UM_MAPS_API", array(
		"api_key" => empty( $google_maps_js_api_key ) ? '' : $google_maps_js_api_key,
	) );

	return '<div id="pp_map" style="height:' . $atts['height'] . 'px;width:100%;margin-bottom:25px;"></div>';

}

add_shortcode( 'pp_maps', 'pp_maps_shortcode' );
