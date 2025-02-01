<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Hero_Filter extends \Elementor\Widget_Base {   
      

    public function get_name() {
        return 'mestate_hero_filter';
    }

    public function get_title() {
        return __( 'MEstate Hero Filter', 'houzez' );
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

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $this->add_control(
            'filter_button_text',
            [
                'label' => __( 'Filter Button Text', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Get Started',
            ]
        );

        $prop_status = array();
        houzez_get_terms_array( 'property_status', $prop_status );

        $this->add_control(
            'status_data',
            [
                'label'     => esc_html__( 'Select Statuses', 'houzez' ),
                'type'      => \Elementor\Controls_Manager::SELECT2,
                'options'   => $prop_status,
                'description' => '',
                'multiple' => true,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-hero-filter-v1');     

    }
    
}