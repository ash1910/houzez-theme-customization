<?php
get_header();

$developer_detail_layout = houzez_option('developer-detail-layout', 'v1');

if( isset( $_GET['single-developer-layout'] ) && $_GET['single-developer-layout'] != "" ) {
	$developer_detail_layout = esc_html($_GET['single-developer-layout']);
}

get_template_part( 'template-parts/realtors/developer/single-developer', $developer_detail_layout );

get_footer();
