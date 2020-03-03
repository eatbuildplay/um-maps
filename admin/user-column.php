<?php

add_filter( 'manage_users_columns', 'pp_manage_users_geo_column' );

function pp_manage_users_geo_column( $columns ) {

	$columns['geo'] = __( 'Geocoded' );

	return $columns;
}

add_action( 'manage_users_custom_column', 'pp_show_users_geo_column_content', 10, 3 );

function pp_show_users_geo_column_content( $value, $column_name, $user_id ) {

	if ( 'geo' == $column_name ) {

		$geo = get_user_meta( $user_id, 'geo_longitude', true );

		if ( empty( $geo ) ) {
			return '<i class="um-faicon-times-circle" style="color:red"></i>';
		} else {
			return '<i class="um-faicon-check-circle" style="color:green"></i>';
		}
	}

	return $value;
}

function pp_maps_geocode_metabox( $user ) {
	?>
	<h3>Map</h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="pp_address">Map Address</label>
			</th>
			<td>
				<input type="text"
					class="regular-text ltr"
					id="pp_address"
					name="pp_address"
					value="<?= esc_attr( get_user_meta( $user->ID, 'pp_address', true ) ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label for="geo_longitude">Longitude</label>
			</th>
			<td>
				<input type="text"
				       class="regular-text ltr"
				       id="geo_longitude"
				       name="geo_longitude"
				       value="<?= esc_attr( get_user_meta( $user->ID, 'geo_longitude', true ) ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label for="geo_latitude">Latitude</label>
			</th>
			<td>
				<input type="text"
				       class="regular-text ltr"
				       id="geo_latitude"
				       name="geo_latitude"
				       value="<?= esc_attr( get_user_meta( $user->ID, 'geo_latitude', true ) ); ?>">
				<p class="description">
					Get your longitude and latitude <a target="_blank" href="https://www.latlong.net/">Here</a>
				</p>
			</td>
		</tr>
	</table>
	<?php
}

// add the field to user's own profile editing screen
add_action(
	'edit_user_profile',
	'pp_maps_geocode_metabox'
);

// add the field to user profile editing screen
add_action(
	'show_user_profile',
	'pp_maps_geocode_metabox'
);

function pp_maps_geocode_metabox_save( $user_id ) {
	// check that the current user have the capability to edit the $user_id
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	// create/update user meta for the $user_id
	update_user_meta(
		$user_id,
		'pp_address',
		$_POST['pp_address']
	);

	update_user_meta(
		$user_id,
		'geo_latitude',
		$_POST['geo_latitude']
	);

	update_user_meta(
		$user_id,
		'geo_longitude',
		$_POST['geo_longitude']
	);
}

// add the save action to user's own profile editing screen update
add_action(
	'personal_options_update',
	'pp_maps_geocode_metabox_save'
);

// add the save action to user profile editing screen update
add_action(
	'edit_user_profile_update',
	'pp_maps_geocode_metabox_save'
);
