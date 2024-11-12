<?php
global $post, $paged, $listing_founds, $search_qry;

$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');
if( $sticky_sidebar['search_sidebar'] != 0 ) { 
    $is_sticky = 'houzez_sticky'; 
}

$listing_view = houzez_option('search_result_posts_layout', 'list-view-v1');
$search_result_layout = houzez_option('search_result_layout');
$search_num_posts = houzez_option('search_num_posts');
$enable_save_search = houzez_option('enable_disable_save_search');

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

if($view_class == 'grid-view' && $search_result_layout == 'no-sidebar') {
    $cols_in_row = 'grid-view-3-cols';
}

$page_content_position = houzez_get_listing_data('listing_page_content_area');


if( $search_result_layout == 'no-sidebar' ) {
    $content_classes = 'col-lg-12 col-md-12';
} else if( $search_result_layout == 'left-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap wrap-order-first';
} else if( $search_result_layout == 'right-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
} else {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
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

    //echo "<pre>";print_r($advertise_qry);exit;
    //echo "<pre>";print_r($advertise_query);exit;
    //echo "<pre>";print_r($advertise_post_ids);exit;
    // End Advertise

// are we on page one?
if(1 == $paged) {

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
    shuffle($combined_posts);
}
else{
    $offset = ($paged - 1) * $posts_per_page - count($advertise_post_ids);

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

//echo "<pre>";print_r($search_query);exit;

$total_records = $search_query->found_posts;

$record_found_text = esc_html__('Result Found', 'houzez');
if( $total_records > 1 ) {
    $record_found_text = esc_html__('Results Found', 'houzez');
}
?>
<section class="listing-wrap <?php echo esc_attr($wrap_class); ?>">
    <div class="container">

        <div class="search-filter-2-wrap">

            <div class="d-flex align-items-center">
                <div class="flex-grow-1">

                    <?php 
                        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    ?>
                    <a class="btn btn-listing verified-listing-btn" href="<?php echo change_url_parameter_with_verified_first($actual_link, "sortby", "verified_first");?>">
                        <span class="btn-txt-1">VERIFIED</span>
                        <span class="btn-txt-2">Listings first</span>
                        <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/is_icon.svg'; ?></i>
                    </a>

                    <!-- <a class="btn btn-listing properties-with-floor-plans-btn" href="#">
                        <span class="btn-txt-2">Properties with floor plans</span>
                        <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/is_icon.svg'; ?></i>
                    </a> -->
                </div><!-- page-title -->

                <?php
                if( $enable_save_search != 0 ) {
                    get_template_part('template-parts/search/save-search-btn');
                }?> 

                <a class="btn btn-listing clear-filters-btn" href='<?php echo clear_all_search_filter_without_one_filter_url($actual_link, "status");?>'>
                    <?php echo houzez_option('srh_btn_clear_filters', 'Clear Filters'); ?>
                </a>

                <?php 
                if($have_switcher) {
                    get_template_part('template-parts/listing/listing-switch-view'); 
                }?> 
            </div><!-- d-flex -->  
                
        </div><!-- page-title-wrap -->

        <div class="row">
            <div class="<?php echo esc_attr($content_classes); ?>">

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

                <div class="listing-tools-wrap">

                    <div class="d-flex align-items-center mb-3">
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

                    </div><!-- d-flex -->

                    <?php 
                    if( $total_records > 1 ) {
                        $type_list = apply_filters("houzez_after_search__get_property_type_list", $search_qry);

                        if( $type_list !== "" ){
                            echo "<div class='page-type-wrap'>$type_list</div>";
                        }

                    }
                    ?>
                    
                    

                </div><!-- listing-tools-wrap -->

                <div class="listing-view <?php echo esc_attr($view_class).' '.esc_attr($cols_in_row).' '.esc_attr($card_deck); ?>">
                    <?php
                    if ( 1 == $paged && !empty($combined_posts) ) :
                        //echo "<pre>";print_r($combined_posts);exit;
                        foreach ($combined_posts as $post) {
                            
                            setup_postdata($post);
                            get_template_part('template-parts/listing/item', $item_layout);

                            $fave_impressions = get_post_meta( $post->ID, 'fave_impressions', true );
                            update_post_meta( $post->ID, 'fave_impressions', intval($fave_impressions) - 1 );
                        }
                    elseif ( $search_query->have_posts() ) :
                        while ( $search_query->have_posts() ) : $search_query->the_post();

                            get_template_part('template-parts/listing/item', $item_layout);

                        endwhile;
                    else:
                        
                        echo '<div class="search-no-results-found-wrap">';
                            echo '<div class="search-no-results-found">';
                                esc_html_e('No results found', 'houzez');
                            echo '</div>';
                        echo '</div>';
                        
                    endif;
                    wp_reset_postdata();
                    ?> 
                </div><!-- listing-view -->

                <?php houzez_pagination( $search_query->max_num_pages ); ?>

            </div><!-- bt-content-wrap -->

            <?php if( $search_result_layout != 'no-sidebar' ) { ?>
            <div class="col-lg-4 col-md-12 bt-sidebar-wrap <?php echo esc_attr($is_sticky); ?>">
                <aside class="sidebar-wrap">
                    <?php
                    if( is_active_sidebar( 'search-sidebar' ) ) {
                        dynamic_sidebar( 'search-sidebar' );
                    }
                    ?>
                </aside>
            </div><!-- bt-sidebar-wrap -->
            <?php } ?>

        </div><!-- row -->

    </div><!-- container -->
</section><!-- listing-wrap -->

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