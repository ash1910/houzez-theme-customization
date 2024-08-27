<?php
/**
 * Template Name: Template all Developers
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/02/16
 * Time: 4:03 PM
 */
get_header();

$developers_layout = houzez_option('developers-template-layout', 'v1');
$developer_header_search = houzez_option('developer_header_search', 1);

if( isset( $_GET['developers-layout'] ) && $_GET['developers-layout'] != "" ) {
    $developers_layout = esc_html($_GET['developers-layout']);
}

if( $developer_header_search ) {
    get_template_part('template-parts/realtors/developer/developer-search');
}
get_template_part('template-parts/realtors/developer/layout', $developers_layout);

if( $developer_header_search ) {
    get_template_part('template-parts/realtors/developer/mobile-developer-search-overlay');
}

get_footer(); ?>
