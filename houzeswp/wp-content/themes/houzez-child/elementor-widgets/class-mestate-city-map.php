<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

use \Elementor\Plugin;

class MEstate_City_Map extends \Elementor\Widget_Base {   

    public function get_name() {
        return 'mestate_city_map';
    }

    public function get_title() {
        return __( 'MEstate City Map', 'houzez' );
    }

    public function get_icon() {
        return 'eicon-map-pin';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'houzez' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_heading',
            [
                'label' => __( 'Section Heading', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Discover What’s Trending Near You',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'section_heading_description',
            [
                'label' => __( 'Section Heading Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Stay ahead of the market with a quick glance at the most popular searches in your favorite regions. Explore top listings and find out what properties are capturing attention in your area.',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'buy_section',
            [
                'label' => __( 'Buy', 'houzez' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Parent Repeater (Main List)
        $repeater = new \Elementor\Repeater();
    
        // Add text control for parent list item
        $repeater->add_control(
            'city_slug',
            [
                'label' => __( 'City', 'houzez' ), 
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_city_options(), // Fetch options dynamically
                'multiple' => false, // Change to true if you want to allow multiple types
                'label_block' => true,
                'description' => __( 'Select a city.', 'houzez' ),
            ]
        );

        $repeater->add_control(
            'highest_price', 
            [
                'label' => __( 'Highest Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'average_price', 
            [
                'label' => __( 'Average Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'lowest_price', 
            [
                'label' => __( 'Lowest Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );
    
        // Add repeater control to the widget
        $this->add_control(
            'city_list',
            [
                'label' => __('City List', 'houzez'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ city_slug }}}', // Display parent item in UI
            ]
        );
        

        $this->end_controls_section();

        $this->start_controls_section(
            'rent_section',
            [
                'label' => __( 'Rent', 'houzez' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Parent Repeater (Main List)
        $city_repeater_rent = new \Elementor\Repeater();
    
        // Add text control for parent list item
        $city_repeater_rent->add_control(
            'city_slug_rent',
            [
                'label' => __( 'City', 'houzez' ), 
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_city_options(), // Fetch options dynamically
                'multiple' => false, // Change to true if you want to allow multiple types
                'label_block' => true,
                'description' => __( 'Select a city.', 'houzez' ),
            ]
        );

        $city_repeater_rent->add_control(
            'highest_price_rent', 
            [
                'label' => __( 'Highest Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $city_repeater_rent->add_control(
            'average_price_rent', 
            [
                'label' => __( 'Average Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $city_repeater_rent->add_control(
            'lowest_price_rent', 
            [
                'label' => __( 'Lowest Price', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );
    
        // Add repeater control to the widget
        $this->add_control(
            'city_list_rent',
            [
                'label' => __('City List', 'houzez'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $city_repeater_rent->get_controls(),
                'title_field' => '{{{ city_slug_rent }}}', // Display parent item in UI
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $city_list = $settings['city_list'];
        $city_list_rent = $settings['city_list_rent'];

        foreach ($city_list as $key => $city) {
            $properties_data = $this->get_properties_data($city['city_slug'], 'for-sale');
            $city_list[$key]['properties_data'] = $properties_data;
        }

        foreach ($city_list_rent as $key => $city) {
            $properties_data_rent = $this->get_properties_data($city['city_slug_rent'], 'for-rent');
            $city_list_rent[$key]['properties_data'] = $properties_data_rent;
        }

        $map_options = array();

        $map_cluster = houzez_option( 'map_cluster', false, 'url' );
        if($map_cluster != '') {
            $map_options['clusterIcon'] = $map_cluster;
        } else {
            $map_options['clusterIcon'] = get_template_directory_uri() . '/img/map/cluster-icon.png';
        }
        $map_options['zoomControl'] = 'yes';
        $map_options['mapCluster'] = 'yes';
        $map_options['markerPricePins'] = houzez_option('markerPricePins');
        $map_options['marker_spiderfier'] = houzez_option('marker_spiderfier');
        $map_options[ 'link_target' ] = houzez_option('listing_link_target', '_self');
        $map_options['closeIcon'] = get_template_directory_uri() . '/img/map/close.png';
        $map_options['infoWindowPlac'] = get_template_directory_uri() . '/img/pixel.gif';
        $map_options['mapbox_api_key'] = '';

        $settings['map_options'] = $map_options;
        $settings['city_list'] = $city_list;
        $settings['city_list_rent'] = $city_list_rent;

        set_query_var('settings', $settings);

        // echo '<pre>';
        // print_r($settings);
        // echo '</pre>';

        get_template_part('elementor-widgets/template-parts/mestate-city-map-v1');
    }
    

    private function get_city_options() {
        $terms = get_terms([
            'taxonomy' => 'property_city',
            'hide_empty' => true,
        ]);
        
        $options = [];
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
        }
    
        return $options;
    }

    private function get_properties_data($city_slug, $property_status) {
        $atts = array(
            'property_city' => $city_slug,
            'property_status' => $property_status,
            'posts_limit' => 20,
        );
        $prop_map_query = houzez_data_source::get_wp_query($atts);

        $map_options = array();
        $properties_data = array();
        
        if ( $prop_map_query->have_posts() ) :
            while ( $prop_map_query->have_posts() ) : $prop_map_query->the_post();

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

                $properties_data[] = $property_array_temp;
            endwhile;
        endif;
        wp_reset_postdata();

        return $properties_data;
    }
    
}