jQuery(document).ready(function () {

  // load google maps api
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "https://maps.google.com/maps/api/js?key=" + UM_MAPS_API.api_key;
  document.body.appendChild(script);

  // spiderfier and callback
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = '/wp-content/plugins/um-maps/assets/js/spider.js?callback=initMap()';
  document.body.appendChild(script);

});
