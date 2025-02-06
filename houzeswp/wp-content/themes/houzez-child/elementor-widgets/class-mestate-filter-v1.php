<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Filter_V1 extends \Elementor\Widget_Base { 

    public function get_name() {
        return 'mestate_filter_v1';
    }

    public function get_title() {
        return __( 'MEstate Listing Filter V1', 'houzez' );
    }

    public function get_icon() {
        return 'houzez-element-icon eicon-site-search';
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
            'sticky_filter',
            [
                'label' => __( 'Sticky Filter', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-hero-filter-top-v1');    

    }
    
}