<?php

class PP_Maps_Role_Metabox {
	function __construct() {
		add_action(
			'um_admin_role_metaboxes',
			array( $this, 'role_metabox' )
		);
	}

	function role_metabox( $roles_metaboxes ) {
		$roles_metaboxes[] = array(
			'id'       => "um-admin-form-maps{" . UM_MAPS_PLUGIN_DIR . "}",
			'title'    => __( 'Map Options', 'pp-maps' ),
			'callback' => array( UM()->metabox(), 'load_metabox_role' ),
			'screen'   => 'um_role_meta',
			'context'  => 'normal',
			'priority' => 'default'
		);

		return $roles_metaboxes;
	}
}
