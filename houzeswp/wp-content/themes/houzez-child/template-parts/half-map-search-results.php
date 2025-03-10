<?php
global $search_qry, $search_style;
$listing_view = houzez_option('search_result_posts_layout', 'list-view-v1');
$search_result_layout = houzez_option('search_result_layout');
$search_num_posts = houzez_option('search_num_posts');
$enable_save_search = houzez_option('enable_disable_save_search');
$page_content_position = houzez_get_listing_data('listing_page_content_area');

$have_switcher = true;
$card_deck = 'card-deck';

$wrap_class = $item_layout = $view_class = $cols_in_row = '';
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
    $have_switcher = false;

} elseif($listing_view == 'grid-view-v4') {
    $wrap_class = 'listing-v4';
    $item_layout = 'v4';
    $view_class = 'grid-view';
    $have_switcher = false;

} elseif($listing_view == 'list-view-v4') {
    $wrap_class = 'listing-list-v4';
    $item_layout = 'list-v4';
    $view_class = 'list-view listing-view-v4';
    $have_switcher = false;
    $card_deck = '';
    $search_result_layout = 'no-sidebar';

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
    $have_switcher = false;

} elseif($listing_view == 'grid-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'v7';
    $view_class = 'grid-view';
    $have_switcher = false;

} elseif($listing_view == 'list-view-v7') {
    $wrap_class = 'listing-v7';
    $item_layout = 'list-v7';
    $view_class = 'list-view';
    $have_switcher = false;
    $card_deck = '';

} else {
    $wrap_class = 'listing-v1';
    $item_layout = 'v1';
    $view_class = 'grid-view';
}

$number_of_prop = $search_num_posts;
if(!$number_of_prop){
    $number_of_prop = 9;
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if ( is_front_page()  ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

    // Advertise
    $advertise_qry = array(
        'post_type' => 'property',
        'posts_per_page' => 10,
        'orderby' => 'rand',
    );
    $advertise_qry = apply_filters( 'houzez20_search_filters_advertise', $advertise_qry );
    $advertise_qry = apply_filters( 'houzez_sold_status_filter', $advertise_qry );
    $advertise_query = new WP_Query( $advertise_qry );

    $advertise_post_ids = wp_list_pluck($advertise_query->posts, 'ID');
    $number_of_prop_first = $number_of_prop - count($advertise_post_ids);
    if($number_of_prop_first < 0)$number_of_prop_first = 0;
    // End Advertise

// are we on page one?
if(1 == $paged) {

    set_advertise_to_posts($advertise_query->posts);

    $search_qry = array(
        'post_type' => 'property',
        'posts_per_page' => $number_of_prop_first,
        //'paged' => $paged,
        'post_status' => 'publish',
        'post__not_in'   => $advertise_post_ids,
        'offset'         => 0
    );

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );
    $search_query = new WP_Query( $search_qry );

    // Combine the results
    $combined_posts = array_merge($advertise_query->posts, $search_query->posts);

    // Shuffle the combined array for random placement of advertise posts within 12 spots
    //shuffle($combined_posts);
}
else{
    $offset = ($paged - 1) * $number_of_prop - count($advertise_post_ids);

    $search_qry = array(
        'post_type' => 'property',
        'posts_per_page' => $number_of_prop,
        //'paged' => $paged,
        'post_status' => 'publish',
        'post__not_in'   => $advertise_post_ids,
        'offset'         => $offset
    );

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );
    $search_query = new WP_Query( $search_qry );
}

$total_properties = $search_query->found_posts + count($advertise_post_ids); 

$enable_search = houzez_option('enable_halfmap_search', 1);
$search_style = houzez_option('halfmap_search_layout', 'v4');

if( isset($_GET['halfmap_search']) && $_GET['halfmap_search'] != '' ) {
    $search_style = $_GET['halfmap_search'];
}

if( wp_is_mobile() ) {
    $search_style = 'v1';
}

if($enable_search != 0 && $search_style != 'v4') {
    get_template_part('template-parts/search/search-half-map-header');
}
?>
<section class="half-map-wrap map-on-left clearfix">
    <div id="map-view-wrap" class="half-map-left-wrap">
        <div class="map-wrap">
            <?php get_template_part('template-parts/map-buttons'); ?>
            
            <div id="houzez-properties-map"></div>

            <?php if(houzez_get_map_system() == 'google') { ?>
            <div id="houzez-map-loading" class="houzez-map-loading">
                <div class="mapPlaceholder">
                    <div class="loader-ripple spinner">
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div id="half-map-listing-area" class="half-map-right-wrap <?php echo esc_attr($wrap_class); ?>">

        <?php 
        if($enable_search != 0 && $search_style == 'v4') {
            get_template_part('template-parts/search/search-half-map');
        }
        ?>

        <div class="page-title-wrap listing-tools-wrap">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="page-title page-title-search flex-grow-1">
                        <h1>Properties <?php //the_title(); 
                        if( isset($_GET["status"]) && in_array("rent", $_GET["status"]) ){
                            echo "for rent";
                        }
                        else if( isset($_GET["status"]) && in_array("buy", $_GET["status"]) ){
                            echo "for buy";
                        }
                        ?> in UAE</h1>
                    </div><!-- page-title -->
                </div>

                <?php get_template_part('template-parts/listing/listing-sort-by'); ?>  

                <?php 
                $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $fave_search_result_page = isset($_GET["fave_search_result_page"]) ? $_GET["fave_search_result_page"] : "";
                $search_result_page_url = str_replace('&fave_search_result_page='.$fave_search_result_page, '', $actual_link); 
                $search_result_page_url_map=$search_result_page_url.'&fave_search_result_page=half_map';
                
                $list_active = "";
                $map_active = "";
                $search_result_page = houzez_option('search_result_page');
                if($search_result_page == 'half_map') {
                    $list_active = "";
                    $map_active = "active";
                } else {
                    $list_active = "active";
                    $map_active = "";
                }
                ?>
                <div class="listing-map-button-view">
                    <ul class="list-inline">
                        <li class="list-inline-item <?php echo $list_active;?>">
                            <a class="btn btn-primary-outlined btn-listing" href="<?php echo $search_result_page_url;?>">
                                <i class="btn-icon">
                                    <?php include get_stylesheet_directory() . '/assets/images/list_icon.svg'; ?>
                                </i>
                                <span>List</span>
                            </a>
                        </li>
                        <li class="list-inline-item <?php echo $map_active;?>">
                            <a class="btn btn-primary-outlined btn-listing" href="<?php echo $search_result_page_url_map;?>">
                                <i class="btn-icon icon-map">
                                        <?php include get_stylesheet_directory() . '/assets/images/map_icon.svg'; ?>
                                </i> 
                                <span>Map</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <?php 
                if($have_switcher) {
                    get_template_part('template-parts/listing/listing-switch-view'); 
                }?> 
            </div>  
        </div>

        <div class="listing-view <?php echo esc_attr($view_class); ?>" data-layout="<?php echo esc_attr($item_layout); ?>">
            <?php
            if ( $page_content_position !== '1' ) {
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        ?>
                        <article <?php post_class(); ?>>
                            <?php the_content(); ?>
                        </article>
                        <?php
                    }
                } 
            }?>

            <div id="houzez_ajax_container">
                <div class="<?php echo esc_attr($card_deck);?>">
                <?php
                if ( 1 == $paged && !empty($combined_posts) ) :
                    //echo "<pre>";print_r($combined_posts);exit;
                    foreach ($combined_posts as $post) {
                        
                        setup_postdata($post);
                        get_template_part('template-parts/listing/item', $item_layout);

                    }
                elseif ( $search_query->have_posts() ) :
                    while ( $search_query->have_posts() ) : $search_query->the_post();

                        get_template_part('template-parts/listing/item', $item_layout);

                    endwhile;
                else:
                    
                    echo '<div class="search-no-results-found">';
                        esc_html_e('No results found', 'houzez');
                    echo '</div>';
                    
                endif;
                wp_reset_postdata();
                ?> 
                </div>
                <div class="clearfix"></div>

                <?php houzez_ajax_pagination( $search_query->max_num_pages ); ?>
            </div>

            <?php
            if ('1' === $page_content_position ) {
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        ?>
                        <section class="content-wrap">
                            <?php the_content(); ?>
                        </section>
                        <?php
                    }
                }
            }
            ?>
            
        </div><!-- listing-view -->

    </div>
</section>
