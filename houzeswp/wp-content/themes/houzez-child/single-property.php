<?php 
global $post, $hide_fields, $top_area, $property_layout, $map_street_view, $loggedin_to_view;

$single_top_area = get_post_meta( get_the_ID(), 'fave_single_top_area', true );
$single_content_area = get_post_meta( get_the_ID(), 'fave_single_content_area', true );
$map_street_view = get_post_meta( get_the_ID(), 'fave_property_map_street_view', true );
$loggedin_to_view = get_post_meta( get_the_ID(), 'fave_loggedintoview', true );
$property_live_status = get_post_status();
$hide_fields = houzez_option('hide_detail_prop_fields');
houzez_count_property_views( $post->ID );

$enable_disclaimer = houzez_option('enable_disclaimer', 1);
$global_disclaimer = houzez_option('property_disclaimer');
$property_disclaimer = get_post_meta( get_the_ID(), 'fave_property_disclaimer', true );

if( !empty(  $property_disclaimer ) ) {
    $global_disclaimer = $property_disclaimer;
}

if( ( $property_live_status == 'on_hold' ) && ( $post->post_author != get_current_user_id() ) ) {
    wp_redirect(  home_url() );
}

$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');

if( isset( $sticky_sidebar['single_property'] ) && $sticky_sidebar['single_property'] != 0 ) { 
    $is_sticky = 'houzez_sticky'; 
}

$is_full_width = houzez_option('is_full_width');
$top_area = houzez_option('prop-top-area');
$property_layout = houzez_option('prop-content-layout');

if(isset($_GET['is_full_width'])) {
    $is_full_width = 1;
}

if($is_full_width == 1) {
    $content_classes = 'col-lg-12 col-md-12 bt-full-width-content-wrap';
} else {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
}

if( !empty( $single_top_area ) && $single_top_area != 'global' ) {
    $top_area = $single_top_area;
}

if( !empty( $single_content_area ) && $single_content_area != 'global' ) {
    $property_layout = $single_content_area;
}

/* For demo purpose only */
if( isset( $_GET['s_top'] ) ) {
    $top_area = $_GET['s_top'];
}
if( isset( $_GET['s_layout'] ) ) {
    $property_layout = $_GET['s_layout'];
}

$layout_class = '';
if( $property_layout == 'minimal' ) {
    $layout_class = "content-wrap-style-minimal";
} else if( $property_layout == 'boxed' ) {
    $layout_class = "content-wrap-style-boxed";
}

get_header();

if( houzez_check_is_elementor() && ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) ) {

    while ( have_posts() ) : the_post();

        the_content();

    endwhile;

} else {

 
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { 

    if( have_posts() ): while( have_posts() ): the_post(); ?>
        
        <?php get_template_part('property-details/single-property-mestate'); ?>

    <?php endwhile; endif; 
 } // <!-- end elementor location -->
} ?> <!-- end houzez_check_is_elementor -->

<?php get_footer(); ?>