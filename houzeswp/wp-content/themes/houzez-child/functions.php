<?php
// code will goes here

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
?>