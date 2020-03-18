<?php

/**
 *
 * Plugin Name: Ultimate Member - Maps
 * Plugin URI: https://eatbuildplay.com/plugins/ultimate-member-maps/
 * Description: Add a filterable map to your Ultimate Members Directory
 * Version: 1.1.0
 * Author: Casey Milne, Eat/Build/Play
 * Author URI: https://eatbuildplay.com/
 */

define( 'UM_MAPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UM_MAPS_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'UM_MAPS_REQUIRES', '2.0.0' );
define( 'UM_MAPS_VERSION', '1.1.0' );

require_once UM_MAPS_PLUGIN_DIR . 'core/init.php';

/* Activation Hook */
require_once UM_MAPS_PLUGIN_DIR . 'src/Cron.php';
register_activation_hook( __FILE__, array( '\\UM_MAPS\CronGeocode', 'onActivation'));
register_deactivation_hook( __FILE__, array( '\\UM_MAPS\CronGeocode', 'onDeactivation'));
