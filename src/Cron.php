<?php

namespace UM_MAPS;

class CronGeocode {

  public $actionKey = 'um_maps_geocoding';


  public function __construct() {
    add_action( $this->actionKey, array( '\UM_MAPS\CronGeocode', 'run'));
  }

  public static function run() {

    $geocode = new \PP_Geocode;
    $geocode->pp_bulk_geocode();

  }

  public static function onActivation() {
    $geocode = new \UM_MAPS\CronGeocode;
    wp_schedule_event( time(), 'hourly', $geocode->actionKey );
  }

  public static function onDeactivation() {
    $geocode = new \UM_MAPS\CronGeocode;
    $timestamp = wp_next_scheduled( $geocode->actionKey );
    wp_unschedule_event( $timestamp, $geocode->actionKey );
  }

}
