<?php

function pp_maps_get_members( $width = 300, $show_avatar = 1, $fields, $ids ) {
	$members = array();

	foreach ( $ids as $user_id ) {
		um_fetch_user( $user_id );
		$name = um_user( 'display_name' );

		$aka = um_user( 'um_aka' );
		$lat  = floatval( um_user( 'geo_latitude' ) );
		$lng  = floatval( um_user( 'geo_longitude' ) );

		$role = um_user( 'role' );
		$desc1 = um_user( 'memb__PrimaryELCStatus' );
		$desc2 = um_user( 'memb__SecondaryCoachStatus' );
		$desc3 = um_user( 'memb__NetworkMembershipType' );
		if ( $lat == 0 && $lng == 0 ) {
			continue;
		}

		$avatar = get_avatar( $user_id, 80 );
		$url    = um_user_profile_url();

		$content = '<div style="width:' . $width . 'px !important">';

		if ( $show_avatar == 1 ) {
			$content .= '<center><a href="' . $url . '">' . $avatar . '</a></center>';
		}

		$content .= '<center>';

		$content .= '<br /><a style="margin-top;5px;" href="' . $url . '"><strong>' . $name . '</strong></a>';

		$content .= '<br />';

		$content .= '<strong>' . $aka . '</strong></a>';

		foreach ( $fields as $map_field ) {
			$content .= "<br />";
			$raw = um_user( $map_field );
			if ( is_array( $raw ) ) {
				$content .= implode( ", ", $raw );
			} else {
				$content .= $raw;
			}
		}

		$content .= '<br />';

		$content .= '<strong>' . $desc1 . '</strong></a>';

		$content .= '<br />';

		$content .= '<strong>' . $desc2 . '</strong></a>';

		$content .= '<br />';

		$content .= '<strong>' . $desc3 . '</strong></a>';

		$content .= '<br />';

		$content .= '</center>';

		$content .= "</div>";

		$members[] = array(
			"lat"     => $lat,
			"lng"     => $lng,
			"name"    => $name,
			"content" => $content,
			"role"    => $role,
		);
	}

	um_reset_user();

	return $members;
}
