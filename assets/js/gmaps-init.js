jQuery(document).ready(function () {
    if (typeof google === 'object' && typeof google.maps === 'object') {
        initMap();
    } else {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://maps.google.com/maps/api/js?callback=initMap";

        if (PP_MAPS_API.api_key != "") {
            script.src += "&key=" + PP_MAPS_API.api_key;
        }

        document.body.appendChild(script);
    }
});
