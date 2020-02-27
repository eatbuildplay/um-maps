<?php

add_filter( 'um_settings_structure', 'pp_add_maps_settings', 10, 1 );
function pp_add_maps_settings( $settings ) {

	$key                                        = ! empty( $settings['extensions']['sections'] ) ? 'pp-maps' : '';
	$settings['extensions']['sections'][ $key ] = array(
		'title'  => __( 'Maps', 'pp-maps' ),
		'fields' => array(
			array(
				'id'      => 'pp_maps_js_api_key',
				'type'    => 'text',
				'label'   => __( 'Google Maps JavaScript API Key', 'pp-maps' ),
				'size'    => 'medium',
				'tooltip' => __( '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#key" target="_blank" title="Get a key from Google" class="button">Get a browser key</a> for Google Maps JavaScript API and use it here.', 'pp-maps' ),
			),
			array(
				'id'      => 'pp_maps_geocode_api_key',
				'type'    => 'text',
				'label'   => __( 'Google Maps Geocoding API Key', 'pp-maps' ),
				'size'    => 'medium',
				'tooltip' => '<a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key" target="_blank" title="Get a key from Google" class="button">Get a server key</a> for Google Maps Geocoding API and use it here.'
			),
			array(
				'id'          => 'pp_maps_geocode_meta',
				'type'        => 'text',
				'placeholder' => 'eg. meta_1,meta_2',
				'label'       => __( 'Override meta field to geocode', 'pp-maps' ),
				'size'        => 'medium',
				'tooltip'     => __( 'If you have already a custom field where users have entered their address and wish to use it for the maps, enter the meta value here. Seperate multiple fields with commas and without spaces eg. meta_1,meta_2.', 'pp-maps' ),
			)
		)
	);

	return $settings;
}

add_filter(
	'um_settings_section_extensions_pp-maps_content',
	'pp_maps_submenu_setting_tab_content',
	10, 2
);

function pp_maps_submenu_setting_tab_content( $settings_section, $section_fields ) {
	return $settings_section . pp_maps_settings_get_geocoding_html();
}

add_filter(
	'um_settings_section_extensions__content',
	'pp_maps_default_tab_setting_tab_content'
);

function pp_maps_default_tab_setting_tab_content( $section ) {

	$settings_section = pp_maps_settings_get_geocoding_html();
	$section          = $section . $settings_section;
	return $section;

}

function pp_maps_settings_get_geocoding_html() {

	$settings_section = '<br/>';
	$settings_section .= '<table class="form-table um-form-table um_options-extensions-pp-maps um-third-column">';
	$settings_section .= '<tr class="um-forms-line" data-prefix="um_options" data-field_type="text">';
	$settings_section .= '<th><label for="um_options_pp_maps_geocode_meta">Bulk geocode</label></th>';
	$settings_section .= '<td>';
	$settings_section .= '<p><i class="description">Geocode existing users using the custom meta field entered in the UM options area. This takes about 1 second for every 5 users.</i></p>';
	$settings_section .= '<p><input style="min-width: 350px; width: 50%;" type="number" id="pp-geocode-limit" placeholder="Limit geocode users (optional)"></p>';
	$settings_section .= '<p><a href="#" class="button" id="pp_bulk_geocode">Geocode all users in DB</a></p>';
	$settings_section .= '<p id="pp_message">&nbsp;</p>';
	$settings_section .= '<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#pp_bulk_geocode").on("click",function(e){
				var btn = jQuery(this);
				var limit = jQuery("#pp-geocode-limit").val(),
				data = {
					"action": "bulk_geocode",
					"pp-geocode-limit": limit,
				};
				jQuery(this).attr("disabled", true);
				jQuery("#pp_message").text("Busy... do not close this page until \"Done\" appears here.");
				btn.hide();
				jQuery.ajax({
					url: ajaxurl,
					type: "POST",
					data: data,
					success: function(data) {
						jQuery("#pp_message").text(data.data.result);
						jQuery("#pp_bulk_geocode").attr("disabled", false);
						btn.show();
					}
				});
				e.preventDefault();
			});
		});
        </script>';
	$settings_section .= '</td>';
	$settings_section .= '</tr>';
	$settings_section .= '</table>';

	return $settings_section;
}

add_filter( "um_predefined_fields_hook", 'pp_maps_add_field', 10 );

function pp_maps_add_field( $fields ) {

	$fields['pp_address'] = array(
		'title'    => __( 'Map Address', 'pp-maps' ),
		'label'    => __( 'Map Address', 'pp-maps' ),
		'metakey'  => 'pp_address',
		'required' => 0,
		'public'   => 1,
		'editable' => 1,
		'allowclear'    => 0,
		'default'			=> 0,
		'type'     => 'text',
		'icon'     => 'um-faicon-map-marker'
	);

	UM()->account()->add_displayed_field( 'pp_address', 'general' );

	$fields['ump_longitude'] = array(
		'title'    => __( 'Longitude', 'pp-maps' ),
		'label'    => __( 'Longitude', 'pp-maps' ),
		'metakey'  => 'geo_longitude',
		'required' => 0,
		'public'   => 0,
		'editable' => 1,
		'type'     => 'text',
		'icon'     => 'um-faicon-map-marker'
	);

	$fields['ump_latitude'] = array(
		'title'    => __( 'Latitude', 'pp-maps' ),
		'label'    => __( 'Latitude', 'pp-maps' ),
		'metakey'  => 'geo_latitude',
		'required' => 0,
		'public'   => 0,
		'editable' => 1,
		'type'     => 'text',
		'icon'     => 'um-faicon-map-marker'
	);

	return $fields;

}
