<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Houzez_Dev_Post_Type_Developer {
    /**
     * Initialize custom post type
     *
     * @access public
     * @return void
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'definition' ) );
        add_action( 'init', array( __CLASS__, 'developer_category' ) );
        add_action( 'init', array( __CLASS__, 'developer_city' ) );
        add_action( 'save_post_houzez_developer', array( __CLASS__, 'save_developer_meta' ), 10, 3 );
        add_filter( 'manage_edit-houzez_developer_columns', array( __CLASS__, 'custom_columns' ) );
        add_action( 'manage_houzez_developer_posts_custom_column', array( __CLASS__, 'custom_columns_manage' ) );
        //add_filter( 'rwmb_meta_boxes', 'houzez_developer_metaboxes' );
        add_filter( 'rwmb_meta_boxes', array( __CLASS__, 'houzez_developer_metaboxes' ) );
    }

    /**
     * Custom post type definition
     *
     * @access public
     * @return void
     */
    public static function definition() {
        $labels = array(
            'name' => __( 'Developers','houzez-theme-developer'),
            'singular_name' => __( 'Developer','houzez-theme-developer' ),
            'add_new' => __('Add New','houzez-theme-developer'),
            'add_new_item' => __('Add New Developer','houzez-theme-developer'),
            'edit_item' => __('Edit Developer','houzez-theme-developer'),
            'new_item' => __('New Developer','houzez-theme-developer'),
            'view_item' => __('View Developer','houzez-theme-developer'),
            'search_items' => __('Search Developer','houzez-theme-developer'),
            'not_found' =>  __('No Developer found','houzez-theme-developer'),
            'not_found_in_trash' => __('No Developer found in Trash','houzez-theme-developer'),
            'parent_item_colon' => ''
        );

        $labels = apply_filters( 'houzez_post_type_developer_labels', $labels );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => true,
            'show_ui' => true,
            'query_var' => true,
            'has_archive' => true,
            'capability_type' => 'post',
            'hierarchical' => true,
            'can_export' => true,
            //'capabilities'    => self::houzez_get_developer_capabilities(),
            'menu_icon' => 'dashicons-admin-users',
            'menu_position' => 15,
            'supports' => array('title','editor', 'thumbnail', 'page-attributes','revisions'),
            'show_in_rest'       => true,
            'rest_base'          => 'developers',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'rewrite' => array( 'slug' => 'developer' ), 
            //'map_meta_cap' => true
        );

        $args = apply_filters( 'houzez_post_type_developer_args', $args );

        register_post_type('houzez_developer',$args);
    }

    public static function developer_category() {

        $labels = array(
            'name'              => __('Categories','houzez-theme-developer'),
            'add_new_item'      => __('Add New Category','houzez-theme-developer'),
            'new_item_name'     => __('New Category','houzez-theme-developer')
        );
        $labels = apply_filters( 'houzez_developer_category_labels', $labels );

        register_taxonomy('developer_category', 'houzez_developer', array(
                'labels' => $labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'developer_category',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => 'developer_category' )
            )
        );
    }

    public static function developer_city() {


        $labels = array(
            'name'              => __('Cities','houzez-theme-developer'),
            'add_new_item'      => __('Add New City','houzez-theme-developer'),
            'new_item_name'     => __('New City','houzez-theme-developer')
        );

        $labels = apply_filters( 'houzez_developer_city_labels', $labels );

        register_taxonomy('developer_city', 'houzez_developer', array(
                'labels' => $labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'developer_city',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => 'developer_city' )
            )
        );
    }

    /**
     * Custom admin columns for post type
     *
     * @access public
     * @return array
     */
    public static function custom_columns() {
        $fields = array(
            'cb' 				=> '<input type="checkbox" />',
            'developer_id' 			=> esc_html__( 'Developer ID', 'houzez-theme-developer' ),
            'title' 			=> esc_html__( 'Developer Name', 'houzez-theme-developer' ),
            'developer_thumbnail' 		=> esc_html__( 'Picture', 'houzez-theme-developer' ),
            'category' 		    => esc_html__( 'Category', 'houzez-theme-developer' ),
            'email'      		=> esc_html__( 'E-mail', 'houzez-theme-developer' ),
            'web'      		    => esc_html__( 'Web', 'houzez-theme-developer' ),
            'mobile'      		=> esc_html__( 'Mobile', 'houzez-theme-developer' ),
        );

        return $fields;
    }

    public static function houzez_get_developer_capabilities() {

        $caps = array(
            // meta caps (don't assign these to roles)
            'edit_post'              => 'edit_developer',
            'read_post'              => 'read_developer',
            'delete_post'            => 'delete_developer',

            // primitive/meta caps
            'create_posts'           => 'create_developers',

            // primitive caps used outside of map_meta_cap()
            'edit_posts'             => 'edit_developers',
            'edit_others_posts'      => 'edit_others_developers',
            'publish_posts'          => 'publish_developers',
            'read_private_posts'     => 'read_private_developers',

            // primitive caps used inside of map_meta_cap()
            'read'                   => 'read',
            'delete_posts'           => 'delete_developers',
            'delete_private_posts'   => 'delete_private_developers',
            'delete_published_posts' => 'delete_published_developers',
            'delete_others_posts'    => 'delete_others_developers',
            'edit_private_posts'     => 'edit_private_developers',
            'edit_published_posts'   => 'edit_published_developers'
        );

        return apply_filters( 'houzez_get_developer_capabilities', $caps );
    }

    /**
     * Custom admin columns implementation
     *
     * @access public
     * @param string $column
     * @return array
     */
    public static function custom_columns_manage( $column ) {
        global $post;
        switch ( $column ) {
            case 'developer_thumbnail':
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'thumbnail', array(
                        'class'     => 'attachment-thumbnail attachment-thumbnail-small',
                    ) );
                } else {
                    echo '-';
                }
                break;
            case 'developer_id':
                echo $post->ID;
                break;
            case 'category':
                echo Houzez_Dev::admin_taxonomy_terms ( $post->ID, 'developer_category', 'houzez_developer' );
                break;
            case 'email':
                $email = get_post_meta( get_the_ID(),  'fave_developer_email', true );

                if ( ! empty( $email ) ) {
                    echo esc_attr( $email );
                } else {
                    echo '-';
                }
                break;
            case 'web':
                $web = get_post_meta( get_the_ID(), 'fave_developer_website', true );

                if ( ! empty( $web ) ) {
                    echo '<a target="_blank" href="'.esc_url( $web ).'">'.esc_url( $web ).'</a>';
                } else {
                    echo '-';
                }
                break;
            case 'mobile':
                $phone = get_post_meta( get_the_ID(), 'fave_developer_mobile', true );

                if ( ! empty( $phone ) ) {
                    echo esc_attr( $phone );
                } else {
                    echo '-';
                }
                break;

        }
    }

    /**
     * Update developer user associated info when developer updated
     *
     * @access public
     * @return
     */
    public static function save_developer_meta($post_id, $post, $update) {

        if (!is_object($post) || !isset($post->post_type)) {
            return;
        }
        
        $slug = 'houzez_developer';
        // If this isn't a 'book' post, don't update it.
        if ($slug != $post->post_type) {
            return;
        }

        if (!isset($_POST['fave_developer_email'])) {
            return;
        }

        $user_as_developer = houzez_option('user_as_developer');

        $allowed_html = array();
        $user_id = get_post_meta( $post_id, 'houzez_user_meta_id', true );
        $email = wp_kses($_POST['fave_developer_email'], $allowed_html);
        $developer_agency = wp_kses($_POST['fave_developer_agencies'], $allowed_html);
        $fave_developer_des = wp_kses($_POST['fave_developer_des'], $allowed_html);
        $fave_developer_position = wp_kses($_POST['fave_developer_position'], $allowed_html);
        $fave_developer_company = wp_kses($_POST['fave_developer_company'], $allowed_html);
        $fave_developer_license = wp_kses($_POST['fave_developer_license'], $allowed_html);
        $fave_developer_tax_no = wp_kses($_POST['fave_developer_tax_no'], $allowed_html);
        $fave_developer_mobile = wp_kses($_POST['fave_developer_mobile'], $allowed_html);
        $fave_developer_whatsapp = wp_kses($_POST['fave_developer_whatsapp'], $allowed_html);
        $fave_developer_telegram = wp_kses($_POST['fave_developer_telegram'], $allowed_html);
        $fave_developer_line_id = wp_kses($_POST['fave_developer_line_id'], $allowed_html);
        $fave_developer_office_num = wp_kses($_POST['fave_developer_office_num'], $allowed_html);
        $fave_developer_fax = wp_kses($_POST['fave_developer_fax'], $allowed_html);
        $fave_developer_skype = wp_kses($_POST['fave_developer_skype'], $allowed_html);
        $fave_developer_website = wp_kses($_POST['fave_developer_website'], $allowed_html);
        $fave_developer_facebook = wp_kses($_POST['fave_developer_facebook'], $allowed_html);
        $fave_developer_twitter = wp_kses($_POST['fave_developer_twitter'], $allowed_html);
        $fave_developer_linkedin = wp_kses($_POST['fave_developer_linkedin'], $allowed_html);
        $fave_developer_googleplus = wp_kses($_POST['fave_developer_googleplus'], $allowed_html);
        $fave_developer_youtube = wp_kses($_POST['fave_developer_youtube'], $allowed_html);
        $fave_developer_instagram = wp_kses($_POST['fave_developer_instagram'], $allowed_html);
        $fave_developer_pinterest = wp_kses($_POST['fave_developer_pinterest'], $allowed_html);
        $fave_developer_vimeo = wp_kses($_POST['fave_developer_vimeo'], $allowed_html);
        $fave_developer_language = wp_kses($_POST['fave_developer_language'], $allowed_html);
        $fave_developer_address = wp_kses($_POST['fave_developer_address'], $allowed_html);
        $fave_developer_service_area = wp_kses($_POST['fave_developer_service_area'], $allowed_html);
        $fave_developer_specialties = wp_kses($_POST['fave_developer_specialties'], $allowed_html);
        $image_id = get_post_thumbnail_id($post_id);
        $full_img = wp_get_attachment_image_src($image_id, 'houzez-image350_350');

        update_user_meta( $user_id, 'aim', '/'.$full_img[0].'/') ;
        update_user_meta( $user_id, 'fave_author_phone' , $fave_developer_office_num) ;
        update_user_meta( $user_id, 'fave_author_language' , $fave_developer_language) ;
        update_user_meta( $user_id, 'fave_author_license' , $fave_developer_license) ;
        update_user_meta( $user_id, 'fave_author_tax_no' , $fave_developer_tax_no) ;
        update_user_meta( $user_id, 'fave_author_fax' , $fave_developer_fax) ;
        update_user_meta( $user_id, 'fave_author_mobile' , $fave_developer_mobile) ;
        update_user_meta( $user_id, 'fave_author_whatsapp' , $fave_developer_whatsapp) ;
        update_user_meta( $user_id, 'fave_author_telegram' , $fave_developer_telegram) ;
        update_user_meta( $user_id, 'fave_author_line_id' , $fave_developer_line_id) ;
        update_user_meta( $user_id, 'description' , $fave_developer_des) ;
        update_user_meta( $user_id, 'fave_author_skype' , $fave_developer_skype) ;
        update_user_meta( $user_id, 'fave_author_title', $fave_developer_position) ;

        update_user_meta( $user_id, 'fave_author_custom_picture', $full_img[0]) ;
        update_user_meta( $user_id, 'fave_author_facebook', $fave_developer_facebook) ;
        update_user_meta( $user_id, 'fave_author_twitter', $fave_developer_twitter) ;
        update_user_meta( $user_id, 'fave_author_linkedin', $fave_developer_linkedin) ;
        update_user_meta( $user_id, 'fave_author_vimeo', $fave_developer_vimeo) ;
        update_user_meta( $user_id, 'fave_author_googleplus', $fave_developer_googleplus) ;
        update_user_meta( $user_id, 'fave_author_youtube', $fave_developer_youtube) ;
        update_user_meta( $user_id, 'fave_author_pinterest', $fave_developer_pinterest) ;
        update_user_meta( $user_id, 'fave_author_instagram', $fave_developer_instagram) ;
        update_user_meta( $user_id, 'fave_author_address', $fave_developer_address);
        update_user_meta( $user_id, 'fave_author_service_areas', $fave_developer_service_area);
        update_user_meta( $user_id, 'fave_author_specialties', $fave_developer_specialties);
        update_user_meta( $user_id, 'url', $fave_developer_website) ;

        if( !empty($developer_agency)) {
            $fave_developer_company = get_the_title($developer_agency);
            update_user_meta( $user_id, 'fave_author_agency_id', $developer_agency);
        } else {
            update_user_meta( $user_id, 'fave_author_agency_id', '');
        }
        update_user_meta( $user_id, 'fave_author_company', $fave_developer_company) ;
        update_post_meta( $post_id, 'fave_developer_company', $fave_developer_company) ;

        $new_user_id = email_exists($email);
        if ($new_user_id) {

        } else {
            $args = array(
                'ID' => $user_id,
                'user_email' => $email
            );
            wp_update_user($args);
        }
    }

    public static function houzez_developer_metaboxes( $meta_boxes ) {
        $houzez_prefix = 'fave_';

        $developer_categories = array();
        $developer_cities = array();

        $agencies_2_array = array(-1 => houzez_option('cl_none', 'None'));
        $agencies_array = array('' => houzez_option('cl_none', 'None'));
        $agencies_posts = get_posts(array('post_type' => 'houzez_agency', 'posts_per_page' => -1));
        if (!empty($agencies_posts)) {
            foreach ($agencies_posts as $agency_post) {
                $agencies_array[$agency_post->ID] = $agency_post->post_title;
                $agencies_2_array[$agency_post->ID] = $agency_post->post_title;
            }
        }

        houzez_get_terms_array( 'developer_category', $developer_categories );
        houzez_get_terms_array( 'developer_city', $developer_cities );
        
        $meta_boxes[] = array(
            'id'        => 'fave_developers_template',
            'title'     => esc_html__('Developers Options', 'houzez'),
            'post_types'     => array( 'page' ),
            'context' => 'normal',
            'priority'   => 'high',
            'show'       => array(
                'template' => array(
                    'template/template-developers.php'
                ),
            ),

            'fields'    => array(
                array(
                    'name'      => esc_html__('Order By', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_orderby',
                    'type'      => 'select',
                    'options'   => array('none' => 'None', 'ID' => 'ID', 'title' => 'Title', 'date' => 'Date', 'rand' => 'Random', 'menu_order' => 'Menu Order' ),
                    'desc'      => '',
                    'columns' => 6,
                    'multiple' => false
                ),
                array(
                    'name'      => esc_html__('Order', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_order',
                    'type'      => 'select',
                    'options'   => array('ASC' => 'ASC', 'DESC' => 'DESC' ),
                    'desc'      => '',
                    'columns' => 6,
                    'multiple' => false
                ),
                //Filters
                array(
                    'name'      => esc_html__('Developer Category', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_category',
                    'type'      => 'select',
                    'options'   => $developer_categories,
                    'desc'      => '',
                    'columns' => 6,
                    'multiple' => true
                ),
                array(
                    'name'      => esc_html__('Developer City', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_city',
                    'type'      => 'select',
                    'options'   => $developer_cities,
                    'desc'      => '',
                    'columns' => 6,
                    'multiple' => true
                )
            )
        );

        $meta_boxes[] = array(
            'title'  => esc_html__( 'Developer Information', 'houzez' ),
            'post_types'  => array('houzez_developer'),
            'fields' => array(

                array(
                    'name'      => esc_html__('Short Description', 'houzez'),
                    'placeholder'      => esc_html__('Enter a short description', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_des',
                    'type'      => 'textarea',
                    'desc'      => '',
                    'columns'   => 12
                ),
                array(
                    'id' => "{$houzez_prefix}developer_email",
                    'name' => esc_html__( 'Email Address', 'houzez' ),
                    'placeholder'      => esc_html__('Enter the email address', 'houzez'),
                    'desc' => esc_html__('All messages related to the developer from the contact form on property details page, will be sent on this email address. ', 'houzez'),
                    'type' => 'email',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_visible",
                    'name' => esc_html__( 'Visibility Hidden', 'houzez' ),
                    'desc' => esc_html__('Hide developer to show on front-end', 'houzez'),
                    'type' => 'checkbox',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('Service Areas', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_service_area',
                    'placeholder'      => esc_html__('Enter your service area', 'houzez'),
                    'type'      => 'text',
                    'desc'      => '',
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('Specialties', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_specialties',
                    'placeholder'      => esc_html__('Enter your speciaties', 'houzez'),
                    'type'      => 'text',
                    'desc'      => '',
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('Position', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_position',
                    'type'      => 'text',
                    'placeholder'      => esc_html__('Enter your position. Example: CEO & Founder', 'houzez'),
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('Company Name', 'houzez'),
                    'placeholder'      => esc_html__('Enter the company name', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_company',
                    'type'      => 'text',
                    'desc'      => '',
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('License', 'houzez'),
                    'placeholder'      => esc_html__('Enter the license', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_license',
                    'type'      => 'text',
                    'desc'      => '',
                    'columns'   => 6
                ),
                array(
                    'name'      => esc_html__('Tax Number', 'houzez'),
                    'placeholder'      => esc_html__('Enter the tax number', 'houzez'),
                    'id'        => $houzez_prefix . 'developer_tax_no',
                    'type'      => 'text',
                    'desc'      => '',
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_mobile",
                    'name' => esc_html__("Mobile Number", 'houzez'),
                    'placeholder'      => esc_html__('Enter the mobile number', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_whatsapp",
                    'name' => esc_html__("WhatsApp", 'houzez'),
                    'placeholder'      => esc_html__('Enter the WhatsApp number with country code', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_line_id",
                    'name' => esc_html__("LINE ID", 'houzez'),
                    'placeholder'      => esc_html__('Enter the line id', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_telegram",
                    'name' => "Telegram Username",
                    'placeholder'      => esc_html__('Enter your telegram username','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_office_num",
                    'name' => esc_html__("Office Number", 'houzez'),
                    'placeholder'      => esc_html__('Enter the office number', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_fax",
                    'name' => esc_html__("Fax Number", 'houzez'),
                    'placeholder'      => esc_html__('Enter the fax number', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_language",
                    'name' => esc_html__( 'Language', 'houzez' ),
                    'placeholder'      => esc_html__('Enter the language you speak', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_address",
                    'name' => esc_html__( 'Address', 'houzez' ),
                    'placeholder'      => esc_html__('Enter your address', 'houzez'),
                    'desc' => esc_html__('It will be used for invoices ', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_skype",
                    'name' => "Skype",
                    'placeholder'      => esc_html__('Enter your Skype account', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_website",
                    'name' => esc_html__("Website", 'houzez'),
                    'placeholder'      => esc_html__('Enter your website URL', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_zillow",
                    'name' => esc_html__("Zillow", 'houzez'),
                    'placeholder'      => esc_html__('Enter your zillow URL', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_realtor_com",
                    'name' => esc_html__("Realtor.com", 'houzez'),
                    'placeholder'      => esc_html__('Enter your realtor.com URL', 'houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_facebook",
                    'name' => "Facebook URL",
                    'placeholder'      => esc_html__('Enter your Facebook profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_twitter",
                    'name' => "X URL",
                    'placeholder'      => esc_html__('Enter your X profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_linkedin",
                    'name' => "Linkedin URL",
                    'placeholder'      => esc_html__('Enter your Linkedin profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_googleplus",
                    'name' => "Google URL",
                    'placeholder'      => esc_html__('Enter your Google profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_youtube",
                    'name' => "Youtube URL",
                    'placeholder'      => esc_html__('Enter your Youtube profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_tiktok",
                    'name' => "Tiktok URL",
                    'placeholder'      => esc_html__('Enter your Tiktok profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_instagram",
                    'name' => "Instagram URL",
                    'placeholder'      => esc_html__('Enter your instagram profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_pinterest",
                    'name' => "Pinterest URL",
                    'placeholder'      => esc_html__('Enter your Pinterest profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                array(
                    'id' => "{$houzez_prefix}developer_vimeo",
                    'name' => "Vimeo URL",
                    'placeholder'      => esc_html__('Enter your Vimeo profile URL','houzez'),
                    'type' => 'text',
                    'std' => "",
                    'columns'   => 6
                ),
                
                array(
                    'name'    => esc_html__('Company Logo', 'houzez'),
                    'id'      => $houzez_prefix . 'developer_logo',
                    'type' => 'image_advanced',
                    'max_file_uploads' => 1,
                    'desc'      => '',
                    'columns'   => 12
                )
            ),
        );

        $meta_boxes[] = array(
            'title'  => esc_html__( 'Agencies', 'houzez' ),
            'post_types'  => array('houzez_developer'),
            'context' => 'side',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id'        => $houzez_prefix . 'developer_agencies',
                    'type'      => 'select',
                    'options'   => $agencies_array,
                    'desc'      => '',
                    'columns' => 12,
                    'multiple' => false
                ),
            )
        );

        return apply_filters('houzez_developer_meta', $meta_boxes);

    }

}
?>