var map,
    markers = [],
    infowindow,
    bounds;

function initMap() {

  console.log('initMap called')

  bounds = new google.maps.LatLngBounds();

  PP_MAPS.members.forEach(function (member) {
    setMarker(member);
    bounds.extend(new google.maps.LatLng(member.lat, member.lng));
  });

  if (markers.length === 0) {
    document.getElementById('pp_map').remove();
    return false;
  }

  map = new google.maps.Map(document.getElementById('pp_map'), {
    mapTypeId: google.maps.MapTypeId[PP_MAPS.type.toUpperCase()],
    maxZoom: PP_MAPS.max_zoom,
    scrollwheel: (PP_MAPS.scrollwheel == 1),
    streetViewControl:false
  });

  map.fitBounds(bounds);

  infowindow = new google.maps.InfoWindow();

  google.maps.event.addListener(map, 'click', function () {
      infowindow.close();
  });

  var markerCluster = new MarkerClusterer(map, [], {
    maxZoom: 14
  });

  markerCluster.addMarkers(markers);

  if (PP_MAPS.initial_zoom != "") {
    google.maps.event.addListenerOnce(map, 'bounds_changed', function (event) {
      map.setZoom(parseInt(PP_MAPS.initial_zoom, 10));
    });
  }

  if (PP_MAPS.center_lat != "" && PP_MAPS.center_lng != "") {
    google.maps.event.addListenerOnce(map, 'bounds_changed', function (event) {
      map.setCenter(new google.maps.LatLng(PP_MAPS.center_lat, PP_MAPS.center_lng));
    });
  }

  oms = new OverlappingMarkerSpiderfier( map, {
    markersWontMove: true,
    markersWontHide: true,
    basicFormatEvents: true
  });

}

function setMarker(member) {

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(member.lat, member.lng),
    title: member.name,
    icon: PP_MAPS.icon
  });

  if (typeof PP_MAPS.role_icons[member.role] !== 'undefined') {
    marker.setIcon(PP_MAPS.role_icons[member.role]);
  }

  google.maps.event.addListener(marker, 'click', function () {
    infowindow.setContent(member.content);
    infowindow.open(map, marker);
  });

  markers.push(marker);

}
