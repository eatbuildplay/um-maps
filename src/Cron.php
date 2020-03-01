<?php

namespace UM_MAPS;

class CronGeocode {

  public function __construct() {

    add_action( $this->actionKey, array( $this, 'run'));

  }

  public $actionKey = 'um_maps_geocoding';

  public function run() {

    $geocode = new PP_Geocode;
    $geocode->pp_bulk_geocode();

  }

  public function onActivation() {
    wp_schedule_event( time(), 'daily', $this->actionKey );
  }

  public function onDeactivation() {
    $timestamp = wp_next_scheduled( $this->actionKey );
    wp_unschedule_event( $timestamp, $this->actionKey );
  }

}
