( function( $ ) {
    'use strict';

    
    window.mestateOpenStreetMapElementor = function(mapID , houzez_map_properties , mapOptions ) {

        if ( typeof houzez_map_properties !== "undefined" ) {
        
            if (0 < houzez_map_properties.length) {


                var houzezMap;
                var mapBounds;
                var clusterPolygons;
                var hideInfoWindows;
                var osm_markers_cluster;
                var markerClusterer = null;
                var markers = new Array();
                var checkOpenedWindows = new Array();
                var closeIcon = "";
                var marker_spiderfier = 0;
                var current_marker = 0;
                var current_page = 0;
                var lastClickedMarker;
                var markerPricePins = 'no';
    
                var mapbox_api_key = mapOptions.mapbox_api_key;
                var zoom_control = mapOptions.zoomControl;
                var infoWindowPlac = mapOptions.infoWindowPlac;
                var clusterIcon = mapOptions.clusterIcon;
                var link_target = mapOptions.link_target;
                var clusterer_zoom = mapOptions.clusterer_zoom;
                var property_status = mapOptions.property_status || "search-results";
                var default_lat = parseFloat(houzez_vars.default_lat);
                var default_long = parseFloat(houzez_vars.default_long);

                var hGet_boolean = function(data) {
                    if(data == 'yes') {
                        return true;
                    }
                    return false;
                }

                var map_cluster_enable = hGet_boolean(mapOptions.mapCluster);

                if( mapbox_api_key != '' ) {

                    var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_api_key, {
                        attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
                        tileSize: 512,
                        maxZoom: 18,
                        zoomOffset: -1,
                        id: 'mapbox/streets-v11',
                        accessToken: 'YOUR_MAPBOX_ACCESS_TOKEN'
                    });

                } else {
                    var tileLayer = L.tileLayer( 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        attribution : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    } );
                }

                var addCommas = (nStr) => {
                    nStr += '';
                    var x = nStr.split('.');
                    var x1 = x[0];
                    var x2 = x.length > 1 ? '.' + x[1] : '';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1 + x2;
                }

                var thousandSeparator = (n) => {
                    if (typeof n === 'number') {
                        n += '';
                        var x = n.split('.');
                        var x1 = x[0];
                        var x2 = x.length > 1 ? '.' + x[1] : '';
                        var rgx = /(\d+)(\d{3})/;
                        while (rgx.test(x1)) {
                            x1 = x1.replace(rgx, '$1' + thousands_separator + '$2');
                        }
                        return x1 + x2;
                    } else {
                        return n;
                    }
                }

                var reloadOSMMarkers = function() {
                    // Loop through markers and set map to null for each
                    for (var i=0; i<markers.length; i++) {

                        //markers[i].setMap(null);
                        houzezMap.removeLayer(markers[i]);
                    }
                    // Reset the markers array
                    markers = [];
                    if (osm_markers_cluster) {
                        houzezMap.removeLayer(osm_markers_cluster);
                    }
                }

                var getMapBounds = function(mapDataProperties) {
                    // get map bounds
                    var mapBounds = [];
                    for( var i = 0; i < mapDataProperties.length; i++ ) {
                        if ( mapDataProperties[i].lat && mapDataProperties[i].lng ) {
                            mapBounds.push( [ mapDataProperties[i].lat, mapDataProperties[i].lng ] );
                        }
                    }

                    return mapBounds;
                }

                // Function to draw cluster coverage polygons
                var drawClusterPolygons = function() {
                    clusterPolygons.clearLayers(); // Remove old polygons
        
                    var clusters = {}; // Store cluster coordinates
        
                    osm_markers_cluster.eachLayer(function(marker) {
                        var cluster = osm_markers_cluster.getVisibleParent(marker);
                        if (cluster && typeof cluster.getChildCount === "function" && cluster.getChildCount() > 1) {
                            var clusterId = cluster._leaflet_id;
                            if (!clusters[clusterId]) {
                                clusters[clusterId] = [];
                            }
                            clusters[clusterId].push(marker.getLatLng());
                        }
                    });
        
                    Object.values(clusters).forEach(coords => {
                        if (coords.length > 0) {
                            var bounds = L.latLngBounds(coords);
                            var nw = bounds.getNorthWest();
                            var ne = bounds.getNorthEast();
                            var polygonCoords = [
                                bounds.getNorthWest(),
                                bounds.getNorthEast(),
                                bounds.getSouthEast(),
                                bounds.getSouthWest()
                            ];
        
                            var polygon = L.polygon(polygonCoords, {
                                color: 'rgba(0, 168, 107, 1)',
                                weight: 1,
                                fillColor: 'rgba(0, 168, 107, 1)',
                                fillOpacity: 0.3
                            });
        
                            clusterPolygons.addLayer(polygon);

                            // Calculate top-center position (Midpoint of NW and NE)
                            var topCenter = L.latLng(nw.lat, (nw.lng + ne.lng) / 2);
                            var count = coords.length;
                            
                            // Get all markers in the cluster
                            var markers_in_cluster = [];
                            osm_markers_cluster.eachLayer(function(marker) {
                                if (bounds.contains(marker.getLatLng())) {
                                    markers_in_cluster.push(marker);
                                }
                            });

                            var propertyIds = markers_in_cluster.map(function(marker) {
                                return marker.property_id;
                            });
                            
                            // Create dynamic URL with property IDs
                            var marker_url = `http://mestate.local/${property_status}/?listing_id%5B%5D=` + propertyIds.join("&listing_id%5B%5D=");

                            var countMarker = L.divIcon({
                                html: `<div class="ms-location__card-action">
                                        <a href="${marker_url}" class="ms-btn ms-btn--bordered">
                                            ${count} properties
                                        <i class="fa-regular fa-arrow-right-long"></i></a>
                                    </div>`,
                                className: 'custom-cluster-label',
                                iconSize: [155, 47]
                            });

                            L.marker(topCenter, { icon: countMarker }).addTo(clusterPolygons);
                        }
                    });
                }

                /*--------------------------------------------------------------------
                * Add Marker
                *--------------------------------------------------------------------*/
                var houzezAddMarkers = function(map_properties, houzezMap) {
                    var propertyMarker;

                    var mBounds = getMapBounds(map_properties);

                    if ( 1 < mBounds.length ) {
                        houzezMap.fitBounds( mBounds );
                        //houzezMap.setView(mBounds[0], 13);
                    }

                    if(map_cluster_enable == 1) {
                        osm_markers_cluster = new L.MarkerClusterGroup({ 
                            iconCreateFunction: function (cluster) {
                                var markers1 = cluster.getAllChildMarkers();
                                var html = `<div class="ms-location__card-action">
                                                <a href="#" class="ms-btn ms-btn--bordered">
                                                    ${markers1.length} properties
                                                <i class="fa-regular fa-arrow-right-long"></i></a>
                                            </div>`;
                                //return L.divIcon({ html: html, className: 'mycluster', iconSize: L.point(180, 48) });
                                return L.divIcon({ html: "", className: 'mycluster', iconSize: L.point(10, 10) });
                            },
                            maxClusterRadius: 220,
                            spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false 
                        });

                        clusterPolygons = L.layerGroup().addTo(houzezMap); // Layer for coverage polygons
                    }

                    for( var i = 0; i < map_properties.length; i++ ) {

                        if ( map_properties[i].lat && map_properties[i].lng ) {

                            var mapData = map_properties[i];

                            var mapCenter = L.latLng( mapData.lat, mapData.lng );

                             var markerOptions = {
                                riseOnHover: true
                            };


                            if ( mapData.title ) {
                                markerOptions.title = mapData.title;
                            }

                            if( markerPricePins == 'yes' ) {
                                var pricePin = '<div data-id="'+map_properties[i].property_id+'" class="gm-marker gm-marker-color-'+map_properties[i].term_id+'"><div class="gm-marker-price">'+map_properties[i].pricePin+'</div></div>';

                                var myIcon = L.divIcon({ 
                                    className:'someclass',
                                    iconSize: new L.Point(0, 0), 
                                    html: pricePin
                                });

                                if(map_cluster_enable == 1) {
                                    propertyMarker = new L.Marker(mapCenter, {icon: myIcon});
                                } else {
                                    propertyMarker = L.marker( mapCenter,{icon: myIcon} ).addTo( houzezMap );
                                }

                            } else {
                                // Marker icon
                                if ( mapData.marker ) {

                                    var iconOptions = {
                                        iconUrl: mapData.marker,
                                        iconSize: [44, 56],
                                        iconAnchor: [20, 57],
                                        popupAnchor: [1, -57]
                                    };
                                    if ( mapData.retinaMarker ) {
                                        iconOptions.iconRetinaUrl = mapData.retinaMarker;
                                    }
                                    markerOptions.icon = L.icon( iconOptions );
                                }

                                if(map_cluster_enable == 1) {
                                    propertyMarker = new L.Marker(mapCenter, markerOptions);
                                } else {
                                    propertyMarker = L.marker( mapCenter, markerOptions ).addTo( houzezMap );
                                }
                            }

                            // Add property_id to the marker
                            propertyMarker.property_id = mapData.property_id;

                            if(map_cluster_enable == 1) {
                                osm_markers_cluster.addLayer(propertyMarker);
                            }

                            var mainContent = document.createElement( "div" );
                            mainContent.className = 'map-info-window';
                            var innerHTML = "";

                            innerHTML += '<div class="item-wrap">';

                                innerHTML += '<div class="item-header">';
                    
                                    if( map_properties[i].thumbnail ) {
                                        innerHTML += '<a target="'+ link_target +'" href="' + map_properties[i].url + '">' + '<img class="img-fluid" src="' + map_properties[i].thumbnail + '" alt="' + map_properties[i].title + '"/>' + '</a>';
                                    } else {
                                        innerHTML += '<a target="'+ link_target +'" href="' + map_properties[i].url + '">' + '<img class="img-fluid" src="' + infoWindowPlac + '" width="120" height="90" alt="' + map_properties[i].title + '"/>' + '</a>';
                                    }
                                innerHTML += '</div>';

                                innerHTML += '<div class="item-body flex-grow-1">';
                                    innerHTML += '<h2 class="item-title">';
                                        innerHTML += '<a target="'+ link_target +'" href="' + map_properties[i].url + '">'+map_properties[i].title+'</a>';
                                    innerHTML += '</h2>';

                                    innerHTML += '<ul class="list-unstyled item-info">';

                                    if(map_properties[i].price) {
                                        innerHTML += '<li class="item-price">'+map_properties[i].price+'</li>';
                                    }

                                    if(map_properties[i].property_type) {
                                        innerHTML += '<li class="item-type">'+map_properties[i].property_type+'</li>';
                                    }
                                    
                                    innerHTML += '</ul>';

                                innerHTML += '</div>';

                            innerHTML += '</div>';

                            mainContent.innerHTML = innerHTML;

                            propertyMarker.id = mapData.property_id;

                            markers.push(propertyMarker);
                            propertyMarker.bindPopup( mainContent );


                        } // end if lat lng

                    } // end for loop 

                    if(map_cluster_enable == 1) {
                        houzezMap.addLayer(osm_markers_cluster);
                    }

                    if(map_cluster_enable == 1) {
                        // Update polygons on zoom, move, or cluster events
                        houzezMap.on('zoomend moveend', drawClusterPolygons);
                        osm_markers_cluster.on('animationend', drawClusterPolygons);
                        osm_markers_cluster.on('clusterclick', drawClusterPolygons);

                        drawClusterPolygons(); // Initial draw
                    }
                    
                } //end houzezAddMarkers

                if ( houzez_map_properties.length > 0 ) {
                    
                    var mapBounds = getMapBounds(houzez_map_properties);
                    // Basic map
                    var mapCenter = L.latLng( default_lat, default_long ); 
                    if ( 1 == mapBounds.length ) {
                        mapCenter = L.latLng( mapBounds[0] );
                    }
                    var mapDragging = true;
                    var mapOptions = {
                        dragging: mapDragging,
                        center: mapCenter,
                        zoom: 10,
                        zoomControl: false,
                        // fullscreenControl: true, // Enable fullscreen button
                        // fullscreenControlOptions: {
                        //     position: 'topright' // Move fullscreen button to top right
                        // },
                        tap: false
                    };
                    houzezMap = L.map( document.getElementById( mapID ), mapOptions );

                    // Add zoom control manually at bottom-right
                    L.control.zoom({
                        position: 'bottomright' // Move zoom buttons to bottom right
                    }).addTo(houzezMap);

                    //houzezMap.scrollWheelZoom.disable();

                    if ( 1 < mapBounds.length ) {
                        houzezMap.fitBounds( mapBounds ); 
                        //houzezMap.setView(mapBounds[0], 13);
                    }

                    houzezMap.addLayer( tileLayer );

                    houzezAddMarkers(houzez_map_properties, houzezMap);


                } else {

                    var fallbackMapOptions = {
                        center : [25.686540,-80.431345],
                        zoom : 10
                    };

                    houzezMap = L.map( document.getElementById( mapID ), fallbackMapOptions );
                    houzezMap.addLayer( tileLayer );
                    houzezMap.scrollWheelZoom.disable();

                }

            } // #houzez-properties-map").length

        } // end typeof
    }
    
} )( jQuery );