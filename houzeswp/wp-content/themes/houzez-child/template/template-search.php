<?php
/** 
 * Template Name: Search Results
 */
get_header();

$search_result_page = houzez_option('search_result_page');

// if($search_result_page == 'half_map') {
//     get_template_part('template-parts/half-map-search-results'); 
// } else {
//     get_template_part('template-parts/normal-page-search-results'); 
// }

$search_page_type = isset($_GET["search_page_type"]) ? $_GET["search_page_type"] : 0; 

if($search_page_type == '1') {
    get_template_part('template-parts/half-map-search-results'); 
} else {
    get_template_part('template-parts/normal-page-search-results'); 
}

get_footer(); ?>