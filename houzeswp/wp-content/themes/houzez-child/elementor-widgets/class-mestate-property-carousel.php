<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MEstate_Property_Carousel extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mestate_property_carousel';
    }

    public function get_title() {
        return __( 'MEstate Property Carousel', 'houzez' );
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
                'default' => 'Explore the New Project',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Number of Properties', 'houzez' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
            ]
        );

        $this->add_control(
            'property_status',
            [
                'label' => __( 'Property Status', 'houzez' ), 
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_property_status_options(), // Fetch options dynamically
                'multiple' => false, // Change to true if you want to allow multiple types
                'label_block' => true,
                'description' => __( 'Select a property status to filter the carousel.', 'houzez' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $section_heading = $settings['section_heading'];

        $atts = array(
            'property_status' => $settings['property_status'],
            'posts_limit' => $settings['posts_per_page'],
        );
        $query = houzez_data_source::get_wp_query($atts);

        //<!-- start: New Projects  -->
        echo '<section class="ms-new-projects section--wrapper">';
        echo '<div class="container-fluid">';
        // section heading
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<div class="ms-section-heading">';
        echo '<h2>'.$section_heading.'</h2>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    
        if ($query->have_posts()) {
            echo '<div class="ms-new-projects__wrapper d-none d-lg-flex">';
            $post_index = 0;
            while ($query->have_posts()) {
                $query->the_post();
                // <!--New Projects for desktop -->
                $post_index++;
                $active_class = ($post_index === 2) ? ' active' : '';
                echo '<div class="ms-new-projects__wrap' . $active_class . '">';
                
                get_template_part('elementor-widgets/template-parts/item-mestate-carousel-v1');
                
                echo '</div>';
            }
    
            echo '</div>';

            // <!--New Projects for mobile -->
            echo '<div class="swiper ms-new-projects__slider d-block d-lg-none">';
            echo '<div class="swiper-wrapper">';
    
            while ($query->have_posts()) {
                $query->the_post();
                echo '<div class="swiper-slide">';
                echo '<div>';
                
                get_template_part('elementor-widgets/template-parts/item-mestate-carousel-v1');
                echo '</div>';
                echo '</div>';
            }
    
            echo '</div>';
            echo '</div>';

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<script>
                if (typeof usePortfolioSlider === \'function\') {
                    usePortfolioSlider();
                }
                if (typeof usePortfolioSlides === \'function\') {
                    usePortfolioSlides();
                }
                </script>';
            }

        } else {
            echo __( 'No properties found', 'houzez' );
        }

        echo '</div>';
        echo '</div>';
    
        wp_reset_postdata();
    }
    

    private function get_property_status_options() {
        $terms = get_terms([
            'taxonomy' => 'property_status',
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