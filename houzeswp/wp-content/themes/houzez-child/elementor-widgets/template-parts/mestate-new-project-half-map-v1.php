<?php
global $post, $paged, $listing_founds, $search_qry;

wp_enqueue_script('leaflet');
wp_enqueue_style('leaflet');
wp_enqueue_script('leafletMarkerCluster');
wp_enqueue_style('leafletMarkerCluster');
wp_enqueue_style('leafletMarkerClusterDefault');
//wp_enqueue_script('houzez-elementor-osm-scripts');
$map_options = array();
wp_register_script('houzez-osm-properties-mestate',  get_stylesheet_directory_uri().'/js/osm-properties-mestate.js', array('jquery', 'leaflet'), '1.0.0', true);
wp_localize_script( 'houzez-osm-properties-mestate', 'houzez_map_properties', $map_options );
wp_enqueue_script( 'houzez-osm-properties-mestate' );

function get_properties_data($post) {

    $property_array_temp = array();

    $property_array_temp[ 'title' ] = get_the_title();
    $property_array_temp[ 'url' ] = get_permalink();
    $property_array_temp['price'] = houzez_listing_price_v5();
    $property_array_temp['property_id'] = get_the_ID();
    $property_array_temp['pricePin'] = houzez_listing_price_map_pins();

    $address = houzez_get_listing_data('property_map_address');
    if(!empty($address)) {
        $property_array_temp['address'] = $address;
    }

    //Property type
    $property_array_temp['property_type'] = houzez_taxonomy_simple('property_type');

    $property_location = houzez_get_listing_data('property_location');
    if(!empty($property_location)){
        $lat_lng = explode(',',$property_location);
        $property_array_temp['lat'] = $lat_lng[0];
        $property_array_temp['lng'] = $lat_lng[1];
    }

    //Get marker 
    $property_type = get_the_terms( get_the_ID(), 'property_type' );
    if ( $property_type && ! is_wp_error( $property_type ) ) {
        foreach ( $property_type as $p_type ) {

            $marker_id = get_term_meta( $p_type->term_id, 'fave_marker_icon', true );
            $property_array_temp[ 'term_id' ] = $p_type->term_id;

            if ( ! empty ( $marker_id ) ) {
                $marker_url = wp_get_attachment_url( $marker_id );

                if ( $marker_url ) {
                    $property_array_temp[ 'marker' ] = esc_url( $marker_url );

                    $retina_marker_id = get_term_meta( $p_type->term_id, 'fave_marker_retina_icon', true );
                    if ( ! empty ( $retina_marker_id ) ) {
                        $retina_marker_url = wp_get_attachment_url( $retina_marker_id );
                        if ( $retina_marker_url ) {
                            $property_array_temp[ 'retinaMarker' ] = esc_url( $retina_marker_url );
                        }
                    }
                    break;
                }
            }
        }
    }

    //Se default markers if property type has no marker uploaded
    if ( ! isset( $property_array_temp[ 'marker' ] ) ) {
        $property_array_temp[ 'marker' ]       = get_template_directory_uri() . '/img/map/pin-single-family.png';           
        $property_array_temp[ 'retinaMarker' ] = get_template_directory_uri() . '/img/map/pin-single-family.png';  
    }

    //Featured image
    if ( has_post_thumbnail() ) {
        $thumbnail_id         = get_post_thumbnail_id();
        $thumbnail_array = wp_get_attachment_image_src( $thumbnail_id, 'houzez-item-image-1' );
        if ( ! empty( $thumbnail_array[ 0 ] ) ) {
            $property_array_temp[ 'thumbnail' ] = $thumbnail_array[ 0 ];
        }
    }

    return $property_array_temp;
}

$sortby = '';

if( houzez_is_half_map_search_result() ) {
	$sortby = houzez_option('search_default_order');
}

if( isset( $_GET['sortby'] ) ) {
    $sortby = $_GET['sortby'];
}
$sort_id = 'sort_properties';
if(houzez_is_half_map()) {
	$sort_id = 'ajax_sort_properties';
}

$search_uri = '';
$get_search_uri = $_SERVER['REQUEST_URI'];
$get_search_uri = explode( '/?', $get_search_uri );
if(isset($get_search_uri[1]) && $get_search_uri[1] != "") {
    $search_uri = $get_search_uri[1];
}

$settings = get_query_var('settings', []);
$status_data = $settings['status_data'];
$type_data = $settings['type_data'];
$map_options = $settings['map_options'];
$properties_data = array();

if( isset($_GET["status"]) && !empty($_GET["status"]) && !empty($_GET["status"][0]) ){
    $status = $_GET["status"][0];
}
elseif( isset($status_data) && !empty($status_data) ){
    $status = $status_data;
}
elseif( isset($type_data) && !empty($type_data) ){
    $status = $type_data;
}
else{
    $status = "";
}
$title = "";
if( $status == "rent" ){
    $title = "Properties for rent in UAE";
}
else if( $status == "buy" ){
    $title = "Properties for buy in UAE";
}
else if( $status == "new-projects" ){
    $title = "New Projects in UAE";
}
else{
    $title = "Properties in UAE";
}

$search_num_posts = houzez_option('search_num_posts');
$enable_save_search = houzez_option('enable_disable_save_search');

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

//echo "<pre>";print_r($search_query);exit;

$total_records = $search_query->found_posts + count($advertise_post_ids);

$record_found_text = esc_html__('Result Found', 'houzez');
if( $total_records > 1 ) {
    $record_found_text = esc_html__('Results Found', 'houzez');
}

?>

<!-- start: Apartments section  -->
<section class="ms-apartments-main ms-apartments-main--2 section--wrapper" style="margin-top: 0;">
    <div class="container-fluid container-fluid--lg">
        <div class="row">

            <!-- heading -->
            <div class="col-12">
                <div class="ms-apartments-main__heading">
                    <h2><?php echo $title; ?></h2>
                    <?php
                    if( houzez_option('enable_disable_save_search', 0) ) {  ?> 
                    <button class="ms-btn ms-btn--bordered save_search_click save-search-btn">
                        <input type="hidden" name="search_args" value='<?php print base64_encode( serialize( $search_qry ) ); ?>'>
                        <input type="hidden" name="search_URI" value="<?php echo esc_attr($search_uri); ?>">
                        <input type="hidden" name="search_geolocation" value="<?php echo isset($_GET['search_location']) ? esc_attr($_GET['search_location']) : ''; ?>">
                        <input type="hidden" name="houzez_save_search_ajax" value="<?php echo wp_create_nonce('houzez-save-search-nounce')?>">
                        <?php get_template_part('template-parts/loader'); ?>
                        <i class="icon-notification"></i> Get Alert
                    </button>
                    <?php }?> 
                </div>
            </div>

            <div class="col-12 col-md-6">

                <div id="ms-half-map-v1" class="ms-apartments__map"></div>


                <!-- <div id="map-view-wrap" class="half-map-left-wrap">
                    <div class="map-wrap">
                        <div class="map-arrows-actions">
                            <button id="listing-mapzoomin" class="map-btn"><i class="houzez-icon icon-add"></i></button>
                            <button id="listing-mapzoomout" class="map-btn"><i class="houzez-icon icon-subtract"></i></button>
                        </div>
                        <div class="map-next-prev-actions">
                            <button id="houzez-gmap-prev" class="map-btn"><i class="houzez-icon icon-arrow-left-1 mr-1"></i> <span>Prev</span></button>
                            <button id="houzez-gmap-next" class="map-btn"><span>Next</span> <i class="houzez-icon icon-arrow-right-1 ml-1"></i></button>
                        </div>
                        <div class="map-zoom-actions">
                            <div id="houzez-gmap-full-osm" class="map-btn">
                                <i class="houzez-icon icon-expand-3 mr-1"></i> <span>Fullscreen</span>
                            </div>
                        </div>          
                        
                        <div id="ms-half-map-v1" class="ms-apartments__map"></div>

                    </div>
                </div> -->

            </div>

            <!-- apartments content -->
            <div class="col-12 col-md-6 d-none d-md-block">
                <!-- button list -->

                <ul class="ms-apartments-main__button-list">
                    <li>
                        <a
                            href="javascript:;"
                            class="ms-btn ms-btn--bordered ms-btn--list goToListPage"
                        >
                            <svg
                                width="22"
                                height="14"
                                viewBox="0 0 22 14"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M1 2C1 1.44772 1.44772 1 2 1H4C4.55228 1 5 1.44772 5 2V4C5 4.55228 4.55228 5 4 5H2C1.44772 5 1 4.55228 1 4V2Z"
                                    fill="#868686"
                                />
                                <path
                                    d="M1 10C1 9.44772 1.44772 9 2 9H4C4.55228 9 5 9.44772 5 10V12C5 12.5523 4.55228 13 4 13H2C1.44772 13 1 12.5523 1 12V10Z"
                                    fill="#868686"
                                />
                                <path
                                    d="M9 1H15M9 9H15M9 5H21M9 13H21M2 5H4C4.55228 5 5 4.55228 5 4V2C5 1.44772 4.55228 1 4 1H2C1.44772 1 1 1.44772 1 2V4C1 4.55228 1.44772 5 2 5ZM2 13H4C4.55228 13 5 12.5523 5 12V10C5 9.44772 4.55228 9 4 9H2C1.44772 9 1 9.44772 1 10V12C1 12.5523 1.44772 13 2 13Z"
                                    stroke="#868686"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            List</a
                        >
                    </li>
                    <li>
                        <a
                            href="javascript:void(0)"
                            class="ms-btn ms-btn--bordered active"
                        >
                            <svg
                                width="16"
                                height="21"
                                viewBox="0 0 16 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M15.5 7.5C15.5 11.4274 10.625 17 8 17C5.375 17 0.5 11.0385 0.5 7.11111C0.5 3 4.13401 0 8 0C11.866 0 15.5 3 15.5 7.5ZM10.5 7C10.5 8.38071 9.38071 9.5 8 9.5C6.61929 9.5 5.5 8.38071 5.5 7C5.5 5.61929 6.61929 4.5 8 4.5C9.38071 4.5 10.5 5.61929 10.5 7ZM2 19.25C1.58579 19.25 1.25 19.5858 1.25 20C1.25 20.4142 1.58579 20.75 2 20.75H14C14.4142 20.75 14.75 20.4142 14.75 20C14.75 19.5858 14.4142 19.25 14 19.25H2Z"
                                    fill="#868686"
                                />
                            </svg>

                            Map</a
                        >
                    </li>
                    <li class="d-none d-lg-block">
                        <svg class="ms-popular-select__svg" width="20" height="14" viewBox="0 0 20 14" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.0625 1C0.0625 0.482233 0.482233 0.0625 1 0.0625H19C19.5178 0.0625 19.9375 0.482233 19.9375 1C19.9375 1.51777 19.5178 1.9375 19 1.9375H1C0.482233 1.9375 0.0625 1.51777 0.0625 1Z"
                                fill="#868686" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.0625 7C0.0625 6.48223 0.482233 6.0625 1 6.0625H19C19.5178 6.0625 19.9375 6.48223 19.9375 7C19.9375 7.51777 19.5178 7.9375 19 7.9375H1C0.482233 7.9375 0.0625 7.51777 0.0625 7Z"
                                fill="#868686" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.0625 13C0.0625 12.4822 0.482233 12.0625 1 12.0625H19C19.5178 12.0625 19.9375 12.4822 19.9375 13C19.9375 13.5178 19.5178 13.9375 19 13.9375H1C0.482233 13.9375 0.0625 13.5178 0.0625 13Z"
                                fill="#868686" />
                        </svg>
                        <select id="ajax_sort_properties" class="ms-nice-select-popular ms-btn ms-btn--bordered ms-btn--popular" title="<?php esc_html_e( 'Popular', 'houzez' ); ?>" data-live-search="false" data-dropdown-align-right="auto">
                            <option value=""><?php esc_html_e( 'Popular', 'houzez' ); ?></option>
                            <option <?php selected($sortby, 'd_date'); ?> value="d_date"><?php esc_html_e('Newest', 'houzez' ); ?></option>
                            <option <?php selected($sortby, 'a_price'); ?> value="a_price"><?php esc_html_e('Lowest Price', 'houzez'); ?></option>
                            <option <?php selected($sortby, 'd_price'); ?> value="d_price"><?php esc_html_e('Highest Price', 'houzez'); ?></option>
                        </select><!-- selectpicker -->
                    </li>
                </ul>

                <!-- apartments cards -->
                <div id="houzez_ajax_container">
                    <div class="ms-apartments-main__card__wrapper ms-apartments-main__card__wrapper--2">
                    <?php
                        $properties_data = array();
                        if ( 1 == $paged && !empty($combined_posts) ) :
                            //echo "<pre>";print_r($combined_posts);exit;
                            foreach ($combined_posts as $post) {
                                
                                setup_postdata($post);
                                get_template_part('elementor-widgets/template-parts/mestate-new-project-listing-item-half-map-v1');
                                $properties_data[] = get_properties_data($post);
                            }
                        elseif ( $search_query->have_posts() ) :
                            while ( $search_query->have_posts() ) : $search_query->the_post();

                            get_template_part('elementor-widgets/template-parts/mestate-new-project-listing-item-half-map-v1');
                            $properties_data[] = get_properties_data($post);
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
                    </div>

                    <!-- paginations -->
                    <?php houzez_ajax_pagination( $search_query->max_num_pages ); ?>
                </div>
            </div>

        </div>
    </div>
</section>


<script>
    function functionPropertyLocationShowMore(){
        // controll location
        const locationList = document.querySelector(
            "ul.ms-apartments-main__location"
        );

        if (locationList) {
            const viewAllBtn = locationList.querySelector(
                ".ms-apartments-main__location__all"
            );
            const viewLessBtn = locationList.querySelector(
                ".ms-apartments-main__location__less"
            );
            function msControlLoaction(isAll) {
                const allItems = locationList.querySelectorAll("li");
                const width = window.innerWidth;
                // parent classs modify
                locationList?.classList?.remove("show-all-items");
                if (isAll) {
                    locationList?.classList?.add("show-all-items");
                }
                allItems?.forEach((item, idx) => {
                    // modify text
                    let itemLink;
                    const isNotLessMore =
                        item?.classList?.contains("ms-apartments-main__location__all") ||
                        item?.classList?.contains("ms-apartments-main__location__less") ||
                        item?.querySelector("hr")
                            ? false
                            : true;

                    let locationName = item?.textContent?.replace(/[\n\t]+/g, "").trim();
                    let locationContentArray;

                    let locationContentText;
                    let locationContentNumb;
                    let newLocationTextContent;
                    if (isNotLessMore) {
                        itemLink = item?.querySelector("a");
                        if (locationName?.length) {
                            locationContentArray = locationName?.split("(");
                            if (locationContentArray?.length) {
                                locationContentText = locationContentArray[0];
                                locationContentNumb = locationContentArray[1];
                                const locationContentTextLength = locationContentText?.length;
                                const locationContentNumbLength = locationContentNumb?.length;
                                const sliceUpperLimit = 14 - (locationContentNumbLength - 1);
                                if (locationName?.length > 19) {
                                    newLocationTextContent = `${locationContentText?.slice(
                                        0,
                                        sliceUpperLimit
                                    )}.. (${locationContentNumb}`;
                                } else {
                                    newLocationTextContent = `${locationContentText}(${locationContentNumb}`;
                                }
                                itemLink.textContent = newLocationTextContent;
                            }
                        }
                    }

                    item?.classList?.remove("ms-show");
                });
                if (isAll) {
                    allItems?.forEach((item2, idx2) => {
                        item2?.classList?.add("ms-show");
                    });
                    viewAllBtn?.classList?.remove("ms-show");
                    viewLessBtn?.classList?.add("ms-show");
                } else {
                    allItems?.forEach((item2, idx2) => {
                        if (width >= 1200 || (width >= 530 && width <= 991)) {
                            if (idx2 < 6) {
                                item2?.classList?.add("ms-show");
                            }
                        } else if (width >= 992 && width <= 1199) {
                            if (idx2 < 8) {
                                item2?.classList?.add("ms-show");
                            }
                        } else {
                            if (idx2 < 4) {
                                item2?.classList?.add("ms-show");
                            }
                        }
                    });
                    viewAllBtn?.classList?.add("ms-show");
                }
            }
            msControlLoaction(false);
            viewAllBtn?.addEventListener("click", function () {
                msControlLoaction(true);
            });
            viewLessBtn?.addEventListener("click", function () {
                msControlLoaction(false);
            });
            window.addEventListener("resize", () => {
                msControlLoaction(false);
            });
        }
    }

    function functionListingItemImageSlider(){
        // card slider
        var formSlider = new Swiper(".ms-aparments-maincardslider", { 
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                renderBullet: function (index, className) {
                    var current = this.realIndex; // Get active slide index
                    var totalSlides = this.slides.length;
                    let bulletsToShow = [];

                    if (current <= totalSlides - 3) {
                        // Case 1: If there are at least 2 next slides -> Current, Next 1, Next 2
                        bulletsToShow = [current, current + 1, current + 2];
                    } else if (current === totalSlides - 2) {
                        // Case 2: If there is only 1 next slide -> Prev 1, Current, Next 1
                        bulletsToShow = [current - 1, current, current + 1];
                    } else if (current === totalSlides - 1) {
                        // Case 3: If no next slides -> Prev 2, Prev 1, Current
                        bulletsToShow = [current - 2, current - 1, current];
                    } else if (current >= 2) {
                        // Case 4: If there are at least 2 previous slides -> Prev 2, Prev 1, Current
                        bulletsToShow = [current - 2, current - 1, current];
                    } else if (current === 1) {
                        // Case 5: If there is only 1 previous slide -> Prev 1, Current, Next 1
                        bulletsToShow = [current - 1, current, current + 1];
                    }

                    // Render bullets only if they match the allowed range
                    if (bulletsToShow.includes(index)) {
                        return `<span class="${className}" data-index="${index}"></span>`;
                    }
                    return ""; // Hide other bullets
                },
            },

            // loop: true,
        });
    }

    function functionGoToListPage(){
        // Handle map page navigation
        jQuery('.goToListPage').on('click', function() {
            let currentUrl = new URL(window.location.href);
            let pathParts = currentUrl.pathname.split('/').filter(part => part !== '');
            
            // Get the last part of the path (e.g. 'rent')
            let lastPart = pathParts[pathParts.length - 1];
            
            // Remove "-map" from the last part if it exists
            pathParts[pathParts.length - 1] = lastPart.replace('-map', '');
            
            // Construct new URL with same query parameters
            let newUrl = '/' + pathParts.join('/') + '?' + currentUrl.searchParams.toString();
            window.location.href = newUrl;
        });
    }

    function callBtnFunc(){
        // Handle both call and email popup buttons
        jQuery(document).on('click', '.hz-call-popup-js, .hz-email-popup-js, .hz-whatsapp-popup-js', function() {
            var dataType = jQuery(this).data('type') || '';
            var dataLink = jQuery(this).data('link') || '';
            var modalId = jQuery(this).data('model-id');
            var $modal = jQuery('#' + modalId);

            // Check if modal exists and show it
            if ($modal.length) {
                $modal.modal('show');
                let select = $modal.find(".ms-nice-select__country-code");
                if (!select.hasClass('niceSelectApplied')) {
                    select.niceSelect();
                    select.addClass('niceSelectApplied');
                }
                //callCountryCodeFunc($modal);
            }
            
            $modal.find('.form-data-type').val(dataType);
            $modal.find('.form-link').val(dataLink);
            $modal.find('.form_messages').html("");
            
            if(dataType === 'c') {
                $modal.find('.email-field-wrapper').show();
                $modal.find('.phone-field-wrapper').show();
                $modal.find('.message-field-wrapper').hide();
                $modal.find('.ms-filter__modal__heading h5').text('Call Agency');
                $modal.find('.accept_text').text('call');
                $modal.find('.phone-field-label').text('Phone Number');
                $modal.find('.phone-field').attr('placeholder', 'Enter Phone Number');
                $modal.find('.submit_btn_text').text('Call');
            } else if(dataType === 'w') {
                $modal.find('.email-field-wrapper').hide();
                $modal.find('.phone-field-wrapper').show();
                $modal.find('.message-field-wrapper').hide();
                $modal.find('.ms-filter__modal__heading h5').text('Whatsapp Agency');
                $modal.find('.accept_text').text('whatsapp');
                $modal.find('.phone-field-label').text('Whatsapp Number');
                $modal.find('.phone-field').attr('placeholder', 'Enter Whatsapp Number');
                $modal.find('.submit_btn_text').text('Start Chat');
            } else {
                $modal.find('.email-field-wrapper').show();
                $modal.find('.phone-field-wrapper').hide();
                $modal.find('.message-field-wrapper').show();
                $modal.find('.ms-filter__modal__heading h5').text('Email Agency');
                $modal.find('.accept_text').text('submit');
                $modal.find('.submit_btn_text').text('Submit');
            }

        }).bind();
    }
    

    <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {?>
        jQuery(".ms-nice-select-popular").niceSelect();
        functionPropertyLocationShowMore();
        functionListingItemImageSlider();
        setTimeout(function() {
            mestate_Add_Markers(<?php echo json_encode($properties_data); ?>);
        }, 100);
        callBtnFunc();
        functionGoToListPage();
    <?php } else { ?>
        jQuery(document).ready(function($) {
            jQuery(".ms-nice-select-popular").niceSelect();
            functionPropertyLocationShowMore();
            functionListingItemImageSlider();
            setTimeout(function() {
                mestate_Add_Markers(<?php echo json_encode($properties_data); ?>);
            }, 100);
            callBtnFunc();
            functionGoToListPage();
        });
    <?php } ?>
</script>