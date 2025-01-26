<?php
global $houzez_opt_name;
/* **********************************************************************
 * Footer
 * **********************************************************************/
Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Footer', 'houzez' ),
    'id'     => 'footer',
    'desc'   => '',
    'icon'             => 'el-icon-website el-icon-small',
    'fields'        => array(
        array(
            'id'       => 'footer_cols',
            'type'     => 'image_select',
            'title'    => esc_html__('Footer Layout', 'houzez'),
            'subtitle' => '',
            'desc'     => esc_html__('Select the footer layout', 'houzez'),
            'options'  => array(
                'one_col' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '1col.png'
                ),
                'two_col' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '2cl.png'
                ),
                'three_cols_middle' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '3cm.png'
                ),
                'three_cols' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '3cl.png'
                ),
                'four_cols' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '4cl.png'
                ),
                'v6' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . '3cr.png'
                ),
                'CTA' => array(
                    'alt' => 'CTA',
                    'img' => get_stylesheet_directory_uri() . '/assets/images/CTA.png'
                ),
            ),
            'default'  => 'three_cols'
        ),

        array(
            'id'       => 'ftb_section-start',
            'type'     => 'section',
            'title'    => esc_html__( 'Footer Bottom', 'houzez' ),
            'subtitle' => '',
            'indent'   => true,
        ),

        array(
            'id'       => 'ft-bottom',
            'type'     => 'select',
            'title'    => esc_html__('Version', 'houzez'),
            'desc' => esc_html__('Select the bottom version', 'houzez'),
            //'desc'     => '',
            'options'  => array(
                'v1'   => esc_html__( 'Version 1', 'houzez' ),
                'v2'   => esc_html__( 'Version 2', 'houzez' ),
                'v3'   => esc_html__( 'Version 3', 'houzez' ),
                'v4'   => esc_html__( 'Version 4', 'houzez' ),
                'v11'   => esc_html__( 'Version MEstate', 'houzez' )
            ),
            'default'  => 'v1',
        ),

        array(
            'id'        => 'footer_logo',
            'url'       => true,
            'type'      => 'media',
            'title'     => esc_html__( 'Logo', 'houzez' ),
            'read-only' => false,
            'default'   => array( 'url' => HOUZEZ_IMAGE .'logo-houzez-white.png' ),
            'desc'  => esc_html__( 'Upload the logo.', 'houzez' ),
        ),
        array(
            'id'       => 'footer_description',
            'type'     => 'textarea',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Description', 'houzez' ),
            'desc'     => esc_html__( 'Enter the description text', 'houzez' ),
            'default'  => 'Stay ahead of the market'
        ),
        array(
            'id'       => 'copy_rights',
            'type'     => 'text',
            'title'    => esc_html__( 'Copyright', 'houzez' ),
            'desc'     => esc_html__( 'Enter the copyright text', 'houzez' ),
            'default'  => 'Houzez - All rights reserved'
        ),
        array(
            'id'       => 'mobile_app',
            'type'     => 'switch',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Mobile App', 'houzez' ),
            'desc'     => esc_html__( 'Enable or disable mobile app links', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'mobile_app_text',
            'type'     => 'text',
            'required' => array( 'mobile_app', '=', '1' ),
            'title'    => esc_html__( 'Mobile App Text', 'houzez' ),
            'desc'     => esc_html__( 'Enter the mobile app text', 'houzez' ),
            'default'  => 'Discover properties on the go!'
        ),
        array(
            'id'        => 'google_app_logo',
            'url'       => true,
            'type'      => 'media',
            'title'     => esc_html__( 'Google App Logo', 'houzez' ),
            'read-only' => false,
            'desc'  => esc_html__( 'Upload the google app logo.', 'houzez' ),
        ),
        array(
            'id'       => 'google_app_url',
            'type'     => 'text',
            'required' => array( 'mobile_app', '=', '1' ),
            'title'    => esc_html__( 'Google App URL', 'houzez' ),
            'desc' => esc_html__( 'Enter the url of google app', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'        => 'ios_app_logo',
            'url'       => true,
            'type'      => 'media',
            'title'     => esc_html__( 'iOS App Logo', 'houzez' ),
            'read-only' => false,
            'desc'  => esc_html__( 'Upload the ios app logo.', 'houzez' ),
        ),
        array(
            'id'       => 'ios_app_url',
            'type'     => 'text',
            'required' => array( 'mobile_app', '=', '1' ),
            'title'    => esc_html__( 'iOS App URL', 'houzez' ),
            'desc' => esc_html__( 'Enter the url of ios app', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'       => 'footer_contact_text',
            'type'     => 'text',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Contact Text', 'houzez' ),
            'desc'     => esc_html__( 'Enter the contact text', 'houzez' ),
            'default'  => ''
        ),
        array(
            'id'       => 'footer_contact_phone',
            'type'     => 'text',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Contact Phone', 'houzez' ),
            'desc'     => esc_html__( 'Enter the phone text', 'houzez' ),
            'default'  => ''
        ),
        array(
            'id'       => 'footer_contact_address',
            'type'     => 'textarea',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Address', 'houzez' ),
            'desc'     => esc_html__( 'Enter the address text', 'houzez' ),
            'default'  => ''
        ),
        array(
            'id'       => 'footer_contact_email',
            'type'     => 'text',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Email', 'houzez' ),
            'desc'     => esc_html__( 'Enter the email text', 'houzez' ),
            'default'  => ''
        ),
        array(
            'id'       => 'footer_contact_time',
            'type'     => 'textarea',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'Office Time', 'houzez' ),
            'desc'     => esc_html__( 'Enter the time text', 'houzez' ),
            'default'  => ''
        ),
        array(
            'id'       => 'social-footer',
            'type'     => 'switch',
            'title'    => esc_html__( 'Footer social media', 'houzez' ),
            'desc'     => esc_html__( 'Enable or disable social media links', 'houzez' ),
            'subtitle' => '',
            'default'  => 0,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'fs-facebook',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Facebook', 'houzez' ),
            'desc' => esc_html__( 'Enter Facebook profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-twitter',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'X', 'houzez' ),
            'desc' => esc_html__( 'Enter X profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-googleplus',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Google Plus', 'houzez' ),
            'desc' => esc_html__( 'Enter Google Plus profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-linkedin',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Linkedin', 'houzez' ),
            'desc' => esc_html__( 'Enter Linkedin profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-instagram',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Instagram', 'houzez' ),
            'desc' => esc_html__( 'Enter Instagram profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-pinterest',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Pinterest', 'houzez' ),
            'desc' => esc_html__( 'Enter Pinterest profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-yelp',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Yelp', 'houzez' ),
            'desc' => esc_html__( 'Enter Yelp profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-behance',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Behance', 'houzez' ),
            'desc' => esc_html__( 'Enter Behance profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),
        array(
            'id'       => 'fs-youtube',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Youtube', 'houzez' ),
            'desc' => esc_html__( 'Enter Youtube profile url', 'houzez' ),
            //'desc'     => '',
            'default'  => false,
        ),

        array(
            'id'       => 'fs-whatsapp',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'WhatsApp', 'houzez' ),
            'desc' => esc_html__( 'Enter the WhatsApp number', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'       => 'fs-tiktok',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'TikTok', 'houzez' ),
            'desc' => esc_html__( 'Enter the tiktok profile URL', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'       => 'fs-telegram',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Telegram', 'houzez' ),
            'desc' => esc_html__( 'Enter the Telegram username', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'       => 'fs-skype',
            'type'     => 'text',
            'required' => array( 'social-footer', '=', '1' ),
            'title'    => esc_html__( 'Skype', 'houzez' ),
            'desc' => esc_html__( 'Enter the Skype username', 'houzez' ),
            'default'  => false,
        ),
        array(
            'id'       => 'cta_section',
            'type'     => 'switch',
            'required' => array( 'ft-bottom', '=', 'v11' ),
            'title'    => esc_html__( 'CTA Section', 'houzez' ),
            'desc'     => esc_html__( 'Enable or disable cta section', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'cta_title',
            'type'     => 'textarea',
            'required' => array( 'cta_section', '=', '1' ),
            'title'    => esc_html__( 'CTA Title', 'houzez' ),
            'desc' => esc_html__( 'Enter the CTA title', 'houzez' ),
            'default'  => 'Ready to get started?',
        ),
        array(
            'id'       => 'cta_description',
            'type'     => 'textarea',
            'required' => array( 'cta_section', '=', '1' ),
            'title'    => esc_html__( 'CTA Description', 'houzez' ),
            'desc' => esc_html__( 'Enter the CTA description', 'houzez' ),
            'default'  => 'We are ready to help you find your dream home.',
        ),
        array(
            'id'        => 'cta_image',
            'url'       => true,
            'type'      => 'media',
            'required' => array( 'cta_section', '=', '1' ),
            'title'     => esc_html__( 'CTA Image', 'houzez' ),
            'read-only' => false,
            'desc'  => esc_html__( 'Upload the cta image.', 'houzez' ),
        ),
        array(
            'id'        => 'cta_image_mobile',
            'url'       => true,
            'type'      => 'media',
            'required' => array( 'cta_section', '=', '1' ),
            'title'     => esc_html__( 'CTA Image Mobile', 'houzez' ),
            'read-only' => false,
            'desc'  => esc_html__( 'Upload the cta image mobile.', 'houzez' ),
        ),

        array(
            'id'     => 'ftb_section_end',
            'type'   => 'section',
            'indent' => false,
        )

    ),
));