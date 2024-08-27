<?php
get_header();
global $post, $houzez_local, $paged, $developer_listing_ids;

$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');
// if( $sticky_sidebar['developer_sidebar'] != 0 ) { 
//     $is_sticky = 'houzez_sticky'; 
// }
$listing_view = houzez_option('developer_listings_layout');
$developer_company_logo = get_post_meta( get_the_ID(), 'fave_developer_logo', true );

$developer_number = get_post_meta( get_the_ID(), 'fave_developer_mobile', true );
$developer_number_call = str_replace(array('(',')',' ','-'),'', $developer_number);
if( empty($developer_number) ) {
    $developer_number = get_post_meta( get_the_ID(), 'fave_developer_office_num', true );
    $developer_number_call = str_replace(array('(',')',' ','-'),'', $developer_number);
}


$item_layout = $view_class = $cols_in_row = '';
$card_deck = 'card-deck';

if($listing_view == 'list-view-v1') {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v1') {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v2') {
    $wrap_class = 'listing-v2';
    $item_layout = 'v2';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v2') {
    $wrap_class = 'listing-v2';
    $item_layout = 'v2';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v3') {
    $wrap_class = 'listing-v3';
    $item_layout = 'v3';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v4') {
    $wrap_class = 'listing-v4';
    $item_layout = 'v4';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v5') {
    $wrap_class = 'listing-v5';
    $item_layout = 'v5';
    $view_class = 'list-view';

} elseif($listing_view == 'grid-view-v5') {
    $wrap_class = 'listing-v5';
    $item_layout = 'v5';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v6') {
    $wrap_class = 'listing-v6';
    $item_layout = 'v6';
    $view_class = 'grid-view';

} elseif($listing_view == 'grid-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'v7';
    $view_class = 'grid-view';

} elseif($listing_view == 'list-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'list-v7';
    $view_class = 'list-view';
    $card_deck = '';
} else {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'grid-view';
}

if(isset($_GET['tab']) || $paged > 0) {

    if(isset($_GET['tab']) && $_GET['tab'] == 'reviews') {
        $active_reviews_tab = 'active';
        $active_reviews_content = 'show active';
        $active_listings_tab = '';
        $active_listings_content = '';
    }
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('html, body').animate({
                scrollTop: $(".agent-nav-wrap").offset().top
            }, 'slow');
        });
    </script>
    <?php
}

$the_query = loop_developer_properties();
$developer_total_listings = developer_properties_count();

if( houzez_option('developer_stats', 0) != 0 ) {
 $developer_listing_ids = get_developer_properties_ids_by_developer_id(get_the_ID());
}

$active_reviews_tab = '';
$active_reviews_content = '';
$active_listings_content = '';
$active_listings_tab = '';
if( houzez_option( 'developer_listings', 0 ) != 1 && houzez_option( 'developer_review', 0 ) != 0 ) {
    $active_reviews_tab = 'active';
    $active_reviews_content = 'show active';

} else if ( $developer_total_listings == 0 ) {
    $active_reviews_tab = 'active';
    $active_reviews_content = 'show active';
} else {
    $active_listings_tab = 'active';
    $active_listings_content = 'show active';
}

$content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
if( houzez_option( 'developer_sidebar', 0 ) == 0 ) { 
    $content_classes = 'col-lg-12 col-md-12';
}
?>
<section class="content-wrap">
    <div class="container">

        <div class="agent-profile-wrap">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="agent-image">
                        <?php if( !empty( $developer_company_logo ) ) {
                        $logo_url = wp_get_attachment_url( $developer_company_logo );
                        if( !empty($logo_url) ) {
                        ?>
                        <div class="agent-company-logo">
                            <img class="img-fluid" src="<?php echo esc_url( $logo_url ); ?>" alt="">
                        </div>
                        <?php }
                        } ?>
                        <?php get_template_part('template-parts/realtors/developer/image'); ?>
                    </div><!-- developer-image -->
                </div><!-- col-lg-4 col-md-4 col-sm-12 -->

                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="agent-profile-top-wrap">
                        <div class="agent-profile-header">
                            <h1><?php the_title(); ?></h1>
                            
                            <?php 
                            if( houzez_option( 'developer_review', 0 ) != 0 ) {
                                get_template_part('template-parts/realtors/rating'); 
                            }?>

                        </div><!-- developer-profile-content -->
                        <?php get_template_part('template-parts/realtors/developer/position'); ?>
                    </div><!-- developer-profile-header -->

                    <div class="agent-profile-content">
                        <ul class="list-unstyled">
                            
                            <?php get_template_part('template-parts/realtors/developer/license'); ?>

                            <?php get_template_part('template-parts/realtors/developer/tax-number'); ?>

                            <?php get_template_part('template-parts/realtors/developer/service-area'); ?>

                            <?php get_template_part('template-parts/realtors/developer/specialties'); ?>

                        </ul>
                    </div><!-- developer-profile-content -->

                    <div class="agent-profile-buttons">
                        
                        <?php if( houzez_option('developer_form_developer_page', 1) ) { ?>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#realtor-form">
                            <?php echo esc_html__('Send Email', 'houzez'); ?>  
                        </button>
                        <?php } ?>
                        
                        <?php if(!empty($developer_number)) { ?>
                        <a href="tel:<?php echo esc_attr($developer_number_call); ?>" class="btn btn-call">
                            <span class="hide-on-click"><?php echo esc_html__('Call', 'houzez'); ?></span>
                            <span class="show-on-click"><?php echo esc_attr($developer_number); ?></span>
                        </a>
                        <?php } ?>


                    </div><!-- developer-profile-buttons -->
                </div><!-- col-lg-8 col-md-8 col-sm-12 -->
            </div><!-- row -->
        </div><!-- developer-profile-wrap -->

        <?php if( !empty($developer_listing_ids) && houzez_option('developer_stats', 0) != 0 ) { ?>
        <div class="agent-stats-wrap">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <?php get_template_part('template-parts/realtors/developer/stats-property-types'); ?> 
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <?php get_template_part('template-parts/realtors/developer/stats-property-status'); ?> 
                </div>

                <?php if(taxonomy_exists('property_city')) { ?>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <?php get_template_part('template-parts/realtors/developer/stats-property-cities'); ?> 
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <div class="<?php echo esc_attr($content_classes); ?>">

                <?php if( houzez_option('developer_bio', 0) != 0 ) { ?>
                <div class="agent-bio-wrap">
                    <h2><?php echo esc_html__('About', 'houzez'); ?> <?php the_title(); ?></h2>
                    <?php the_content(); ?>

                    <?php get_template_part('template-parts/realtors/developer/languages'); ?> 
                </div><!-- developer-bio-wrap --> 
                <?php } ?>
                
                <?php if( houzez_option( 'developer_listings', 0 ) != 0 || houzez_option( 'developer_review', 0 ) != 0 ) { ?>
                <div id="review-scroll" class="agent-nav-wrap">
                    <ul class="nav nav-pills nav-justified">
                        
                        <?php if( houzez_option( 'developer_listings', 0 ) != 0 && $developer_total_listings > 0 ) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo esc_attr($active_listings_tab); ?>" href="#tab-properties" data-toggle="pill" role="tab">
                                <?php esc_html_e('Listings', 'houzez'); ?> (<?php echo esc_attr($developer_total_listings); ?>)
                            </a>
                        </li>
                        <?php } ?>

                        <?php if( houzez_option( 'developer_review', 0 ) != 0 ) { ?>
                        <li class="nav-item">
                            <a class="nav-link hz-review-tab <?php echo esc_attr($active_reviews_tab); ?>" href="#tab-reviews" data-toggle="pill" role="tab">
                                <?php esc_html_e('Reviews', 'houzez'); ?> (<?php echo houzez_reviews_count('review_developer_id'); ?>)
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div><!-- developer-nav-wrap -->
                
                <div class="tab-content" id="tab-content">
                    
                    <?php if( houzez_option( 'developer_listings', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_listings_content); ?>" id="tab-properties">
                        <div class="listing-tools-wrap">
                            <div class="d-flex align-items-center">
                                <div class="listing-tabs flex-grow-1">
                                    <?php get_template_part('template-parts/realtors/developer/listing-tabs'); ?> 
                                </div>
                                <?php get_template_part('template-parts/listing/listing-sort-by'); ?>  
                            </div><!-- d-flex -->
                        </div><!-- listing-tools-wrap -->

                        <section class="listing-wrap <?php echo esc_attr($wrap_class); ?>">
                            <div class="listing-view <?php echo esc_attr($view_class).' '.esc_attr($card_deck); ?>">
                                <?php
                                if ( $the_query->have_posts() ) :
                                    while ( $the_query->have_posts() ) : $the_query->the_post();

                                        $developer_listing_ids[] = get_the_ID(); 
                                        get_template_part('template-parts/listing/item', $item_layout);

                                    endwhile;
                                    wp_reset_postdata();
                                else:
                                    get_template_part('template-parts/listing/item', 'none');
                                endif;
                                ?> 
                            </div><!-- listing-view -->

                            <?php houzez_pagination( $the_query->max_num_pages ); ?>
                        </section>
                    </div><!-- tab-pane -->
                    <?php } ?>

                    <?php if( houzez_option( 'developer_review', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_reviews_content); ?>" id="tab-reviews">
                        <?php get_template_part('template-parts/reviews/main'); ?> 
                    </div><!-- tab-pane -->
                    <?php } ?>
                </div><!-- tab-content -->
                <?php } ?>

            </div><!-- bt-content-wrap -->

            <?php if( houzez_option( 'developer_sidebar', 0 ) != 0 ) { ?>
            <div class="col-lg-4 col-md-12 bt-sidebar-wrap <?php echo esc_attr($is_sticky); ?>">
                <aside class="sidebar-wrap">
                    <?php get_template_part('template-parts/realtors/developer/developer-contacts') ;?> 
                    <?php 
                    if (is_active_sidebar('developer-sidebar')) {
                        dynamic_sidebar('developer-sidebar');
                    }
                    ?>
                </aside>
            </div><!-- bt-sidebar-wrap -->
            <?php } ?>
        </div><!-- row -->
    </div><!-- container -->
</section><!-- listing-wrap -->

<?php get_footer(); ?>
