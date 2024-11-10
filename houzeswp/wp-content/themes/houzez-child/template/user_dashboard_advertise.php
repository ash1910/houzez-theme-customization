<?php
/**
 * Template Name: User Dashboard Advertise
 * Author: Waqas Riaz.
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}
if( !class_exists('Fave_Insights')) {
    $msg = esc_html__('Please install and activate Favethemes Insights plugin.', 'houzez');
    wp_die($msg);
}

get_header(); 

if (!empty($_GET['listing_id'])) {

    $user_id = get_current_user_id();
    $insights = new Fave_Insights();

    $listing_id = isset($_GET['listing_id']) ? $_GET['listing_id'] : '';

    if(!empty($listing_id)) {
        $insights_stats = $insights->fave_listing_stats($_GET['listing_id']);
        $author_id = get_post_field( 'post_author', $listing_id );
    } else {
        $insights_stats = $insights->fave_user_stats($user_id);
    }

    get_template_part('template-parts/dashboard/advertise/advance_states');
}
else{
    get_template_part('template-parts/dashboard/advertise/advertise');
}

get_footer(); ?>