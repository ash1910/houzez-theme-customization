<?php
get_header();
global $post, $houzez_local, $paged, $developer_listing_ids;

$listing_view = houzez_option('developer_listings_layout');
$developer_company_logo = get_post_meta( get_the_ID(), 'fave_developer_logo', true );

$developer_number = get_post_meta( get_the_ID(), 'fave_developer_mobile', true );
$developer_number_call = str_replace(array('(',')',' ','-'),'', $developer_number);
if( empty($developer_number) ) {
    $developer_number = get_post_meta( get_the_ID(), 'fave_developer_office_num', true );
    $developer_number_call = str_replace(array('(',')',' ','-'),'', $developer_number);
}
$developer_position = get_post_meta( get_the_ID(), 'fave_developer_position', true );
$developer_company = get_post_meta( get_the_ID(), 'fave_developer_company', true );
$developer_agency_id = get_post_meta( get_the_ID(), 'fave_developer_agencies', true );

$href = "";
if( !empty($developer_agency_id) ) {
    $href = ' href="'.esc_url(get_permalink($developer_agency_id)).'"';
    $developer_company = get_the_title( $developer_agency_id );
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

$the_query = Houzez_Query::loop_developer_properties();
$developer_total_listings = $the_query->found_posts; 

if( houzez_option('developer_stats', 0) != 0 ) {
 $developer_listing_ids = Houzez_Query::get_developer_properties_ids_by_developer_id(get_the_ID());
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
?>
<section class="content-wrap">
    <div class="agent-profile-wrap">
        <div class="container">
            <div class="agent-profile-top-wrap">
                <div class="agent-image">
                    <?php get_template_part('template-parts/realtors/developer/image'); ?>
                </div><!-- developer-image -->
                <div class="agent-profile-header">
                    <?php if( $developer_position != '' ) { ?>
                    <div class="agent-list-position">
                        <a><?php echo esc_attr($developer_position); ?></a>
                    </div>
                    <?php } ?>
                    
                    <h1><?php the_title(); ?></h1>
                    
                    <?php 
                    if( houzez_option( 'developer_review', 0 ) != 0 ) {
                        get_template_part('template-parts/realtors/rating'); 
                    }?>

                    <?php if( $developer_company != "" ) { ?>
                    <div class="agent-list-position">
                        <a<?php echo $href; ?>><?php echo esc_attr( $developer_company ); ?></a>
                    </div><!-- developer-list-position -->
                    <?php } ?>

                    <div class="agent-profile-cta">
                        <ul class="list-inline">
                            <?php if( houzez_option('developer_form_developer_page', 1) ) { ?>
                            <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#realtor-form"><i class="houzez-icon icon-messages-bubble mr-1"></i> <?php esc_html_e( 'Ask a question', 'houzez' ); ?></a></li>
                            <?php } ?>

                            <?php if(!empty($developer_number)) { ?>
                            <li class="list-inline-item">
                                <a href="tel:<?php echo esc_attr($developer_number_call); ?>">
                                    <i class="houzez-icon icon-phone mr-1"></i> <?php echo esc_attr($developer_number); ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div><!-- developer-profile-header -->
            </div><!-- developer-profile-top-wrap -->
        </div><!-- container -->
    </div><!-- developer-profile-wrap -->

    <div class="agent-nav-wrap">
        <?php if( houzez_option( 'developer_listings', 0 ) != 0 || houzez_option( 'developer_review', 0 ) != 0 ) { ?>
        <div class="container">
            <ul class="nav">
                <?php 
                if( houzez_option( 'developer_listings', 0 ) != 0 && $developer_total_listings > 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link  <?php echo esc_attr($active_listings_tab); ?>" href="#tab-properties" data-toggle="pill" role="tab"><?php esc_html_e('Listings', 'houzez'); ?> (<?php echo esc_attr($developer_total_listings); ?>)</a>
                </li>
                <?php } ?>

                <?php if( houzez_option( 'developer_review', 0 ) != 0 ) { ?>
                <li class="nav-item">
                    <a class="nav-link hz-review-tab <?php echo esc_attr($active_reviews_tab); ?>" href="#tab-reviews" data-toggle="pill" role="tab"><?php esc_html_e('Reviews', 'houzez'); ?> (<?php echo houzez_reviews_count('review_developer_id'); ?>)</a>
                </li>
                <?php } ?>
            </ul>
        </div><!-- container -->
        <?php } ?>
    </div><!-- developer-nav-wrap -->

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 bt-content-wrap">
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
                    </div><!-- tab-pane -->
                    <?php } ?>

                    <?php if( houzez_option( 'developer_review', 0 ) != 0 ) { ?>
                    <div class="tab-pane fade <?php echo esc_attr($active_reviews_content); ?>" id="tab-reviews">
                        <?php get_template_part('template-parts/reviews/main'); ?> 
                    </div><!-- tab-pane -->
                    <?php } ?>

                </div><!-- tab-content -->
            </div><!-- bt-content-wrap -->

            <div class="col-lg-4 col-md-12 bt-sidebar-wrap">
                <aside class="sidebar-wrap">
                    
                    <?php if( houzez_option('developer_bio', 0) != 0 ) { ?>
                    <div class="agent-bio-wrap">
                        <h2><?php echo esc_html__('About', 'houzez'); ?> <?php the_title(); ?></h2>
                        
                        <?php the_content(); ?>

                        <?php get_template_part('template-parts/realtors/developer/languages'); ?> 
                    </div><!-- developer-bio-wrap --> 
                    <?php } ?>

                    <div class="agent-profile-content">
                        <ul class="list-unstyled">
                            <?php get_template_part('template-parts/realtors/developer/license'); ?>

                            <?php get_template_part('template-parts/realtors/developer/tax-number'); ?>

                            <?php get_template_part('template-parts/realtors/developer/service-area'); ?>

                            <?php get_template_part('template-parts/realtors/developer/specialties'); ?>
                        </ul>
                    </div><!-- developer-profile-content -->
                    <?php get_template_part('template-parts/realtors/developer/developer-contacts') ;?>
                    
                    <?php if( !empty($developer_listing_ids) && houzez_option('developer_stats', 0) != 0 ) { ?>
                    <div class="agent-stats-wrap">
                        <?php get_template_part('template-parts/realtors/developer/stats-property-types'); ?>  
                        <?php get_template_part('template-parts/realtors/developer/stats-property-status'); ?>
                        <?php get_template_part('template-parts/realtors/developer/stats-property-cities'); ?>
                    </div><!-- developer-stats-wrap -->
                    <?php } ?>
                </aside>
            </div><!-- bt-sidebar-wrap -->
        </div><!-- row -->
    </div><!-- container -->

</section><!-- content-wrap -->


<?php get_footer(); ?>
