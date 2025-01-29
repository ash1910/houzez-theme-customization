<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Offer extends \Elementor\Widget_Base {    

    public function get_name() {
        return 'mestate_offer';
    }

    public function get_title() {
        return __( 'MEstate Offer', 'houzez' );
    }

    public function get_icon() {
        return 'eicon-post-grid';
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
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Be the First to Know – Exclusive Deals Await!',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Don’t miss out on our handpicked exclusive offers and promising investment projects. Join our WhatsApp channel or mailing list today and stay updated with the latest opportunities!',
            ]
        );

        $this->add_control(
            'whatsapp_button_text',
            [
                'label' => __( 'WhatsApp Button Text', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Join WhatsApp',
            ]
        );

        $this->add_control(
            'whatsapp_button_link',
            [
                'label' => __( 'WhatsApp Button Link', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'https://example.com',
            ]
        );

        $this->add_control(
            'mailing_list_button_text',
            [
                'label' => __( 'Mailing List Button Text', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Subscribe to Mailing List',
            ]
        );

        $this->add_control(
            'mailing_list_button_link',
            [
                'label' => __( 'Mailing List Button Link', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'https://example.com',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'houzez' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-offer-v1');     

    }
    
}