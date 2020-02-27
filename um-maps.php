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

define( 'PP_MAPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PP_MAPS_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'PP_MAPS_REQUIRES', '2.0.0' );
define( 'PP_MAPS_STORE_URL', 'https://plusplugins.com' );
define( 'PP_MAPS_ITEM_NAME', 'Ultimate Member Maps' );
define( 'PP_MAPS_VERSION', '1.0.0' );
define( 'PP_MAPS_LICENSE_STATUS', 'pp-maps-license-status' );

require_once PP_MAPS_PLUGIN_DIR . 'core/init.php';
