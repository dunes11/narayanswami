/*!
 * Leaflet for PHPMaker v2023.13.0
 * Copyright (c) e.World Technology Limited. All rights reserved.
 */
this.ew = this.ew || {};
this.ew.maps = this.ew.maps || {};
(function (exports) {
  'use strict';

  /**
   * Mapbox access token
   */
  var accessToken = "";

  /**
   * Settings for Mapbox
   */
  var mapboxSettings = {
    urlTemplate: "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
    tileLayerOptions: {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: "mapbox/streets-v11",
      tileSize: 512,
      zoomOffset: -1,
      detectRetina: true
    },
    geocodeUrlTemplate: "https://api.mapbox.com/geocoding/v5/mapbox.places/{q}.json?limit=1&access_token={accessToken}" // See https://docs.mapbox.com/help/tutorials/local-search-geocoding-api/
  };

  /**
   * Settings for OpenStreetMap
   */
  var openstreetmapSettings = {
    urlTemplate: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    tileLayerOptions: {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 18,
      minZoom: 0,
      tileSize: 512,
      zoomOffset: -1,
      detectRetina: true
    },
    geocodeUrlTemplate: "https://nominatim.openstreetmap.org/search?format=json&limit=1&q={q}" // See https://nominatim.org/release-docs/develop/api/Search/
  };

  /**
   * HD width
   */
  var hdWidth = 1920;

  /**
   * Default map options (see https://leafletjs.com/reference-1.7.1.html#map-option)
   */
  var mapOptions = {};

  /**
   * Default marker options (see https://leafletjs.com/reference-1.7.1.html#marker)
   */
  var markerOptions = {};

  /**
   * Default marker clusterer options (see https://github.com/Leaflet/Leaflet.markercluster#options)
   */
  var markerClustererOptions = {};

  /**
   * Send geocode requests
   * @param {Object} data Map data
   * @returns Promise
   */
  function geocode(data) {
    let mapbox = data.service == "mapbox",
      settings = mapbox ? this.mapboxSettings : this.openstreetmapSettings;
    return fetch(settings.geocodeUrlTemplate.replace("{q}", encodeURIComponent(data.address)).replace("{accessToken}", encodeURIComponent(this.accessToken))).then(res => res.json()).then(data => {
      var _data$features;
      if (mapbox) return (data == null ? void 0 : (_data$features = data.features) == null ? void 0 : _data$features.length) > 0 ? this.createLatLng(data.features[0].center.reverse()) : null;else return (data == null ? void 0 : data.length) > 0 ? this.createLatLng(parseFloat(data[0].lat), parseFloat(data[0].lon)) : null;
    });
  }

  /**
   * Adjust tile layer options for FHD or higher
   * @param {Object} options Tile layer options
   */
  function adjustTileLayerOptions(options) {
    if (options.detectRetina) {
      if ((window.devicePixelRatio || window.screen.deviceXDPI / window.screen.logicalXDPI) > 1)
        // Retina
        return options; // To be handled by Leaflet
      if (window.screen.width >= this.hdWidth && options.maxZoom > 0) {
        let opts = Object.assign({}, options);
        opts.tileSize = Math.floor(opts.tileSize / 2);
        if (!opts.zoomReverse) {
          opts.zoomOffset++;
          opts.maxZoom--;
        } else {
          opts.zoomOffset--;
          opts.minZoom++;
        }
        opts.minZoom = Math.max(0, opts.minZoom);
        return opts;
      }
    }
    return options;
  }

  /**
   * Create map
   * @param {HTMLElement} el HTML element
   * @param {Object} data Map data
   */
  function createMap(el, data) {
    el.dataset.ext = "leaflet";
    let map = L.map(el, Object.assign({}, this.mapOptions, {
        zoom: data.zoom || 10,
        center: data.latlng
      })),
      urlTemplate,
      tileLayerOptions;
    if (data.service == "mapbox") {
      urlTemplate = this.mapboxSettings.urlTemplate;
      tileLayerOptions = this.mapboxSettings.tileLayerOptions;
    } else {
      urlTemplate = this.openstreetmapSettings.urlTemplate;
      tileLayerOptions = this.adjustTileLayerOptions(this.openstreetmapSettings.tileLayerOptions);
    }
    L.tileLayer(urlTemplate, Object.assign({}, tileLayerOptions, {
      accessToken: this.accessToken
    })).addTo(map);
    return map;
  }

  /**
   * Create LatLng object
   * @param {Array|number} latitude Latitude or a geographical point as an array of the form [Number, Number]
   * @param {number} longitude Longitude
   */
  function createLatLng(latitude, longitude) {
    return L.latLng(latitude, longitude);
  }

  /**
   * Create marker
   * @param {Object} data Map data
   */
  function createMarker(data) {
    let marker = L.marker(data.latlng, Object.assign({}, this.markerOptions, {
      title: String(data.title || "")
    }));
    if (data.icon) marker.setIcon(L.icon({
      iconUrl: data.icon
    }));
    let desc = String(data.description || "").trim();
    if (desc)
      // Popup
      marker.bindPopup(desc).openPopup();
    if (data.useMarkerClusterer) {
      data.markerClusterer.addLayer(marker);
    } else {
      marker.addTo(data.map);
    }
    if (data.useSingleMap && data.showAllMarkers) data.bounds.extend(data.latlng); // Extend bounds
    return marker;
  }

  /**
   * Create marker clusterer
   * @param {Object} data Map data
   */
  function createMarkerClusterer(data) {
    let markerClusterer = L.markerClusterGroup(Object.assign({}, this.markerClustererOptions));
    data.map.addLayer(markerClusterer);
    return markerClusterer;
  }

  /**
   * Create bounds object
   */
  function createBounds() {
    return L.latLngBounds();
  }

  /**
   * Fit bounds
   * @param {Object} data Map data
   */
  function fitBounds(data) {
    if (data.map && data.bounds) data.map.fitBounds(data.bounds);
  }

  exports.accessToken = accessToken;
  exports.adjustTileLayerOptions = adjustTileLayerOptions;
  exports.createBounds = createBounds;
  exports.createLatLng = createLatLng;
  exports.createMap = createMap;
  exports.createMarker = createMarker;
  exports.createMarkerClusterer = createMarkerClusterer;
  exports.fitBounds = fitBounds;
  exports.geocode = geocode;
  exports.hdWidth = hdWidth;
  exports.mapOptions = mapOptions;
  exports.mapboxSettings = mapboxSettings;
  exports.markerClustererOptions = markerClustererOptions;
  exports.markerOptions = markerOptions;
  exports.openstreetmapSettings = openstreetmapSettings;

  Object.defineProperty(exports, '__esModule', { value: true });

})(this.ew.maps.leaflet = this.ew.maps.leaflet || {});
