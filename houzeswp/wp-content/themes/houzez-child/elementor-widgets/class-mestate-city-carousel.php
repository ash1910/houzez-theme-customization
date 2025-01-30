<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_City_Carousel extends \Elementor\Widget_Base {    

    public function get_name() {
        return 'mestate_city_carousel';
    }

    public function get_title() {
        return __( 'MEstate City Carousel', 'houzez' );
    }

    public function get_icon() {
        return 'eicon-post-slider';
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
            'city_image',
            [
                'label' => __( 'City Image', 'houzez' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'city_description',
            [
                'label' => __( 'City Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
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
            'city_image_rent',
            [
                'label' => __( 'City Image', 'houzez' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $city_repeater_rent->add_control(
            'city_description_rent',
            [
                'label' => __( 'City Description', 'houzez' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
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
        set_query_var('settings', $settings);

        get_template_part('elementor-widgets/template-parts/mestate-city-carousel-v1');
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
    
}