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
    
        // Child Repeater (Nested List)
        $child_repeater = new \Elementor\Repeater();
    
        // Add text control for child list item
        $child_repeater->add_control(
            'area_slug',
            [
                'label' => __('Area', 'houzez'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Area', 'houzez'),
            ]
        );

        $child_repeater->add_control(
            'area_url',
            [
                'label' => __('Area URL', 'houzez'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Area URL', 'houzez'),
            ]
        );
    
        // Add child repeater inside parent repeater
        $repeater->add_control(
            'area_list',
            [
                'label' => __('Area List', 'houzez'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $child_repeater->get_controls(),
                'title_field' => '{{{ area_slug }}}', // Display child item in UI
                'condition' => [
                    'city_slug!' => '', // Ensures that a parent item must be selected first
                ],
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
                  // portfolio slider
                  portfolioSlides = document.querySelectorAll(".ms-new-projects__wrap");
                  if (portfolioSlides?.length) {
                    portfolioSlides?.forEach((portfolioSlide, id) => {
                      portfolioSlide?.addEventListener("mouseenter", function (e) {
                        portfolioSlides?.forEach((portfolioSlide) => {
                          portfolioSlide.classList.remove("active");
                        });
                
                        this.classList.add("active");
                      });
                    });
                  }
                  // portfolio
                  var portfolioSlider = new Swiper(".ms-new-projects__slider", {
                    slidesPerView: 1.4,
                    spaceBetween: 12,
                    centeredSlides: true,
                    loop: true,
                    autoplay: {
                      delay: 5000,
                    },
                    breakpoints: {
                      768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                      },
                    },
                  });
                </script>';
            }

        } else {
            echo __( 'No properties found', 'houzez' );
        }

        echo '</div>';
        echo '</div>';
    
        wp_reset_postdata();
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