<?php
$show_similer = houzez_option( 'houzez_similer_properties' );
$similer_criteria = houzez_option( 'houzez_similer_properties_type', array( 'property_type', 'property_city' ) );
$listing_view = houzez_option( 'houzez_similer_properties_view' );
$similer_count = houzez_option( 'houzez_similer_properties_count' );

if( $show_similer ) {

	$properties_args = array(
		'post_type'           => 'property',
		'posts_per_page'      => intval( $similer_count ),
		'post__not_in'        => array( get_the_ID() ),
		'post_parent__not_in' => array( get_the_ID() ),
        'post_status' => 'publish'
	);

	if ( ! empty( $similer_criteria ) && is_array( $similer_criteria ) ) {

		$similar_taxonomies_count = count( $similer_criteria );
		$tax_query                = array();

		for ( $i = 0; $i < $similar_taxonomies_count; $i ++ ) {
			
			$similar_terms = get_the_terms( get_the_ID(), $similer_criteria[ $i ] );
			if ( ! empty( $similar_terms ) && is_array( $similar_terms ) ) {
				$terms_array = array();
				foreach ( $similar_terms as $property_term ) {
					$terms_array[] = $property_term->term_id;
				}
				$tax_query[] = array(
					'taxonomy' => $similer_criteria[ $i ],
					'field'    => 'id',
					'terms'    => $terms_array,
				);
			}
		}

		$tax_count = count( $tax_query );  
		if ( $tax_count > 1 ) {
			$tax_query['relation'] = 'AND'; 
		}
		if ( $tax_count > 0 ) {
			$properties_args['tax_query'] = $tax_query; 
		}

	}

	$sort_by = houzez_option( 'similar_order', 'd_date' );
	if ( $sort_by == 'a_title' ) {
        $properties_args['orderby'] = 'title';
        $properties_args['order'] = 'ASC';
    } else if ( $sort_by == 'd_title' ) {
        $properties_args['orderby'] = 'title';
        $properties_args['order'] = 'DESC';
    } else if ( $sort_by == 'a_price' ) {
        $properties_args['orderby'] = 'meta_value_num';
        $properties_args['meta_key'] = 'fave_property_price';
        $properties_args['order'] = 'ASC';
    } else if ( $sort_by == 'd_price' ) {
        $properties_args['orderby'] = 'meta_value_num';
        $properties_args['meta_key'] = 'fave_property_price';
        $properties_args['order'] = 'DESC';
    } else if ( $sort_by == 'a_date' ) {
        $properties_args['orderby'] = 'date';
        $properties_args['order'] = 'ASC';
    } else if ( $sort_by == 'd_date' ) {
        $properties_args['orderby'] = 'date';
        $properties_args['order'] = 'DESC';
    } else if ( $sort_by == 'featured_first' ) {
        $properties_args['orderby'] = 'meta_value date';
        $properties_args['meta_key'] = 'fave_featured';
    } else if ( $sort_by == 'featured_first_random' ) {
        $properties_args['orderby'] = 'meta_value DESC rand';
        $properties_args['meta_key'] = 'fave_featured';
    } else if ( $sort_by == 'random' ) {
        $properties_args['orderby'] = 'rand date';
    }

	$wp_query = new WP_Query($properties_args);

    if ($wp_query->have_posts()) :

        $status = get_the_terms(get_the_ID(), 'property_status'); 
        $types = get_the_terms(get_the_ID(), 'property_type'); 
        $newprojects = 0;
        foreach ($types as $type) {
            if($type->slug == "new-projects") {
                $newprojects = 1;
            }
        }
?>


<!-- new projects  -->
<div class="ms-apartments-main__section">
    <div class="ms-apartments-main__heading ms-apartments-main__heading--2">
        <h4><?php echo houzez_option('sps_similar_listings', 'Similar Listings'); ?></h4>
        <?php 
        if($status && count($status) > 0 ) {
            $page_url = get_page_by_path($status[0]->slug) ? home_url() . '/' . $status[0]->slug : home_url() . '/search-results?status%5B%5D=' . $status[0]->slug;
        ?>
        <a href="<?php echo $page_url; ?>"> See All </a>
        <?php } ?>
    </div>
    <!-- apartments cards -->
    <div class="ms-apartments-main__card__wrapper">
        <!-- card 2 -->
        <?php
        while ($wp_query->have_posts()) : $wp_query->the_post();

        if($newprojects == 1) {
            get_template_part('elementor-widgets/template-parts/mestate-new-project-listing-item-v1');
        } else {
            get_template_part('elementor-widgets/template-parts/mestate-listing-item-v1');
        }

        endwhile;
        ?>
    </div>
</div>

<?php
	endif;
	wp_reset_query();
}?>

<script>
    <?php if($newprojects == 1) {?>
    function functionListingItemImageSlider(){
        // card slider
        var formSlider = new Swiper(".ms-aparments-maincardslider", { 
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                renderBullet: function (index, className) {
                    var current = this.realIndex; // Get active slide index
                    var totalSlides = this.slides.length;
                    let bulletsToShow = [];

                    if (current <= totalSlides - 3) {
                        // Case 1: If there are at least 2 next slides -> Current, Next 1, Next 2
                        bulletsToShow = [current, current + 1, current + 2];
                    } else if (current === totalSlides - 2) {
                        // Case 2: If there is only 1 next slide -> Prev 1, Current, Next 1
                        bulletsToShow = [current - 1, current, current + 1];
                    } else if (current === totalSlides - 1) {
                        // Case 3: If no next slides -> Prev 2, Prev 1, Current
                        bulletsToShow = [current - 2, current - 1, current];
                    } else if (current >= 2) {
                        // Case 4: If there are at least 2 previous slides -> Prev 2, Prev 1, Current
                        bulletsToShow = [current - 2, current - 1, current];
                    } else if (current === 1) {
                        // Case 5: If there is only 1 previous slide -> Prev 1, Current, Next 1
                        bulletsToShow = [current - 1, current, current + 1];
                    }

                    // Render bullets only if they match the allowed range
                    if (bulletsToShow.includes(index)) {
                        return `<span class="${className}" data-index="${index}"></span>`;
                    }
                    return ""; // Hide other bullets
                },
            },

            // loop: true,
        });
    }
    jQuery(document).ready(function($) {
        functionListingItemImageSlider();
    });
    <?php } ?>
</script>