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
define( 'UM_MAPS_STORE_URL', 'https://plusplugins.com' );
define( 'UM_MAPS_ITEM_NAME', 'Ultimate Member Maps' );
define( 'UM_MAPS_VERSION', '1.0.0' );
define( 'UM_MAPS_LICENSE_STATUS', 'pp-maps-license-status' );

require_once UM_MAPS_PLUGIN_DIR . 'core/init.php';

/*
 * Cron scheduling
 */
register_activation_hook( UM_MAPS_PLUGIN_DIR . '/um-maps.php', 'registerActivationHook');
register_deactivation_hook( UM_MAPS_PLUGIN_DIR . '/um-maps.php', 'registerDeactivationHook');

registerActivationHook() {
  wp_schedule_event( time(), 'daily', 'um_maps_geocoding' );
}

registerDeactivationHook() {
  $timestamp = wp_next_scheduled( 'um_maps_geocoding' );
  wp_unschedule_event( $timestamp, 'um_maps_geocoding' );
}
