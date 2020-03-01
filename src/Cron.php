<?php

namespace UM_MAPS;

class CronGeocode {

  public $actionKey = 'um_maps_geocoding';

  public function onActivation() {
    wp_schedule_event( time(), 'daily', $this->actionKey );
  }

  public function onDeactivation() {
    $timestamp = wp_next_scheduled( $this->actionKey );
    wp_unschedule_event( $timestamp, $this->actionKey );
  }

}
