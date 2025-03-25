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
                'default' => 6,
            ]
        );

        $this->add_control(
            'property',
            [
                'label' => __( 'Property', 'houzez' ), 
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_property_options(), // Fetch options dynamically
                'multiple' => true, // Change to true if you want to allow multiple types
                'label_block' => true,
                'description' => __( 'Select property to display in the carousel.', 'houzez' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'houzez' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Inquire Now',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $section_heading = $settings['section_heading'];
        set_query_var('settings', $settings);

        $search_qry = array(
            'post_type' => 'property',
            'post_status' => 'publish',
        );

        // Add post__in parameter if specific properties are selected
        if (!empty($settings['property']) && is_array($settings['property'])) {
            $search_qry['post__in'] = $settings['property'];
        }
        else{
            $search_qry['posts_per_page'] = $settings['posts_per_page'] !== "" ? $settings['posts_per_page'] : 6;
        }
        // Always create a WP_Query object
        $query = new WP_Query($search_qry);
        

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

            echo '<script>
                // portfolio slides
                function usePortfolioSlides() {
                    const portfolioSlides = document.querySelectorAll(".ms-new-projects__wrap");
                    if (portfolioSlides?.length) {
                        portfolioSlides?.forEach((portfolioSlide, id) => {
                            portfolioSlide?.addEventListener("mouseenter", function (e) {
                                portfolioSlides?.forEach(portfolioSlide => {
                                    portfolioSlide.classList.remove("active");
                                });

                                this.classList.add("active");
                            });
                        });
                    }
                }
                function usePortfolioSlider() {
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
                }
            </script>';

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<script>
                    usePortfolioSlides();
                    usePortfolioSlider();
                </script>';
            }
            else{
                echo '<script>
                    jQuery(document).ready(function($) {
                        usePortfolioSlides();
                        usePortfolioSlider();
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

    private function get_property_options() {
        // Advertise
        $advertise_qry = array(
            'post_type' => 'property',
            'posts_per_page' => 100,
            'orderby' => 'rand',
        );
        //$advertise_qry = apply_filters( 'houzez20_search_filters_advertise', $advertise_qry );
        $advertise_qry = apply_filters( 'houzez_sold_status_filter', $advertise_qry );
        $advertise_query = new WP_Query( $advertise_qry );

        $options = [];
        if ($advertise_query->have_posts()) {
            while ($advertise_query->have_posts()) {
                $advertise_query->the_post();
                $options[get_the_ID()] = get_the_title();
            }
            wp_reset_postdata();
        }

        return $options;
    }
    
}