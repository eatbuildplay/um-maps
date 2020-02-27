var map,
    markers = [],
    infowindow,
    bounds;

function initMap() {

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
        streetViewControl:false,
        // enter custom options here
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
}

function setMarker(member) {

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(member.lat, member.lng),
        title: member.name,
        icon: PP_MAPS.icon,

    });

    if (typeof PP_MAPS.role_icons[member.role] !== 'undefined') {
        marker.setIcon(PP_MAPS.role_icons[member.role]);
    }

    google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent(member.content);
        infowindow.open(map, marker);
    });

    markers.push(marker);

    for (var i = 0; i < markers.length; i++) {
        var existingMarker = markers[i];
        var pos = existingMarker.getPosition();
        var latlng = marker.getPosition();
        
        //if a marker already exists in the same position as this marker
        if (latlng.equals(pos)) {
            //update the position of the coincident marker by applying a small multipler to its coordinates
            var newLat = latlng.lat() + (Math.random() - .5) / 1500; // * (Math.random() * (max - min) + min);
            var newLng = latlng.lng() + (Math.random() - .5) / 1500; // * (Math.random() * (max - min) + min);
            finalLatLng = new google.maps.LatLng(newLat, newLng);
            marker.setPosition(finalLatLng);
        }
    }
}
