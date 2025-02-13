<?php 
if(houzez_get_map_system() == 'google') {
	wp_enqueue_script('houzez-overview-listing-map', HOUZEZ_JS_DIR_URI. 'single-property-google-overview-map.js', array('jquery'), '1.0.0', true);
} else {
	wp_enqueue_script('houzez-overview-listing-map', HOUZEZ_JS_DIR_URI. 'single-property-osm-overview-map.js', array('jquery'), '1.0.0', true);
}

if(empty($listing_id)) {
    $listing_id = get_the_ID();
} 
$sale_price     = get_post_meta( $listing_id, 'fave_property_price', true );
$second_price   = get_post_meta( $listing_id, 'fave_property_sec_price', true );
$price_postfix  = get_post_meta( $listing_id, 'fave_property_price_postfix', true );
$price_prefix   = get_post_meta( $listing_id, 'fave_property_price_prefix', true );

$key = '';
$userID      =   get_current_user_id();
$fav_option = 'houzez_favorites-'.$userID;
$fav_option = get_option( $fav_option );
if( !empty($fav_option) ) {
    $key = array_search($listing_id, $fav_option);
}

$icon = '';
if( $key != false || $key != '' ) {
    $icon = 'text-danger';
}

$features = wp_get_post_terms( get_the_ID(), 'property_feature', array("fields" => "all"));
$features_icons = houzez_option('features_icons');
$additional_features = get_post_meta( get_the_ID(), 'additional_features', true );
$hide_detail = houzez_option('hide_detail_prop_fields');
?>

<!-- start: New Project Details  -->
<section class="ms-apartments-main ms-apartments-main--details section--wrapper">
    <div class="container">
        <div class="row">
            <!-- apartments content -->
            <div class="col-12 col-xl-8 mb-3 mb-md-5 mb-xl-0">
                <!-- breadcrumb -->
                <div class="ms-apartments-main__breadcrumb">
                    <ul>
                        <li><a href="<?php echo home_url(); ?>">Home</a></li>
                        <li>></li>
                        <?php $status = get_the_terms(get_the_ID(), 'property_status'); 
                        if($status && count($status) > 0 ) {
                            $page_url = get_page_by_path($status[0]->slug) ? home_url() . '/' . $status[0]->slug : home_url() . '/search-results?status%5B%5D=' . $status[0]->slug;
                        ?>
                        <li><a href="<?php echo $page_url; ?>">
                            <?php echo $status[0]->name; ?>
                        </a></li>
                        <li>></li>
                        <?php } ?>
                        <li>
                            <p><?php the_title(); ?></p>
                        </li>
                    </ul>
                </div>

                <!-- new projects title  -->

                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading ms-apartments-main__heading--details">
                        <h3><a href="javascript:void(0);"><?php the_title(); ?></a></h3>
                        <div class="ms-apartments-main____card__price">
                            <p>Starting Price</p>
                            <h6><?php echo $price_prefix. houzez_get_property_price($sale_price) . $price_postfix; ?></h6>
                        </div>

                        <ul class="ms-apartments-main____card__button-list">
                            <?php if( houzez_option('prop_detail_favorite') != 0 ) { ?>
                            <li>
                                <a href="javascript:void(0)" class="ms-btn ms-btn--primary add-favorite-js" data-listid="<?php echo intval($listing_id)?>">
                                    <i class="icon-heart_fill <?php echo $icon; ?>"></i>
                                    Save</a>
                            </li>
                            <?php } ?>
                            <?php if( houzez_option('prop_detail_share') != 0 ) { ?>
                            <li>
                                <a href="" class="ms-btn ms-btn--primary item-tool-share dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-share"></i>
                                    Share
                                </a>
                                <div class="dropdown-menu dropdown-menu-right item-tool-dropdown-menu">
                                    <?php get_template_part('property-details/partials/share'); ?>
                                </div>
                            </li>
                            <?php } ?>
                            <li>
                                <button data-toggle="modal" data-target="#msReportModal" class="ms-btn ms-btn--primary">
                                    <i class="icon-report"></i>
                                    Report
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- overwiew -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Overview</h4>
                    </div>

                    <div
                        class="ms-apartments-main__overview ms-apartments-main__overview--2 ms-apartments-main__overview--3 d-none d-md-flex">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $property_type = houzez_taxonomy_simple('property_type');
                            if(!empty($property_type)) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $bedrooms = get_post_meta(get_the_ID(), 'fave_property_bedrooms', true);
                            if(!empty($bedrooms)) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $bathrooms = get_post_meta(get_the_ID(), 'fave_property_bathrooms', true);
                            if(!empty($bathrooms)) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                            <li>
                                <span></span>
                            </li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $garages = get_post_meta(get_the_ID(), 'fave_property_garage', true);
                            if(!empty($garages)) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $sqft = get_post_meta(get_the_ID(), 'fave_property_size', true);
                            if(!empty($sqft)) { ?>
                            <li>
                                <p>sqft</p>
                                <h6><?php echo $sqft; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $property_id = get_post_meta(get_the_ID(), 'fave_property_id', true);
                            if(!empty($property_id)) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                            <li>
                                <span></span>
                            </li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $year_built = get_post_meta(get_the_ID(), 'fave_property_year', true);
                            if(!empty($year_built)) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- mobile -->
                    <div
                        class="ms-apartments-main__overview ms-apartments-main__overview--2 ms-apartments-main__overview--3 d-flex d-md-none">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $property_type = houzez_taxonomy_simple('property_type');
                            if(!empty($property_type)) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $bedrooms = get_post_meta(get_the_ID(), 'fave_property_bedrooms', true);
                            if(!empty($bedrooms)) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $bathrooms = get_post_meta(get_the_ID(), 'fave_property_bathrooms', true);
                            if(!empty($bathrooms)) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $garages = get_post_meta(get_the_ID(), 'fave_property_garage', true);
                            if(!empty($garages)) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $sqft = get_post_meta(get_the_ID(), 'fave_property_size', true);
                            if(!empty($sqft)) { ?>
                            <li>
                                <p>sqft</p>
                                <h6><?php echo $sqft; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $property_id = get_post_meta(get_the_ID(), 'fave_property_id', true);
                            if(!empty($property_id)) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $year_built = get_post_meta(get_the_ID(), 'fave_property_year', true);
                            if(!empty($year_built)) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <!-- description -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Description</h4>
                    </div>

                    <div class="ms-apartments-main__desc">
                        <?php the_content(); ?>
                    </div>
                </div>
                <!-- details -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading ms-apartments-main__heading--mobile">
                        <h4>Details</h4>
                        <?php if( $hide_fields['updated_date'] != 1 ) { ?>
                        <p>
                            <i class="icon-calendar_balck_fill"></i> 
                            <?php esc_html_e( 'Updated on', 'houzez' ); ?> <?php the_modified_time('F j, Y'); ?> <?php esc_html_e( 'at', 'houzez' ); ?> <?php the_modified_time('g:i a'); ?>
                        </p>
                        <?php } ?>
                    </div>

                    <div class="ms-apartments-main__overview ms-apartments-main__overview--4 d-none d-md-flex">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['property_id'] != 1 ) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['price'] != 1 ) { ?>
                            <li>
                                <p>Price</p>
                                <h6><small>Start from </small> <?php echo $price_prefix. houzez_get_property_price($sale_price) . $price_postfix; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['size'] != 1 ) { ?>
                            <li>
                                <p>Property Size</p>
                                <h6><?php echo $sqft; ?> sqft</h6> 
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                            <li>
                                <span></span>
                            </li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list ms-apartments-main__overview__item-list--2">
                            <?php $land_area = get_post_meta(get_the_ID(), 'fave_property_land', true);
                            if( $hide_fields['land_area'] != 1 && !empty($land_area) ) { ?>
                            <li>
                                <p>Land Area</p>
                                <h6><?php echo $land_area; ?> sqft</h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['bedrooms'] != 1 && !empty($bedrooms) ) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['bathrooms'] != 1 && !empty($bathrooms) ) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <?php $land_area = get_post_meta(get_the_ID(), 'fave_property_land', true);
                            if( $hide_fields['land_area'] != 1 && !empty($land_area) ) { ?>
                            <li>
                                <span></span>
                            </li>
                            <?php } ?>
                            <?php if( $hide_fields['bedrooms'] != 1 && !empty($bedrooms) ) { ?>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['bathrooms'] != 1 && !empty($bathrooms) ) { ?>
                            <li>
                                <span></span>
                            </li>
                            <?php } ?>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['garages'] != 1 && !empty($garages) ) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $garage_size = get_post_meta(get_the_ID(), 'fave_property_garage_size', true);
                            if( $hide_fields['garage_size'] != 1 && !empty($garage_size) ) { ?>
                            <li>
                                <p>Garage Size</p>
                                <h6><?php echo $garage_size; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['year_built'] != 1 && !empty($year_built) ) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <?php if( $hide_fields['garages'] != 1 && !empty($garages) ) { ?>
                            <li>
                                <span></span>
                            </li>
                            <?php } ?>
                            <?php if( $hide_fields['garage_size'] != 1 && !empty($garage_size) ) { ?>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['year_built'] != 1 && !empty($year_built) ) { ?>
                            <li>
                                <span></span>
                            </li>
                            <?php } ?>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['property_type'] != 1 && !empty($property_type) ) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php $property_status = houzez_taxonomy_simple('property_status');
                            if( $hide_fields['property_status'] != 1 && !empty($property_status) ) { ?>
                            <li>
                                <p>Property Status</p>
                                <h6><?php echo $property_status; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- mobile -->
                    <div class="ms-apartments-main__overview ms-apartments-main__overview--2 d-flex d-md-none">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['property_id'] != 1 ) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['price'] != 1 ) { ?>
                            <li>
                                <p>Price</p>
                                <h6><small>Start from </small> <?php echo $price_prefix. houzez_get_property_price($sale_price) . $price_postfix; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['size'] != 1 ) { ?>
                            <li>
                                <p>Property Size</p>
                                <h6><?php echo $sqft; ?> sqft</h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['land_area'] != 1 ) { ?>
                            <li>
                                <p>Land Area</p>
                                <h6><?php echo $land_area; ?> sqft</h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['bedrooms'] != 1 ) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['bathrooms'] != 1 ) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['garages'] != 1 ) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['garage_size'] != 1 ) { ?>
                            <li>
                                <p>Garage Size</p>
                                <h6><?php echo $garage_size; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['year_built'] != 1 ) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['property_type'] != 1 ) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                        <ul class="ms-apartments-main__overview__line-list">
                            <li>
                                <span></span>
                            </li>
                            <li><span></span></li>
                        </ul>
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['property_status'] != 1 ) { ?>
                            <li>
                                <p>Property Status</p>
                                <h6><?php echo $property_status; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- map -->
                <?php if( $hide_fields['map'] != 1 ) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Map</h4>
                    </div>

                    <div class="ms-apartments-main__map">
                        <div id="houzez-overview-listing-map" style="height: 350px;"></div>
                    </div>
                </div>
                <?php } ?>
                <!-- features -->
                <?php if(!empty($features) || !empty($additional_features)) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Features</h4>
                    </div>

                    <div class="ms-apartments-main__fetures">
                        <ul class="ms-input__list ms-input__list--auto-width">
                        <?php if (!empty($features)):
			            foreach ($features as $term):
			                $term_link = get_term_link($term, 'property_feature');
			                if (is_wp_error($term_link))
			                    continue;

			                $feature_icon = '';
			                $icon_type = get_term_meta($term->term_id, 'fave_feature_icon_type', true);
			                $icon_class = get_term_meta($term->term_id, 'fave_prop_features_icon', true);
			                $img_icon = get_term_meta($term->term_id, 'fave_feature_img_icon', true);

			                $feature_icon = '';
                            ?>
                            <li>
                                <button>
                                    <?php if($icon_type == 'custom') { ?>
                                        <img src="<?php echo $img_icon; ?>" alt="">
                                    <?php } else { ?>
                                        <i class="<?php echo $icon_class ? 'icon-'.$icon_class : 'icon-smart_home_system'; ?>"></i>
                                    <?php } ?>
                                    <span></span> <?php echo $term->name; ?>
                                </button>
                            </li>
                            <?php
                            endforeach;
                        endif;
                        ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
                <!-- videos -->
                <?php 
                $video1 = get_post_meta($listing_id, 'fave_video-url-1', true);
                $video2 = get_post_meta($listing_id, 'fave_video-url-2', true);
                $video1_thumb = get_post_meta($listing_id, 'fave_video-thumbnail-1', true);
                $video2_thumb = get_post_meta($listing_id, 'fave_video-thumbnail-2', true);

                if(!empty($video1) || !empty($video2)) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Videos</h4>
                    </div>

                    <div class="ms-apartments-main__videos ms-apartments-main__videos--slider">
                        <?php if(!empty($video1) && !empty($video1_thumb)) { ?>
                        <div class="ms-apartments-main__videos__single">
                            <a class="ms-lightcase" href="<?php echo $video1; ?>"
                                data-rel="lightcase:myCollection">
                                <img src="<?php echo $video1_thumb; ?>" alt="" />
                                <span class="ms-apartments-main__videos__play-btn"><i class="icon-icn_playstore"></i></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php if(!empty($video2) && !empty($video2_thumb)) { ?>
                        <div class="ms-apartments-main__videos__single">
                            <a class="ms-lightcase" href="<?php echo $video2; ?>"
                                data-rel="lightcase:myCollection">
                                <img src="<?php echo $video2_thumb; ?>" alt="" />
                                <span class="ms-apartments-main__videos__play-btn"><i class="icon-icn_playstore"></i></span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <!-- floor plan -->
                <?php 
                if($status && count($status) > 0 && $status[0]->slug == 'new-projects' ) {
                $floor_plans = get_post_meta($listing_id, 'floor_plans', true);
                if( isset($floor_plans[0]['fave_plan_title']) && !empty( $floor_plans[0]['fave_plan_title'] ) ) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Floor Plan</h4>
                        <a href="javascript:void(0)">From developer</a>
                    </div>

                    <div class="ms-apartments-main__floor-plan">
                        <h6>Apartment</h6>
                        <div class="accordion ms-apartments-main__floor-plan__accordion" id="ms-details-accordion">
                            <?php 
                            $i = 0;
                            foreach( $floor_plans as $plan ):
                                $i++;
                                $price_postfix = '';
                                if( !empty( $plan['fave_plan_price_postfix'] ) ) {
                                    $price_postfix = ' / '.$plan['fave_plan_price_postfix'];
                                }

                                $plan_image = isset($plan['fave_plan_image']) ? $plan['fave_plan_image'] : '';
                                $filetype = wp_check_filetype($plan_image);

                                $plan_title = isset($plan['fave_plan_title']) ? esc_attr($plan['fave_plan_title']) : '';
                            ?>
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <div class="ms-apartments-main__floor-plan__accordion__control-wrapper mb-0">
                                        <div class="ms-apartments-main__floor-plan__accordion__control text-left" type="button"
                                            data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="true"
                                            aria-controls="collapse<?php echo $i; ?>">
                                            <ul class="ms-apartments-main____card__details-list  ">
                                                <?php if( isset($plan['fave_plan_rooms']) && !empty( $plan['fave_plan_rooms'] ) ) { ?>
                                                <li><i class="icon-bed"> </i> <?php echo $plan['fave_plan_rooms']; ?> Beds</li>
                                                <?php } ?>

                                                <?php if( isset($plan['fave_plan_price']) && !empty( $plan['fave_plan_price'] ) ) { ?>
                                                <li>from <?php echo houzez_get_property_price( $plan['fave_plan_price'] ).$price_postfix; ?></li>
                                                <?php } ?>
                                            </ul>
                                            <ul class="ms-apartments-main____card__details-list">
                                                <?php if( isset($plan['fave_plan_size']) && !empty( $plan['fave_plan_size'] ) ) { ?>
                                                <li><i class="icon-scale"> </i> <?php echo esc_attr( $plan['fave_plan_size'] ); ?> m2</li>
                                                <?php } ?>
                                                <li><i class="icon-plus"> </i></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div id="collapse<?php echo $i; ?>" class="collapse <?php echo $i == 1 ? 'show' : ''; ?>" aria-labelledby="headingOne"
                                    data-parent="#ms-details-accordion">
                                    <div class="card-body">
                                        <ul class="ms-apartments-main__floor-plan__accordion__content-list">
                                            <li>
                                                <h6>Layout type</h6>
                                                <p><?php echo esc_attr( $plan_title ); ?></p>
                                            </li>
                                            <li>
                                                <h6>Size (sqft)</h6>
                                                <p><?php echo esc_attr( $plan['fave_plan_size'] ); ?> m2</p>
                                            </li>
                                            <?php if( !empty( $plan_image ) ) { ?>
                                            <li>
                                                <h6>Layout</h6>
                                                <?php if($filetype['ext'] != 'pdf' ) {?>
                                                <a target="_blank" href="<?php echo esc_url( $plan['fave_plan_image'] ); ?>" data-lightbox="roadtrip">
                                                    <img style="max-width: 220px;" src="<?php echo esc_url( $plan['fave_plan_image'] ); ?>" alt="image">
                                                </a>
                                                <?php } else { 
                                                    
                                                    $path = $plan_image;
                                                    $file = basename($path); 
                                                    $file = basename($path, ".pdf");
                                                    echo '<a href="'.esc_url( $plan_image ).'" download>';
                                                    echo $file;
                                                    echo '</a>';
                                                } ?>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php } }?>

                <!-- payment plan -->
                <?php 
                if($status && count($status) > 0 && $status[0]->slug == 'new-projects' ) {
                    $prop_payment_plan_down = get_post_meta($listing_id, 'fave_down-payment', true);
                    $prop_payment_plan_during_construction = get_post_meta($listing_id, 'fave_during-construction', true);
                    $prop_payment_plan_on_handover = get_post_meta($listing_id, 'fave_on-handover', true);
                if( !empty($prop_payment_plan_down) || !empty($prop_payment_plan_during_construction) || !empty($prop_payment_plan_on_handover) ) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Payment Plan</h4>
                    </div>

                    <div class="ms-apartments-main__payment-plan">
                        <!-- plan single -->
                        <div class="ms-apartments-main__payment-plan__single">
                            <h4><?php echo $prop_payment_plan_down; ?>%</h4>
                            <p>
                                Down payment At <br />
                                sales launch
                            </p>
                        </div>
                        <!-- plan single -->
                        <div class="ms-apartments-main__payment-plan__single">
                            <h4><?php echo $prop_payment_plan_during_construction; ?>%</h4>
                            <p>
                                During <br />
                                construction
                            </p>
                        </div>
                        <!-- plan single -->
                        <div class="ms-apartments-main__payment-plan__single">
                            <h4><?php echo $prop_payment_plan_on_handover; ?>%</h4>
                            <p>
                                On <br />
                                handover
                            </p>
                        </div>
                    </div>
                </div>
                <?php } }?>

                <!-- virtual tour -->
                <?php 
                if($status && count($status) > 0 && $status[0]->slug == 'new-projects' ) {
                $virtual_tour = houzez_get_listing_data('virtual_tour');
                if( !empty($virtual_tour) ) { ?>
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>360° Virtual Tour</h4>
                    </div>

                    <div class="ms-apartments-main__virtual-tour">
                        <h6>
                            <span>Presented by Hulu</span>
                            <span><i class="icon-location_black"></i> Seinfeld
                                Apartment</span>
                        </h6>
                        <?php 
                        // Check if the content contains either <iframe> or <embed> tags
                        if (strpos($virtual_tour, '<iframe') !== false || strpos($virtual_tour, '<embed') !== false) {
                            $virtual_tour = houzez_ensure_iframe_closing_tag($virtual_tour);
                            echo $virtual_tour;
                        } else { 
                            $virtual_tour = '<iframe width="853" height="480" src="'.$virtual_tour.'" frameborder="0" allowfullscreen="allowfullscreen" class="ms-apartments-main__virtual-tour__content" loading="lazy"></iframe>';
                            echo $virtual_tour;
                        }
                        ?>
                    
                    </div>
                </div>
                <?php } }?>

                <!-- mortgage calculator -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Mortgage Calculator</h4>
                    </div>

                    <?php get_template_part('property-details/partials/mortgage-calculator'); ?>

                </div>

                <!-- Regulatory Information-->
                <!-- <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Regulatory Information</h4>
                    </div>

                    <div class="ms-apartments-main__regulatory-information">
                        <div class="ms-apartments-main__overview">
                            <ul class="ms-apartments-main__overview__item-list">
                                <li>
                                    <p>Reference</p>
                                    <h6>ASD751515412_65</h6>
                                </li>
                                <li><span></span></li>
                                <li>
                                    <p>Listed</p>
                                    <h6>3 days ago</h6>
                                </li>
                            </ul>
                            <ul class="ms-apartments-main__overview__line-list">
                                <li>
                                    <span></span>
                                </li>
                                <li><span></span></li>
                            </ul>

                            <ul class="ms-apartments-main__overview__item-list">
                                <li>
                                    <p>Broker License</p>
                                    <h6>456541</h6>
                                </li>
                                <li><span></span></li>
                                <li>
                                    <p>Zone Name</p>
                                    <h6>Mohammed Bin Zayed</h6>
                                </li>
                            </ul>
                            <ul class="ms-apartments-main__overview__line-list">
                                <li>
                                    <span></span>
                                </li>
                                <li><span></span></li>
                            </ul>

                            <ul class="ms-apartments-main__overview__item-list">
                                <li>
                                    <p>DLD Permit Number</p>
                                    <h6>6454154862</h6>
                                </li>
                                <li><span></span></li>
                                <li>
                                    <p>Agent License</p>
                                    <h6>451746</h6>
                                </li>
                            </ul>
                            <ul class="ms-apartments-main__overview__line-list d-flex d-md-none">
                                <li>
                                    <span></span>
                                </li>
                                <li><span></span></li>
                            </ul>
                        </div>

                        <div class="ms-apartments-main__qr-code">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/new-projects/qr-code.png" alt="" />
                        </div>
                    </div>
                </div> -->

                <?php get_template_part('property-details/partials/similar-listing'); ?>
            </div>
            <!-- apartment sidebar -->
            <div class="col-12 col-md-7 col-xl-4 pl-3">
                <!-- sidebar single -->
                <?php get_template_part('property-details/partials/sidebar-agent-details'); ?>
                
                <!-- sidebar single -->
                <div class="ms-apartments-main__sidebar__single">
                    <a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sidebar/sidebar-banner.png" alt="" /></a>

                    <a href="#" class="ms-btn ms-btn--primary">Download Now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- start: New Project Details    -->

