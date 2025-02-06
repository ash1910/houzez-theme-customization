<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Listing_Search_Results_V1 extends \Elementor\Widget_Base { 

    public function get_name() {
        return 'mestate_listing_search_results_v1';
    }

    public function get_title() {
        return __( 'MEstate Listing Search Results V1', 'houzez' );
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

        $this->add_control(
            'sidebar_image',
            [
                'label' => __( 'Sidebar Image', 'houzez' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'sidebar_download_url',
            [
                'label' => __( 'Sidebar Download URL', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::URL,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $status_data = $settings['status_data'] ?? 'new-projects';

        set_query_var('settings', $settings);

        if($status_data == 'new-projects') {
            get_template_part('elementor-widgets/template-parts/mestate-new-project-v1');     
        }
        else {
            get_template_part('elementor-widgets/template-parts/mestate-search-results-v1');     
        }

    }
    
}