<?php

add_action( 'init', function () {
	wp_register_script( 'pp_maps_cluster', PP_MAPS_PLUGIN_URI . 'assets/js/cluster.js', array( 'jquery' ), false, true );
	wp_localize_script( 'pp_maps_cluster', 'PP_MAPS_CLUSTER_IMAGES', array( "src" => PP_MAPS_PLUGIN_URI . 'assets/img/cluster/m' ) );
	wp_register_script( 'pp_maps_display', PP_MAPS_PLUGIN_URI . 'assets/js/pp-maps.js', array( 'jquery', 'pp_maps_cluster' ), false, true );
	wp_register_style( 'pp_maps_css', PP_MAPS_PLUGIN_URI . 'assets/css/map.css' );
} );
