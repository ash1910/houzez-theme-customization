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
                'key' => 'fave_developer_display_option',
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
                'key' => 'fave_developer_display_option',
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
add_action( 'wp_ajax_nopriv_houzez_register', 'houzez_register2' );

if( !function_exists('houzez_register2') ) {
    function houzez_register2() {
        echo "def1";exit;
        check_ajax_referer('houzez_register_nonce', 'houzez_register_security');

        $allowed_html = array();

        $usermane          = trim( sanitize_text_field( wp_kses( $_POST['username'], $allowed_html ) ));
        $email             = trim( sanitize_text_field( wp_kses( $_POST['useremail'], $allowed_html ) ));
        $term_condition    = isset( $_POST['term_condition'] ) ? wp_kses( $_POST['term_condition'], $allowed_html ) : "off";
        
        $enable_password   = houzez_option('enable_password');

        $response = isset( $_POST["g-recaptcha-response"] ) ? $_POST["g-recaptcha-response"] : "";

        $user_roles = array ( 'houzez_agency', 'houzez_agent', 'houzez_buyer', 'houzez_seller', 'houzez_owner', 'houzez_manager' );

        $user_role = get_option( 'default_role' );

        if( $user_role == 'administrator' ) {
            $user_role = 'subscriber';
        }

        
        if( isset( $_POST['role'] ) && $_POST['role'] != '' && in_array( $_POST['role'], $user_roles ) ) {
            $user_role = isset( $_POST['role'] ) ? sanitize_text_field( wp_kses( $_POST['role'], $allowed_html ) ) : $user_role;
        } else {
            $user_role = $user_role;
        }

        if( houzez_option('header_register') != 1 ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Access denied.', 'houzez-login-register') ) );
            wp_die();
        }

        if( get_option('users_can_register') != 1 ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Access denied.', 'houzez-login-register') ) );
            wp_die();
        }


        $firstname = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
        if( empty($firstname) && houzez_option('register_first_name', 0) == 1 ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('The first name field is empty.', 'houzez-login-register') ) );
            wp_die();
        }

        $lastname = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
        if( empty($lastname) && houzez_option('register_last_name', 0) == 1 && $user_role != 'houzez_agency' ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('The last name field is empty.', 'houzez-login-register') ) );
            wp_die();
        }

        if( empty( $usermane ) ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('The username field is empty.', 'houzez-login-register') ) );
            wp_die();
        }
        if( strlen( $usermane ) < 3 ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Minimum 3 characters required', 'houzez-login-register') ) );
            wp_die();
        }
        if (preg_match("/^[0-9A-Za-z_]+$/", $usermane) == 0) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Invalid username (do not use special characters or spaces)!', 'houzez-login-register') ) );
            wp_die();
        }

        if( username_exists( $usermane ) ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('This username is already registered.', 'houzez-login-register') ) );
            wp_die();
        }
        
        if( empty( $email ) ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('The email field is empty.', 'houzez-login-register') ) );
            wp_die();
        }

        if( email_exists( $email ) ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('This email address is already registered.', 'houzez-login-register') ) );
            wp_die();
        }

        if( !is_email( $email ) ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Invalid email address.', 'houzez-login-register') ) );
            wp_die();
        }

        $phone_number = isset( $_POST['phone_number'] ) ? $_POST['phone_number'] : '';
        if( empty($phone_number) && houzez_option('register_mobile', 0) == 1 ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('Please enter your number', 'houzez-login-register') ) );
            wp_die();
        }

        if( $enable_password == 'yes' ){
            $user_pass         = trim( sanitize_text_field(wp_kses( $_POST['register_pass'] ,$allowed_html) ) );
            $user_pass_retype  = trim( sanitize_text_field(wp_kses( $_POST['register_pass_retype'] ,$allowed_html) ) );

            if ($user_pass == '' || $user_pass_retype == '' ) {
                echo json_encode( array( 'success' => false, 'msg' => esc_html__('One of the password field is empty!', 'houzez-login-register') ) );
                wp_die();
            }

            if ($user_pass !== $user_pass_retype ){
                echo json_encode( array( 'success' => false, 'msg' => esc_html__('Passwords do not match', 'houzez-login-register') ) );
                wp_die();
            }
        }

        $term_condition = ( $term_condition == 'on') ? true : false;

        if( !$term_condition ) {
            echo json_encode( array( 'success' => false, 'msg' => esc_html__('You need to agree with terms & conditions.', 'houzez-login-register') ) );
            wp_die();
        }

        do_action('houzez_before_register');

        houzez_google_recaptcha_callback();

        if($enable_password == 'yes' ) {
            $user_password = $user_pass;
        } else {
            $user_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        }

        $user_id = wp_create_user( $usermane, $user_password, $email );

        if ( is_wp_error($user_id) ) {
            echo json_encode( array( 'success' => false, 'msg' => $user_id ) );
            wp_die();
        } else {

            wp_update_user( array( 'ID' => $user_id, 'role' => $user_role ) );

            if( $enable_password =='yes' ) {
                echo json_encode( array( 'success' => true, 'msg' => esc_html__('Your account was created and you can login now!', 'houzez-login-register') ) );
            } else {
                echo json_encode( array( 'success' => true, 'msg' => esc_html__('An email with the generated password was sent!', 'houzez-login-register') ) );
            }

            update_user_meta( $user_id, 'first_name', $firstname);
            update_user_meta( $user_id, 'last_name', $lastname);


            if( $user_role == 'houzez_agency' ) {
                update_user_meta( $user_id, 'fave_author_phone', $phone_number);
            } else {
                update_user_meta( $user_id, 'fave_author_mobile', $phone_number);
            }
 
            $user_as_agent = houzez_option('user_as_agent');

            if( $user_as_agent == 'yes' ) {

                if( !empty($firstname) && !empty($lastname) ) {
                    $usermane = $firstname.' '.$lastname;
                }

                if ($user_role == 'houzez_agent' || $user_role == 'author') {
                    houzez_register_as_agent($usermane, $email, $user_id, $phone_number);

                } else if ($user_role == 'houzez_agency') {
                    houzez_register_as_agency($usermane, $email, $user_id, $phone_number);
                }
            }
            houzez_wp_new_user_notification( $user_id, $user_password, $phone_number );

            do_action('houzez_after_register', $user_id);
        }
        wp_die();

    }
}
?>