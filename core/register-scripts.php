<?php

add_action( 'init', function() {
		wp_register_script( 'pp_maps_cluster', UM_MAPS_PLUGIN_URI . 'assets/js/cluster.js', array( 'jquery' ), false, true );
		wp_localize_script( 'pp_maps_cluster', 'UM_MAPS_CLUSTER_IMAGES', array( "src" => UM_MAPS_PLUGIN_URI . 'assets/img/cluster/m' ) );
		wp_register_script( 'pp_maps_display', UM_MAPS_PLUGIN_URI . 'assets/js/pp-maps.js', array( 'jquery' ), false, true );
		wp_register_style( 'pp_maps_css', UM_MAPS_PLUGIN_URI . 'assets/css/map.css' );
	}
);
