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
                            <?php if( $hide_fields['land_area'] != 1 ) { 
                                $land_area = get_post_meta(get_the_ID(), 'fave_property_land', true);?>
                            <li>
                                <p>Land Area</p>
                                <h6><?php echo $land_area; ?> sqft</h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
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
                            <li>
                                <span></span>
                            </li>
                        </ul>

                        <ul class="ms-apartments-main__overview__item-list">
                            <?php if( $hide_fields['garages'] != 1 ) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['garage_size'] != 1 ) { 
                                $garage_size = get_post_meta(get_the_ID(), 'fave_property_garage_size', true);?>
                            <li>
                                <p>Garage Size</p>
                                <h6><?php echo $garage_size; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['year_built'] != 1 ) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
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
                            <?php if( $hide_fields['property_type'] != 1 ) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <li><span></span></li>
                            <?php } ?>
                            <?php if( $hide_fields['property_status'] != 1 ) {
                                $property_status = houzez_taxonomy_simple('property_status'); ?>
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
                $video1 = get_post_meta(get_the_ID(), 'fave_video-url-1', true);
                $video2 = get_post_meta(get_the_ID(), 'fave_video-url-2', true);
                $video1_thumb = get_post_meta(get_the_ID(), 'fave_video-thumbnail-1', true);
                $video2_thumb = get_post_meta(get_the_ID(), 'fave_video-thumbnail-2', true);

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
                <!-- mortgage calculator -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Mortgage Calculator</h4>
                    </div>

                    <?php get_template_part('property-details/partials/mortgage-calculator'); ?>

                </div>

                <!-- Regulatory Information-->
                <div class="ms-apartments-main__section">
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
                </div>

                <?php get_template_part('property-details/partials/similar-listing'); ?>
            </div>
            <!-- apartment sidebar -->
            <div class="col-12 col-md-7 col-xl-4 pl-3">
                <!-- sidebar single -->
                <div
                    class="ms-apartments-main__sidebar__single ms-apartments-main__sidebar__single--broker ms-apartments-main__sidebar__single--broker-2">
                    <div class="ms-apartments-main__sidebar__broker-details">
                        <div>
                            <a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/new-projects/broker/2.png" alt="" /></a>
                        </div>
                        <div class="ms-apartments-main__sidebar__broker-name">
                            <h6>Michelle Erlewine</h6>
                            <!-- list -->
                            <ul class="ms-apartments-main__sidebar__broker-details__list">
                                <li>
                                    <span>
                                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.9635 5.87072C13.8719 5.58724 13.6204 5.38589 13.3229 5.35908L9.28216 4.99217L7.68432 1.25229C7.56651 0.9782 7.29819 0.800781 7.00007 0.800781C6.70195 0.800781 6.43364 0.9782 6.31582 1.25293L4.71798 4.99217L0.67656 5.35908C0.379616 5.38653 0.128816 5.58724 0.0366355 5.87072C-0.0555452 6.15421 0.0295858 6.46514 0.254216 6.66115L3.30857 9.33984L2.40791 13.3072C2.34201 13.5989 2.45523 13.9005 2.69727 14.0754C2.82737 14.1694 2.97958 14.2173 3.13307 14.2173C3.26542 14.2173 3.39669 14.1816 3.51451 14.1111L7.00007 12.0279L10.4844 14.1111C10.7393 14.2645 11.0607 14.2505 11.3022 14.0754C11.5444 13.9 11.6575 13.5983 11.5916 13.3072L10.6909 9.33984L13.7453 6.66168C13.9699 6.46514 14.0557 6.15474 13.9635 5.87072Z"
                                                fill="#FFC107" />
                                        </svg>
                                        4.2 (6.2k)</span>
                                </li>
                                <li><a href="#">Write a Review</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ms-apartments-main__sidebar__whatsapp">
                        <button data-toggle="modal" data-target="#msQualityListerModal" class="ms-btn ms-btn--black">
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.7573 13.5967C13.7573 13.5967 11.9814 10.5446 11.6629 9.99715C12.0253 9.88659 12.4114 9.78896 12.5718 9.51197C12.8392 9.05044 12.3633 8.29334 12.4882 7.79865C12.6169 7.28951 13.3768 6.84895 13.3768 6.33143C13.3768 5.82723 12.5912 5.23819 12.4624 4.73204C12.3366 4.23743 12.811 3.4795 12.5428 3.01854C12.2746 2.55753 11.3813 2.5954 11.0135 2.24154C10.6352 1.87738 10.6363 0.986409 10.1799 0.74237C9.72169 0.497424 8.97695 0.992462 8.46816 0.875199C7.96517 0.759277 7.51579 0.00195312 6.9904 0.00195312C6.45722 0.00195312 5.60919 0.861017 5.47618 0.891933C4.96766 1.01015 4.22196 0.516319 3.7642 0.762303C3.30825 1.00716 3.31106 1.89822 2.93337 2.26298C2.56623 2.6175 1.67283 2.58126 1.40545 3.04279C1.1381 3.50423 1.61399 4.26117 1.48907 4.75612C1.36381 5.25233 0.57747 5.7551 0.57747 6.32897C0.57747 6.84653 1.33925 7.28571 1.46875 7.79458C1.59461 8.28919 1.12016 9.04703 1.38837 9.50817C1.53438 9.75917 1.86568 9.86221 2.19654 9.96127C2.23515 9.97281 2.30818 10.016 2.25872 10.0881C2.03236 10.4788 0.210505 13.6231 0.210505 13.6231C0.0655689 13.8731 0.183264 14.0875 0.471968 14.0997L1.88513 14.1586C2.17384 14.1708 2.536 14.3806 2.69011 14.625L3.44419 15.8216C3.59829 16.066 3.8428 16.0614 3.9877 15.8114C3.9877 15.8114 6.09761 12.1687 6.09847 12.1676C6.1408 12.1182 6.18335 12.1283 6.20372 12.1456C6.43457 12.3422 6.75639 12.5383 7.02499 12.5383C7.2884 12.5383 7.53231 12.3537 7.77358 12.1478C7.79321 12.1311 7.84099 12.0968 7.87593 12.168C7.87649 12.1691 9.98407 15.7919 9.98407 15.7919C10.1293 16.0416 10.3739 16.0458 10.5275 15.8011L11.2795 14.6031C11.4332 14.3585 11.7949 14.1479 12.0836 14.1353L13.4967 14.0737C13.7853 14.0611 13.9026 13.8464 13.7573 13.5967ZM9.32584 10.32C7.76337 11.229 5.8879 11.1316 4.45541 10.228C2.35639 8.88354 1.66574 6.10227 2.92757 3.92438C4.2038 1.72138 7.00925 0.944035 9.2321 2.15467C9.24378 2.16103 9.25532 2.1676 9.26691 2.17409C9.28338 2.18321 9.29981 2.19246 9.3162 2.2018C10.002 2.59718 10.5966 3.17099 11.0224 3.90289C12.3238 6.13988 11.5628 9.01862 9.32584 10.32Z"
                                    fill="white" />
                                <path
                                    d="M8.96698 2.85981C8.96049 2.85604 8.95392 2.85254 8.94743 2.84882C7.76001 2.16159 6.24658 2.11135 4.97817 2.8493C3.09591 3.94436 2.45542 6.36654 3.55048 8.24876C3.88463 8.82314 4.34252 9.28164 4.87081 9.61069C4.91574 9.63914 4.96118 9.66711 5.00762 9.69401C6.89191 10.7857 9.3128 10.1408 10.4044 8.25658C11.496 6.37229 10.8513 3.95145 8.96698 2.85981ZM9.43836 5.94016L8.77858 6.58325C8.57424 6.78241 8.44651 7.17553 8.49481 7.4568L8.65056 8.36485C8.69881 8.64612 8.53165 8.76758 8.27905 8.63479L7.46353 8.20604C7.21093 8.07325 6.79761 8.07325 6.54501 8.20604L5.72953 8.63479C5.47693 8.76758 5.30973 8.64612 5.35798 8.36485L5.51373 7.4568C5.56198 7.17553 5.43425 6.78241 5.22991 6.58325L4.57018 5.94016C4.36579 5.74101 4.42969 5.54445 4.71208 5.50337L5.62381 5.37089C5.9062 5.32985 6.24061 5.08689 6.36691 4.83101L6.77465 4.00485C6.90095 3.74896 7.10763 3.74896 7.23389 4.00485L7.64167 4.83101C7.76797 5.08689 8.10233 5.32985 8.38477 5.37089L9.2965 5.50337C9.57884 5.54445 9.64271 5.74101 9.43836 5.94016Z"
                                    fill="white" />
                            </svg>

                            Quality Lister
                        </button>
                    </div>
                    <!-- button list -->
                    <div class="ms-apartments-main__card--2">
                        <ul class="ms-apartments-main____card__button-list ms-apartments-main____card__button-list--2">
                            <li>
                                <a href="#" class="ms-btn ms-btn--bordered">
                                    Elevate Marketing Co.</a>
                            </li>
                        </ul>
                        <ul class="ms-apartments-main____card__button-list">
                            <li>
                                <a href="tel:01234557890" class="ms-btn ms-btn--bordered">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.3057 16.7743C17.3057 17.0743 17.2391 17.3827 17.0974 17.6827C16.9557 17.9827 16.7724 18.266 16.5307 18.5327C16.1224 18.9827 15.6724 19.3077 15.1641 19.516C14.6641 19.7243 14.1224 19.8327 13.5391 19.8327C12.6891 19.8327 11.7807 19.6327 10.8224 19.2243C9.86406 18.816 8.90573 18.266 7.95573 17.5743C6.9974 16.8743 6.08906 16.0993 5.2224 15.241C4.36406 14.3743 3.58906 13.466 2.8974 12.516C2.21406 11.566 1.66406 10.616 1.26406 9.67435C0.864063 8.72435 0.664062 7.81602 0.664062 6.94935C0.664062 6.38268 0.764062 5.84102 0.964062 5.34102C1.16406 4.83268 1.48073 4.36602 1.9224 3.94935C2.45573 3.42435 3.03906 3.16602 3.65573 3.16602C3.88906 3.16602 4.1224 3.21602 4.33073 3.31602C4.5474 3.41602 4.73906 3.56602 4.88906 3.78268L6.8224 6.50768C6.9724 6.71602 7.08073 6.90768 7.15573 7.09102C7.23073 7.26602 7.2724 7.44102 7.2724 7.59935C7.2724 7.79935 7.21406 7.99935 7.0974 8.19102C6.98906 8.38268 6.83073 8.58268 6.63073 8.78268L5.9974 9.44102C5.90573 9.53268 5.86406 9.64102 5.86406 9.77435C5.86406 9.84102 5.8724 9.89935 5.88906 9.96602C5.91406 10.0327 5.93906 10.0827 5.95573 10.1327C6.10573 10.4077 6.36406 10.766 6.73073 11.1993C7.10573 11.6327 7.50573 12.0743 7.93906 12.516C8.38906 12.9577 8.8224 13.366 9.26406 13.741C9.6974 14.1077 10.0557 14.3577 10.3391 14.5077C10.3807 14.5243 10.4307 14.5493 10.4891 14.5743C10.5557 14.5993 10.6224 14.6077 10.6974 14.6077C10.8391 14.6077 10.9474 14.5577 11.0391 14.466L11.6724 13.841C11.8807 13.6327 12.0807 13.4743 12.2724 13.3743C12.4641 13.2577 12.6557 13.1993 12.8641 13.1993C13.0224 13.1993 13.1891 13.2327 13.3724 13.3077C13.5557 13.3827 13.7474 13.491 13.9557 13.6327L16.7141 15.591C16.9307 15.741 17.0807 15.916 17.1724 16.1243C17.2557 16.3327 17.3057 16.541 17.3057 16.7743Z"
                                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10"></path>
                                        <path
                                            d="M14.4167 8.9987C14.4167 8.4987 14.025 7.73203 13.4417 7.10703C12.9083 6.53203 12.2 6.08203 11.5 6.08203"
                                            stroke="#1B1B1B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M17.3333 8.99935C17.3333 5.77435 14.725 3.16602 11.5 3.16602" stroke="#1B1B1B"
                                            stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    Call</a>
                            </li>
                            <li>
                                <a href="mailto:mail@mail.com" class="ms-btn ms-btn--bordered">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.6719 17.5827H6.33854C3.83854 17.5827 2.17188 16.3327 2.17188 13.416V7.58268C2.17188 4.66602 3.83854 3.41602 6.33854 3.41602H14.6719C17.1719 3.41602 18.8385 4.66602 18.8385 7.58268V13.416C18.8385 16.3327 17.1719 17.5827 14.6719 17.5827Z"
                                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M14.6615 8L12.0531 10.0833C11.1948 10.7667 9.78645 10.7667 8.92812 10.0833L6.32812 8"
                                            stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                    Email</a>
                            </li>
                            <li>
                                <a href="https://wa.me/1234567890" class="ms-btn ms-btn--bordered">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_3124_517" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0"
                                            width="21" height="21">
                                            <path d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z" fill="white"></path>
                                        </mask>
                                        <g mask="url(#mask0_3124_517)">
                                            <path
                                                d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                                stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                                fill="#1B1B1B"></path>
                                        </g>
                                    </svg>

                                    WhatsApp</a>
                            </li>
                        </ul>
                    </div>

                    <!-- bottom -->
                    <ul class="ms-apartments-main__sidebar__bottom-list">
                        <li>
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.94754 1.4101L9.90023 2.12323C10.1608 2.31826 10.435 2.41807 10.76 2.43616L11.9482 2.50226C12.6596 2.54182 13.2375 3.02666 13.4 3.72048L13.6714 4.87913C13.7456 5.19601 13.8916 5.44876 14.1289 5.67148L14.9966 6.48588C15.5162 6.97351 15.6471 7.71635 15.3257 8.35229L14.7889 9.41432C14.642 9.70479 14.5914 9.9922 14.63 10.3154L14.7712 11.497C14.8558 12.2045 14.4786 12.8577 13.8236 13.1383L12.7297 13.6068C12.4305 13.7349 12.207 13.9225 12.0288 14.1949L11.3775 15.1908C10.9875 15.7871 10.2787 16.0451 9.59661 15.839L8.45748 15.4947C8.14595 15.4006 7.85411 15.4006 7.54254 15.4947L6.40342 15.839C5.72136 16.0451 5.01254 15.7871 4.62254 15.1908L3.9712 14.1949C3.79304 13.9225 3.56948 13.7349 3.27032 13.6068L2.17632 13.1382C1.52132 12.8577 1.14417 12.2044 1.22873 11.4969L1.36998 10.3153C1.40861 9.99213 1.35792 9.70473 1.21111 9.41426L0.674262 8.35223C0.352824 7.71632 0.483793 6.97348 1.00336 6.48582L1.87107 5.67141C2.10839 5.4487 2.25429 5.19595 2.32851 4.87907L2.59995 3.72041C2.76245 3.02663 3.34029 2.54179 4.05176 2.5022L5.23992 2.4361C5.56489 2.41804 5.83914 2.3182 6.0997 2.12316L7.05239 1.41004C7.62279 0.983102 8.37711 0.983102 8.94754 1.4101ZM7.39223 8.90757L6.35836 7.8737C6.09039 7.60573 5.65573 7.60573 5.38779 7.8737C5.11982 8.14166 5.11982 8.57629 5.38779 8.84426L6.90845 10.3649C7.17642 10.6328 7.61107 10.6329 7.87901 10.3649C8.79557 9.44829 9.70176 8.52141 10.6141 7.60063C10.8801 7.33213 10.8793 6.89888 10.6111 6.63223C10.3429 6.36551 9.90845 6.36626 9.64214 6.63523L7.39223 8.90757ZM7.99995 3.38863C6.58867 3.38863 5.31095 3.9607 4.38607 4.88557C3.4612 5.81048 2.88914 7.08816 2.88914 8.49945C2.88914 9.91073 3.4612 11.1884 4.38607 12.1133C5.31095 13.0382 6.58867 13.6103 7.99995 13.6103C9.41123 13.6103 10.6889 13.0382 11.6138 12.1133C12.5387 11.1884 13.1108 9.91073 13.1108 8.49945C13.1108 7.08816 12.5387 5.81045 11.6138 4.88557C10.6889 3.9607 9.41123 3.38863 7.99995 3.38863ZM11.2603 5.2391C10.4259 4.40473 9.2732 3.88863 7.99995 3.88863C6.7267 3.88863 5.57398 4.40473 4.73961 5.2391C3.90523 6.07348 3.38914 7.2262 3.38914 8.49945C3.38914 9.7727 3.90523 10.9254 4.73961 11.7598C5.57398 12.5942 6.7267 13.1103 7.99995 13.1103C9.2732 13.1103 10.4259 12.5942 11.2603 11.7598C12.0947 10.9254 12.6107 9.77273 12.6107 8.49948C12.6108 7.2262 12.0947 6.07348 11.2603 5.2391Z"
                                    fill="#1B1B1B" />
                            </svg>

                            <a href="#"> 25 <span>projects</span></a>
                        </li>
                        <li>
                            <a href="#"> View all Properties </a>
                        </li>
                    </ul>
                </div>
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


<script>
// window.addEventListener('load', function() {
//     jQuery(function($) {
//         const videoSlider = jQuery(".ms-apartments-main__videos--slider");
//         if (videoSlider?.length) {
//             videoSlider.slick({
//                 slidesToShow: 2,
//                 slidesToScroll: 1,
//                 initialSlide: 0,

//                 dots: false /* image slide dots */,
//                 arrows: false /* image slide arrow */,
//                 centerMode: false,
//                 focusOnSelect: true,
//                 centerPadding: "30px",
//                 // prevArrow:
//                 //   '<a class="slick-prev"><i class="fas fa-arrow-left" alt="Arrow Icon"></i></a>',
//                 // nextArrow:
//                 //   '<a class="slick-next"><i class="fas fa-arrow-right" alt="Arrow Icon"></i></a>',
//                 responsive: [
//                     {
//                         breakpoint: 1600,
//                         settings: {
//                             arrows: false,
//                             dots: false,
//                         },
//                     },
//                     {
//                         breakpoint: 1200,
//                         settings: {
//                             arrows: false,
//                             dots: false,
//                         },
//                     },
//                     {
//                         breakpoint: 768,
//                         settings: {
//                             arrows: false,
//                             dots: false,
//                         },
//                     },
//                     {
//                         breakpoint: 767,
//                         settings: {
//                             arrows: false,
//                             dots: false,
//                             slidesToShow: 1.17,
//                         },
//                     },
//                 ],
//             });
//         }
//         /*  14. LightCase jQuery Active  */
//         const msLightcase = jQuery(".ms-lightcase");
//         if (msLightcase?.length) {
//             msLightcase.lightcase({
//                 transition: "elastic",
//                 swipe: true,
//                 maxWidth: 1170,
//                 maxHeight: 600,
//             });
//         }
//     });
// });
</script>