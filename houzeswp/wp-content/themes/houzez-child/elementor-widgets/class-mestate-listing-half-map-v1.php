<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Listing_Half_Map_V1 extends \Elementor\Widget_Base { 

    public function get_name() {
        return 'mestate_listing_half_map_v1';
    }

    public function get_title() {
        return __( 'MEstate Listing Half Map V1', 'houzez' );
    }

    public function get_icon() {
        return 'houzez-element-icon eicon-posts-grid';
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

        $prop_status = array();
        houzez_get_terms_array( 'property_status', $prop_status );

        $this->add_control(
            'status_data',
            [
                'label'     => esc_html__( 'Select Status', 'houzez' ),
                'type'      => \Elementor\Controls_Manager::SELECT2,
                'options'   => $prop_status,
                'description' => '',
                'multiple' => false,
                'label_block' => true,
                'default' => 'new-projects',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $status_data = $settings['status_data'] ?? 'new-projects';

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

        set_query_var('settings', $settings);

        if($status_data == 'new-projects') {
            get_template_part('elementor-widgets/template-parts/mestate-new-project-half-map-v1');     
        }
        else {
            get_template_part('elementor-widgets/template-parts/mestate-half-map-v1');     
        }

    }
    
}