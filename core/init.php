<?php

class UM_Maps_API {

	public $notice_message = '';
	public $plugin_inactive = false;

	function __construct() {

		$this->plugin_inactive = false;

		add_action( 'plugins_loaded', array( &$this, 'plugin_check' ), -1 );
		add_action( 'init', array( &$this, 'init' ), -1 );
		add_action( 'admin_notices', array( $this, 'add_notice' ), 20 );

	}

	/***
	 ***    @Check plugin requirements
	 */
	function plugin_check() {

		if ( ! class_exists( 'UM' ) ) {

			$this->notice_message   = __( 'The <strong>Ultimate Member Maps</strong> plugin requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'pp-maps' );
			$this->plugin_inactive = true;

		} else if ( ! version_compare( ultimatemember_version, UM_MAPS_REQUIRES, '>=' ) ) {

			$this->notice_message   = __( 'The <strong>Ultimate Member Maps</strong> plugin  requires a <a href="https://wordpress.org/plugins/ultimate-member">newer version</a> of Ultimate Member to work properly.', 'pp-maps' );
			$this->plugin_inactive = true;

		}
	}

	/***
	 ***    @Add notice
	 */
	function add_notice() {
		if ( ! is_admin() || empty( $this->notice_message ) ) {
			return;
		}

		echo '<div class="error"><p>' . $this->notice_message . '</p></div>';
	}

	/***
	 ***    @Init
	 */
	function init() {

		if ( $this->plugin_inactive ) {
			return;
		}

		require_once UM_MAPS_PLUGIN_DIR . 'src/Cron.php';

		require_once UM_MAPS_PLUGIN_DIR . 'core/register-scripts.php';
		require_once UM_MAPS_PLUGIN_DIR . 'core/metabox.php';
		require_once UM_MAPS_PLUGIN_DIR . 'core/display.php';
		require_once UM_MAPS_PLUGIN_DIR . 'core/geocode.php';
		require_once UM_MAPS_PLUGIN_DIR . 'admin/settings.php';
		require_once UM_MAPS_PLUGIN_DIR . 'admin/user-column.php';
		require_once UM_MAPS_PLUGIN_DIR . 'admin/role-metabox.php';

		$this->metabox       = new PP_Maps_Metabox();
		$this->geocode       = new PP_Geocode();
		$this->roles_metabox = new PP_Maps_Role_Metabox();

		new \UM_MAPS\CronGeocode();

	}

}

$pp_maps = new UM_Maps_API();
