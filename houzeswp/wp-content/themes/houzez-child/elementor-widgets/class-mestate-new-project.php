<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_New_Project extends \Elementor\Widget_Base { 

    public function get_name() {
        return 'mestate_new_project';
    }

    public function get_title() {
        return __( 'MEstate New Project', 'houzez' );
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-hero-filter-top-v1'); 

        get_template_part('elementor-widgets/template-parts/mestate-new-project-v1');     

    }
    
}