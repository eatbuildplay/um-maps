<div class="um-admin-metabox">
	<?php
	$searchable_fields = UM()->builtin()->all_user_fields();
	$info_fields       = array();
	foreach ( $searchable_fields as $key => $arr ) {
		$info_fields[ $key ] = isset( $arr['title'] ) ? $arr['title'] : '';
	}

	$post_id        = get_the_ID();
	$_um_map_fields = get_post_meta( $post_id, '_um_map_fields', true );

	UM()->admin_forms( array(
		'class'     => 'um-member-directory-map um-half-column',
		'prefix_id' => 'um_metadata',
		'fields'    => array(
			array(
				'id'      => '_um_maps',
				'type'    => 'checkbox',
				'label'   => __( 'Enable Map', 'pp-maps' ),
				'tooltip' => __( 'Enable map to show in the directory.', 'pp-maps' ),
				'value'   => UM()->query()->get_meta_value( '_um_maps' ),
			),
			array(
				'id'          => '_um_maps_location',
				'type'        => 'select',
				'multi'       => false,
				'label'       => __( 'Map Location', 'pp-maps' ),
				'tooltip'     => __( 'Choose a location in the template to render the map.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_location' ),
				'options'     => array(
					'below_search'   => 'Below Search Bar',
					'above_search'   => 'Above Search Bar',
					'below_profiles' => 'Below Profiles'
				),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_height',
				'type'        => 'text',
				'label'       => __( 'Map Height', 'pp-maps' ),
				'tooltip'     => __( 'Customize the map display height.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_height' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_type',
				'type'        => 'select',
				'multi'       => false,
				'label'       => __( 'Map type', 'pp-maps' ),
				'tooltip'     => __( 'Default map type shown when the map loads.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_type' ),
				'options'     => array(
					'road'      => 'Road',
					'hybrid'    => 'Hybrid',
					'satellite' => 'Satellite',
					'terrain'   => 'Terrain'
				),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_icon',
				'type'        => 'text',
				'label'       => __( 'Custom marker icon URL', 'pp-maps' ),
				'tooltip'     => __( 'Choose a custom marker icon URL.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_icon' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_initial_zoom',
				'type'        => 'text',
				'label'       => __( 'Initial zoom level', 'pp-maps' ),
				'tooltip'     => __( 'Choose the initial zoom level (between 1-12, higher numbers are more zoom in).', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_initial_zoom' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_max_zoom',
				'type'        => 'text',
				'label'       => __( 'Maximum zoom level', 'pp-maps' ),
				'tooltip'     => __( 'Choose the maximum zoom level (1-12) offered to the user.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_max_zoom' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_center_lat',
				'type'        => 'text',
				'label'       => __( 'Map center latitude', 'pp-maps' ),
				'tooltip'     => __( 'Initial latitude map center at load.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_center_lat' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_center_long',
				'type'        => 'text',
				'label'       => __( 'Map center longitude', 'pp-maps' ),
				'tooltip'     => __( 'Initial longitude map center at load.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_center_long' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_scrollwheel',
				'type'        => 'checkbox',
				'label'       => __( 'Enable mouse wheel zooming', 'pp-maps' ),
				'tooltip'     => __( 'If turned on, users will be able use their mouse wheel to zoom in on the map.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_scrollwheel' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_info_width',
				'type'        => 'text',
				'label'       => __( 'Width in pixels', 'pp-maps' ),
				'tooltip'     => __( 'Map width defined in pixels.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_info_width' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'          => '_um_maps_avatar',
				'type'        => 'checkbox',
				'label'       => __( 'Show avatar', 'pp-maps' ),
				'tooltip'     => __( 'Show the profile avatar.', 'pp-maps' ),
				'value'       => UM()->query()->get_meta_value( '_um_maps_avatar' ),
				'conditional' => array( '_um_maps', '=', 1 )
			),
			array(
				'id'                  => '_um_map_fields',
				'type'                => 'multi_selects',
				'label'               => __( 'Choose field(s) to display in info window', 'pp-maps' ),
				'value'               => $_um_map_fields,
				'conditional'         => array( '_um_maps', '=', 1 ),
				'options'             => $info_fields,
				'add_text'            => __( 'Add New Custom Field', 'pp-maps' ),
				'show_default_number' => 1,
			)
		)
	) )->render_form();
	?>
	<div class="um-admin-clear"></div>
</div>
