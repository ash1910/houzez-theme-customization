<?php
$developer_address = get_post_meta( get_the_ID(), 'fave_developer_address', true );

if( ! empty( $developer_address ) ) {
	if(houzez_get_map_system() == 'google') {
		$mapData = houzez_getLatLongFromAddress($developer_address);
	} else {
		$mapData = houzezOSM_getLatLngFromAddress($developer_address);
	}
}

if(houzez_get_map_system() == 'google') {
	wp_enqueue_script('houzez-agent-single-map', HOUZEZ_JS_DIR_URI. 'single-agent-google-map.js', array('jquery'), '1.0.0', true);
} else {
	wp_enqueue_script('houzez-agent-single-map', HOUZEZ_JS_DIR_URI. 'single-agent-osm-map.js', array('jquery'), '1.0.0', true);
}

if( ! empty( $mapData ) ) { ?>
	<div id="houzez-agent-sidebar-map" data-lat="<?php echo esc_attr($mapData['lat']); ?>" data-lng="<?php echo esc_attr($mapData['lng']); ?>"></div>
<?php } ?>