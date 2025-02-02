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

    if( $prop_id > 0 ) {

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
        $package_reload_agency = sanitize_text_field( $_POST['package_reload_agency'] );
        $package_id = sanitize_text_field( $_POST['package_id'] );
        $package_activation = sanitize_text_field( $_POST['package_activation'] );
        $package_listings = sanitize_text_field( $_POST['package_listings'] );
        $package_featured_listings = sanitize_text_field( $_POST['package_featured_listings'] );
        $package_reload = sanitize_text_field( $_POST['package_reload'] );
        $package_listings_agent = sanitize_text_field( $_POST['package_listings_agent'] );
        $package_featured_listings_agent = sanitize_text_field( $_POST['package_featured_listings_agent'] );
        $package_reload_agent = sanitize_text_field( $_POST['package_reload_agent'] );

        $package_listings_type = sanitize_text_field( $_POST['package_listings_type'] );
        $package_featured_listings_type = sanitize_text_field( $_POST['package_featured_listings_type'] );
        $package_reload_type = sanitize_text_field( $_POST['package_reload_type'] );

        $transfer_success = 0;

        if( !empty($package_listings_type) && !empty($package_listings) && (int)$package_listings > 0 ){
            if( ($package_listings_type == "Add") && ((int)$package_listings <= $package_listings_agency) ){
                update_user_meta( $agency_id, 'package_listings', (int)$package_listings_agency - (int)$package_listings );
                update_user_meta( $user_id, 'package_listings', (int)$package_listings_agent + (int)$package_listings );
                $transfer_success = 1;
            }
            if( ($package_listings_type == "Subtract") && ((int)$package_listings <= $package_listings_agent) ){
                update_user_meta( $agency_id, 'package_listings', (int)$package_listings_agency + (int)$package_listings );
                update_user_meta( $user_id, 'package_listings', (int)$package_listings_agent - (int)$package_listings );
                $transfer_success = 1;
            }
        }

        if( !empty($package_featured_listings_type) && !empty($package_featured_listings) && (int)$package_featured_listings > 0 ){
            if( ($package_featured_listings_type == "Add") && ((int)$package_featured_listings <= $package_featured_listings_agency) ){
                update_user_meta( $agency_id, 'package_featured_listings', (int)$package_featured_listings_agency - (int)$package_featured_listings );
                update_user_meta( $user_id, 'package_featured_listings', (int)$package_featured_listings_agent + (int)$package_featured_listings );
                $transfer_success = 1;
            }
            if( ($package_featured_listings_type == "Subtract") && ((int)$package_featured_listings <= $package_featured_listings_agent) ){
                update_user_meta( $agency_id, 'package_featured_listings', (int)$package_featured_listings_agency + (int)$package_featured_listings );
                update_user_meta( $user_id, 'package_featured_listings', (int)$package_featured_listings_agent - (int)$package_featured_listings );
                $transfer_success = 1;
            }
        }

        if( !empty($package_reload_type) && !empty($package_reload) && (int)$package_reload > 0 ){
            if( ($package_reload_type == "Add") && ((int)$package_reload <= $package_reload_agency) ){
                update_user_meta( $agency_id, 'package_reloads', (int)$package_reload_agency - (int)$package_reload );
                update_user_meta( $user_id, 'package_reloads', (int)$package_reload_agent + (int)$package_reload );
                $transfer_success = 1;
            }
            if( ($package_reload_type == "Subtract") && ((int)$package_reload <= $package_reload_agent) ){
                update_user_meta( $agency_id, 'package_reloads', (int)$package_reload_agency + (int)$package_reload );
                update_user_meta( $user_id, 'package_reloads', (int)$package_reload_agent - (int)$package_reload );
                $transfer_success = 1;
            }
        }

        if( $transfer_success == 0 ){

            $ajax_response = array('success' => false, 'msg' => esc_html__('You don\'t have sufficient credits.', 'houzez'));

            echo json_encode($ajax_response);

            wp_die();
        }


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
        $pack_reloads_remaining = get_the_author_meta( 'package_reloads' , $user_id );
        $pack_impressions_remaining = get_the_author_meta( 'package_impressions' , $user_id );
        $ads_package_id = get_the_author_meta( 'ads_package_id' , $user_id );

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
            $pack_reloads = get_post_meta( $package_id, 'fave_package_reloads', true );
            $transfer_credit = get_post_meta( $package_id, 'fave_transfer_credit', true );
            $account_manager = get_post_meta( $package_id, 'fave_account_manager', true );
            $add_floor_plans = get_post_meta( $package_id, 'fave_add_floor_plans', true );
            $add_3d_view = get_post_meta( $package_id, 'fave_add_3d_view', true );

            $package_reloads_included = get_user_meta( $user_id, 'package_reloads_included', true );
            if( $package_reloads_included != "" ){
                $pack_reloads = (int)$pack_reloads + (int)$package_reloads_included;
            }

            $pack_impressions = "";
            // if( !empty( $ads_package_id ) ) {
            //     $pack_impressions = get_post_meta( $ads_package_id, 'fave_package_impressions', true );
            // }
            $package_impressions_included = get_user_meta( $user_id, 'package_impressions_included', true );
            if( $package_impressions_included != "" ){
                $pack_impressions = (int)$package_impressions_included;
            }

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
            
            echo '<li>'.esc_html__('Reload Included: ','houzez').'<strong>'.esc_attr( $pack_reloads ).'</strong></li>';
            echo '<li>'.esc_html__('Reload Remaining: ','houzez').'<strong>'.esc_attr( $pack_reloads_remaining ).'</strong></li>';
            echo '<li>'.esc_html__('Impressions Included: ','houzez').'<strong>'.esc_attr( $pack_impressions ).'</strong></li>';
            echo '<li>'.esc_html__('Impressions Remaining: ','houzez').'<strong>'.esc_attr( $pack_impressions_remaining ).'</strong></li>';
            
            echo '<li>'.esc_html__('Transfer Credit: ','houzez').'<strong>'.esc_attr( !empty($transfer_credit) ? "Yes" : "No" ).'</strong></li>';
            echo '<li>'.esc_html__('Account Manager: ','houzez').'<strong>'.esc_attr( !empty($account_manager) ? "Yes" : "No" ).'</strong></li>';
            echo '<li>'.esc_html__('Add Floor Plans: ','houzez').'<strong>'.esc_attr( !empty($add_floor_plans) ? "Yes" : "No" ).'</strong></li>';
            echo '<li>'.esc_html__('Add 3D View: ','houzez').'<strong>'.esc_attr( !empty($add_3d_view) ? "Yes" : "No" ).'</strong></li>';


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
        //echo "<pre>";print_r($search_qry);exit;
        $taxonomy = "type";
        $search_qry_param = "type";
        $output = "";

        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url=parse_url($actual_link);
        if( !empty($url["query"]) ){
            parse_str($url["query"],$parameters);
            if( !empty($parameters["type"]) && !empty($parameters["type"][0])  ){
                if( !empty($parameters["areas"]) && !empty($parameters["areas"][0])  ){
                    return $output;
                }
                elseif( !empty($parameters["location"]) && !empty($parameters["location"][0])  ){
                    $taxonomy = "area";
                    $search_qry_param = "areas";
                    //echo "<pre>";print_r($parameters);exit;
                }
                else{
                    $taxonomy = "city";
                    $search_qry_param = "location";
                    //echo "<pre>";print_r($parameters);exit;
                }
                
            }
        }

        $terms = get_terms("property_$taxonomy");
        //echo "<pre>";print_r($terms);exit;
        if ( !empty( $terms ) && !is_wp_error( $terms ) ){
            $list_inc = 0;
            $className = "";
            foreach ( $terms as $term ) {
                $search_query_property_type = $search_qry;
                $search_query_property_type['posts_per_page'] = -1;

                $search_query_property_type['tax_query'][] = array(
                    'taxonomy' => "property_$taxonomy",
                    'field' => 'id',
                    'terms' => $term->term_id,
                );
                $search_query_property_type_final = new WP_Query( $search_query_property_type );

                if( $search_query_property_type_final->found_posts > 0 ){
                    $list_inc++;
                    if($list_inc > 3){
                        $className = "moreType";
                    }
                    $output .= '<li class="'.$className.'"><a href="'.change_url_parameter($actual_link, $search_qry_param, array($term->slug)).'">' . $term->name . " <span>(" . $search_query_property_type_final->found_posts . ")</span></a></li>";
                }

            }

            if( $output !== "" )$output = "<ul>$output</ul>";
            
            if($list_inc > 3){
                $output .= '<div class="page-type-show-more show-more bootstrap-select"><button class="dropdown-toggle" onclick="functionShowMore()">Show More</button></div>';
                $output .= '<div class="page-type-show-more show-less bootstrap-select"><button class="dropdown-toggle" onclick="functionShowLess()">Show Less</button></div>';
            }

        }

        return $output;
    }
    add_filter('houzez_after_search__get_property_type_list', 'houzez_after_search__get_property_type_list');
}

function change_url_parameter($url,$parameterName,$parameterValue) {
    $parameters = array();
    $url=parse_url($url);
    if( !empty($url["query"]) ){
        parse_str($url["query"],$parameters);
        unset($parameters[$parameterName]);
    }
    $parameters[$parameterName]=$parameterValue;
    return  sprintf("%s://%s%s?%s", 
        $url["scheme"],
        isset($url["port"]) ? $url["host"].':'.$url["port"] : $url["host"],
        $url["path"],
        http_build_query($parameters));
}

function clear_all_search_filter_without_one_filter_url($url,$parameterName) {
    $parameters = array();
    $new_parameters = array();
    $url=parse_url($url);
    if( !empty($url["query"]) ){
        parse_str($url["query"],$parameters);
        $new_parameters[$parameterName] = $parameters[$parameterName];
    }
    
    return  sprintf("%s://%s%s?%s", 
        $url["scheme"],
        isset($url["port"]) ? $url["host"].':'.$url["port"] : $url["host"],
        $url["path"],
        http_build_query($new_parameters));
}

function change_url_parameter_with_verified_first($url,$parameterName,$parameterValue) {
    $parameters = array();
    $url=parse_url($url);
    if( !empty($url["query"]) ){
        parse_str($url["query"],$parameters);
        unset($parameters[$parameterName]);
    }
    $parameters[$parameterName]=$parameterValue;
    if( isset($url["path"]) ){
        $url["path"] = "/search-results/";
    }
    return  sprintf("%s://%s%s?%s", 
        $url["scheme"],
        isset($url["port"]) ? $url["host"].':'.$url["port"] : $url["host"],
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

if ( !function_exists( 'houzez_get_agency_photo_url_by_agent_user_id' ) ) {
    function houzez_get_agency_photo_url_by_agent_user_id($agent_user_id)
    {
        $output = "";

        $user_agent = get_users(array(
            'meta_key' => 'fave_author_agent_id',
            'meta_value' => $agent_user_id
        ));
        if( !empty( $user_agent[0] )) {
            $agent_agency_id = get_user_meta( $user_agent[0]->ID, 'fave_agent_agency', true );

            $agency_photo_url = get_user_meta( $agent_agency_id, 'fave_author_custom_picture', true );
        }

        if ( isset($agency_photo_url) && !empty($agency_photo_url) ){
            $output = '<div class="list-item-agency-profile-pic"><img class="img-fluid" src="' . $agency_photo_url . '"></div>';
        }

        return $output;
    }
    add_filter('houzez_get_agency_photo_url_by_agent_user_id', 'houzez_get_agency_photo_url_by_agent_user_id');
}


function load_houzez_property_js_child() {
    if (houzez_is_dashboard() ) {
        wp_enqueue_script('houzez_property_child',  get_stylesheet_directory_uri().'/js/houzez_property_child.js', array('jquery'), '1.0.0', true);
    }
    if ( is_page_template('template/user_dashboard_advertise.php') ) {
        wp_enqueue_script('chart');
    }

    wp_enqueue_script('houzez_listing_child',  get_stylesheet_directory_uri().'/js/houzez_listing_child.js', array('jquery'), '1.0.0', true);

    // New Theme MEstate : Assets
    if (!houzez_is_dashboard() ) {
        wp_enqueue_script('houzez_mestate_nice',  get_stylesheet_directory_uri().'/assets/js/nice-select.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('houzez_mestate_meanmenu',  get_stylesheet_directory_uri().'/assets/js/meanmenu.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('houzez_mestate_swiper',  get_stylesheet_directory_uri().'/assets/js/swiper-bundle.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('houzez_mestate_price_range',  get_stylesheet_directory_uri().'/assets/js/price-range.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('houzez_mestate_main',  get_stylesheet_directory_uri().'/assets/js/main.js', array('jquery'), '1.0.0', true);

        // Enqueue CSS
        wp_enqueue_style('houzez_mestate_font-awesome-pro', get_stylesheet_directory_uri() . '/assets/css/font-awesome-pro.min.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_iconmoon', get_stylesheet_directory_uri() . '/assets/css/iconmoon.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_general-sanse', get_stylesheet_directory_uri() . '/assets/css/general-sanse.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_meanmenu', get_stylesheet_directory_uri() . '/assets/css/meanmenu.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_nice-select', get_stylesheet_directory_uri() . '/assets/css/nice-select.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_swiper-bundle', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_price-range', get_stylesheet_directory_uri() . '/assets/css/price-range.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_style', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), '1.0.0', 'all');
        wp_enqueue_style('houzez_mestate_responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array(), '1.0.0', 'all');
        
    }

}
add_action( 'wp_enqueue_scripts', 'load_houzez_property_js_child', 20 );

add_filter('houzez_before_update_property', 'houzez_submit_listing_attachment');

function houzez_submit_listing_attachment($new_property) {

    $prop_id = $new_property['ID'];
    $add_verification = $need_verification = 0;
    if(isset($_GET['add_verification']) && $_GET['add_verification'] == 1) {
        $add_verification = 1;
    }
    if(isset($_GET['need_verification']) && $_GET['need_verification'] == 1) {
        $need_verification = 1;
    }

    if( $prop_id > 0 && $add_verification == 1) {

        if( $need_verification == 1) {
            update_post_meta($prop_id, 'fave_verified_badge', 0);

            if (isset($_POST['verified_badge'])) {
                $verified_badge = $_POST['verified_badge'];
                if ($verified_badge == 'on') {
                    $verified_badge = 1;
                }
                update_post_meta($prop_id, 'fave_verified_badge', sanitize_text_field($verified_badge));

                if($verified_badge == 1){
                    update_post_meta($prop_id, 'fave_verification_status', 'approved');
                }
                else{
                    update_post_meta($prop_id, 'fave_verification_status', 'disapproved');
                }
            }
            else{
                update_post_meta($prop_id, 'fave_verification_status', 'disapproved');
            }

        }
        else{
            update_post_meta($prop_id, 'fave_verification_status', 'need');
        }
        
        delete_post_meta( $prop_id, 'fave_attachments_form_a' );

        if( isset( $_POST['propperty_attachment_form_a_ids'] ) ) {
            $property_attach_ids = array();
            foreach ($_POST['propperty_attachment_form_a_ids'] as $prop_atch_id ) {
                $property_attach_ids[] = intval( $prop_atch_id );
                add_post_meta($prop_id, 'fave_attachments_form_a', $prop_atch_id);
            }
        }

        delete_post_meta( $prop_id, 'fave_attachments_title_deed' );

        if( isset( $_POST['propperty_attachment_title_deed_ids'] ) ) {
            $property_attach_ids = array();
            foreach ($_POST['propperty_attachment_title_deed_ids'] as $prop_atch_id ) {
                $property_attach_ids[] = intval( $prop_atch_id );
                add_post_meta($prop_id, 'fave_attachments_title_deed', $prop_atch_id);
            }
        }

        delete_post_meta( $prop_id, 'fave_attachments_passport' );

        if( isset( $_POST['propperty_attachment_passport_ids'] ) ) {
            $property_attach_ids = array();
            foreach ($_POST['propperty_attachment_passport_ids'] as $prop_atch_id ) {
                $property_attach_ids[] = intval( $prop_atch_id );
                add_post_meta($prop_id, 'fave_attachments_passport', $prop_atch_id);
            }
        }

        update_post_meta($prop_id, 'fave_verified_check1', 0);

        if (isset($_POST['verified_check1'])) {
            $verified_check1 = $_POST['verified_check1'];
            if ($verified_check1 == 'on') {
                $verified_check1 = 1;
            }

            update_post_meta($prop_id, 'fave_verified_check1', sanitize_text_field($verified_check1));
        }

        update_post_meta($prop_id, 'fave_verified_check2', 0);

        if (isset($_POST['verified_check2'])) {
            $verified_check2 = $_POST['verified_check2'];
            if ($verified_check2 == 'on') {
                $verified_check2 = 1;
            }

            update_post_meta($prop_id, 'fave_verified_check2', sanitize_text_field($verified_check2));
        }

        update_post_meta($prop_id, 'fave_verified_check3', 0);

        if (isset($_POST['verified_check3'])) {
            $verified_check3 = $_POST['verified_check3'];
            if ($verified_check3 == 'on') {
                $verified_check3 = 1;
            }

            update_post_meta($prop_id, 'fave_verified_check3', sanitize_text_field($verified_check3));
        }

        update_post_meta($prop_id, 'fave_verified_check4', 0);

        if (isset($_POST['verified_check4'])) {
            $verified_check4 = $_POST['verified_check4'];
            if ($verified_check4 == 'on') {
                $verified_check4 = 1;
            }

            update_post_meta($prop_id, 'fave_verified_check4', sanitize_text_field($verified_check4));
        }

        update_post_meta($prop_id, 'fave_verified_check5', 0);

        if (isset($_POST['verified_check5'])) {
            $verified_check5 = $_POST['verified_check5'];
            if ($verified_check5 == 'on') {
                $verified_check5 = 1;
            }

            update_post_meta($prop_id, 'fave_verified_check5', sanitize_text_field($verified_check5));
        }

        // Verification Redirect
        if($need_verification == 1){
            wp_redirect("/my-properties?prop_status=need_verification&need_verification=1");
            exit;
        }
        if($add_verification == 1){
            wp_redirect("/my-properties?add_verification=1");
            exit;
        }

    }

    return $new_property;
}

/*-----------------------------------------------------------------------------------*/
// Remove property attachments Form
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_ajax_houzez_remove_property_documents_form', 'houzez_remove_property_documents_form' );
add_action( 'wp_ajax_nopriv_houzez_remove_property_documents_form', 'houzez_remove_property_documents_form' );
if( !function_exists('houzez_remove_property_documents') ) {
    function houzez_remove_property_documents_form() {

        $nonce = $_POST['removeNonce'];
        $remove_attachment = false;
        if (!wp_verify_nonce($nonce, 'verify_gallery_nonce')) {

            echo json_encode(array(
                'remove_attachment' => false,
                'reason' => esc_html__('Invalid Nonce', 'houzez')
            ));
            wp_die();
        }

        if (isset($_POST['thumb_id']) && isset($_POST['prop_id']) && isset($_POST['meta_name'])) {
            $thumb_id = intval($_POST['thumb_id']);
            $prop_id = intval($_POST['prop_id']);
            $meta_name = $_POST['meta_name'];

            $property_status = get_post_status ( $prop_id );

            if ( $thumb_id > 0 && $prop_id > 0 && $property_status != "draft" ) {
                delete_post_meta($prop_id, $meta_name, $thumb_id);
                $remove_attachment = wp_delete_attachment($thumb_id);
            } elseif ( $thumb_id > 0 && $prop_id > 0 && $property_status == "draft" ) {
                delete_post_meta($prop_id, $meta_name, $thumb_id);
                $remove_attachment = true;
            } elseif ($thumb_id > 0) {
                if( false == wp_delete_attachment( $thumb_id )) {
                    $remove_attachment = false;
                } else {
                    $remove_attachment = true;
                }
            }
        }

        echo json_encode(array(
            'remove_attachment' => $remove_attachment,
        ));
        wp_die();

    }
}

/*-----------------------------------------------------------------------------------*/
/*  Properties verified_badge sorting
/*-----------------------------------------------------------------------------------*/
if( !function_exists( 'houzez_prop_sort_by_verified_badge' ) ){

    function houzez_prop_sort_by_verified_badge( $query_args ) {
        $sort_by = '';
        if( isset( $_GET['sortby'] ) && $_GET['sortby'] != '' ) {
            $sort_by = $_GET['sortby'];
        }

        if ( $sort_by == 'verified_first' ) {
            $query_args['order'] = 'DESC';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['meta_type'] = 'NUMERIC';

            $query_args['meta_query'] = array(
            'relation' => 'OR',
                array(
                'key'=>'fave_verified_badge',
                'compare' => 'EXISTS'         
                ),
                array(
                    'key'=>'fave_verified_badge',
                    'compare' => 'NOT EXISTS'         
                )
                );
        }

        return $query_args;
    }
    add_filter('houzez_sort_properties', 'houzez_prop_sort_by_verified_badge');
}

if( ! function_exists('houzez_user_posts_count') ) {
    function houzez_user_posts_count( $post_status = 'any', $mine = false, $post_type = 'property' ) {
        $userID = get_current_user_id();

        // Common arguments for both queries
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => -1, // Set to -1 to fetch all records
            'post_status'    => $post_status,
            'fields'         => 'ids', // Fetch only the IDs for performance
        ];

        if( houzez_is_admin() || houzez_is_editor() ) {
            
            if( $mine ) {
                $args['author'] = $userID; 
            }

        } else if( houzez_is_agency() ) {
            
            if( $mine ) {
                $args['author'] = $userID; 
            } else {
                $agents = houzez_get_agency_agents($userID);
                if( $agents ) {
                    if (!in_array($userID, $agents)) {
                        $agents[] = $userID;
                    }
                    $args['author__in'] = $agents;
                } else {
                    $args['author'] = $userID;
                }
            }
        } else {
            $args['author'] = $userID; 
        }

        if( $post_status == "need_verification" ){
            $args['meta_key'] = 'fave_verification_status';
            $args['meta_value'] = 'need';
        }

        // Query for counting all records
        $query = new WP_Query($args);
        $total_records = $query->found_posts; // Total count of all records

        return $total_records;

    }
}

//Tracking visits data
add_action( 'wp_ajax_houzez_add_tracking_views', 'houzez_add_tracking_views' );
add_action( 'wp_ajax_nopriv_houzez_add_tracking_views', 'houzez_add_tracking_views' );
if( ! function_exists('houzez_add_tracking_views') ) {
    function houzez_add_tracking_views() { 

        if ( isset($_POST['type']) && isset($_POST['prop_id']) && !empty($_POST['prop_id']) ) {

            houzez_insert_tracking_views($_POST['type'], $_POST['prop_id']);

            echo json_encode(array(
                'success' => true,
            ));
            wp_die();

        }

        echo json_encode(array(
            'success' => false,
        ));
        wp_die();
        
    }
}

if( ! function_exists('houzez_insert_tracking_views') ) {
    function houzez_insert_tracking_views($type, $prop_id) { 
        global $wpdb;

        $user_id = get_current_user_id();

        $table_name = $wpdb->prefix . 'houzez_crm_viewed_listings_statistics'; 

        $data = array(
            'user_id'        => $user_id,
            'listing_id'     => $prop_id,
            'type'     => $type,
        );

        $format = array(
            '%d',
            '%d',
            '%s'
        );

        $wpdb->insert($table_name, $data, $format);
        
    }
}

if(!function_exists('houzez_views_percentage_ns')) {
    function houzez_views_percentage_ns($old_number, $new_number) {

        if( $old_number != 0 ) {
            $percent = (($new_number - $old_number) / $old_number * 100);
        } else {
            $percent = $new_number * 100;
        }
        
        $output = round($percent, 1).'%';

        return $output;
    }
}

if(!function_exists('houzez_views_user_stats')) {
    function houzez_views_user_stats($user_id, $activities_start_date, $activities_end_date, $listing_id = false) {

        //echo "{$user_id}, {$activities_start_date}, {$activities_end_date}";exit;

        if( houzez_is_admin() || houzez_is_editor() ) {
            $user_id = '';
        } else if( houzez_is_agency() ) {
            $agents = houzez_get_agency_agents($user_id);

            if( $agents ) {
                if (!in_array($user_id, $agents)) {
                    $agents[] = $user_id;
                }
                $user_id = $agents;
            } else {
                $user_id = $user_id;
            }

        } else {
            $user_id = $user_id;
        }

        $stats = array();
        $args = array('user_id' => $user_id, 'listing_id' => $listing_id, 'activities_start_date' => $activities_start_date, 'activities_end_date' => $activities_end_date);

        $stats['views'] = houzez_count_views($args);
        $stats['whatsapp'] = houzez_count_views_tracking($args, 'w');
        $stats['phone'] = houzez_count_views_tracking($args, 'c');
        $stats['message'] = houzez_count_messages($args);

        if( !empty($stats['views']) ){
            $stats['conversation'] = ($stats['whatsapp'] + $stats['phone'] + $stats['message']) / $stats['views'] * 100;
        }
        $stats['conversation'] = empty($stats['conversation']) ? 0 : number_format((float)$stats['conversation'], 2, '.', '');

        return $stats;
    }
}

if(!function_exists('houzez_count_messages')) {
    function houzez_count_messages( $args = array() ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'houzez_crm_activities';
        $query = array();

        $args = wp_parse_args( $args, [
            'user_id' => false,
            'activities_start_date' => false,
            'activities_end_date' => false,
            'listing_id' => false,
        ] );

        $query[] = "SELECT COUNT( {$table_name}.activity_id ) AS count";
        $query[] = "FROM {$table_name} WHERE {$table_name}.user_id IS NOT NULL";

        if (!empty($args['listing_id'])) {
            $query[] = sprintf( " AND {$table_name}.meta REGEXP '.*\"listing_id\";i:%d.*' ", intval( $args['listing_id'] ) );
        }
        else if (!empty($args['user_id'])) {
            if ( is_array( $args['user_id'] ) ) {
                $user_ids = implode( ',', array_map( 'intval', $args['user_id'] ) );
                $query[] = " AND {$table_name}.user_id IN ({$user_ids}) ";
            } else {
                $query[] = sprintf( " AND {$table_name}.user_id = %d ", intval( $args['user_id'] ) );
            }
        }
        if (!empty($args['activities_start_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time >= '%s' ",
                $args['activities_start_date']
            );
        }
        if (!empty($args['activities_end_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time <= '%s' ",
                $args['activities_end_date']
            );
        }

        $query = join( "\n", $query );

        $results = $wpdb->get_row( $query, OBJECT );

        return is_object( $results ) && ! empty( $results->count ) ? (int) $results->count : 0;
    }
}

if(!function_exists('houzez_count_views_tracking')) {
    function houzez_count_views_tracking( $args = array(), $type = false ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'houzez_crm_viewed_listings_statistics';
        $query = array();

        $args = wp_parse_args( $args, [
            'user_id' => false,
            'activities_start_date' => false,
            'activities_end_date' => false,
            'listing_id' => false,
        ] );

        $query[] = "SELECT COUNT( {$table_name}.id ) AS count";
        $query[] = "FROM {$table_name}";
        $query[] = "INNER JOIN {$wpdb->posts} ON ( {$wpdb->posts}.ID = {$table_name}.listing_id )";
        $query[] = "WHERE {$wpdb->posts}.post_status = 'publish'";

        if (!empty($args['listing_id'])) {
            $query[] = sprintf( " AND {$table_name}.listing_id = %d ", intval( $args['listing_id'] ) );
        }
        else if (!empty($args['user_id'])) {
            if ( is_array( $args['user_id'] ) ) {
                $user_ids = implode( ',', array_map( 'intval', $args['user_id'] ) );
                $query[] = " AND {$wpdb->posts}.post_author IN ({$user_ids}) ";
            } else {
                $query[] = sprintf( " AND {$wpdb->posts}.post_author = %d ", intval( $args['user_id'] ) );
            }
        }
        if (!empty($args['activities_start_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time >= '%s' ",
                $args['activities_start_date']
            );
        }
        if (!empty($args['activities_end_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time <= '%s' ",
                $args['activities_end_date']
            );
        }
        if (!empty($type)) {
            $query[] = sprintf(
                " AND {$table_name}.type = '%s' ",
                $type
            );
        }

        $query = join( "\n", $query );

        $results = $wpdb->get_row( $query, OBJECT );

        return is_object( $results ) && ! empty( $results->count ) ? (int) $results->count : 0;
    }
}

if(!function_exists('houzez_count_views')) {
    function houzez_count_views( $args = array() ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'favethemes_insights';
        $query = array();

        $args = wp_parse_args( $args, [
            'user_id' => false,
            'activities_start_date' => false,
            'activities_end_date' => false,
            'listing_id' => false,
        ] );

        $query[] = "SELECT COUNT( {$table_name}.id ) AS count";
        $query[] = "FROM {$table_name}";
        $query[] = "INNER JOIN {$wpdb->posts} ON ( {$wpdb->posts}.ID = {$table_name}.listing_id )";
        $query[] = "WHERE {$wpdb->posts}.post_status = 'publish'";

        if (!empty($args['listing_id'])) {
            $query[] = sprintf( " AND {$table_name}.listing_id = %d ", intval( $args['listing_id'] ) );
        }
        else if (!empty($args['user_id'])) {
            if ( is_array( $args['user_id'] ) ) {
                $user_ids = implode( ',', array_map( 'intval', $args['user_id'] ) );
                $query[] = " AND {$wpdb->posts}.post_author IN ({$user_ids}) ";
            } else {
                $query[] = sprintf( " AND {$wpdb->posts}.post_author = %d ", intval( $args['user_id'] ) );
            }
        }
        if (!empty($args['activities_start_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time >= '%s' ",
                $args['activities_start_date']
            );
        }
        if (!empty($args['activities_end_date'])) {
            $query[] = sprintf(
                " AND {$table_name}.time <= '%s' ",
                $args['activities_end_date']
            );
        }

        $query = join( "\n", $query );

        $results = $wpdb->get_row( $query, OBJECT );

        return is_object( $results ) && ! empty( $results->count ) ? (int) $results->count : 0;
    }
}

add_action( 'wp_ajax_houzez_property_actions_child', 'houzez_property_actions_reload_advertise' );

if( !function_exists('houzez_property_actions_reload_advertise') ){
    function  houzez_property_actions_reload_advertise(){

        $prop_id = intval( $_POST['propid'] );
        $type = $_POST['type'];
        
        if( $type == 'set_advertise' ) {
            update_post_meta( $prop_id, 'fave_advertise', 1 );
        } else if ( $type == 'remove_advertise' ) {
            update_post_meta( $prop_id, 'fave_advertise', 0 );

        } else if ( $type == 'reload' ) {
            $userID = get_current_user_id();

            $packageUserId = $userID;
            $agent_agency_id = houzez_get_agent_agency_id( $userID );
            if( $agent_agency_id ) {
                $packageUserId = $agent_agency_id;
            }

            $package_reloads = get_the_author_meta( 'package_reloads' , $packageUserId );

            if ($package_reloads > 0) {
                houzez_update_package_reloads($packageUserId);

                $time = current_time('mysql');
                $listing_data = array(
                    'ID' => $prop_id,
                    'post_date'     => $time,
                    'post_date_gmt' => get_gmt_from_date( $time )
                );
                wp_update_post($listing_data);
            }
            else{
                echo json_encode(array('success' => false, 'msg' => 'Expired'));
                wp_die();
            }


        }

        echo json_encode(array('success' => true, 'msg' => ''));
        wp_die();

    }
}

if( !function_exists('houzez_update_package_reloads') ) {
    function houzez_update_package_reloads($user_id) {
        $package_reloads = get_the_author_meta( 'package_reloads' , $user_id );

        if ( $package_reloads-1 >= 0 ) {
            update_user_meta( $user_id, 'package_reloads', $package_reloads - 1 );
        } else if( $package_reloads == 0 ) {
            update_user_meta( $user_id, 'package_reloads', 0 ) ;
        }
    }
}

add_filter( 'manage_houzez_packages_posts_columns', 'manage_houzez_packages_columns' );
add_action( 'manage_houzez_packages_posts_custom_column', 'manage_houzez_packages_posts_custom_column' );

if( !function_exists('manage_houzez_packages_columns') ) {

    function manage_houzez_packages_columns() {
        $fields = array(
            'cb' 				=> '<input type="checkbox" />',
            'title' 			=> esc_html__( 'Package Name', 'houzez' ),
            'fave_package_role' => esc_html__( 'Agency/Developer?', 'houzez' ),
            'fave_billing_time_unit' 			=> esc_html__( 'Billing Period', 'houzez' ),
            'fave_package_price' 			=> esc_html__( 'Package Price', 'houzez' ),
            
            'fave_package_listings' => esc_html__( 'Listings', 'houzez' ),
            'fave_package_featured_listings' => esc_html__( 'Featured', 'houzez' ),
            'fave_package_reloads' => esc_html__( 'Reloads', 'houzez' ),
            'fave_transfer_credit' => esc_html__( 'Transfer Credit', 'houzez' ),
            'fave_account_manager' => esc_html__( 'Account Manager', 'houzez' ),
            'fave_add_floor_plans' => esc_html__( 'Add floor plans', 'houzez' ),
            'fave_add_3d_view' => esc_html__( 'Add 3d view', 'houzez' ),
            'date' 			=> esc_html__( 'Date', 'houzez' ),
        );

        return $fields;
    }
}
if( !function_exists('manage_houzez_packages_posts_custom_column') ) {
function manage_houzez_packages_posts_custom_column( $column ) {
    global $post;
    switch ( $column ) {
        case 'fave_package_listings':
            $fave_package_listings = get_post_meta( get_the_ID(),  'fave_package_listings', true );

            if ( ! empty( $fave_package_listings ) ) {
                echo esc_attr( $fave_package_listings );
            } else {
                echo '-';
            }
            break;
        case 'fave_package_featured_listings':
            $fave_package_featured_listings = get_post_meta( get_the_ID(),  'fave_package_featured_listings', true );

            if ( ! empty( $fave_package_featured_listings ) ) {
                echo esc_attr( $fave_package_featured_listings );
            } else {
                echo '-';
            }
            break;
        case 'fave_package_reloads':
            $fave_package_reloads = get_post_meta( get_the_ID(),  'fave_package_reloads', true );

            if ( ! empty( $fave_package_reloads ) ) {
                echo esc_attr( $fave_package_reloads );
            } else {
                echo '-';
            }
            break;

        case 'fave_transfer_credit':
            $fave_transfer_credit = get_post_meta( get_the_ID(),  'fave_transfer_credit', true );

            if ( ! empty( $fave_transfer_credit ) ) {
                echo "Yes";
            } else {
                echo 'No';
            }
            break;

        case 'fave_account_manager':
            $fave_account_manager = get_post_meta( get_the_ID(),  'fave_account_manager', true );

            if ( ! empty( $fave_account_manager ) ) {
                echo "Yes";
            } else {
                echo 'No';
            }
            break;
        case 'fave_add_floor_plans':
            $fave_add_floor_plans = get_post_meta( get_the_ID(),  'fave_add_floor_plans', true );

            if ( ! empty( $fave_add_floor_plans ) ) {
                echo "Yes";
            } else {
                echo 'No';
            }
            break;
        case 'fave_add_3d_view':
            $fave_add_3d_view = get_post_meta( get_the_ID(),  'fave_add_3d_view', true );

            if ( ! empty( $fave_add_3d_view ) ) {
                echo "Yes";
            } else {
                echo 'No';
            }
            break;

        case 'fave_package_role':
            $fave_package_role = get_post_meta( get_the_ID(),  'fave_package_role', true );

            if ( ! empty( $fave_package_role ) ) {
                echo esc_attr( $fave_package_role );
            } else {
                echo '-';
            }
            break;
        
        case 'fave_package_price':
            $fave_package_price = get_post_meta( get_the_ID(),  'fave_package_price', true );

            if ( ! empty( $fave_package_price ) ) {
                echo esc_attr( $fave_package_price );
            } else {
                echo '-';
            }
            break;

        case 'fave_billing_time_unit':
            $fave_billing_time_unit = get_post_meta( get_the_ID(),  'fave_billing_time_unit', true );
            $fave_billing_unit = get_post_meta( get_the_ID(),  'fave_billing_unit', true );

            if ( ! empty( $fave_billing_time_unit ) ) {
                echo esc_attr( $fave_billing_unit ) . " " . esc_attr( $fave_billing_time_unit );
            } else {
                echo '-';
            }
            break;

    }
}
}


if( !function_exists('houzez_packages_metaboxes') ) {

    function houzez_packages_metaboxes( $meta_boxes ) {
        $houzez_prefix = 'fave_';
        
        $meta_boxes[] = array(
            'title'  => esc_html__( 'Package Details', 'houzez' ),
            'post_types'  => array('houzez_packages'),
            'fields' => array(
                array(
                    'id' => "{$houzez_prefix}billing_time_unit",
                    'name' => esc_html__( 'Billing Period', 'houzez' ),
                    'type' => 'select',
                    'std' => "",
                    'options' => array( 'Day' => esc_html__('Day', 'houzez' ), 'Week' => esc_html__('Week', 'houzez' ), 'Month' => esc_html__('Month', 'houzez' ), 'Year' => esc_html__('Year', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}billing_unit",
                    'name' => esc_html__( 'Billing Frequency', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the frequency number', 'houzez' ),
                    'type' => 'text',
                    'std' => "0",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_role",
                    'name' => esc_html__( 'For Agency/Developer?', 'houzez' ),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array( 'agency' => esc_html__( 'Agency', 'houzez' ), 'developer' => esc_html__( 'Developer', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_listings",
                    'name' => esc_html__( 'How many listings are included?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of listings', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}unlimited_listings",
                    'name' => esc_html__( "Unlimited listings", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Unlimited listings', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_featured_listings",
                    'name' => esc_html__( 'How many Featured listings are included?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of listings', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_reloads",
                    'name' => esc_html__( 'How many Reloads are included?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of Reloads', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}transfer_credit",
                    'name' => esc_html__( "Transfer Credit", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Transfer Credit', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}account_manager",
                    'name' => esc_html__( "Account Manager", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Account Manager', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}add_floor_plans",
                    'name' => esc_html__( "Add Floor Plans", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Add Floor Plans', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}add_3d_view",
                    'name' => esc_html__( "Add 3D View", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Add 3D View', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_price",
                    'name' => esc_html__( 'Package Price ', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the price', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_stripe_id",
                    'name' => esc_html__( 'Package Stripe id (Example: gold_pack)', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_visible",
                    'name' => esc_html__( 'Is It Visible?', 'houzez' ),
                    'type' => 'select',
                    'std' => "",
                    'options' => array( 'yes' => esc_html__( 'Yes', 'houzez' ), 'no' => esc_html__( 'No', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}stripe_taxId",
                    'name' => esc_html__( 'Stripe Tax ID', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'placeholder' => esc_html__( 'Enter your stripe account tax id.', 'houzez' ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_tax",
                    'name' => esc_html__( 'Taxes', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the tax percentage (Only digits)', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}package_images",
                    'name' => esc_html__( 'How many images are included per listing?', 'houzez' ),
                    'placeholder' => esc_html__( 'Enter the number of images', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
                array(
                    'id' => "{$houzez_prefix}unlimited_images",
                    'name' => esc_html__( "Unlimited Images", 'houzez' ),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Same as defined in Theme Options', 'houzez'),
                    'std' => "",
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_popular",
                    'name' => esc_html__( 'Is Popular/Featured?', 'houzez' ),
                    'type' => 'select',
                    'std' => "no",
                    'options' => array( 'no' => esc_html__( 'No', 'houzez' ), 'yes' => esc_html__( 'Yes', 'houzez' ) ),
                    'columns' => 6,
                ),
                array(
                    'id' => "{$houzez_prefix}package_custom_link",
                    'name' => esc_html__( 'Custom Link', 'houzez' ),
                    'desc' => esc_html__('Leave empty if you do not want to custom link.', 'houzez'),
                    'placeholder' => esc_html__( 'Enter the custom link', 'houzez' ),
                    'type' => 'text',
                    'std' => "",
                    'columns' => 6,

                ),
            ),
        );
        

        return apply_filters('houzez_packages_meta', $meta_boxes);

    }

    add_filter( 'rwmb_meta_boxes', 'houzez_packages_metaboxes' );
}

/**
 * Show custom user profile fields
 * @param  obj $user The user object.
 * @return void
 */
if( !function_exists('houzez_custom_user_profile_fields')) {
    function houzez_custom_user_profile_fields($user) {

        if ( in_array('houzez_agent', (array)$user->roles ) ) {
            $information_title = esc_html__('Agent Profile Info', 'houzez');
            $title = esc_html__('Title/Position', 'houzez');

        } elseif ( in_array('houzez_agency', (array)$user->roles ) ) {
            $information_title = esc_html__('Agency Profile Info', 'houzez');
            $title = esc_html__('Agency Name', 'houzez');

        } elseif ( in_array('author', (array)$user->roles ) ) {
            $information_title = esc_html__('Author Profile Info', 'houzez');
            $title = esc_html__('Title/Position', 'houzez');
        } else {
            $information_title = esc_html__('Profile Info', 'houzez');
            $title = esc_html__('Title/Position', 'houzez');
        }
    ?>
        <h2><?php echo $information_title; ?></h2>
        <table class="form-table">
            <input type="hidden" name="houzez_role" value="<?php echo esc_attr($user->roles[0]); ?>">
            <tbody>
                <tr class="user-fave_author_title-wrap">
                    <th><label for="fave_author_title"><?php echo $title; ?></label></th>
                    <td><input type="text" name="fave_author_title" id="fave_author_title" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_title', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>

                <?php if ( !in_array('houzez_agency', (array)$user->roles ) ) { ?>
                <tr class="user-fave_author_company-wrap">
                    <th><label for="fave_author_company"><?php echo esc_html__('Company Name', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_company" id="fave_author_company" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_company', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <?php } ?>

                <tr class="user-fave_author_language-wrap">
                    <th><label for="fave_author_language"><?php echo esc_html__('Language', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_language" id="fave_author_language" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_language', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_license-wrap">
                    <th><label for="fave_author_license"><?php echo esc_html__('License', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_license" id="fave_author_license" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_license', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_tax_no-wrap">
                    <th><label for="fave_author_tax_no"><?php echo esc_html__('Tax Number', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_tax_no" id="fave_author_tax_no" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_tax_no', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_phone-wrap">
                    <th><label for="fave_author_phone"><?php echo esc_html__('Phone', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_phone" id="fave_author_phone" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_phone', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_fax-wrap">
                    <th><label for="fave_author_fax"><?php echo esc_html__('Fax Number', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_fax" id="fave_author_fax" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_fax', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_mobile-wrap">
                    <th><label for="fave_author_mobile"><?php echo esc_html__('Mobile', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_mobile" id="fave_author_mobile" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_mobile', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_whatsapp-wrap">
                    <th><label for="fave_author_whatsapp"><?php echo esc_html__('WhatsApp', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_whatsapp" id="fave_author_whatsapp" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_whatsapp', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_line_id-wrap">
                    <th><label for="fave_author_line_id"><?php echo esc_html__('Line ID', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_line_id" id="fave_author_line_id" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_line_id', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_telegram-wrap">
                    <th><label for="fave_author_telegram"><?php echo esc_html__('Telegram Username', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_telegram" id="fave_author_telegram" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_telegram', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_skype-wrap">
                    <th><label for="fave_author_skype"><?php echo esc_html__('Skype', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_skype" id="fave_author_skype" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_skype', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_custom_picture-wrap">
                    <th><label for="fave_author_custom_picture"><?php echo esc_html__('Picture Url', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_custom_picture" id="fave_author_custom_picture" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_custom_picture', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_agency_id-wrap">
                    <th><label for="fave_author_agency_id"><?php echo esc_html__('Agency ID', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_agency_id" id="fave_author_agency_id" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_agency_id', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_agent_id-wrap">
                    <th><label for="fave_author_agent_id"><?php echo esc_html__('User Agent ID', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_agent_id" id="fave_author_agent_id" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_agent_id', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_agent_id-wrap">
                    <th><label for="fave_author_agent_id"><?php echo esc_html__('Currency', 'houzez'); ?></label></th>
                    <td><input placeholder="<?php echo esc_html__('Enter currency shortcode', 'houzez'); ?>" type="text" name="fave_author_currency" id="fave_author_currency" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_currency', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>

                <tr class="user-fave_author_agent_id-wrap">
                    <th><label for="fave_author_agent_id"><?php echo esc_html__('Service Areas', 'houzez'); ?></label></th>
                    <td><input placeholder="<?php echo esc_html__('Enter your service areas', 'houzez'); ?>" type="text" name="fave_author_service_areas" id="fave_author_service_areas" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_service_areas', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>

                <tr class="user-fave_author_agent_id-wrap">
                    <th><label for="fave_author_agent_id"><?php echo esc_html__('Specialties', 'houzez'); ?></label></th>
                    <td><input placeholder="<?php echo esc_html__('Enter your specialties', 'houzez'); ?>" type="text" name="fave_author_specialties" id="fave_author_specialties" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_specialties', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_agent_id-wrap">
                    <th><label for="fave_author_agent_id"><?php echo esc_html__('Address', 'houzez'); ?></label></th>
                    <td><input placeholder="<?php echo esc_html__('Enter your address', 'houzez'); ?>" type="text" name="fave_author_address" id="fave_author_address" value="<?php echo esc_attr( get_the_author_meta( 'fave_author_address', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>

        <h2><?php echo esc_html__('Package Info', 'houzez'); ?></h2>
        <table class="form-table">
            <tbody>
                <tr class="user-package_id-wrap">
                    <th><label for="package_id"><?php echo esc_html__('Package Id', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_id" id="package_id" value="<?php echo esc_attr( get_the_author_meta( 'package_id', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-package_activation-wrap">
                    <th><label for="package_activation"><?php echo esc_html__('Package Activation', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_activation" id="package_activation" value="<?php echo esc_attr( get_the_author_meta( 'package_activation', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-package_listings-wrap">
                    <th><label for="package_listings"><?php echo esc_html__('Listings available', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_listings" id="package_listings" value="<?php echo esc_attr( get_the_author_meta( 'package_listings', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-package_featured_listings-wrap">
                    <th><label for="package_featured_listings"><?php echo esc_html__('Featured Listings available', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_featured_listings" id="package_featured_listings" value="<?php echo esc_attr( get_the_author_meta( 'package_featured_listings', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-package_reloads-wrap">
                    <th><label for="package_reloads"><?php echo esc_html__('Reload available', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_reloads" id="package_reloads" value="<?php echo esc_attr( get_the_author_meta( 'package_reloads', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-package_impressions-wrap">
                    <th><label for="package_impressions"><?php echo esc_html__('Impression available', 'houzez'); ?></label></th>
                    <td><input type="text" name="package_impressions" id="package_impressions" value="<?php echo esc_attr( get_the_author_meta( 'package_impressions', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_paypal_profile-wrap">
                    <th><label for="fave_paypal_profile"><?php echo esc_html__('Paypal Recuring Profile', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_paypal_profile" id="fave_paypal_profile" value="<?php echo esc_attr( get_the_author_meta( 'fave_paypal_profile', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_stripe_user_profile-wrap">
                    <th><label for="fave_stripe_user_profile"><?php echo esc_html__('Stripe Consumer Profile', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_stripe_user_profile" id="fave_stripe_user_profile" value="<?php echo esc_attr( get_the_author_meta( 'fave_stripe_user_profile', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>

        <!-- <h2><?php echo esc_html__('Watermark Settings', 'houzez'); ?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="watermark_image"><?php esc_html_e("Watermark Image", "houzez"); ?></label></th>
                    <td>
                        <input type="text" name="fave_watermark_image" id="fave_watermark_image" value="<?php echo esc_attr(get_the_author_meta('fave_watermark_image', $user->ID)); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e("Please enter your watermark image URL.", "houzez"); ?></span>
                    </td>
                </tr>
            </tbody>
        </table> -->

        <h2><?php echo esc_html__('Social Info', 'houzez'); ?></h2>
        <table class="form-table">
            <tbody>
                <tr class="user-fave_author_facebook-wrap">
                    <th><label for="fave_author_facebook"><?php echo esc_html__('Facebook', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_facebook" id="fave_author_facebook" value="<?php echo esc_url( get_the_author_meta( 'fave_author_facebook', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_linkedin-wrap">
                    <th><label for="fave_author_linkedin"><?php echo esc_html__('LinkedIn', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_linkedin" id="fave_author_linkedin" value="<?php echo esc_url( get_the_author_meta( 'fave_author_linkedin', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_twitter-wrap">
                    <th><label for="fave_author_twitter"><?php echo esc_html__('Twitter', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_twitter" id="fave_author_twitter" value="<?php echo esc_url( get_the_author_meta( 'fave_author_twitter', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_pinterest-wrap">
                    <th><label for="fave_author_pinterest"><?php echo esc_html__('Pinterest', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_pinterest" id="fave_author_pinterest" value="<?php echo esc_url( get_the_author_meta( 'fave_author_pinterest', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_instagram-wrap">
                    <th><label for="fave_author_instagram"><?php echo esc_html__('Instagram', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_instagram" id="fave_author_instagram" value="<?php echo esc_url( get_the_author_meta( 'fave_author_instagram', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_youtube-wrap">
                    <th><label for="fave_author_youtube"><?php echo esc_html__('Youtube', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_youtube" id="fave_author_youtube" value="<?php echo esc_url( get_the_author_meta( 'fave_author_youtube', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                
                <tr class="user-fave_author_tiktok-wrap">
                    <th><label for="fave_author_tiktok"><?php echo esc_html__('TikTok', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_tiktok" id="fave_author_tiktok" value="<?php echo esc_url( get_the_author_meta( 'fave_author_tiktok', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_vimeo-wrap">
                    <th><label for="fave_author_vimeo"><?php echo esc_html__('Vimeo', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_vimeo" id="fave_author_vimeo" value="<?php echo esc_url( get_the_author_meta( 'fave_author_vimeo', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_zillow-wrap">
                    <th><label for="fave_author_zillow"><?php echo esc_html__('Zillow', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_zillow" id="fave_author_zillow" value="<?php echo esc_url( get_the_author_meta( 'fave_author_zillow', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_realtor_com-wrap">
                    <th><label for="fave_author_realtor_com"><?php echo esc_html__('Realtor.com', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_realtor_com" id="fave_author_realtor_com" value="<?php echo esc_url( get_the_author_meta( 'fave_author_realtor_com', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
                <tr class="user-fave_author_googleplus-wrap">
                    <th><label for="fave_author_googleplus"><?php echo esc_html__('Google', 'houzez'); ?></label></th>
                    <td><input type="text" name="fave_author_googleplus" id="fave_author_googleplus" value="<?php echo esc_url( get_the_author_meta( 'fave_author_googleplus', $user->ID ) ); ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>

    <?php
    }
    add_action('show_user_profile', 'houzez_custom_user_profile_fields');
    add_action('edit_user_profile', 'houzez_custom_user_profile_fields');
}



if( !function_exists('houzez_update_extra_profile_fields_package') ) {
    function houzez_update_extra_profile_fields_package($user_id)
    {
        
        // Check for the current user's permissions
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        /*
         * Package Info
        --------------------------------------------------------------------------------*/
        update_user_meta($user_id, 'package_reloads', $_POST['package_reloads']);
        update_user_meta($user_id, 'package_impressions', $_POST['package_impressions']);
    }
    add_action('edit_user_profile_update', 'houzez_update_extra_profile_fields_package');
    add_action('personal_options_update', 'houzez_update_extra_profile_fields_package');
}

if( ! function_exists( 'houzez_update_membership_package' ) ) {
    function houzez_update_membership_package( $user_id, $package_id ) {

        // Get selected package listings
        $pack_listings            =   get_post_meta( $package_id, 'fave_package_listings', true );
        $pack_featured_listings   =   get_post_meta( $package_id, 'fave_package_featured_listings', true );
        $pack_unlimited_listings  =   get_post_meta( $package_id, 'fave_unlimited_listings', true );
        $pack_reload              =   get_post_meta( $package_id, 'fave_package_reloads', true );
        $pack_impressions         =   get_post_meta( $package_id, 'fave_package_impressions', true );
        
        $date = date_i18n( get_option('date_format').' '.get_option('time_format') );

        // ADS Packages So Update only ADS packages fields
        if( $pack_impressions != "" ) {
            $user_pack_impressions = get_user_meta( $user_id, 'package_impressions', true );
            $new_pack_impressions = (int)$user_pack_impressions + (int)$pack_impressions;
            update_user_meta( $user_id, 'package_impressions', $new_pack_impressions);

            $user_pack_impressions_included = get_user_meta( $user_id, 'package_impressions_included', true );
            $new_pack_impressions_included = (int)$user_pack_impressions_included + (int)$pack_impressions;
            update_user_meta( $user_id, 'package_impressions_included', $new_pack_impressions_included);

            update_user_meta( $user_id, 'ads_package_activation', $date );
            update_user_meta( $user_id, 'ads_package_id', $package_id );
            return;
        }
        // END ADS Packages

        // Reload Package
        $reload_package_total  = get_user_meta( $user_id, 'reload_package_total', true );
        $product_title     = get_the_title( $package_id );
        if( $reload_package_total != "" && str_contains(strtolower($product_title), "reload") ){
            $user_pack_reloads = get_user_meta( $user_id, 'package_reloads', true );
            $user_pack_reloads_included = get_user_meta( $user_id, 'package_reloads_included', true );
            $pack_price              = get_post_meta( $package_id, 'fave_package_price', true );
            $reload_credits_price_per_unit = 2;
            $reload_credits_price_per_unit = $pack_price;
            $pack_reload = $reload_package_total / $reload_credits_price_per_unit;

            $new_pack_reloads = (int)$user_pack_reloads + (int)$pack_reload;
            update_user_meta( $user_id, 'package_reloads', $new_pack_reloads);

            $new_pack_reloads_included = (int)$user_pack_reloads_included + (int)$pack_reload;
            update_user_meta( $user_id, 'package_reloads_included', $new_pack_reloads_included);

            update_user_meta( $user_id, 'reload_package_activation', $date );
            update_user_meta( $user_id, 'reload_package_id', $package_id );
            return;
        }
        // End Reload Package


        if( $pack_featured_listings == '' ) {
            $pack_featured_listings = 0;
        }
        if( $pack_reload == '' ) {
            $pack_reload = 0;
        }

        $user_current_posted_listings           =   houzez_get_user_num_posted_listings ( $user_id ); // get user current number of posted listings ( no expired )
        $user_current_posted_featured_listings  =   houzez_get_user_num_posted_featured_listings( $user_id ); // get user number of posted featured listings ( no expired )


        if( houzez_check_user_existing_package_status_for_update_package( $user_id, $package_id ) ) {
            $new_pack_listings           =  $pack_listings - $user_current_posted_listings;
            $new_pack_featured_listings  =  $pack_featured_listings -  $user_current_posted_featured_listings;
            $new_pack_reload  =  $pack_reload;
        } else {
            $new_pack_listings           =  $pack_listings;
            $new_pack_featured_listings  =  $pack_featured_listings;
            $new_pack_reload  =  $pack_reload;
        }

        if( $new_pack_listings < 0 ) {
            $new_pack_listings = 0;
        }

        if( $new_pack_featured_listings < 0 ) {
            $new_pack_featured_listings = 0;
        }

        if( $new_pack_reload < 0 ) {
            $new_pack_reload = 0;
        }

        if ( $pack_unlimited_listings == 1 ) {
            $new_pack_listings = -1 ;
        }

        update_user_meta( $user_id, 'package_listings', $new_pack_listings);
        update_user_meta( $user_id, 'package_featured_listings', $new_pack_featured_listings);
        update_user_meta( $user_id, 'package_reloads', $new_pack_reload);


        // Use for user who submit property without having account and membership
        $user_submit_has_no_membership = get_the_author_meta( 'user_submit_has_no_membership', $user_id );
        if( !empty( $user_submit_has_no_membership ) ) {
            houzez_update_package_listings( $user_id );
            houzez_update_property_from_draft( $user_submit_has_no_membership ); // change property status from draft to pending or publish
            delete_user_meta( $user_id, 'user_submit_has_no_membership' );
        }


        /*$time = time();
        $date = date('Y-m-d H:i:s',$time);*/
        $date = date_i18n( get_option('date_format').' '.get_option('time_format') );
        update_user_meta( $user_id, 'package_activation', $date );
        update_user_meta( $user_id, 'package_id', $package_id );
        update_user_meta( $user_id, 'houzez_membership_id', $package_id);

    }
}
if( !function_exists('create_advertise_packages') ) {
  function create_advertise_packages() {
    register_post_type( 'houzez_ads_packages',
      array(
        'labels' => array(
          'name' => __( 'Advertise Packages' ),
          'singular_name' => __( 'package' ),
          'add_new' => _x('New package', 'package'),
          'add_new_item' => __('New package'),
          'edit_item' => __('Edit package'),
          'new_item' => __('New package'),
          'view_item' => __('View package'),
          'search_items' => __('Search package'),
          'not_found_in_trash' => __('not found in trash'),
        ),
        'public' => true,
        'menu_icon' => 'dashicons-admin-users',
        'can_export' => true,
        'rewrite' => array( 'slug' => 'advertise_packages', 'with_front' => true ),
        'menu_position' => 12,
        'show_in_menu'        => false,
        'hierarchical' => true,
        'supports' => array('title','page-attributes','revisions'),
      )
    );
  }
  add_action( 'init', 'create_advertise_packages' );
}
if( !function_exists('add_ads_packages_menu') ) {
  function add_ads_packages_menu( $submenus ) {
    $slug = 'houzez-real-estate';
    $capability = 'edit_posts';

    $submenus['advertise_packages'] = array(
        $slug,
        esc_html__( 'Advertise Packages', 'houzez-theme-advertise_packages' ),
        esc_html__( 'Advertise Packages', 'houzez-theme-advertise_packages' ),
        $capability,
        'edit.php?post_type=houzez_ads_packages',
    );

    return $submenus;
  }
  add_filter( 'houzez_admin_realestate_menu', 'add_ads_packages_menu' );
}

if( !function_exists('houzez_advertise_packages_metaboxes') ) {
  function houzez_advertise_packages_metaboxes( $meta_boxes ) {
    $houzez_prefix = 'fave_';
    
    $meta_boxes[] = array(
        'title'  => esc_html__( 'Advertise Package Details', 'houzez' ),
        'post_types'  => array('houzez_ads_packages'),
        'fields' => array(
            array(
                'id' => "{$houzez_prefix}billing_time_unit",
                'name' => esc_html__( 'Billing Period', 'houzez' ),
                'type' => 'select',
                'std' => "",
                'options' => array( 'Day' => esc_html__('Day', 'houzez' ), 'Week' => esc_html__('Week', 'houzez' ), 'Month' => esc_html__('Month', 'houzez' ), 'Year' => esc_html__('Year', 'houzez' ) ),
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}billing_unit",
                'name' => esc_html__( 'Billing Frequency', 'houzez' ),
                'placeholder' => esc_html__( 'Enter the frequency number', 'houzez' ),
                'type' => 'text',
                'std' => "0",
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_role",
                'name' => esc_html__( 'For Agency/Developer?', 'houzez' ),
                'type' => 'select',
                'std' => "no",
                'options' => array( 'agency' => esc_html__( 'Agency', 'houzez' ), 'developer' => esc_html__( 'Developer', 'houzez' ) ),
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_impressions",
                'name' => esc_html__( 'How many impressions are included?', 'houzez' ),
                'placeholder' => esc_html__( 'Enter the number of impressions', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'columns' => 6,

            ),
            array(
                'id' => "{$houzez_prefix}package_price_per_unit",
                'name' => esc_html__( 'Package Price Per Unit ', 'houzez' ),
                'placeholder' => esc_html__( 'Enter the price', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_price",
                'name' => esc_html__( 'Package Price ', 'houzez' ),
                'placeholder' => esc_html__( 'Enter the price', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_discount",
                'name' => esc_html__( 'Package Discount (%)', 'houzez' ),
                'placeholder' => esc_html__( 'Enter the discount', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_stripe_id",
                'name' => esc_html__( 'Package Stripe id (Example: gold_pack)', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}package_visible",
                'name' => esc_html__( 'Is It Visible?', 'houzez' ),
                'type' => 'select',
                'std' => "",
                'options' => array( 'yes' => esc_html__( 'Yes', 'houzez' ), 'no' => esc_html__( 'No', 'houzez' ) ),
                'columns' => 6,
            ),
            array(
                'id' => "{$houzez_prefix}stripe_taxId",
                'name' => esc_html__( 'Stripe Tax ID', 'houzez' ),
                'type' => 'text',
                'std' => "",
                'placeholder' => esc_html__( 'Enter your stripe account tax id.', 'houzez' ),
                'columns' => 6,
            ),
        ),
    );
    

    return apply_filters('houzez_ads_packages_meta', $meta_boxes);

  }
  add_filter( 'rwmb_meta_boxes', 'houzez_advertise_packages_metaboxes' );
}

if( !function_exists('manage_houzez_ads_packages_posts_columns') ) {
    function manage_houzez_ads_packages_posts_columns() {
        $fields = array(
            'cb' 				=> '<input type="checkbox" />',
            'title' 			=> esc_html__( 'Package Name', 'houzez' ),
            'fave_package_role' => esc_html__( 'Agency/Developer?', 'houzez' ),
            'fave_billing_time_unit' => esc_html__( 'Billing Period', 'houzez' ),
            'fave_package_price'	=> esc_html__( 'Package Price', 'houzez' ),
            'fave_package_price_per_unit'	=> esc_html__( 'Price/Unit', 'houzez' ),
            'fave_package_discount' => esc_html__( 'Discount', 'houzez' ),
            'fave_package_impressions' => esc_html__( 'Impressions', 'houzez' ),
            'date' 			=> esc_html__( 'Date', 'houzez' ),
        );

        return $fields;
    }
    add_filter( 'manage_houzez_ads_packages_posts_columns', 'manage_houzez_ads_packages_posts_columns' );
}

if( !function_exists('manage_houzez_ads_packages_posts_custom_column') ) {
    function manage_houzez_ads_packages_posts_custom_column( $column ) {
        global $post;
        switch ( $column ) {
            case 'fave_package_impressions':
                $fave_package_impressions = get_post_meta( get_the_ID(),  'fave_package_impressions', true );

                if ( ! empty( $fave_package_impressions ) ) {
                    echo esc_attr( $fave_package_impressions );
                } else {
                    echo '-';
                }
                break;

            case 'fave_package_role':
                $fave_package_role = get_post_meta( get_the_ID(),  'fave_package_role', true );

                if ( ! empty( $fave_package_role ) ) {
                    echo esc_attr( $fave_package_role );
                } else {
                    echo '-';
                }
                break;
            
            case 'fave_package_price_per_unit':
                $fave_package_price_per_unit = get_post_meta( get_the_ID(),  'fave_package_price_per_unit', true );

                if ( ! empty( $fave_package_price_per_unit ) ) {
                    echo esc_attr( $fave_package_price_per_unit );
                } else {
                    echo '-';
                }
                break;

            case 'fave_package_discount':
                $fave_package_discount = get_post_meta( get_the_ID(),  'fave_package_discount', true );

                if ( ! empty( $fave_package_discount ) ) {
                    echo esc_attr( $fave_package_discount );
                } else {
                    echo '-';
                }
                break;

            case 'fave_package_price':
                $fave_package_price = get_post_meta( get_the_ID(),  'fave_package_price', true );

                if ( ! empty( $fave_package_price ) ) {
                    echo esc_attr( $fave_package_price );
                } else {
                    echo '-';
                }
                break;

            case 'fave_billing_time_unit':
                $fave_billing_time_unit = get_post_meta( get_the_ID(),  'fave_billing_time_unit', true );
                $fave_billing_unit = get_post_meta( get_the_ID(),  'fave_billing_unit', true );

                if ( ! empty( $fave_billing_time_unit ) ) {
                    echo esc_attr( $fave_billing_unit ) . " " . esc_attr( $fave_billing_time_unit );
                } else {
                    echo '-';
                }
                break;

        }
    }
    add_action( 'manage_houzez_ads_packages_posts_custom_column', 'manage_houzez_ads_packages_posts_custom_column' );
}

if( ! function_exists( 'houzez_stripe_package_payment' ) ) {
    function houzez_stripe_package_payment() {
        require_once( get_template_directory() . '/framework/stripe-php/init.php' );
        $stripe_secret_key = houzez_option('stripe_secret_key');
        $stripe_publishable_key = houzez_option('stripe_publishable_key');

        $stripe = array(
            "secret_key" => $stripe_secret_key,
            "publishable_key" => $stripe_publishable_key
        );

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $stripe = new \Stripe\StripeClient( $stripe['secret_key'] );

        $currency    = houzez_option('currency_paid_submission');
        $blogInfo    = get_bloginfo('name');
        $userID      = get_current_user_id();
        $package_id  = intval($_POST['package_id']);
        $user_email  = wp_get_current_user()->user_email;

        $planId = get_post_meta( $package_id, 'fave_package_stripe_id', true );
        $tax_rate_id = get_post_meta( $package_id, 'fave_stripe_taxId', true );
        $is_stripe_recurring   = sanitize_text_field( wp_unslash( $_POST['is_stripe_recurring'] ) );

        $return_link  =  houzez_get_template_link('template/template-stripe-charge.php');
        $cancelled_link  =  houzez_get_template_link('template/template-payment.php');
        $product_title     = get_the_title( $package_id );
        $product_image_url = get_the_post_thumbnail_url( $package_id, 'large' );

        $api_error = '';

        try {

            $stripe_customer_id = get_user_meta($userID, 'fave_stripe_user_profile', true);

            if( $is_stripe_recurring == "true" && ! empty( $planId ) ) {

                // Check for existing subscription

                if( ! empty($stripe_customer_id) ) {
                    $subscriptions = $stripe->subscriptions->all(['customer' => $stripe_customer_id, 'status' => 'active']);

                    if (count($subscriptions->data) > 0) {
                        $existing_subscription = $subscriptions->data[0];
                        // Cancel existing subscription and create a new one
                        $stripe->subscriptions->cancel($existing_subscription->id);
                    }
                }

                $data = [
                    'success_url' => $return_link . '?houzez_stripe_recurring=1&is_houzez_membership=1&mode=package&pack_id='.esc_attr($package_id).'&success=1&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $cancelled_link . '?selected_package=' .$package_id. '&cancel=1',
                    'payment_method_types' => [
                      'card',
                    ],
                    'subscription_data' => [
                        'items' => [[
                            'plan' => $planId,
                        ]],
                        'metadata' => [
                            'payment_type' => 'subscription_fee',
                            'userID' => get_current_user_id(),
                            'package_id' => esc_attr($package_id)
                        ],
                    ],
                    'locale' => 'auto',
                    "metadata" => [
                        'payment_type' => 'subscription_fee',
                        'userID' => get_current_user_id(),
                        'package_id' => esc_attr($package_id)
                    ],
                ];

                if( $stripe_customer_id != '' ) {
                     $data['customer'] = $stripe_customer_id;
                }

                if($tax_rate_id != null && !empty(trim($tax_rate_id))){
                    $data['subscription_data']['default_tax_rates'] = [$tax_rate_id];
                }

                $checkout_session = \Stripe\Checkout\Session::create($data);

            } else {

                $amount  = get_post_meta( $package_id, 'fave_package_price', true );

                // Reload package
                $cancel_url = $cancelled_link . '?selected_package=' .$package_id. '&cancel=1';
                $reload_package_total  = get_user_meta( get_current_user_id(), 'reload_package_total', true );

                if( $reload_package_total != "" && str_contains(strtolower($product_title), "reload") ){
                    $amount  = (float)$reload_package_total;
                    $cancel_url = $cancelled_link . '?selected_package=' .$package_id. '&reload_package_total='.$amount.'&cancel=1';
                }

                if( in_array( $currency, houzez_stripe_zero_decimal_currencies() ) ) {
                    $amount = $amount;
                } else if( in_array( $currency, houzez_stripe_3digits_currencies() ) ) {
                    $amount = round($amount * 100) * 10;
                } else {
                    $amount = round( $amount * 100, 2 );
                }

                $product_data = array( 'name' => esc_html( $product_title ) );
                if ( ! empty( $product_image_url ) ) {
                    $product_data['images'] = array( esc_url( $product_image_url ) );
                }

                $data = array(
                    'payment_method_types' => array( 'card' ),
                    'line_items' => array(
                        array(
                            'price_data' => array(
                                'currency'     => $currency,
                                'unit_amount'  => $amount,
                                'product_data' => $product_data,
                            ),
                            'quantity'   => 1,
                        ),
                    ),
                    'locale' => 'auto',
                    "metadata" => [
                        'user_id'     => get_current_user_id(),
                        "package_id"  => $package_id,
                        "title"       => get_the_title($package_id),
                    ],
                    'mode'        => 'payment',
                    'success_url' => $return_link . '?is_houzez_membership=0&mode=simple_package&pack_id='.esc_attr($package_id).'&success=1&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $cancel_url,
                );

                if( $stripe_customer_id != '' ) {
                     $data['customer'] = $stripe_customer_id;
                }

                if($tax_rate_id != null && !empty(trim($tax_rate_id))){
                    $data['line_items'][0]['tax_rates'] = [$tax_rate_id];
                }

                $checkout_session = \Stripe\Checkout\Session::create($data);
                

            }
        } catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 

        if( empty($api_error) && $checkout_session ) { 
            $response = array( 
                'status' => true, 
                'message' => esc_html__('Checkout Session created successfully!', 'houzez'), 
                'sessionId' => $checkout_session['id'],
                'paymeny_link' => $checkout_session->url
            ); 
        }else{ 
            $response = array( 
                'status' => false, 
                'message' => esc_html__('Checkout Session creation failed!', 'houzez').' '.$api_error
            ); 
        } 
        update_user_meta( get_current_user_id(), 'houzez_stripe_temp_session_id', $checkout_session->id );
        echo json_encode($response);
        wp_die();

    }
}

if( !function_exists('houzez_generate_invoice') ){
    function houzez_generate_invoice( $billingFor, $billionType, $packageID, $invoiceDate, $userID, $featured, $upgrade, $paypalTaxID, $paymentMethod, $is_package = 0 ) {

        $total_taxes = 0;
        $price_per_submission = houzez_option('price_listing_submission');
        $price_featured_submission = houzez_option('price_featured_listing_submission');

        $price_per_submission      = floatval( $price_per_submission );
        $price_featured_submission = floatval( $price_featured_submission );

        $args = array(
            'post_title'    => 'Invoice ',
            'post_status'   => 'publish',
            'post_type'     => 'houzez_invoice'
        );
        $inserted_post_id =  wp_insert_post( $args );

        if( $billionType != 'one_time' ) {
            $billionType = __( 'Recurring', 'houzez' );
        } else {
            $billionType = __( 'One Time', 'houzez' );
        }

        if( $is_package == 0 ) {
            if( $upgrade == 1 ) {
                $total_price = $price_featured_submission;

            } else {
                if( $featured == 1 ) {
                    $total_price = $price_per_submission+$price_featured_submission;
                } else {
                    $total_price = $price_per_submission;
                }
            }
        } else {
            $pack_price = get_post_meta( $packageID, 'fave_package_price', true);
            $pack_tax = get_post_meta( $packageID, 'fave_package_tax', true );

            // Reload Package
            $reload_package_total  = get_user_meta( get_current_user_id(), 'reload_package_total', true );
            $product_title     = get_the_title( $packageID );
            if( $reload_package_total != "" && str_contains(strtolower($product_title), "reload") ){
                $pack_price  = (float)$reload_package_total;
                //update_user_meta( get_current_user_id(), 'reload_package_total', "" ); // empty 
            }
            // End Reload Package

            if( !empty($pack_tax) && !empty($pack_price) ) {
                $total_taxes = intval($pack_tax)/100 * $pack_price;
                $total_taxes = round($total_taxes, 2);
            }
            
            $total_price = $pack_price + $total_taxes;
        }
        
        $fave_meta = array();

        $fave_meta['invoice_billion_for'] = $billingFor;
        $fave_meta['invoice_billing_type'] = $billionType;
        $fave_meta['invoice_item_id'] = $packageID;
        $fave_meta['invoice_item_price'] = $total_price;
        $fave_meta['invoice_tax'] = $total_taxes;
        $fave_meta['invoice_purchase_date'] = $invoiceDate;
        $fave_meta['invoice_buyer_id'] = $userID;
        $fave_meta['paypal_txn_id'] = $paypalTaxID;
        $fave_meta['invoice_payment_method'] = $paymentMethod;

        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_buyer', $userID );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_type', $billionType );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_for', $billingFor );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_item_id', $packageID );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_price', $total_price );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_tax', $total_taxes );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_date', $invoiceDate );
        update_post_meta( $inserted_post_id, 'HOUZEZ_paypal_txn_id', $paypalTaxID );
        update_post_meta( $inserted_post_id, 'HOUZEZ_invoice_payment_method', $paymentMethod );

        update_post_meta( $inserted_post_id, '_houzez_invoice_meta', $fave_meta );

        // Update post title
        $update_post = array(
            'ID'         => $inserted_post_id,
            'post_title' => 'Invoice '.$inserted_post_id,
        );
        wp_update_post( $update_post );
        return $inserted_post_id;
    }
}

if( !function_exists('houzez_downgrade_package') ){
    function houzez_downgrade_package( $user_id, $pack_id ) {

        

        // SKIP for ADS Packages AND Reload Package
        $pack_impressions    =   get_post_meta( $pack_id, 'fave_package_impressions', true );
        if( $pack_impressions != "" ) {
            return;
        }

        $reload_package_total  = get_user_meta( $user_id, 'reload_package_total', true );
        $product_title     = get_the_title( $pack_id );
        if( $reload_package_total != "" && str_contains(strtolower($product_title), "reload") ){
            return;
        }
        // END SKIP for ADS Packages AND Reload Package

        $pack_listings           =  get_post_meta( $pack_id, 'pack_listings', true );
        $pack_featured_listings  =  get_post_meta( $pack_id, 'pack_featured_listings', true );

        update_user_meta( $user_id, 'package_listings', $pack_listings );
        update_user_meta( $user_id, 'package_featured_listings', $pack_featured_listings );

        $args = array(
            'post_type'   => 'property',
            'post_status' => 'any'
        );

        $agency_agents = houzez_get_agency_agents( $user_id );
        $package_permission = houzez_can_agent_user_agency_package( $user_id );

        if( $agency_agents && $package_permission ) {
            $agency_agents[] = $user_id;
            $args['author__in'] = $agency_agents;
        } else {
            $args['author'] = $user_id;
        }

        $query = new WP_Query( $args );
        global $post;
        while( $query->have_posts()){
            $query->the_post();

            $property = array(
                'ID'          => $post->ID,
                'post_type'   => 'property',
                'post_status' => 'expired'
            );

            wp_update_post( $property );
            update_post_meta( $post->ID, 'fave_featured', 0 );
            update_post_meta( $post->ID, 'houzez_featured_listing_date', '' );
        }
        wp_reset_postdata();

        $user = get_user_by( 'id', $user_id );
        $user_email = $user->user_email;

        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message  = esc_html__('Account Downgraded,','houzez') . "\r\n\r\n";
        $message .= sprintf( __("Hello, You downgraded your subscription on  %s. Because your listings number was greater than what the actual package offers, we set the status of all your listings to \"expired\". You will need to choose which listings you want live and send them again for approval. Thank you!",'houzez'), get_option('blogname')) . "\r\n\r\n";

        wp_mail($user_email,
            sprintf(esc_html__('[%s] Account Downgraded','houzez'), get_option('blogname')),
            $message,
            $headers);
    }
}

if( !function_exists('houzez_is_dashboard_child') ) {
    function houzez_is_dashboard_child($files) {

        array_push($files,'template/user_dashboard_advertise.php');
        
        return $files;
    }
    add_filter( 'houzez_is_dashboard_filter', 'houzez_is_dashboard_child' );
}

if( !function_exists('houzez_is_dashboard') ) {
    function houzez_is_dashboard() {

        $files = apply_filters( 'houzez_is_dashboard_filter', array(
            'template/user_dashboard_profile.php',
            'template/user_dashboard_insight.php',
            'template/user_dashboard_crm.php',
            'template/user_dashboard_properties.php',
            'template/user_dashboard_favorites.php',
            'template/user_dashboard_invoices.php',
            'template/user_dashboard_saved_search.php',
            'template/user_dashboard_floor_plans.php',
            'template/user_dashboard_multi_units.php',
            'template/user_dashboard_membership.php',
            'template/user_dashboard_gdpr.php',
            'template/user_dashboard_submit.php',
            'template/user_dashboard_messages.php'
            
        ) );

        

        if ( is_page_template($files) ) {
            return true;
        }
        return false;
    }
}


if( !function_exists('houzez_property_add_impression') ){
    function  houzez_property_add_impression(){

        $userID = get_current_user_id();

        $packageUserId = $userID;
        $agent_agency_id = houzez_get_agent_agency_id( $userID );
        if( $agent_agency_id ) {
            $packageUserId = $agent_agency_id;
        }

        $package_impressions = get_the_author_meta( 'package_impressions' , $packageUserId );

        $add_impression_value = intval( $_POST['add_impression_value'] );
        $prop_id = $_POST['listing_id'];
        
        if( $prop_id != "" && $add_impression_value > 0 && $add_impression_value <= intval($package_impressions) ) {
            $fave_impressions = get_post_meta( $prop_id, 'fave_impressions', true );
            update_post_meta( $prop_id, 'fave_impressions', intval($fave_impressions) + $add_impression_value );

            $fave_impressions_included = get_post_meta( $prop_id, 'fave_impressions_included', true );
            update_post_meta( $prop_id, 'fave_impressions_included', intval($fave_impressions_included) + $add_impression_value );

            if ( $package_impressions - $add_impression_value >= 0 ) {
                update_user_meta( $packageUserId, 'package_impressions', $package_impressions - $add_impression_value );
            } else {
                update_user_meta( $packageUserId, 'package_impressions', 0 ) ;
            }

            echo json_encode(array('success' => true, 'msg' => 'Added Impression Credits Successfully.'));
            wp_die();
        }

        echo json_encode(array('success' => false, 'msg' => 'Failed'));
        wp_die();
    }
    add_action( 'wp_ajax_houzez_property_add_impression', 'houzez_property_add_impression' );
}

if(!function_exists('houzez20_search_filters_advertise')) {
	function houzez20_search_filters_advertise($search_qry) {
		$tax_query = array();
        $meta_query = array();
        $allowed_html = array();
        $keyword_array = '';
        $keyword_field = houzez_option('keyword_field');

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

        $meta_query[] = array(
            'key' => 'fave_advertise',
            'value' => 1,
            'compare' => '='
        );

        $meta_query[] = array(
            'key' => 'fave_impressions',
            'value' => 0,
            'compare' => '>'
        );

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
        /*echo '<pre>';
        print_r($search_qry);
        echo '</pre>';*/
        return $search_qry;

	}
	add_filter('houzez20_search_filters_advertise', 'houzez20_search_filters_advertise');
}

if( ! function_exists('set_advertise_to_posts') ) {
    function set_advertise_to_posts($posts) { 

        foreach ($posts as $post) {

            $fave_impressions = get_post_meta( $post->ID, 'fave_impressions', true );
            update_post_meta( $post->ID, 'fave_impressions', intval($fave_impressions) - 1 );

            houzez_insert_tracking_views('ads', $post->ID);
        }
        
    }
}

if( !function_exists('houzez20_half_map_listings') ) {
    function houzez20_half_map_listings() {
        global $post, $houzez_local;
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
        if($paged == 0) $paged = 1;

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

    set_advertise_to_posts($advertise_query->posts);

    $search_qry = array(
        'post_type' => 'property',
        'posts_per_page' => $number_of_prop_first,
        //'paged' => $paged,
        'post_status' => 'publish',
        'post__not_in'   => $advertise_post_ids,
        'offset'         => 0
    );

    //$search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    //$search_qry = houzez_prop_sort ( $search_qry );
    //$search_query = new WP_Query( $search_qry );

    // Combine the results
    //$combined_posts = array_merge($advertise_query->posts, $search_query->posts);

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

    //$search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    //$search_qry = houzez_prop_sort ( $search_qry );
    //$search_query = new WP_Query( $search_qry );
}

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

        //while( $query_args->have_posts() ): $query_args->the_post();
        if(1 == $paged) {
            $combined_posts = array_merge($advertise_query->posts, $query_args->posts);
        }
        else{
            $combined_posts = $query_args->posts;
        }

        foreach ($combined_posts as $post) {
            setup_postdata($post);

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

        }
        wp_reset_postdata();
        //endwhile;


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

if(!function_exists('houzez_views_ads_stats')) {
    function houzez_views_ads_stats($user_id, $activities_start_date, $activities_end_date, $listing_id = false) {

        //echo "{$user_id}, {$activities_start_date}, {$activities_end_date}";exit;

        if( houzez_is_admin() || houzez_is_editor() ) {
            $user_id = '';
        } else if( houzez_is_agency() ) {
            $agents = houzez_get_agency_agents($user_id);

            if( $agents ) {
                if (!in_array($user_id, $agents)) {
                    $agents[] = $user_id;
                }
                $user_id = $agents;
            } else {
                $user_id = $user_id;
            }

        } else {
            $user_id = $user_id;
        }

        $stats = array();
        $args = array('user_id' => $user_id, 'listing_id' => $listing_id, 'activities_start_date' => $activities_start_date, 'activities_end_date' => $activities_end_date);

        $stats['views'] = houzez_count_views($args);
        $stats['impressions'] = houzez_count_views_tracking($args, 'ads');

        if( !empty($stats['views']) && !empty($stats['impressions']) ){
            $stats['conversation'] = $stats['views'] / $stats['impressions'] * 100;
        }
        $stats['conversation'] = empty($stats['conversation']) ? 0 : number_format((float)$stats['conversation'], 2, '.', '');

        return $stats;
    }
}

if(!function_exists('houzez_impressions_ads_stats')) {
    function houzez_impressions_ads_stats($user_id, $listing_id = false) {

        //echo "{$user_id}, {$listing_id},";exit;

        if( houzez_is_admin() || houzez_is_editor() ) {
            $user_id = '';
        } else if( houzez_is_agency() ) {
            $agents = houzez_get_agency_agents($user_id);

            if( $agents ) {
                if (!in_array($user_id, $agents)) {
                    $agents[] = $user_id;
                }
                $user_id = $agents;
            } else {
                $user_id = $user_id;
            }

        } else {
            $user_id = $user_id;
        }

        // Define an array of time periods to retrieve insights for
        $time_periods = [
            'lastday', 'lasttwo', 'lastweek', 'last2week', 'lastmonth', 'last2month'
        ];

        // Initialize an array to store views data for each time period
        $views = [];

        // Retrieve views data for each time period and store it in the array
        foreach ($time_periods as $time_period) {
            $activities_start_date = get_modified_time($time_period);

            $args = array('user_id' => $user_id, 'listing_id' => $listing_id, 'activities_start_date' => $activities_start_date, 'activities_end_date' => false);

            $views[$time_period] = houzez_count_views_tracking($args, 'ads');
        }

        // Return the array containing views data for each time period
        return $views;
    }
}

if(!function_exists('get_modified_time')) {
    function get_modified_time($time) {
        $DateTimeZone = wp_timezone();
        $DateTime = new DateTime('now', $DateTimeZone);

        $time_token = [
            'lastday' => '-1 day',
            'lasttwo' => '-2 day',
            'lastweek' => '-7 days',
            'last2week' => '-14 days',
            'lastmonth' => '-30 days',
            'last2month' => '-60 days',
            'lasthalfyear' => '-182 days',
            'lastyear' => '-365 days'
        ];

        $modifiedTime = $DateTime->modify($time_token[$time])->format('Y-m-d H:i:s');
        return $modifiedTime;
    }
}

if(!function_exists('get_charts_data_impressions')) {
    function get_charts_data_impressions( $listing_id ) {
            
        $return = array();
        $user_id = false;
        $listing_id = isset( $listing_id ) ? $listing_id : false;
        
        $return['lastday'] = get_chart_impressions( [ 'time' => 'lastday', 'group_by' => 'hour', 'user_id' => $user_id, 'listing_id' => $listing_id ] );
        
        
        $return['lastweek'] = get_chart_impressions( [ 'time' => 'lastweek', 'group_by' => 'day', 'user_id' => $user_id, 'listing_id' => $listing_id ] );
        

        $return['lastmonth'] = get_chart_impressions( [ 'time' => 'lastmonth', 'group_by' => 'day', 'user_id' => $user_id, 'listing_id' => $listing_id ] );
        
        
        $return['lasthalfyear'] = get_chart_impressions( [ 'time' => 'lasthalfyear', 'group_by' => 'week', 'user_id' => $user_id, 'listing_id' => $listing_id ] );


        $return['lastyear'] = get_chart_impressions( [ 'time' => 'lastyear', 'group_by' => 'month', 'user_id' => $user_id, 'listing_id' => $listing_id ] );
        

        return $return;
    }
}
if(!function_exists('get_chart_impressions')) {
    function get_chart_impressions( $args = array() ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'houzez_crm_viewed_listings_statistics';
        $visits = array();
        $query = array();

        $args = wp_parse_args( $args, [
            'listing_id' => false,
            'user_id' => false,
            'time' => false,
            'group_by' => 'day',
        ] );

        $groups = [
            'hour' => [
                'query' => "DATE_FORMAT( {$table_name}.time, '%Y-%m-%d %H:00:00' )",
                'modifier' => '-1 hour',
                'id' => 'Y-m-d H:00:00',
                'count' => function( $a ) { return $a * 24; },
                'label' => function( $date ) { return date_i18n( 'H:00', $date->getTimestamp() ); },
            ],
            'day' => [
                'query' => "DATE( {$table_name}.time )",
                'modifier' => '-1 day',
                'id' => 'Y-m-d',
                'count' => function( $b ) { return $b; },
                'label' => function( $date ) { return date_i18n( 'M j', $date->getTimestamp() ); },
            ],
            'week' => [
                'query' => "DATE_FORMAT( {$table_name}.time, '%x-%v' )",
                'modifier' => '-1 week',
                'count' => function( $c ) { return $c / 7; },
                'id' => 'o-W',
                'label' => function( $date ) { return date_i18n( 'M j', $date->getTimestamp() ); },
            ],
            'month' => [
                'query' => "DATE_FORMAT( {$table_name}.time, '%Y-%m-01' )",
                'modifier' => '-1 month',
                'id' => 'Y-m-01',
                'count' => function( $d ) { return $d / 31; },
                'label' => function( $date ) { return date_i18n( 'M', $date->getTimestamp() ); },
            ],
        ];

        
        $group = isset( $groups[ $args['group_by'] ] ) ? $groups[ $args['group_by'] ] : $groups['day'];

        
        $query[] = "
            SELECT
                COUNT( {$table_name}.id ) AS views,
                {$group['query']} as date
        ";

        $query[] = "FROM {$table_name}";
        $query[] = "INNER JOIN {$wpdb->posts} ON ( {$wpdb->posts}.ID = {$table_name}.listing_id )";
        $query[] = "WHERE {$wpdb->posts}.post_status = 'publish'";

        if (!empty($args['listing_id'])) {
            $query[] = sprintf( " AND {$table_name}.listing_id = %d ", intval( $args['listing_id'] ) );
        }
        else if (!empty($args['user_id'])) {
            if ( is_array( $args['user_id'] ) ) {
                $user_ids = implode( ',', array_map( 'intval', $args['user_id'] ) );
                $query[] = " AND {$wpdb->posts}.post_author IN ({$user_ids}) ";
            } else {
                $query[] = sprintf( " AND {$wpdb->posts}.post_author = %d ", intval( $args['user_id'] ) );
            }
        }

        $activities_start_date = get_modified_time($args['time']);

        if (!empty($activities_start_date)) {
            $query[] = sprintf(
                " AND {$table_name}.time >= '%s' ",
                $activities_start_date
            );
        }

        $type = "ads";

        if (!empty($type)) {
            $query[] = sprintf(
                " AND {$table_name}.type = '%s' ",
                $type
            );
        }

        $query[] = "GROUP BY date";
        $query = join( "\n", $query );

        $results = $wpdb->get_results( $query, OBJECT );
        if ( ! is_array( $results ) || empty( $results ) ) {
            $results = array();
        }

        //return $results;

        $DateTimeZone = wp_timezone();//new DateTimeZone( '+02:30' );
        $date = new DateTime('now', $DateTimeZone);

        $counts = [
            'lastyear' => 365,
            'lasthalfyear' => 182,
            'lastmonth' => 30,
            'lastweek' => 7,
            'lastday' => 1,
        ];

        $count = isset( $counts[ $args['time'] ] ) ? $counts[ $args['time'] ] : 1;
        $count = $group['count']( $count );

        for ( $i = 0; $i < $count; $i++ ) {
            $id = $date->format( $group['id'] );
            $visits[ $id ] = [
                'views' => 0,
                'date' => $id,
                'label' => $group['label']( $date ),
            ];
            $date->modify( $group['modifier'] );
        }

        foreach ( $results as $result ) {
            $result->views = isset( $result->views ) ? $result->views : 0;
            $visits[ $result->date ]['views'] = $result->views;
        }

        return array_reverse( $visits );
    }
}

add_action( 'elementor/init', function() {
    if ( class_exists( '\Elementor\Widget_Base' ) ) {

        add_action( 'elementor/widgets/widgets_registered', function() {
            require_once get_stylesheet_directory() . '/elementor-widgets/class-mestate-property-carousel.php';

            \Elementor\Plugin::instance()->widgets_manager->register( new MEstate_Property_Carousel() );
        } );

        add_action( 'elementor/widgets/widgets_registered', function() {
            require_once get_stylesheet_directory() . '/elementor-widgets/class-mestate-offer.php';

            \Elementor\Plugin::instance()->widgets_manager->register( new MEstate_Offer() );
        } );


        add_action( 'elementor/widgets/widgets_registered', function() {
            require_once get_stylesheet_directory() . '/elementor-widgets/class-mestate-city-carousel.php';

            \Elementor\Plugin::instance()->widgets_manager->register( new MEstate_City_Carousel() ); 
        } );

        add_action( 'elementor/widgets/widgets_registered', function() {
            require_once get_stylesheet_directory() . '/elementor-widgets/class-mestate-city-map.php';

            \Elementor\Plugin::instance()->widgets_manager->register( new MEstate_City_Map() ); 
        } );

        add_action( 'elementor/widgets/widgets_registered', function() {
            require_once get_stylesheet_directory() . '/elementor-widgets/class-mestate-hero-filter.php';

            \Elementor\Plugin::instance()->widgets_manager->register( new MEstate_Hero_Filter() ); 
        } );

        // add_action('init', function() {
        //     // if ( class_exists('\Elementor\Plugin') ) {
        //     //     $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
        //     //     $registered_widgets = $widgets_manager->get_widget_types();

        //     //     // echo '<pre>';
        //     //     // foreach ($registered_widgets as $widget) {
        //     //     //     echo get_class($widget) . "\n";
        //     //     // }
        //     //     // echo '</pre>';
        //     // }
        // });

    }
});

function get_city_name_by_slug($slug) {
    $term = get_term_by('slug', $slug, 'property_city');
    return $term ? $term->name : ucwords(str_replace('-', ' ', $slug));
}



//$user_package_id = houzez_get_user_package_id($userID);
//$package_images = get_post_meta( $user_package_id, 'fave_package_images', true );
?>