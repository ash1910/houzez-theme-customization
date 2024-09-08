<?php
// code will goes here
add_filter( 'houzez_localization', 'houzez_developer_localization' );

function houzez_developer_localization( $localization ) {

    $localization['developer_license'] = esc_html__( 'Developer License', 'houzez' );
    $localization['search_developer_name'] = esc_html__( 'Enter developer name', 'houzez' );
    $localization['search_developer_btn'] = esc_html__( 'Search Developer', 'houzez' );
    $localization['all_developer_cats'] = esc_html__( 'All Categories', 'houzez' );
    $localization['all_developer_cities'] = esc_html__( 'All Cities', 'houzez' );

    return $localization;
}

add_filter('houzez_developers_search_filter', 'houzez_developers_search_filter_callback');
if( !function_exists('houzez_developers_search_filter_callback') ) {
    function houzez_developers_search_filter_callback( $query_args ) {
        global $paged;

        $tax_query = array();
        $meta_query = array();

        $paged = 1;
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } elseif ( get_query_var( 'page' ) ) { // if is static front page
            $paged = get_query_var( 'page' );
        }

        $query_args['paged'] = $paged;

        $number_of_developers = houzez_option('num_of_developers');

        if(!$number_of_developers){
            $query_args[ 'posts_per_page' ]  = 9;
        } else {
            $query_args[ 'posts_per_page' ] = $number_of_developers;
        }

        if(isset($_GET['city']) && $_GET['city'] != "") {
            $tax_query[] = array(
                'taxonomy' => 'developer_city',
                'field' => 'slug',
                'terms' => $_GET['city']
            );
        }
        if(isset($_GET['category']) && $_GET['category'] != "") {
            $tax_query[] = array(
                'taxonomy' => 'developer_category',
                'field' => 'slug',
                'terms' => $_GET['category']
            );
        }

        $tax_count = count( $tax_query );

        if( $tax_count > 1 ) {
            $tax_query['relation'] = 'AND';
        }
        if( $tax_count > 0 ){
            $query_args['tax_query'] = $tax_query;
        }

        /* Search by keyword */ 
        if( isset ( $_GET['developer_name'] ) ) {
            $keyword = trim( $_GET['developer_name'] );
            $keyword = sanitize_text_field($keyword);
            if ( ! empty( $keyword ) ) {
                $query_args['s'] = $keyword;
            }
        }

        return $query_args;
    }
}

function developer_properties_count( $developer_id = null ) {
    if ( null == $developer_id ) {
        $developer_id = get_the_ID();
    }

    $args = array(
        'post_type' => 'property',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'fave_developers',
                'value' => $developer_id,
                'compare' => '='
            ),
            array(
                'key' => 'fave_agent_display_option',
                'value' => 'developer_info',
                'compare' => '='
            )
        )
    );

    $args = apply_filters( 'houzez_sold_status_filter', $args );

    $qry = new WP_Query( $args );
    return $qry->found_posts;
}

function loop_developer_properties( $developer_id = null ) {
    global $paged;

    if ( is_front_page()  ) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    }

    if ( null == $developer_id ) {
        $developer_id = get_the_ID();
    }

    $tax_query = array();

    if ( isset( $_GET['tab'] ) && !empty($_GET['tab']) && $_GET['tab'] != "reviews") {
        $tax_query[] = array(
            'taxonomy' => 'property_status',
            'field' => 'slug',
            'terms' => $_GET['tab']
        );
    }

    $args = array(
        'post_type' => 'property',
        'posts_per_page' => houzez_option('num_of_developer_listings', 10),
        'post_status' => 'publish',
        'paged' => $paged,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'fave_developers',
                'value' => $developer_id,
                'compare' => '='
            ),
            array(
                'key' => 'fave_agent_display_option',
                'value' => 'developer_info',
                'compare' => '='
            )
        )
    );

    $args = apply_filters( 'houzez_sold_status_filter', $args );

    $count = count($tax_query);
    if($count > 0 ) {
        $args['tax_query'] = $tax_query;
    }

    $args = houzez_prop_sort($args);

    $the_query = new WP_Query( $args );
    return $the_query;
}

function get_developer_properties_ids_by_developer_id( $developer_id = 0 ) {
        

    $property_ids = array();
    
    if ( empty( $developer_id ) ) {
        return $property_ids;
    }

    $args = array(
        'post_type'         => 'property',
        'posts_per_page'    => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'fave_developers',
                'value' => $developer_id,
                'compare' => '='
            ),
            array(
                'key' => 'fave_developer_display_option',
                'value' => 'developer_info',
                'compare' => '='
            )
        )
    );

    $args = apply_filters( 'houzez_sold_status_filter', $args );

    $qry = new WP_Query( $args );

    if( $qry->have_posts() ):
        while( $qry->have_posts() ):
            $qry->the_post();

                $property_ids[] = get_the_ID();
        endwhile;
    endif;
    Houzez_Query::loop_reset();

    return $property_ids;
}

if(!function_exists('houzez_is_developers_template')) {
    function houzez_is_developers_template() {

        if( is_page_template(array('template/template-developers.php')) ) {
            return true;

        }
        return false;
    }
}

add_filter( 'houzez_search_needed_filter', 'houzez_search_needed_filter' );

function houzez_search_needed_filter( $search ) {

    $search[] = 'template/template-developers.php';

    return $search;
}

add_filter( 'houzez_main_wrap_class', 'houzez_main_wrap_class_developer' );

function houzez_main_wrap_class_developer( $classes ) {

    if ( is_singular() ) {

        $developer_detail_layout = houzez_option('developer-detail-layout', 'v1');

        $developers_layout = houzez_option('developers-template-layout', 'v1');

        if( isset( $_GET['developers-layout'] ) && $_GET['developers-layout'] != "" ) {
            $developers_layout = esc_html($_GET['developers-layout']);
        }

        if( isset( $_GET['single-developer-layout'] ) && $_GET['single-developer-layout'] != "" ) {
            $developer_detail_layout = esc_html($_GET['single-developer-layout']);
        }

        if ( is_page_template( 'template/template-developers.php' ) && $developers_layout != 'v1' ) {
            $classes[] = 'houzez-main-wrap-v2'; 
        }

        if( ( is_singular('houzez_developer') && $developer_detail_layout == 'v2' ) ) {
            $classes[] = 'agent-detail-page-v2';
        }

    }

    return array_unique( $classes );

}

function houzez_search_needed() {

    $files = apply_filters( 'houzez_search_needed_filter', array(
        'template/property-listings-map.php',
        'template/template-agents.php',
        'template/template-agencies.php',
        'template/user_dashboard_profile.php',
        'template/user_dashboard_properties.php',
        'template/user_dashboard_favorites.php',
        'template/user_dashboard_invoices.php',
        'template/user_dashboard_saved_search.php',
        'template/user_dashboard_floor_plans.php',
        'template/user_dashboard_multi_units.php',
        'template/user_dashboard_membership.php',
        'template/user_dashboard_gdpr.php',
        'template/user_dashboard_submit.php',
        'template/template-packages.php',
        'template/template-payment.php',
        'template/template-thankyou.php',
        'template/user_dashboard_messages.php'
    ) );

    if( !houzez_option('single_prop_search') && is_singular('property') ) {
        return false;
    } elseif( is_search() ) {
        return false;
    } elseif( is_author() ) {
        return false;
    } elseif( is_404() ) {
        return false;
    } elseif ( is_page_template( $files ) ) {
        return false;

    } elseif(houzez_is_half_map()) {
        return false;

    } elseif( is_singular('houzez_agent') ) {
        return false;

    } elseif( is_singular('houzez_agency') ) {
        return false;

    } elseif( !houzez_option('is_tax_page', 1) && is_tax() ) {
        return false;

    } elseif( !houzez_option('blog_page_search') && is_singular('post') ) {
        return false;

    } elseif( !houzez_option('blog_page_search') && is_home() ) {
        return false;

    } elseif( is_post_type_archive('houzez_agent') ) {
        return false;

    } elseif( is_post_type_archive('houzez_agency') ) {
        return false;

    } elseif(houzez_is_splash()) {
        return false;
    }elseif( is_singular('houzez_developer') ) {
        return false;

    } elseif( is_post_type_archive('houzez_developer') ) {
        return false;

    }
    return true;
}

/**
 *	---------------------------------------------------------------------------------------
 *	Widgets
 *	---------------------------------------------------------------------------------------
 */
require_once(get_theme_file_path('/framework/widgets/developers-search.php'));

add_action('widgets_init', 'houzez_widgets_developer_init');
function houzez_widgets_developer_init()
{
    register_sidebar(array(
        'name' => esc_html__('Developer Sidebar', 'houzez'),
        'id' => 'developer-sidebar',
        'description' => esc_html__('Widgets in this area will be shown in developers template and developer detail page.', 'houzez'),
        'before_widget' => '<div id="%1$s" class="widget widget-wrap %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widget-header"><h3 class="widget-title">',
        'after_title' => '</h3></div>',
    ));
   
}

if(!function_exists('houzez_map_needed')) {
    function houzez_map_needed() {
        global $post;
        
        $post_id = isset($post->ID) ? $post->ID : 0;
        $header_type = get_post_meta($post_id, 'fave_header_type', true);
        
        if(is_page_template('template/user_dashboard_submit.php')) {
            return true;

        } elseif($header_type == 'property_map') {
            return true;

        } elseif(is_page_template('template/property-listings-map.php')) {
            return true;

        } elseif(is_page_template('template/template-search.php') && houzez_option('search_result_page') == 'half_map') {
            return true;

        } elseif ( is_singular( 'property' ) || is_singular( 'houzez_agent' ) || is_singular( 'houzez_agency' ) ) {
            return true;
        } elseif ( is_singular( 'houzez_developer' ) ) {
            return true;
        }

        return false;
    }
}

add_role(
    'houzez_developer',
    __( 'Developer' ),
    array(
        'read'                      => true,  // true allows this capability
        'edit_posts'                => true,
        'delete_posts'              => true, // Use false to explicitly deny
        'read_property'             => true,
        'publish_posts'             => true,
        'edit_property'             => true,
        'create_properties'         => true,
        'edit_properties'           => true,
        'delete_properties'       => true,
        'edit_published_properties'    => true,
        'publish_properties'        => true,
        'delete_published_properties'   => true,
        'delete_private_properties' => true,
        'read_testimonial'             => true,
        'edit_testimonial'             => true,
        'create_testimonials'         => true,
        'edit_testimonials'           => true,
        'edit_published_testimonials'    => true,
        'publish_testimonials'        => true,
        'delete_published_testimonials'   => true
    )
);

/*-----------------------------------------------------------------------------------*/
// Register
/*-----------------------------------------------------------------------------------*/
add_action( 'houzez_after_register', 'houzez_after_register' );

function houzez_after_register($user_id) {
    $allowed_html = array();

    $usermane          = trim( sanitize_text_field( wp_kses( $_POST['username'], $allowed_html ) ));
    $email             = trim( sanitize_text_field( wp_kses( $_POST['useremail'], $allowed_html ) ));
    $phone_number = isset( $_POST['phone_number'] ) ? $_POST['phone_number'] : '';
    //echo $user_id;echo "<pre>";print_r($_POST);exit;
    if( isset( $_POST['role'] ) && $_POST['role'] == 'houzez_developer' ) {
        $user_role = sanitize_text_field( wp_kses( $_POST['role'], $allowed_html ) );
        wp_update_user( array( 'ID' => $user_id, 'role' => $user_role ) );

        houzez_register_as_developer($usermane, $email, $user_id, $phone_number);
    }
}

if( !function_exists('houzez_register_as_developer') ) {

    function houzez_register_as_developer( $username, $email, $user_id, $mobile_num = null, $image_url = null ) {

        // Create post object
        $args = array(
            'post_title'    => $username,
            'post_type' => 'houzez_developer',
            'post_status'   => 'publish'
        );

        // Insert the post into the database
        $post_id =  wp_insert_post( $args );
        update_post_meta( $post_id, 'houzez_user_meta_id', $user_id);  // used when developer custom post type updated
        update_user_meta( $user_id, 'fave_author_agent_id', $post_id);
        update_post_meta( $post_id, 'fave_developer_email', $email);
        update_post_meta( $post_id, 'fave_developer_mobile', $mobile_num);

        if( houzez_option('realtor_visible', 0) ) {
            update_post_meta( $post_id, 'fave_developer_visible', 1);
        }

        if( !empty($image_url) ) {
            houzez_set_image_from_url($post_id, $image_url);
        }

    }
}

/* ------------------------------------------------------------------------------
* Ajax Update Profile function
/------------------------------------------------------------------------------ */
add_action( 'wp_ajax_nopriv_houzez_ajax_update_profile', 'houzez_ajax_update_profile_developer' );
add_action( 'wp_ajax_houzez_ajax_update_profile', 'houzez_ajax_update_profile_developer' );

if( !function_exists('houzez_ajax_update_profile_developer') ){

    function houzez_ajax_update_profile_developer() {
        //echo "<pre>";print_r($_POST);exit;
        if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
            $userID = intval($_POST['user_id']); // Sanitize the input
            $current_user = get_userdata($userID);
            if ( ! $current_user ) {
                echo json_encode( array( 'success' => false, 'msg' => esc_html__('User not found or invalid user.', 'houzez') ) );
                wp_die();
            } 

        } else {
            $current_user = wp_get_current_user();
            $userID  = get_current_user_id();
        }

        $user_company = $userlangs = $latitude = $longitude = $tax_number = $user_location = $license = $user_address = $fax_number = $firstname = $lastname = $title = $about = $userphone = $usermobile = $userskype = $facebook = $tiktok = $telegram = $twitter = $linkedin = $instagram = $pinterest = $profile_pic = $profile_pic_id = $website = $useremail = $service_areas = $specialties = $whatsapp = $line_id = $zillow = $realtor_com = '';

        $firstname = sanitize_text_field( $_POST['firstname'] );
        $gdpr_agreement = sanitize_text_field( $_POST['gdpr_agreement'] );
        $lastname = sanitize_text_field( $_POST['lastname'] );
        $userlangs = sanitize_text_field( $_POST['userlangs'] );
        $user_company = sanitize_text_field( $_POST['user_company'] );
        $title = sanitize_text_field( $_POST['title'] );
        $about = wp_kses_post( wpautop( wptexturize( $_POST['bio'] ) ) );
        $userphone = sanitize_text_field( $_POST['userphone'] );
        $fax_number = sanitize_text_field( $_POST['fax_number'] );
        $service_areas = sanitize_text_field( $_POST['service_areas'] );
        $specialties = sanitize_text_field( $_POST['specialties'] );
        $usermobile = sanitize_text_field( $_POST['usermobile'] );
        $whatsapp = sanitize_text_field( $_POST['whatsapp'] );
        $line_id = sanitize_text_field( $_POST['line_id'] );
        $telegram = sanitize_text_field( $_POST['telegram'] );
        $userskype = sanitize_text_field( $_POST['userskype'] );
        $facebook = sanitize_text_field( $_POST['facebook'] );
        $twitter = sanitize_text_field( $_POST['twitter'] );
        $linkedin = sanitize_text_field( $_POST['linkedin'] );
        $instagram = sanitize_text_field( $_POST['instagram'] );
        $pinterest = sanitize_text_field( $_POST['pinterest'] );
        $youtube = sanitize_text_field( $_POST['youtube'] );
        $tiktok = sanitize_text_field( $_POST['tiktok'] );
        $zillow = sanitize_text_field( $_POST['zillow'] );
        $realtor_com = sanitize_text_field( $_POST['realtor_com'] );
        $vimeo = sanitize_text_field( $_POST['vimeo'] );
        $googleplus = sanitize_text_field( $_POST['googleplus'] );
        $website = sanitize_text_field( $_POST['website'] );
        $license = sanitize_text_field( $_POST['license'] );
        $tax_number = sanitize_text_field( $_POST['tax_number'] );
        $user_address = sanitize_text_field( $_POST['user_address'] );
        $user_location = sanitize_text_field( $_POST['user_location'] );
        $latitude = sanitize_text_field( $_POST['latitude'] );
        $longitude = sanitize_text_field( $_POST['longitude'] );
        $useremail = sanitize_email( $_POST['useremail'] );

        $developer_id = get_user_meta( $userID, 'fave_author_agent_id', true );

        // UPDATE DEVELOPER - PROFILE
        if (in_array('houzez_developer', (array)$current_user->roles)) {
            houzez_update_user_developer ( $developer_id, $firstname, $lastname, $title, $about, $userphone, $usermobile, $whatsapp, $userskype, $facebook, $twitter, $linkedin, $instagram, $pinterest, $youtube, $vimeo, $googleplus, $profile_pic, $profile_pic_id, $website, $useremail, $license, $tax_number, $fax_number, $userlangs, $user_address, $user_company, $service_areas, $specialties, $tiktok, $telegram, $line_id, $zillow, $realtor_com );
        }

    }

}

/* ------------------------------------------------------------------------------
* Update agent user
/------------------------------------------------------------------------------ */
if( !function_exists('houzez_update_user_developer') ) {
    function houzez_update_user_developer ( $developer_id, $firstname, $lastname, $title, $about, $userphone, $usermobile, $whatsapp, $userskype, $facebook, $twitter, $linkedin, $instagram, $pinterest, $youtube, $vimeo, $googleplus, $profile_pic, $profile_pic_id, $website, $useremail, $license, $tax_number, $fax_number, $userlangs, $user_address, $user_company, $service_areas, $specialties, $tiktok, $telegram, $line_id, $zillow, $realtor_com ) {

        if( !empty( $firstname ) || !empty( $lastname ) ) {
            $agr = array(
                'ID' => $developer_id,
                'post_title' => $firstname.' '.$lastname,
                'post_content' => $about
            );
            $post_id = wp_update_post($agr);
        } else {
            $agr = array(
                'ID' => $developer_id,
                'post_content' => $about
            );
            $post_id = wp_update_post($agr);
        }

        
        update_post_meta( $developer_id, 'fave_developer_license', $license );
        update_post_meta( $developer_id, 'fave_developer_tax_no', $tax_number );
        update_post_meta( $developer_id, 'fave_developer_facebook', $facebook );
        update_post_meta( $developer_id, 'fave_developer_linkedin', $linkedin );
        update_post_meta( $developer_id, 'fave_developer_twitter', $twitter );
        update_post_meta( $developer_id, 'fave_developer_pinterest', $pinterest );
        update_post_meta( $developer_id, 'fave_developer_instagram', $instagram );
        update_post_meta( $developer_id, 'fave_developer_youtube', $youtube );
        update_post_meta( $developer_id, 'fave_developer_tiktok', $tiktok );
        update_post_meta( $developer_id, 'fave_developer_telegram', $telegram );
        update_post_meta( $developer_id, 'fave_developer_vimeo', $vimeo );
        update_post_meta( $developer_id, 'fave_developer_zillow', $zillow );
        update_post_meta( $developer_id, 'fave_developer_realtor_com', $realtor_com );
        update_post_meta( $developer_id, 'fave_developer_website', $website );
        update_post_meta( $developer_id, 'fave_developer_googleplus', $googleplus );
        update_post_meta( $developer_id, 'fave_developer_office_num', $userphone );
        update_post_meta( $developer_id, 'fave_developer_fax', $fax_number );
        update_post_meta( $developer_id, 'fave_developer_mobile', $usermobile );
        update_post_meta( $developer_id, 'fave_developer_whatsapp', $whatsapp );
        update_post_meta( $developer_id, 'fave_developer_line_id', $line_id );
        update_post_meta( $developer_id, 'fave_developer_skype', $userskype );
        update_post_meta( $developer_id, 'fave_developer_position', $title );
        update_post_meta( $developer_id, 'fave_developer_des', $about );
        update_post_meta( $developer_id, 'fave_developer_email', $useremail );
        update_post_meta( $developer_id, 'fave_developer_language', $userlangs );
        update_post_meta( $developer_id, 'fave_developer_address', $user_address );
        update_post_meta( $developer_id, 'fave_developer_company', $user_company );
        update_post_meta( $developer_id, 'fave_developer_service_area', $service_areas );
        update_post_meta( $developer_id, 'fave_developer_specialties', $specialties );
        update_post_meta( $developer_id, '_thumbnail_id', $profile_pic_id );

    }
}


if ( !function_exists( 'houzez_change_user_role' ) ) :
    function houzez_change_user_role()
    {

        check_ajax_referer( 'houzez_role_pass_ajax_nonce', 'houzez-role-security-pass' );

        $ajax_response = array();
        $user_roles = Array ( 'houzez_agency', 'houzez_agent','houzez_developer', 'houzez_buyer', 'houzez_seller', 'houzez_owner', 'houzez_manager' );

        if ( is_user_logged_in() && isset( $_POST['role'] ) && in_array( $_POST['role'], $user_roles ) ) {

            global $current_user;
            wp_get_current_user();
            $userID = $current_user->ID;
            $username = $current_user->user_login;
            $user_email = $current_user->user_email;
            $role = $_POST['role'];
            $current_author_meta = get_user_meta( $userID );
            $authorAgentID = $current_author_meta['fave_author_agent_id'][0];
            $authorAgencyID = $current_author_meta['fave_author_agency_id'][0];

            $user_as_agent = houzez_option('user_as_agent');

            $user_id = wp_update_user( Array ( 'ID' => $userID, 'role' => $role ) );

            if ( is_wp_error( $user_id ) ) {

                $ajax_response = array('success' => false, 'reason' => esc_html__('Role not updated!', 'houzez'));

            } else {

                $ajax_response = array('success' => true, 'reason' => esc_html__('Role updated!', 'houzez'));

                if( $user_as_agent == "yes" && ($role == 'houzez_agent' || $role == 'houzez_developer' || $role == 'houzez_agency') ) {
                    if( $role == 'houzez_agency' ) {
                        wp_delete_post( $authorAgentID, true );
                        houzez_register_as_agency($username, $user_email, $userID);
                        update_user_meta( $userID, 'fave_author_agent_id', '');
                        
                    }elseif( $role == 'houzez_agent' ) {
                        wp_delete_post( $authorAgencyID, true );
                        houzez_register_as_agent($username, $user_email, $userID);
                        update_user_meta( $userID, 'fave_author_agency_id', '');
                    }elseif( $role == 'houzez_developer' ) {
                        wp_delete_post( $authorAgencyID, true );
                        houzez_register_as_developer($username, $user_email, $userID);
                        update_user_meta( $userID, 'fave_author_agency_id', '');
                    }
                } else {
                    wp_delete_post( $authorAgentID, true );
                    wp_delete_post( $authorAgencyID, true );
                    update_user_meta( $userID, 'fave_author_agent_id', '');
                    update_user_meta( $userID, 'fave_author_agency_id', '');
                }
            }

        } else {

            $ajax_response = array('success' => false, 'reason' => esc_html__('Role not updated!', 'houzez'));

        }

        echo json_encode($ajax_response);

        wp_die();
    }
endif;

/*-----------------------------------------------------------------------------------*/
// Save Front-end user as agent
/*-----------------------------------------------------------------------------------*/

if( !function_exists('houzez_register_as_developer') ) {

    function houzez_register_as_developer( $username, $email, $user_id, $mobile_num = null, $image_url = null ) {

        // Create post object
        $args = array(
            'post_title'    => $username,
            'post_type' => 'houzez_developer',
            'post_status'   => 'publish'
        );

        // Insert the post into the database
        $post_id =  wp_insert_post( $args );
        update_post_meta( $post_id, 'houzez_user_meta_id', $user_id);  // used when agent custom post type updated
        update_user_meta( $user_id, 'fave_author_agent_id', $post_id);
        update_post_meta( $post_id, 'fave_developer_email', $email);
        update_post_meta( $post_id, 'fave_developer_mobile', $mobile_num);

        if( houzez_option('realtor_visible', 0) ) {
            update_post_meta( $post_id, 'fave_developer_visible', 1);
        }

        if( !empty($image_url) ) {
            houzez_set_image_from_url($post_id, $image_url);
        }

    }
}

if( !function_exists('houzez_save_user_photo')) {
    function houzez_save_user_photo($user_id, $pic_id, $thumbnail_url) {
        
        update_user_meta( $user_id, 'fave_author_picture_id', $pic_id );
        update_user_meta( $user_id, 'fave_author_custom_picture', $thumbnail_url[0] );

        $user_agent_id = get_the_author_meta('fave_author_agent_id', $user_id);
        $user_agency_id = get_the_author_meta('fave_author_agency_id', $user_id);
        
        if( !empty($user_agent_id) && houzez_is_agent($user_id) ) {
            update_post_meta( $user_agent_id, '_thumbnail_id', $pic_id );
        }

        if( !empty($user_agent_id) && houzez_is_developer($user_id) ) {
            update_post_meta( $user_agent_id, '_thumbnail_id', $pic_id );
        }
        
        if( !empty($user_agency_id) && houzez_is_agency($user_id) ) {
            update_post_meta( $user_agency_id, '_thumbnail_id', $pic_id );
        }

    }
}

if( !function_exists('houzez_is_developer') ) {
    function houzez_is_developer( $user_id = null ) {
        // If a user ID is provided, get the user data for the given user ID; otherwise, get the current user.
        if (!empty($user_id)) {
            $user_data = get_userdata($user_id);
        } else {
            $user_data = wp_get_current_user();
        }

        // Check if the user data was successfully retrieved and the user has the 'houzez_agent' role.
        if ($user_data) {
            return in_array('houzez_developer', (array)$user_data->roles);
        }

        return false;
    }
}

/*-----------------------------------------------------------------------------------*/
// Submit Property filter
/*-----------------------------------------------------------------------------------*/
add_filter('houzez_after_property_submit', 'houzez_submit_listing_developer');
add_filter('houzez_after_property_update', 'houzez_submit_listing_developer');

function houzez_submit_listing_developer($prop_id) {

    if( $prop_id > 0 && isset( $_POST['fave_agent_display_option'] ) ) {

        $prop_agent_display_option = sanitize_text_field( $_POST['fave_agent_display_option'] );

        if( $prop_agent_display_option == 'developer_info' ) {

            $prop_developer = isset( $_POST['fave_developers'] ) ? $_POST['fave_developers'] : '';

            if(is_array($prop_developer)) {
                delete_post_meta( $prop_id, 'fave_developers' );
                foreach ($prop_developer as $developer) {
                    intval($developer);
                    add_post_meta($prop_id, 'fave_developers', intval($developer) );
                }
            }
        
            update_post_meta( $prop_id, 'fave_agent_display_option', $prop_agent_display_option );

        }

        //Construction Status
        if(isset($_POST['prop_construction_status'])) {
            $prop_construction_status = sanitize_text_field($_POST['prop_construction_status']);
            update_post_meta( $prop_id, 'fave_prop_construction_status', $prop_construction_status );
        }

        //Number of buildings
        if(isset($_POST['prop_number_of_buildings'])) {
            $prop_number_of_buildings = sanitize_text_field($_POST['prop_number_of_buildings']);
            update_post_meta( $prop_id, 'fave_prop_number_of_buildings', $prop_number_of_buildings );
        }

        //Handover Date
        if(isset($_POST['prop_handover_q'])) {
            $prop_handover_q = sanitize_text_field($_POST['prop_handover_q']);
            update_post_meta( $prop_id, 'fave_prop_handover_q', $prop_handover_q );
        }
        if(isset($_POST['prop_handover_y'])) {
            $prop_handover_y = sanitize_text_field($_POST['prop_handover_y']);
            update_post_meta( $prop_id, 'fave_prop_handover_y', $prop_handover_y );
        }
        //Payment Plan
        if(isset($_POST['prop_payment_plan_down'])) {
            $prop_payment_plan_down = sanitize_text_field($_POST['prop_payment_plan_down']);
            update_post_meta( $prop_id, 'fave_prop_payment_plan_down', $prop_payment_plan_down );
        }
        if(isset($_POST['prop_payment_plan_during_construction'])) {
            $prop_payment_plan_during_construction = sanitize_text_field($_POST['prop_payment_plan_during_construction']);
            update_post_meta( $prop_id, 'fave_prop_payment_plan_during_construction', $prop_payment_plan_during_construction );
        }
        if(isset($_POST['prop_payment_plan_on_handover'])) {
            $prop_payment_plan_on_handover = sanitize_text_field($_POST['prop_payment_plan_on_handover']);
            update_post_meta( $prop_id, 'fave_prop_payment_plan_on_handover', $prop_payment_plan_on_handover );
        }

    }
}

// UPDATE AGENCY AND AGENT - Transfer Listing Credit FROM AGENCY TO AGENT
add_action( 'wp_ajax_houzez_ajax_transfer_listing_credit_from_agency_to_agent', 'houzez_ajax_transfer_listing_credit_from_agency_to_agent' );

if( !function_exists('houzez_ajax_transfer_listing_credit_from_agency_to_agent') ){

    function houzez_ajax_transfer_listing_credit_from_agency_to_agent() {
        $user_id = sanitize_text_field( $_POST['user_id'] );
        $agency_id = sanitize_text_field( $_POST['agency_id'] );
        $package_listings_agency = sanitize_text_field( $_POST['package_listings_agency'] );
        $package_featured_listings_agency = sanitize_text_field( $_POST['package_featured_listings_agency'] );
        $package_id = sanitize_text_field( $_POST['package_id'] );
        $package_activation = sanitize_text_field( $_POST['package_activation'] );
        $package_listings = sanitize_text_field( $_POST['package_listings'] );
        $package_featured_listings = sanitize_text_field( $_POST['package_featured_listings'] );
        $package_listings_agent = sanitize_text_field( $_POST['package_listings_agent'] );
        $package_featured_listings_agent = sanitize_text_field( $_POST['package_featured_listings_agent'] );

        $package_listings_agency -= (int)$package_listings - (int)$package_listings_agent;
        $package_featured_listings_agency -= (int)$package_featured_listings - (int)$package_featured_listings_agent;

        if( $package_listings_agency < 0 || $package_featured_listings_agency < 0 || $package_listings < 0 || $package_featured_listings < 0 ){

            $ajax_response = array('success' => false, 'msg' => esc_html__('Error', 'houzez'));

            echo json_encode($ajax_response);

            wp_die();
        }

        update_user_meta( $agency_id, 'package_listings', $package_listings_agency );
        update_user_meta( $agency_id, 'package_featured_listings', $package_featured_listings_agency );

        update_user_meta( $user_id, 'package_listings', $package_listings );
        update_user_meta( $user_id, 'package_featured_listings', $package_featured_listings );
        update_user_meta( $user_id, 'package_id', $package_id );
        update_user_meta( $user_id, 'package_activation', $package_activation );

        $ajax_response = array('success' => true, 'msg' => esc_html__('Transfer Listing Credit Updated', 'houzez'));

        echo json_encode($ajax_response);

        wp_die();
    }
}

if( !function_exists('houzez_get_user_current_package') ) {
    function houzez_get_user_current_package( $user_id ) {

        $remaining_listings = houzez_get_remaining_listings( $user_id );
        $pack_featured_remaining_listings = houzez_get_featured_remaining_listings( $user_id );
        $package_id = houzez_get_user_package_id( $user_id );
        $packages_page_link = houzez_get_template_link('template/template-packages.php');

        if( $remaining_listings == -1 ) {
            $remaining_listings = esc_html__('Unlimited', 'houzez');
        }

        if( !empty( $package_id ) ) {

            $seconds = 0;
            $pack_title = get_the_title( $package_id );
            $pack_listings = get_post_meta( $package_id, 'fave_package_listings', true );
            $pack_unmilited_listings = get_post_meta( $package_id, 'fave_unlimited_listings', true );
            $pack_featured_listings = get_post_meta( $package_id, 'fave_package_featured_listings', true );
            $pack_billing_period = get_post_meta( $package_id, 'fave_billing_time_unit', true );
            $pack_billing_frequency = get_post_meta( $package_id, 'fave_billing_unit', true );
            $pack_date =  get_user_meta( $user_id, 'package_activation',true );

            if( $pack_billing_period == 'Day')
                $pack_billing_period = 'days';
            elseif( $pack_billing_period == 'Week')
                $pack_billing_period = 'weeks';
            elseif( $pack_billing_period == 'Month')
                $pack_billing_period = 'months';
            elseif( $pack_billing_period == 'Year')
                $pack_billing_period = 'years';

            $expired_date = strtotime($pack_date. ' + '.$pack_billing_frequency.' '.$pack_billing_period);
            $expired_date = date_i18n( get_option('date_format').' '.get_option('time_format'),  $expired_date );
            
            if(!houzez_is_agent($user_id)) {
                echo '<li>'.esc_html__( 'Your Current Package', 'houzez' ).'<strong>'.esc_attr( $pack_title ).'</strong></li>';
            }
            if( $pack_unmilited_listings == 1 ) {
                echo '<li>'.esc_html__('Listings Included: ','houzez').'<strong>'.esc_html__('unlimited listings ','houzez').'</strong></li>';
                echo '<li>'.esc_html__('Listings Remaining: ','houzez').'<strong>'.esc_html__('unlimited listings ','houzez').'</strong></li>';
            } else {

                echo '<li>'.esc_html__('Listings Included: ','houzez').'<strong>'.esc_attr( $pack_listings ).'</strong></li>';

                echo '<li>'.esc_html__('Listings Remaining: ','houzez').'<strong>'.esc_attr( $remaining_listings ).'</strong></li>';
            }

            echo '<li>'.esc_html__('Featured Included: ','houzez').'<strong>'.esc_attr( $pack_featured_listings ).'</strong></li>';

            echo '<li>'.esc_html__('Featured Remaining: ','houzez').'<strong>'.esc_attr( $pack_featured_remaining_listings ).'</strong></li>';
            echo '<li>'.esc_html__('Ends On','houzez').'<strong>';
            echo ' '.esc_attr( $expired_date );
            echo '</strong></li>';

        }
    }
}

if ( !function_exists( 'houzez_get_agent_info_top' ) ) {
    function houzez_get_agent_info_top($args, $type, $is_single = true)
    {
        $view_listing = houzez_option('agent_view_listing');
        $agent_phone_num = houzez_option('agent_phone_num');

        if( empty($args['agent_name']) ) {
            return '';
        }

        if ($type == 'for_grid_list') {
            return '<a href="' . $args['link'] . '">' . $args['agent_name'] . '</a> ';

        } elseif ($type == 'agent_form') {
            $output = '';

            $output .= '<div class="agent-details">';
                $output .= '<div class="d-flex align-items-center">';
                    
                    $output .= '<div class="agent-image">';
                        
                        if ( $is_single == false ) {
                            $output .= '<input type="checkbox" class="houzez-hidden" checked="checked" class="multiple-agent-check" name="target_email[]" value="' . $args['agent_email'] . '" >';
                        }

                        $output .= '<img class="rounded" src="' . $args['picture'] . '" alt="' . $args['agent_name'] . '" width="70" height="70">';

                    $output .= '</div>';

                    $output .= '<ul class="agent-information list-unstyled">';

                        if (!empty($args['agent_company'])) {
                            $output .= '<li class="agent-name">';
                                $output .= '<i class="houzez-icon icon-single-neutral mr-1"></i> '.$args['agent_company'];
                            $output .= '</li>';
                        }
                        else if (!empty($args['agent_name'])) {
                            $output .= '<li class="agent-name">';
                                $output .= '<i class="houzez-icon icon-single-neutral mr-1"></i> '.$args['agent_name'];
                            $output .= '</li>';
                        }
                        
                        if ( $is_single == false && !empty($args['agent_mobile'])) {
                            $output .= '<li class="agent-phone agent-phone-hidden">';
                                $output .= '<i class="houzez-icon icon-phone mr-1"></i> ' . esc_attr($args['agent_mobile']);
                            $output .= '</li>';
                        }

                        
                        if($view_listing != 0) {
                            $output .= '<li class="agent-link">';
                                $output .= '<a href="' . $args['link'] . '">' . houzez_option('spl_con_view_listings', 'View listings') . '</a>';
                            $output .= '</li>';
                        }


                    $output .= '</ul>';
                $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }
}


if ( !function_exists( 'houzez_after_search__get_property_type_list' ) ) {
    function houzez_after_search__get_property_type_list($search_qry)
    {
        $output = "";
        $terms = get_terms('property_type');

        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ( !empty( $terms ) && !is_wp_error( $terms ) ){
            $output .= "<ul>";
            $list_inc = 0;
            $className = "";
            foreach ( $terms as $term ) {
                $search_query_property_type = $search_qry;
                $search_query_property_type['posts_per_page'] = -1;

                $search_query_property_type['tax_query'][] = array(
                    'taxonomy' => 'property_type',
                    'field' => 'id',
                    'terms' => $term->term_id,
                );
                $search_query_property_type_final = new WP_Query( $search_query_property_type );

                if( $search_query_property_type_final->found_posts > 0 ){
                    $list_inc++;
                    if($list_inc > 2){
                        $className = "moreType";
                    }
                    $output .= '<li class="'.$className.'"><a href="/'.change_url_parameter($actual_link, "type", array($term->slug)).'">' . $term->name . " <span>(" . $search_query_property_type_final->found_posts . ")</span></a></li>";
                }

            }
            $output .= "</ul>";
        }

        return $output;
    }
    add_filter('houzez_after_search__get_property_type_list', 'houzez_after_search__get_property_type_list');
}

function change_url_parameter($url,$parameterName,$parameterValue) {
    $url=parse_url($url);
    parse_str($url["query"],$parameters);
    unset($parameters[$parameterName]);
    $parameters[$parameterName]=$parameterValue;
    return  sprintf("%s://%s%s?%s", 
        $url["scheme"],
        $url["host"],
        $url["path"],
        http_build_query($parameters));
}

/*-----------------------------------------------------------------------------------*/
// get taxonomies with with id value
/*-----------------------------------------------------------------------------------*/
if(!function_exists('houzez_get_taxonomies_with_id_value')){
    function houzez_get_taxonomies_with_id_value($taxonomy, $parent_taxonomy, $taxonomy_id, $prefix = " " ){

        if (!empty($parent_taxonomy)) {
            foreach ($parent_taxonomy as $term) {
                if ($taxonomy_id != $term->term_id) {
                    
                    if(  houzez_is_developer() && $term->name == "New projects" ){
                        echo '<option value="' . $term->term_id . '" selected="selected">' . $prefix . $term->name . '</option>';
                    }
                    else{
                        echo '<option value="' . $term->term_id . '">' . $prefix . $term->name . '</option>';
                    }
                } else {
                    echo '<option value="' . $term->term_id . '" selected="selected">' . $prefix . $term->name . '</option>';
                }
                $get_child_terms = get_terms($taxonomy, array(
                    'hide_empty' => false,
                    'parent' => $term->term_id
                ));

                if (!empty($get_child_terms)) {
                    houzez_get_taxonomies_with_id_value( $taxonomy, $get_child_terms, $taxonomy_id, "- ".$prefix );
                }
            }
        }
    }
}
?>