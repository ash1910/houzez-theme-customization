<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Login extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mestate_login';
    }

    public function get_title() {
        return __( 'MEstate Login', 'houzez' );
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

        $this->add_control(
            'logo',
            [
                'label' => __( 'Logo', 'houzez' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'houzez' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<h2>welcome <span>back!</span></h2>',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __('Slider Slides', 'houzez'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'slide_image',
                        'label' => __('Slide Image', 'houzez'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => '',
                        ],
                    ],
                    [
                        'name' => 'slide_heading',
                        'label' => __('Heading', 'houzez'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Redefining Real Estate',
                    ],
                    [
                        'name' => 'slide_description',
                        'label' => __('Description', 'houzez'),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => 'From Dream Homes to Commercial Ventures',
                    ],
                ],
                'default' => [
                    [
                        'slide_heading' => 'Redefining Real Estate',
                        'slide_description' => 'From Dream Homes to Commercial Ventures',
                    ],
                ],
                'title_field' => '{{{ slide_heading }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-login-v1');     

    }
    
}