<?php
global $post, $paged, $listing_founds, $search_qry;

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
$sidebar_image = $settings['sidebar_image'];
$sidebar_download_url = $settings['sidebar_download_url'];
$sidebar_download_text = $settings['sidebar_download_text'];

if( isset($_GET["status"]) && !empty($_GET["status"]) && !empty($_GET["status"][0]) ){
    $status = $_GET["status"][0];
}
elseif( isset($status_data) && !empty($status_data) ){
    $status = $status_data;
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

    // Filter by listing_id if present in URL
    if(isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
        $listing_ids = array_map('intval', $_GET['listing_id']);
        $search_qry['post__in'] = $listing_ids;
    }

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );

    //echo "<pre>";print_r($search_qry);exit;
    $search_query = new WP_Query( $search_qry );

    // Create a separate query for price calculation that ignores pagination
    $price_query_args = $search_qry;
    $price_query_args['posts_per_page'] = -1; // Get all posts
    $price_query_args['fields'] = 'ids'; // Only get post IDs for better performance
    unset($price_query_args['offset']); // Remove offset
    unset($price_query_args['post__not_in']); // Remove post__not_in to include all posts
    $price_calculation_query = new WP_Query($price_query_args);

    // Initialize min and max price variables
    $min_price = PHP_FLOAT_MAX;
    $max_price = 0;

    // Loop through all post IDs to find min and max prices
    if ($price_calculation_query->have_posts()) {
        foreach ($price_calculation_query->posts as $post_id) {
            $property_price = get_post_meta($post_id, 'fave_property_price', true);
            
            // Convert price to numeric value, removing any formatting
            $price_numeric = preg_replace('/[^0-9.]/', '', $property_price);
            $price_numeric = floatval($price_numeric);
            
            if ($price_numeric > 0) {
                $min_price = min($min_price, $price_numeric);
                $max_price = max($max_price, $price_numeric);
            }
        }
    }

    // If no valid prices were found, reset min_price
    if ($min_price === PHP_FLOAT_MAX) {
        $min_price = 0;
    }

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

    // Filter by listing_id if present in URL
    if(isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
        $listing_ids = array_map('intval', $_GET['listing_id']);
        $search_qry['post__in'] = $listing_ids;
    }

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );
    $search_query = new WP_Query( $search_qry );

    // Create a separate query for price calculation that ignores pagination
    $price_query_args = $search_qry;
    $price_query_args['posts_per_page'] = -1; // Get all posts
    $price_query_args['fields'] = 'ids'; // Only get post IDs for better performance
    unset($price_query_args['offset']); // Remove offset
    unset($price_query_args['post__not_in']); // Remove post__not_in to include all posts
    $price_calculation_query = new WP_Query($price_query_args);

    // Initialize min and max price variables
    $min_price = PHP_FLOAT_MAX;
    $max_price = 0;

    // Loop through all post IDs to find min and max prices
    if ($price_calculation_query->have_posts()) {
        foreach ($price_calculation_query->posts as $post_id) {
            $property_price = get_post_meta($post_id, 'fave_property_price', true);
            
            // Convert price to numeric value, removing any formatting
            $price_numeric = preg_replace('/[^0-9.]/', '', $property_price);
            $price_numeric = floatval($price_numeric);
            
            if ($price_numeric > 0) {
                $min_price = min($min_price, $price_numeric);
                $max_price = max($max_price, $price_numeric);
            }
        }
    }

    // If no valid prices were found, reset min_price
    if ($min_price === PHP_FLOAT_MAX) {
        $min_price = 0;
    }


}

//echo "<pre>";print_r($search_query);exit;

$total_records = $search_query->found_posts + count($advertise_post_ids);

$record_found_text = esc_html__('Result Found', 'houzez');
if( $total_records > 1 ) {
    $record_found_text = esc_html__('Results Found', 'houzez');
}

?>

<!-- start: Apartments section  -->
<section class="ms-apartments-main section--wrapper">
    <div class="container">
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


            <!-- apartments content -->
            <div class="col-12 col-xl-8 mb-2 mb-md-5 mb-xl-0">
                <!-- locations -->
                <div id="ajax_location_container">
                <?php 
                if( $total_records > 1 ) {
                    $locations_list = apply_filters("houzez_after_search__get_property_type_list", $search_qry);

                    if( $locations_list !== "" ){
                        echo $locations_list;
                    }
                }
                ?>
                </div>

                <!-- button list -->

                <ul class="ms-apartments-main__button-list">
                    <li>
                        <a
                            href="javascript:void(0)"
                            class="ms-btn ms-btn--bordered ms-btn--list active"
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
                            href="javascript:;"
                            class="ms-btn ms-btn--bordered goToMapPage"
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
                    <li class="ms-apartments-main__varify-switcher">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="msverify">Show Verified First
                            </label>
                            <?php 
                                $is_verified_first = isset($_GET['sortby']) && $_GET['sortby'] === 'verified_first';
                            ?>
                            <input class="form-check-input" type="checkbox" id="msverify" 
                                <?php echo $is_verified_first ? 'checked' : ''; ?>
                            />
                        </div>
                    </li>
                </ul>

                <!-- apartments cards -->
                <div id="houzez_ajax_container">
                    <div class="ms-apartments-main__card__wrapper listing_price_min_max" data-min_price="<?php echo $min_price;?>" data-max_price="<?php echo $max_price;?>">
                    <?php
                        if ( 1 == $paged && !empty($combined_posts) ) :
                            //echo "<pre>";print_r($combined_posts);exit;
                            foreach ($combined_posts as $post) {
                                
                                setup_postdata($post);
                                get_template_part('elementor-widgets/template-parts/mestate-listing-item-v1');

                            }
                        elseif ( $search_query->have_posts() ) :
                            while ( $search_query->have_posts() ) : $search_query->the_post();

                            get_template_part('elementor-widgets/template-parts/mestate-listing-item-v1');

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


            <!-- apartment sidebar -->
            <div class="col-12 col-md-7 col-xl-4 pl-3">
                <!-- sidebar single -->
                <?php if(!empty($sidebar_image)) { ?>
                <div class="ms-apartments-main__sidebar__single">
                    <a href="<?php echo !empty($sidebar_download_url) ? esc_url($sidebar_download_url['url']) : '#'; ?>"
                        ><img src="<?php echo esc_url($sidebar_image['url']); ?>" alt="<?php echo esc_attr($sidebar_image['alt']); ?>"
                    /></a>

                    <a href="<?php echo !empty($sidebar_download_url) ? esc_url($sidebar_download_url['url']) : '#'; ?>" class="ms-btn ms-btn--primary"><?php echo $sidebar_download_text; ?></a>
                </div>
                <?php }?>
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
                            item2?.classList?.add("ms-show");
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
    window.functionPropertyLocationShowMore = functionPropertyLocationShowMore;

    function functionGoToMapPage(){
        // Handle map page navigation
        jQuery('.goToMapPage').on('click', function() {
            let currentUrl = new URL(window.location.href);
            let pathParts = currentUrl.pathname.split('/').filter(part => part !== '');
            
            // Get the last part of the path (e.g. 'rent')
            let lastPart = pathParts[pathParts.length - 1];
            
            // Replace the current page with map version
            pathParts[pathParts.length - 1] = lastPart + '-map';
            
            // Construct new URL with same query parameters
            let newUrl = '/' + pathParts.join('/') + '?' + currentUrl.searchParams.toString();
            window.location.href = newUrl;
        });
    }
    function functionVerifiedFirst(){
        // Add checkbox change handler
        jQuery('#msverify').on('change', function() {
            //console.log('change');
            if (this.checked) {
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sortby', 'verified_first');
                window.location.href = currentUrl.toString();
            } else {
                // Remove verified_first from sortby parameter
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('sortby');
                window.location.href = currentUrl.toString();
            }
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

    function callCountryCodeFunc($modal){
        // nice select for country code
        if ($modal.find(".ms-nice-select__country-code:not(.niceSelectApplied)")?.length) {
            fetch("https://restcountries.com/v3.1/all")
                .then(response => response.json())
                .then(data => {
                    let select = $modal.find(".ms-nice-select__country-code");

                    let countryCodes = new Set(); // To avoid duplicates

                    data.forEach(country => {
                        if (country.idd?.root) {
                            let fullCode =
                                country.idd.root +
                                (country.idd.suffixes ? country.idd.suffixes[0] : "");
                            countryCodes.add(fullCode);
                        }
                    });

                    // Sort and add unique country codes to the dropdown
                    [...countryCodes].sort().forEach(code => {
                        select.append(`<option value="${code}">${code}</option>`);
                    });

                    select.niceSelect(); // Initialize Nice Select
                })
                .catch(error => console.error("Error fetching country codes:", error));
        }
    }
    

    <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {?>

        jQuery(".ms-nice-select-popular").niceSelect();
        functionPropertyLocationShowMore();
        functionGoToMapPage();
        functionVerifiedFirst();
        callBtnFunc();
    <?php } else { ?>
        jQuery(document).ready(function($) {
            jQuery(".ms-nice-select-popular").niceSelect();
            functionPropertyLocationShowMore();
            functionGoToMapPage();
            functionVerifiedFirst();
            callBtnFunc();
        });
    <?php } ?>
</script>