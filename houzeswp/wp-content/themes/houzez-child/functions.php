<?php
include_once get_stylesheet_directory() . '/framework/functions/child_emails_functions.php';

/*-----------------------------------------------------------------------------------*/
// Include Child Js Files
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'load_child_theme_scripts', 9);
function load_child_theme_scripts()
{
    global $current_user, $post;
    wp_get_current_user();
    $userID = $current_user->ID;

    wp_enqueue_script('child_scripts', get_stylesheet_directory_uri() . '/js/custom-child.js', array('jquery'));
    wp_localize_script(
        'child_scripts',
        'child_vars',
        array(
            'AjaxUrl' => admin_url('admin-ajax.php'),
            'userID' => $userID,
            'mu_title_text' => houzez_option('cl_subl_title', 'Title'),
            'mu_type_text' => houzez_option('cl_subl_type', 'Property Type'),
            'mu_beds_text' => houzez_option('cl_subl_bedrooms', 'Bedrooms'),
            'mu_beds_text' => houzez_option('cl_subl_bedrooms_plac', 'Bedrooms'),
            'mu_baths_text' => houzez_option('cl_subl_bathrooms', 'Bathrooms'),
            'mu_baths_text' => houzez_option('cl_subl_bathrooms_plac', 'Bathrooms'),
            'mu_size_text' => houzez_option('cl_subl_size', 'Property Size'),
            'mu_size_text' => houzez_option('cl_subl_size_plac', 'Property Size'),
            'mu_size_postfix_text' => houzez_option('cl_subl_size_postfix', 'Size Postfix'),
            'mu_price_text' => houzez_option('cl_subl_price', 'Price'),
            'mu_price_postfix_text' => houzez_option('cl_subl_price_postfix', 'Price Postfix'),
            'mu_availability_text' => houzez_option('cl_subl_date', 'Availability Date'),
            'are_you_sure_text' => esc_html__('Are you sure you want to do this?', 'houzez'),
            'delete_btn_text' => esc_html__('Delete', 'houzez'),
            'cancel_btn_text' => esc_html__('Cancel', 'houzez'),
            'confirm_btn_text' => esc_html__('Confirm', 'houzez'),
            'processing_text' => esc_html__('Processing, Please wait...', 'houzez'),
            'add_listing_msg' => esc_html__('Submitting, Please wait...', 'houzez'),
            'featured_listings_none' => esc_html__('You have used all the "Featured" listings in your package.', 'houzez'),
        )
    );
}

/**
 * Function to get all users who have listing in database 
 *
 * @return void
 */
function get_users_with_listings()
{
    global $wpdb;

    $users_with_listings = $wpdb->get_results(
        "
        SELECT DISTINCT u.ID, u.user_email, u.user_login 
        FROM $wpdb->users u
        INNER JOIN $wpdb->posts p ON u.ID = p.post_author
        WHERE p.post_type = 'property' AND p.post_status IN ('publish', 'pending', 'expired')
        "
    );

    return $users_with_listings;
}



// function remove_houzez_expire_listing() {
//     remove_action( 'admin_action_houzez_expire_listing', array( 'Houzez_Class_Name', 'houzez_expire_listing' ) );
// }
// add_action( 'init', 'remove_houzez_expire_listing' );



// remove_action('admin_action_houzez_expire_listing', 'houzez_expire_listing');
// function custom_houzez_expire_listing() {
//     if (! ( isset( $_GET['listing_id']) || isset( $_POST['listing_id']) || ( isset($_REQUEST['action']) && 'houzez_expire_listing' == $_REQUEST['action'] ) ) ) {
//         wp_die('No property exists');
//     }

//     $listing_id = (isset($_GET['listing_id']) ? $_GET['listing_id'] : $_POST['listing_id']);
//     $post_id = absint($listing_id);

//     $listing_data = array(
//         'ID' => $post_id,
//         'post_status' => 'expired'
//     );
//     wp_update_post($listing_data);

//     update_post_meta($post_id, 'fave_featured', '0');

//     $author_id = get_post_field('post_author', $post_id);
//     $user = get_user_by('id', $author_id);
//     $user_email = $user->user_email;
//     $first_name = get_user_meta( $user->ID, 'first_name', true );
//     $args = array(
//         'user_login_firstname ' => $first_name, // Pass the user's login name
//         'listing_title' => get_the_title($post_id),
//         'listing_url' => get_permalink($post_id),
//         'user_email_register' => $user->user_email  // Pass the user's email
        

//     );
    
//     // Send email to the user
//     houzez_email_type($user_email, 'listing_expired', $args);

//     wp_redirect(admin_url('edit.php?post_type=property'));
//     exit;
// }

// // Hook the custom function to the same action
// add_action('admin_action_houzez_expire_listing', 'custom_houzez_expire_listing');



/*-----------------------------------------------------------------------------------*/
// Get price
/*-----------------------------------------------------------------------------------*/
// if( !function_exists('houzez_get_property_price_sublistings') ) {
//     function houzez_get_property_price_sublistings ( $listing_price ) {

    
//         if( $listing_price ) {
            
            
//             $currency_maker = currency_maker();

//             $listings_currency = $currency_maker['currency'];
//             $price_decimals = $currency_maker['decimals'];
//             $listing_currency_pos = $currency_maker['currency_position'];
//             $price_thousands_separator = $currency_maker['thousands_separator'];
//             $price_decimal_point_separator = $currency_maker['decimal_point_separator'];
        
//             $short_prices = houzez_option('short_prices');

//             if($short_prices != 1 ) {

//                 $listing_price =  $listing_price ;
//                 if ( class_exists( 'FCC_Rates' ) && houzez_currency_switcher_enabled() && isset( $_COOKIE[ "houzez_set_current_currency" ] ) ) {

//                     $listing_price = apply_filters( 'houzez_currency_switcher_filter', $listing_price );
//                     return $listing_price;
//                 }
                
//                 $indian_format = houzez_option('indian_format');
//                 if($indian_format == 1) {
//                     $final_price = houzez_moneyFormatIndia ($listing_price);
//                 } else {
//                     //number_format() — Format a number with grouped thousands
//                     $final_price = number_format($listing_price , $price_decimals , $price_decimal_point_separator , $price_thousands_separator);
//                 }


//             } else {
//                 $final_price = $listing_price . $price_decimals;
//             }
//             if(  $listing_currency_pos == 'before' ) {
//                 return $listings_currency . $final_price;
//             } else {
//                 return $final_price . $listings_currency;
//             }

//         } else {
//             $listings_currency = '';
//         }

//         return $listings_currency;
//     }
// }



if( !function_exists('houzez_save_search') ) {
    function houzez_save_search() {

        $nonce = $_REQUEST['houzez_save_search_ajax'];
        if( !wp_verify_nonce( $nonce, 'houzez-save-search-nounce' ) ) {
            echo json_encode(array(
                'success' => false,
                'msg' => esc_html__( 'Unverified Nonce!', 'houzez')
            ));
            wp_die();
        }

        global $wpdb, $current_user;

        wp_get_current_user();
        $userID       =  $current_user->ID;
        $userEmail    =  $current_user->user_email;
        $search_args  =  $_REQUEST['search_args'];
        $table_name   = $wpdb->prefix . 'houzez_search';
        $request_url  = $_REQUEST['search_URI'];

        // UPDATE SEARCH
        if( !empty($request_url) ){
            parse_str($request_url, $search_query);
            $search_id  = (int)$search_query['search_id'];

            if( isset($search_id) && !empty($search_id) ){
                $wpdb->update($table_name, array('query'=>$search_args, 'url'=>$request_url, 'time'=>current_time( 'mysql' )), array('id'=>$search_id));
                echo json_encode( array( 'success' => true, 'msg' => esc_html__('Search is updated. You will receive an email notification when new properties matching your search will be published', 'houzez') ) );
                wp_die();
            }
        }
        // END UPDATE SEARCH

        $wpdb->insert(
            $table_name,
            array(
                'auther_id' => $userID,
                'query'     => $search_args,
                'email'     => $userEmail,
                'url'       => $request_url,
                'time'      => current_time( 'mysql' )
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        echo json_encode( array( 'success' => true, 'msg' => esc_html__('Search is saved. You will receive an email notification when new properties matching your search will be published', 'houzez') ) );
        wp_die();
    }
}

if( !function_exists('houzez20_half_map_listings') ) {
    function houzez20_half_map_listings() {
        global $houzez_local;
        $houzez_local = houzez_get_localization();

    	$tax_query = array();
        $meta_query = array();
        $allowed_html = array();
        $keyword_array = '';
        $keyword_field = houzez_option('keyword_field');
        $search_num_posts = houzez_option('search_num_posts');

        $number_of_prop = $search_num_posts;
		if(!$number_of_prop){
		    $number_of_prop = 9;
		}

        $listing_page_id = $_GET['listing_page_id'] ?? '';
        $sort_by = $listing_page_id ? get_post_meta($listing_page_id, 'fave_properties_sort', true) : '';
        
        if( isset( $_GET['sortby'] ) && $_GET['sortby'] != '' ) {
            $sort_by = $_GET['sortby'];
        }

        $paged = $_GET['paged'] ?? '';

    	$search_qry = array(
            'post_type' => 'property',
            'posts_per_page' => $number_of_prop,
            'paged' => $paged,
            'post_status' => 'publish'
        );

        $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );

        $item_layout = $_GET['item_layout'] ?? 'v1';

        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            if ($keyword_field == 'prop_address') {
                
                $keyword_array = houzez_keyword_meta_address();

            } else if ($keyword_field == 'prop_city_state_county') {
            
                $taxlocation[] = sanitize_title(wp_kses($_GET['keyword'], $allowed_html));
		        $_tax_query = Array();
		        $_tax_query['relation'] = 'OR';

		        $_tax_query[] = array(
		            'taxonomy' => 'property_area',
		            'field' => 'slug',
		            'terms' => $taxlocation
		        );

		        $_tax_query[] = array(
		            'taxonomy' => 'property_city',
		            'field' => 'slug',
		            'terms' => $taxlocation
		        );

		        $_tax_query[] = array(
		            'taxonomy' => 'property_state',
		            'field' => 'slug',
		            'terms' => $taxlocation
		        );
		        $tax_query[] = $_tax_query;
                
            } else {
            
                $search_qry = houzez_keyword_search($search_qry);
            }
        }

        $tax_query = apply_filters( 'houzez_taxonomy_search_filter', $tax_query );
		$tax_count = count($tax_query);
        
        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }

        if ($tax_count > 0) {
            $search_qry['tax_query'] = $tax_query;
        }

        $meta_query = apply_filters( 'houzez_meta_search_filter', $meta_query );
        $meta_count = count($meta_query);
        if ($meta_count > 0 || !empty($keyword_array)) {
            $search_qry['meta_query'] = array(
                'relation' => 'AND',
                $keyword_array,
                array(
                    'relation' => 'AND',
                    $meta_query
                ),
            );
        }


        if ( $sort_by == 'a_title' ) {
            $search_qry['orderby'] = 'title';
            $search_qry['order'] = 'ASC';
        } else if ( $sort_by == 'd_title' ) {
            $search_qry['orderby'] = 'title';
            $search_qry['order'] = 'DESC';
        } else if ( $sort_by == 'a_price' ) {
            $search_qry['orderby'] = 'meta_value_num';
            $search_qry['meta_key'] = 'fave_property_price';
            $search_qry['order'] = 'ASC';
        } else if ( $sort_by == 'd_price' ) {
            $search_qry['orderby'] = 'meta_value_num';
            $search_qry['meta_key'] = 'fave_property_price';
            $search_qry['order'] = 'DESC';
        } else if ( $sort_by == 'featured' ) {
            $search_qry['meta_key'] = 'fave_featured';
            $search_qry['meta_value'] = '1';
            $search_qry['orderby'] = 'meta_value date';
        } else if ( $sort_by == 'featured_random' ) {
            $search_qry['meta_key'] = 'fave_featured';
            $search_qry['meta_value'] = '1';
            $search_qry['orderby'] = 'meta_value DESC rand';
        } else if ( $sort_by == 'a_date' ) {
            $search_qry['orderby'] = 'date';
            $search_qry['order'] = 'ASC';
        } else if ( $sort_by == 'd_date' ) {
            $search_qry['orderby'] = 'date';
            $search_qry['order'] = 'DESC';
        } else if ( $sort_by == 'featured_first' ) {
            $search_qry['orderby'] = 'meta_value date';
            $search_qry['meta_key'] = 'fave_featured';
        } else if ( $sort_by == 'featured_first_random' ) {
            $search_qry['meta_key'] = 'fave_featured';
            $search_qry['orderby'] = 'meta_value DESC rand'; 
        } else if ( $sort_by == 'featured_top' ) {
            $search_qry['orderby'] = 'meta_value date';
            $search_qry['meta_key'] = 'fave_featured';
        } else if ( $sort_by == 'random' ) {
            $search_qry['orderby'] = 'rand';
        }

        
        $properties_data = array();
        $query_args = new WP_Query( $search_qry );

        $deck_start = $deck_end = '';

        if( $item_layout != 'list-v7' ) {
            $deck_start = '<div class="card-deck">';
            $deck_end   = '</div>';
        }

        
        ob_start();

        echo $deck_start;

        $total_properties = $query_args->found_posts;

        while( $query_args->have_posts() ): $query_args->the_post();

            $property_array_temp = array();

        	$property_array_temp[ 'title' ] = get_the_title();
            $property_array_temp[ 'url' ] = get_permalink();
            $property_array_temp[ 'link_target' ] = houzez_option('listing_link_target', '_self');
            $property_array_temp['price'] = houzez_listing_price_v1();
            $property_array_temp['property_id'] = get_the_ID();
            $property_array_temp['pricePin'] = houzez_listing_price_map_pins();
            $property_array_temp['property_type'] = houzez_taxonomy_simple('property_type');

            $address = houzez_get_listing_data('property_map_address');
            if(!empty($address)) {
                $property_array_temp['address'] = $address;
            }

            //Property meta
            $property_array_temp['meta'] = houzez_map_listing_meta();

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
                $property_array_temp[ 'marker' ]       = HOUZEZ_IMAGE . 'map/pin-single-family.png';           
                $property_array_temp[ 'retinaMarker' ] = HOUZEZ_IMAGE . 'map/pin-single-family.png';  
            }

            //Featured image
            if ( has_post_thumbnail() ) {
                $thumbnail_id         = get_post_thumbnail_id();
                $thumbnail_array = wp_get_attachment_image_src( $thumbnail_id, 'houzez-item-image-1' );
                if ( ! empty( $thumbnail_array[ 0 ] ) ) {
                    $property_array_temp[ 'thumbnail' ] = $thumbnail_array[ 0 ];
                }
            }

        	$properties_data[] = $property_array_temp;

            get_template_part('template-parts/listing/item', $item_layout);


        endwhile;

        wp_reset_query();
        echo $deck_end;

        echo '<div class="clearfix"></div>';
        houzez_ajax_pagination( $query_args->max_num_pages );

        $listings_html = ob_get_contents();
        ob_end_clean();

        $encoded_query = base64_encode( serialize( $query_args->query ) );

        $search_uri = '';
        $get_search_uri = $_SERVER['HTTP_REFERER'];
        $get_search_uri = explode( '/?', $get_search_uri );
        if(isset($get_search_uri[1]) && $get_search_uri[1] != "") {
            $search_uri = $get_search_uri[1];
        }

        $request_url  = $_REQUEST['search_URI'];

        // UPDATE SEARCH
        if( !empty($request_url) ){
            parse_str($request_url, $search_query);
            $search_id  = (int)$search_query['search_id'];

            if( isset($search_id) && !empty($search_id) ){
                $search_uri = $search_uri.'&search_id='.$search_id;
            }
        }
        // END UPDATE SEARCH

        if( count($properties_data) > 0 ) {
            echo json_encode( array( 'getProperties' => true, 'properties' => $properties_data, 'total_results' => $total_properties, 'propHtml' => $listings_html, 'query' => $encoded_query, 'search_uri' => $search_uri ) );
            exit();
        } else {
            echo json_encode( array( 'getProperties' => false, 'total_results' => $total_properties, 'query' => $encoded_query, 'search_uri' => $search_uri ) );
            exit();
        }
        die();

	}
}

if(!function_exists('houzez_search_min_max_rate')) {
	function houzez_search_min_max_rate($meta_query) {
		if (isset($_GET['min_rate']) && !empty($_GET['min_rate']) && isset($_GET['max_rate']) && !empty($_GET['max_rate'])) {
            $min_rate = doubleval(houzez_clean($_GET['min_rate']));
            $max_rate = doubleval(houzez_clean($_GET['max_rate']));

            if ($min_rate > 0 && $max_rate >= $min_rate) { 
                $meta_query[] = array(
                    'key' => 'fave_rent',
                    'value' => array($min_rate, $max_rate),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                );
            }
        } else if (isset($_GET['min_rate']) && !empty($_GET['min_rate'])) {
            $min_rate = doubleval(houzez_clean($_GET['min_rate']));
            if ($min_rate > 0) {
                $meta_query[] = array(
                    'key' => 'fave_rent',
                    'value' => $min_rate,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
            }
        } else if (isset($_GET['max_rate']) && !empty($_GET['max_rate'])) {
            $max_rate = doubleval(houzez_clean($_GET['max_rate']));
            if ($max_rate > 0) {
                $meta_query[] = array(
                    'key' => 'fave_rent',
                    'value' => $max_rate,
                    'type' => 'NUMERIC',
                    'compare' => '<=',
                );
            }
        }
        return $meta_query;
	}

	add_filter('houzez_meta_search_filter', 'houzez_search_min_max_rate');
}

if(!function_exists('houzez_search_min_max_size')) {
	function houzez_search_min_max_size($meta_query) {
		if (isset($_GET['min_size']) && !empty($_GET['min_size']) && isset($_GET['max_size']) && !empty($_GET['max_size'])) {
            $min_size = doubleval(houzez_clean($_GET['min_size']));
            $max_size = doubleval(houzez_clean($_GET['max_size']));

            if ($min_size > 0 && $max_size >= $min_size) { 
                $meta_query[] = array(
                    'key' => 'fave_space-size',
                    'value' => array($min_size, $max_size),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                );
            }
        } else if (isset($_GET['min_size']) && !empty($_GET['min_size'])) {
            $min_size = doubleval(houzez_clean($_GET['min_size']));
            if ($min_size > 0) {
                $meta_query[] = array(
                    'key' => 'fave_space-size',
                    'value' => $min_size,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                );
            }
        } else if (isset($_GET['max_size']) && !empty($_GET['max_size'])) {
            $max_size = doubleval(houzez_clean($_GET['max_size']));
            if ($max_size > 0) {
                $meta_query[] = array(
                    'key' => 'fave_space-size',
                    'value' => $max_size,
                    'type' => 'NUMERIC',
                    'compare' => '<=',
                );
            }
        }
        return $meta_query;
	}

	add_filter('houzez_meta_search_filter', 'houzez_search_min_max_size');
}