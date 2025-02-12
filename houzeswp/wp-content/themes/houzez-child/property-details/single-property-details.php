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