<?php
global $houzez_opt_name, $allowed_html_array;
Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Developers', 'houzez' ),
    'id'     => 'houzez-developers',
    'desc'   => '',
    'icon'   => 'el-icon-user el-icon-small',
    'fields'        => array(
        array(
            'id'       => 'developers-template-layout',
            'type'     => 'image_select',
            'title'    => esc_html__('Developers Layout', 'houzez'),
            'subtitle' => '',
            'desc'     => '',
            'options'  => array(
                'v1' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . 'all-agents-style-1.jpg'
                ),
                'v2' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . 'all-agents-style-2.jpg'
                ),
                'v3' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . 'all-agents-style-3.jpg'
                ),
            ),
            'default'  => 'v1',
        ),
        array(
            'id'       => 'num_of_developers',
            'type'     => 'text',
            'title'    => esc_html__( 'Number of Developers', 'houzez' ),
            'subtitle'    => esc_html__( 'Number of developers to display on the All Developers page template', 'houzez' ),
            'desc'    => esc_html__( 'Enter the number of developers', 'houzez' ),
            'default'  => '9'
        ),
        
        array(
            'id'        => 'houzez_developer_placeholder',
            'url'       => false,
            'type'      => 'media',
            'title'     => esc_html__( 'Placeholder', 'houzez' ),
            'default'   => array( 'url' => '' ),
            'subtitle'  => esc_html__( 'Upload default placeholder. Recommended Size 500 x 500 pixels', 'houzez' ),
            'desc'      => '',
        ), 

        array(
            'id'       => 'developer_header_search',
            'type'     => 'switch',
            'title'    => esc_html__( 'Developer Header Search', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_mobile',
            'type'     => 'switch',
            'title'    => esc_html__( 'Mobile', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_phone',
            'type'     => 'switch',
            'title'    => esc_html__( 'Office Phone', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),

         array(
            'id'       => 'developer_fax',
            'type'     => 'switch',
            'title'    => esc_html__( 'Fax', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),

         array(
            'id'       => 'developer_email',
            'type'     => 'switch',
            'title'    => esc_html__( 'Email', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),

         array(
            'id'       => 'developer_website',
            'type'     => 'switch',
            'title'    => esc_html__( 'Website', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),

         array(
            'id'       => 'developer_social',
            'type'     => 'switch',
            'title'    => esc_html__( 'Social', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
    ),
    
));

Redux::setSection( $houzez_opt_name, array(
    'title'  => esc_html__( 'Developer Detail Page', 'houzez' ),
    'id'     => 'developer-detail-page',
    'desc'   => '',
    'subsection' => true,
    'fields' => array(
        array(
            'id'       => 'developer-detail-layout',
            'type'     => 'image_select',
            'title'    => esc_html__('Single Developer Layout', 'houzez'),
            'subtitle' => '',
            'desc'     => '',
            'options'  => array(
                'v1' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . 'agent-detail-page-style-1.jpg'
                ),
                'v2' => array(
                    'alt' => '',
                    'img' => HOUZEZ_IMAGE . 'agent-detail-page-style-2.jpg'
                ),
            ),
            'default'  => 'v1',
        ),
        array(
            'id'       => 'developer_tabs',
            'type'     => 'switch',
            'title'    => esc_html__( 'Tabs', 'houzez' ),
            'subtitle' => esc_html__('Property status tabs displayed in the developer detail page', 'houzez'),
            'desc' => esc_html__( 'Enable or disable the tabs on developer detail page', 'houzez' ),
            'default'  => 0,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_detail_tab_1',
            'type'     => 'select',
            'title'    => esc_html__('Tab 1', 'houzez'),
            'subtitle' => esc_html__('Property status tab in the developer detail page', 'houzez'),
            'desc'     => esc_html__('Select the status', 'houzez'),
            'data'     => 'terms',
            'required' => array('developer_tabs', '=', '1'),
            'args'        =>  array('taxonomy'=>'property_status'),
            'default' => ''
        ),
        array(
            'id'       => 'developer_detail_tab_2',
            'type'     => 'select',
            'title'    => esc_html__('Tab 2', 'houzez'),
            'subtitle' => esc_html__('Property status tab in the developer detail page', 'houzez'),
            'desc'     => esc_html__('Select the status', 'houzez'),
            'required' => array('developer_tabs', '=', '1'),
            'data'        => 'terms',
            'args'        =>  array('taxonomy'=>'property_status'),
            'default' => ''
        ),

        array(
            'id'       => 'developer_listings_layout',
            'type'     => 'select',
            'title'    => __('Listings Layout', 'houzez'),
            'subtitle' => __('Select the listings layout for the developer detail page', 'houzez'),
            'desc'     => esc_html__('Select the layout', 'houzez'),
            'options'  => array(
                'Listings Version 1' => array(
                    'list-view-v1' => 'List View',
                    'grid-view-v1' => 'Grid View',
                ),
                'Listings Version 2' => array(
                    'list-view-v2' => 'List View',
                    'grid-view-v2' => 'Grid View',
                ),

                'Listings Version 3' => array(
                    'grid-view-v3' => 'Grid View',
                ),

                'Listings Version 4' => array(
                    'grid-view-v4' => 'Grid View',
                ),

                'Listings Version 5' => array(
                    'list-view-v5' => 'List View',
                    'grid-view-v5' => 'Grid View',
                ),

                'Listings Version 6' => array(
                    'grid-view-v6' => 'Grid View',
                ),

                'Listings Version 7' => array(
                    'list-view-v7' => 'List View',
                    'grid-view-v7' => 'Grid View',
                ),
            ),
            'default' => 'list-view-v1'
        ),
        array(
            'id'       => 'num_of_developer_listings',
            'type'     => 'text',
            'title'    => esc_html__( 'Number of Listings', 'houzez' ),
            'subtitle'    => esc_html__( 'Number of listings to display on the developer detail page', 'houzez' ),
            'desc'    => esc_html__( 'Enter the number of listings', 'houzez' ),
            'default'  => '10'
        ),
        array(
            'id'       => 'developer_listings_order',
            'type'     => 'select',
            'title'    => __('Default Order', 'houzez'),
            'subtitle' => __('Listings order on the developer detail page', 'houzez'),
            'desc' => __('Select the listings order.', 'houzez'),
            'options'  => array(
                'default' => esc_html__( 'Default', 'houzez' ),
                'a_title' => esc_html__( 'Title - ASC', 'houzez' ),
                'd_title' => esc_html__( 'Title - DESC', 'houzez' ),
                'd_date' => esc_html__( 'Date New to Old', 'houzez' ),
                'a_date' => esc_html__( 'Date Old to New', 'houzez' ),
                'd_price' => esc_html__( 'Price (High to Low)', 'houzez' ),
                'a_price' => esc_html__( 'Price (Low to High)', 'houzez' ),
                'featured_first' => esc_html__( 'Show Featured Listings on Top', 'houzez' ),
            ),
            'default' => 'default'
        ),
        array(
            'id'       => 'developer_stats',
            'type'     => 'switch',
            'title'    => esc_html__( 'Stats', 'houzez' ),
            'subtitle' => esc_html__('Enable or disable the stats on developer detail page', 'houzez'),
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_review',
            'type'     => 'switch',
            'title'    => esc_html__( 'Review & Rating', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_listings',
            'type'     => 'switch',
            'title'    => esc_html__( 'Listings', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_bio',
            'type'     => 'switch',
            'title'    => esc_html__( 'About Developer', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_sidebar',
            'type'     => 'switch',
            'title'    => esc_html__( 'Developer Sidebar', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
        array(
            'id'       => 'developer_sidebar_map',
            'type'     => 'switch',
            'title'    => esc_html__( 'Developer Map', 'houzez' ),
            'subtitle' => '',
            'default'  => 1,
            'on'       => 'Enabled',
            'off'      => 'Disabled',
        ),
    )
));