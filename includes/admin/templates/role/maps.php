<div class="um-admin-metabox">
	<?php
	$role = $object['data'];

	UM()->admin_forms( array(
		'class'     => 'um-role-map um-half-column',
		'prefix_id' => 'role',
		'fields'    => array(
			array(
				'id'      => '_um_maps_pin',
				'type'    => 'text',
				'label'   => __( 'Maps pin', 'pp-maps' ),
				'tooltip' => __( 'Enter global pin URL for this user role (PNG recommended)', 'pp-maps' ),
				'value'   => isset( $role['_um_maps_pin'] ) ? $role['_um_maps_pin'] : '',
			)
		)
	) )->render_form();
	?>
	<div class="um-admin-clear"></div>
	<div class="info">
		<h4>Example Pins:</h4>
		<p><img src="http://maps.google.com/mapfiles/ms/micons/green.png"><span>http://maps.google.com/mapfiles/ms/micons/green.png</span></p>
		<p><img src="http://maps.google.com/mapfiles/ms/micons/blue.png"><span>http://maps.google.com/mapfiles/ms/micons/blue.png</span></p>
		<p><img src="http://maps.google.com/mapfiles/ms/micons/ylw-pushpin.png"><span>http://maps.google.com/mapfiles/ms/micons/ylw-pushpin.png</span></p>
		<p><img src="http://maps.google.com/mapfiles/ms/micons/orange-dot.png"><span>http://maps.google.com/mapfiles/ms/micons/orange-dot.png</span></p>
		<p>More pins can be found on <a href="https://sites.google.com/site/gmapsdevelopment/" target="_blank">https://sites.google.com/site/gmapsdevelopment/</a></p>
	</div>
	<div class="um-admin-clear"></div>
</div>