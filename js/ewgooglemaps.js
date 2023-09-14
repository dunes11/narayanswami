/*!
 * Google Maps for PHPMaker v2023.13.0
 * Copyright (c) e.World Technology Limited. All rights reserved.
 */
this.ew = this.ew || {};
this.ew.maps = this.ew.maps || {};
(function (exports) {
  'use strict';

  /**
   * Default map options (see https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions)
   */
  var mapOptions = {};

  /**
   * Default marker options (see https://developers.google.com/maps/documentation/javascript/reference/marker#MarkerOptions)
   */
  var markerOptions = {};

  /**
   * Default marker clusterer options (see https://googlemaps.github.io/js-markerclustererplus/interfaces/markerclustereroptions.html)
   */
  var markerClustererOptions = {};

  /**
   * Send geocode requests
   * @param {Object} data Map data
   * @returns Promise
   */
  function geocode(data) {
    return new Promise(function (resolve, reject) {
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({
        address: data.address
      }, (results, status) => {
        if (status == google.maps.GeocoderStatus.OK) {
          resolve(results[0].geometry.location);
        } else {
          // Geocoding not successful
          reject(status);
        }
      });
    });
  }

  /**
   * Create map
   * @param {HTMLElement} el HTML element
   * @param {Object} data Map data
   */
  function createMap(el, data) {
    return new google.maps.Map(el, Object.assign({}, this.mapOptions, {
      zoom: data.zoom || 10,
      center: data.latlng,
      mapTypeId: ["SATELLITE", "HYBRID", "TERRAIN"].includes(data.type) ? google.maps.MapTypeId[data.type] : google.maps.MapTypeId.ROADMAP
    }));
  }

  /**
   * Create LatLng object
   * @param {number} latitude Latitude
   * @param {number} longitude Longitude
   */
  function createLatLng(latitude, longitude) {
    return new google.maps.LatLng(latitude, longitude);
  }

  /**
   * Create marker
   * @param {Object} data Map data
   */
  function createMarker(data) {
    let marker = new google.maps.Marker(Object.assign({}, this.markerOptions, {
      // Marker
      position: data.latlng,
      map: data.map,
      icon: data.icon || null,
      title: String(data.title || "")
    }));
    let desc = String(data.description || "").trim();
    if (desc) {
      // Info window
      let infoWindow = new google.maps.InfoWindow({
        content: desc
      });
      google.maps.event.addListener(marker, "click", () => {
        infoWindow.open(data.map, marker);
      });
    }
    if (data.useMarkerClusterer) data.markerClusterer.addMarker(marker);
    if (data.useSingleMap && data.showAllMarkers) data.bounds.extend(data.latlng); // Extend bounds
    return marker;
  }

  /**
   * Create marker clusterer
   * @param {Object} data Map data
   */
  function createMarkerClusterer(data) {
    return new MarkerClusterer(data.map, [], Object.assign({}, this.markerClustererOptions, {
      maxZoom: data.clustererMaxZoom == -1 ? null : data.clustererMaxZoom,
      gridSize: data.clustererGridSize == -1 ? null : data.clustererGridSize,
      clusterClass: "custom-clustericon",
      styles: [{
        width: 30,
        height: 30,
        className: "custom-clustericon-1"
      }, {
        width: 40,
        height: 40,
        className: "custom-clustericon-2"
      }, {
        width: 50,
        height: 50,
        className: "custom-clustericon-3"
      }]
    }));
  }

  /**
   * Create bounds object
   */
  function createBounds() {
    return new google.maps.LatLngBounds();
  }

  /**
   * Fit bounds
   * @param {Object} data Map data
   */
  function fitBounds(data) {
    if (data.map && data.bounds) data.map.fitBounds(data.bounds);
  }

  exports.createBounds = createBounds;
  exports.createLatLng = createLatLng;
  exports.createMap = createMap;
  exports.createMarker = createMarker;
  exports.createMarkerClusterer = createMarkerClusterer;
  exports.fitBounds = fitBounds;
  exports.geocode = geocode;
  exports.mapOptions = mapOptions;
  exports.markerClustererOptions = markerClustererOptions;
  exports.markerOptions = markerOptions;

  Object.defineProperty(exports, '__esModule', { value: true });

})(this.ew.maps.googlemaps = this.ew.maps.googlemaps || {});
