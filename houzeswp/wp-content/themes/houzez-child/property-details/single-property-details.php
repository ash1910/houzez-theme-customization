<?php 
global $hide_fields;

if(houzez_get_map_system() == 'google') {
	wp_enqueue_script('houzez-overview-listing-map', HOUZEZ_JS_DIR_URI. 'single-property-google-overview-map.js', array('jquery'), '1.0.0', true);
} else {
    wp_enqueue_script('houzez-overview-listing-map-detail',  get_stylesheet_directory_uri().'/js/single-property-osm-overview-map-detail.js', array('jquery'), '1.0.0', true);
}

if(empty($listing_id)) {
    $listing_id = get_the_ID();
} 
$sale_price     = get_post_meta( $listing_id, 'fave_property_price', true );
$second_price   = get_post_meta( $listing_id, 'fave_property_sec_price', true );
$price_postfix  = get_post_meta( $listing_id, 'fave_property_price_postfix', true );
$price_prefix   = get_post_meta( $listing_id, 'fave_property_price_prefix', true );

$added_wishlist = '';
$icon = '';
$userID = get_current_user_id();
$favorite_ids = get_user_meta( $userID, 'houzez_favorites', true );
if( !empty($favorite_ids) && in_array($listing_id, $favorite_ids) ) {
    $added_wishlist = 'added-wishlist';
    $icon = 'text-danger';
}

$features = wp_get_post_terms( get_the_ID(), 'property_feature', array("fields" => "all"));
$features_icons = houzez_option('features_icons');
$additional_features = get_post_meta( get_the_ID(), 'additional_features', true );
$hide_detail = houzez_option('hide_detail_prop_fields');

$status = get_the_terms(get_the_ID(), 'property_status'); 
$types = get_the_terms(get_the_ID(), 'property_type'); 
$newprojects = 0;
foreach ($types as $type) {
    if($type->slug == "new-projects") {
        $newprojects = 1;
    }
}
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
                        <?php
                        if($newprojects == 1) {
                            $page_url = get_page_by_path("new-projects") ? home_url() . '/new-projects' : home_url() . '/search-results?type%5B%5D=new-projects';
                        ?>
                        <li><a href="<?php echo $page_url; ?>">
                            New Projects
                        </a></li>
                        <li>></li>
                        <?php }
                        elseif($status && count($status) > 0 ) {
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
                            <h6><?php echo $price_prefix. houzez_get_property_price($sale_price) . (!empty($price_postfix) ? "/" . $price_postfix : ""); ?></h6>
                        </div>

                        <ul class="ms-apartments-main____card__button-list">
                            <?php if( houzez_option('prop_detail_favorite') != 0 ) { ?>
                            <li>
                                <a href="javascript:void(0)" class="ms-btn ms-btn--primary add-favorite-js <?php echo $added_wishlist; ?>" data-listid="<?php echo intval($listing_id)?>">
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
                            <?php if($newprojects != 1) { ?>
                            <li>
                                <button data-toggle="modal" data-target="#msReportModal" class="ms-btn ms-btn--primary">
                                    <i class="icon-report"></i>
                                    Report
                                </button>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- overwiew -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Overview</h4>
                    </div>
                    <?php if($newprojects == 1) { ?>
                        <div class="ms-apartments-main__overview ms-apartments-main__overview--2 ms-apartments-main__overview--3">
                            <ul class="ms-apartments-main__overview__item-list">
                                <?php $property_type = houzez_taxonomy_simple('property_type');
                                if(!empty($property_type)) { ?>
                                <li>
                                    <p>Property Type</p>
                                    <h6><?php echo $property_type; ?></h6>
                                </li>
                                <?php } ?>
                                <?php $sale_price = get_post_meta(get_the_ID(), 'fave_property_price', true);
                                if( @$hide_fields['price'] != 1 && !empty($sale_price) ) { ?>
                                <li>
                                    <p>Starting From</p>
                                    <h6><?php echo $price_prefix. houzez_get_property_price($sale_price) . (!empty($price_postfix) ? "/" . $price_postfix : ""); ?></h6>
                                </li>
                                <?php } ?>
                                <?php $construction_status = houzez_get_listing_data('completion');
                                if(!empty($construction_status)) { ?>
                                <li>
                                    <p>Construction Status</p>
                                    <h6><?php echo $construction_status; ?></h6>
                                </li>
                                <?php } ?>
                                <?php 
                                $prop_payment_val = "";
                                $prop_payment_plan_down  = houzez_get_listing_data('down-payment');
                                $prop_payment_plan_during_construction  = houzez_get_listing_data('during-construction');
                                $prop_payment_plan_on_handover  = houzez_get_listing_data('on-handover');
                                if(!empty($prop_payment_plan_down)) {
                                    $prop_payment_val = $prop_payment_plan_down;
                                }
                                if(!empty($prop_payment_plan_during_construction)) {
                                    $prop_payment_val .= "/".$prop_payment_plan_during_construction;
                                }
                                if(!empty($prop_payment_plan_on_handover)) {
                                    $prop_payment_val .= "/".$prop_payment_plan_on_handover;
                                }
                                if(!empty($prop_payment_val)) { ?>
                                <li>
                                    <p>Payment Plan</p>
                                    <h6><?php echo $prop_payment_val; ?></h6>
                                </li>
                                <?php } ?>
                                <?php $number_of_buildings = houzez_get_listing_data('number-of-buildings');
                                if(!empty($number_of_buildings)) { ?>
                                <li>
                                    <p>Number of buildings</p>
                                    <h6><?php echo $number_of_buildings; ?></h6>
                                </li>
                                <?php } ?>
                                <?php $handover = houzez_get_listing_data('handover');
                                if(!empty($handover)) { ?>
                                <li>
                                    <p>Handover</p>
                                    <h6><?php echo $handover; ?></h6>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } else { ?>
                    <div
                        class="ms-apartments-main__overview ms-apartments-main__overview--2 ms-apartments-main__overview--3">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php $property_type = houzez_taxonomy_simple('property_type');
                            if(!empty($property_type)) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $bedrooms = get_post_meta(get_the_ID(), 'fave_property_bedrooms', true);
                            if(!empty($bedrooms)) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $bathrooms = get_post_meta(get_the_ID(), 'fave_property_bathrooms', true);
                            if(!empty($bathrooms)) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $garages = get_post_meta(get_the_ID(), 'fave_property_garage', true);
                            if(!empty($garages)) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $sqft = get_post_meta(get_the_ID(), 'fave_property_size', true);
                            if(!empty($sqft)) { ?>
                            <li>
                                <p>sqft</p>
                                <h6><?php echo $sqft; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $property_id = get_post_meta(get_the_ID(), 'fave_property_id', true);
                            if(!empty($property_id)) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $year_built = get_post_meta(get_the_ID(), 'fave_property_year', true);
                            if(!empty($year_built)) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
                <?php 
                if($newprojects == 1) {
                    $project_announcement = get_post_meta($listing_id, 'fave_project-announcement', true);
                    $expected_booking = get_post_meta($listing_id, 'fave_expected-booking', true);
                    $construction_started = get_post_meta($listing_id, 'fave_construction-started', true);
                    $expected_completion = get_post_meta($listing_id, 'fave_expected-completion', true);
                    $project_announcement_date = get_post_meta($listing_id, 'fave_project-announcement-date', true);
                    $expected_booking_date = get_post_meta($listing_id, 'fave_expected-booking-date', true);
                    $construction_started_date = get_post_meta($listing_id, 'fave_construction-started-date', true);
                    $expected_completion_date = get_post_meta($listing_id, 'fave_expected-completion-date', true);

                    if( !empty($project_announcement) || !empty($project_announcement_date) || 
                    !empty($expected_booking) || !empty($expected_booking_date) ||
                    !empty($construction_started) || !empty($construction_started_date) ||
                    !empty($expected_completion) || !empty($expected_completion_date) ){
                ?>
                <!-- timeline -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Project timeline</h4>
                    </div>

                    <div class="ms-apartments-main__timeline">
                        <!-- timeline single -->
                        <?php if( !empty($project_announcement) || !empty($project_announcement_date) ){?>
                        <div class="ms-apartments-main__timeline__single <?php echo !empty($project_announcement) && $project_announcement == 'Yes' ? 'complete' : ''; ?>">
                            <div>
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.69316 5.95817C2.18691 5.62004 1.51065 5.69692 1.0919 6.13942C0.673779 6.5813 0.634402 7.2613 1.00003 7.74817L4.75003 12.7482C4.97628 13.0494 5.32627 13.2332 5.70315 13.2475C6.0794 13.2613 6.44253 13.105 6.69066 12.8213L15.4407 2.82128C15.8725 2.32816 15.8494 1.58503 15.3881 1.11878C14.9269 0.652534 14.1844 0.622535 13.6863 1.04878L5.65565 7.93254L2.69316 5.95817Z"
                                        fill="#F5F5F5" />
                                </svg>
                            </div>

                            <div>
                                <p class="ms-apartments-main__timeline__single__title">
                                    Project Announcement
                                </p>
                                <p><?php echo $project_announcement_date;?></p>
                            </div>
                        </div>
                        <?php }?>

                        <?php if( !empty($expected_booking) || !empty($expected_booking_date) ){?>
                        <div class="ms-apartments-main__timeline__single <?php echo !empty($expected_booking) && $expected_booking == 'Yes' ? 'complete' : ''; ?>">
                            <div>
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.69316 5.95817C2.18691 5.62004 1.51065 5.69692 1.0919 6.13942C0.673779 6.5813 0.634402 7.2613 1.00003 7.74817L4.75003 12.7482C4.97628 13.0494 5.32627 13.2332 5.70315 13.2475C6.0794 13.2613 6.44253 13.105 6.69066 12.8213L15.4407 2.82128C15.8725 2.32816 15.8494 1.58503 15.3881 1.11878C14.9269 0.652534 14.1844 0.622535 13.6863 1.04878L5.65565 7.93254L2.69316 5.95817Z"
                                        fill="#F5F5F5" />
                                </svg>
                            </div>

                            <div>
                                <p class="ms-apartments-main__timeline__single__title">
                                    Expected Booking Date
                                </p>
                                <p><?php echo $expected_booking_date;?></p>
                            </div>
                        </div>
                        <?php }?>

                        <?php if( !empty($construction_started) || !empty($construction_started_date) ){?>
                        <div class="ms-apartments-main__timeline__single <?php echo !empty($construction_started) && $construction_started == 'Yes' ? 'complete' : ''; ?>">
                            <div>
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.69316 5.95817C2.18691 5.62004 1.51065 5.69692 1.0919 6.13942C0.673779 6.5813 0.634402 7.2613 1.00003 7.74817L4.75003 12.7482C4.97628 13.0494 5.32627 13.2332 5.70315 13.2475C6.0794 13.2613 6.44253 13.105 6.69066 12.8213L15.4407 2.82128C15.8725 2.32816 15.8494 1.58503 15.3881 1.11878C14.9269 0.652534 14.1844 0.622535 13.6863 1.04878L5.65565 7.93254L2.69316 5.95817Z"
                                        fill="#F5F5F5" />
                                </svg>
                            </div>

                            <div>
                                <p class="ms-apartments-main__timeline__single__title">
                                    Construction Started
                                </p>
                                <p><?php echo $construction_started_date;?></p>
                            </div>
                        </div>
                        <?php }?>

                        <?php if( !empty($expected_completion) || !empty($expected_completion_date) ){?>
                        <div class="ms-apartments-main__timeline__single <?php echo !empty($expected_completion) && $expected_completion == 'Yes' ? 'complete' : ''; ?>">
                            <div>
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.69316 5.95817C2.18691 5.62004 1.51065 5.69692 1.0919 6.13942C0.673779 6.5813 0.634402 7.2613 1.00003 7.74817L4.75003 12.7482C4.97628 13.0494 5.32627 13.2332 5.70315 13.2475C6.0794 13.2613 6.44253 13.105 6.69066 12.8213L15.4407 2.82128C15.8725 2.32816 15.8494 1.58503 15.3881 1.11878C14.9269 0.652534 14.1844 0.622535 13.6863 1.04878L5.65565 7.93254L2.69316 5.95817Z"
                                        fill="#F5F5F5" />
                                </svg>
                            </div>

                            <div>
                                <p class="ms-apartments-main__timeline__single__title">
                                    Expected Completion
                                </p>
                                <p><?php echo $expected_completion_date;?></p>
                            </div>
                        </div>
                        <?php }?>

                    </div>
                </div>
                <?php } }?>

                <!-- description -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Description</h4>
                    </div>

                    <div class="ms-apartments-main__desc">
                        <?php the_content(); ?>

                        <?php 
                        if($newprojects == 1) {
                        $attachments = get_post_meta(get_the_ID(), 'fave_attachments', false);
                        $documents_download = houzez_option('documents_download');
                        if(!empty($attachments) && $attachments[0] != "" ) { ?>

                        <!-- document -->
                        <h6>Property Documents</h6>
                        <div class="ms-apartments-main__doc-list">
                            <?php 
                            foreach( $attachments as $attachment_id ) {
                            $attachment_meta = houzez_get_attachment_metadata($attachment_id); 

                            if(!empty($attachment_meta )) {
                            ?>
                            <a href="<?php echo esc_url( $attachment_meta->guid ); ?>" target="_blank">
                                <div class="ms-apartments-main__doc__content">
                                    <span class="ms-apartments-main__doc__icon">
                                        <svg width="28" height="32" viewBox="0 0 28 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.7139 1.1578C17.7685 0.454742 16.63 0.0587898 15.4523 0.0234247C12.0218 -0.0604081 8.58942 0.0808327 5.17734 0.446237C2.89828 0.710612 0.712969 2.89592 0.448594 5.17499C-0.149531 10.7672 -0.149531 21.1509 0.448594 26.7422C0.712969 29.0212 2.89828 31.2075 5.17734 31.4719C10.7551 32.0694 16.3808 32.0694 21.9586 31.4719C24.2377 31.2075 26.423 29.0222 26.6873 26.7422C27.0952 22.9341 27.2245 16.8984 27.0764 11.6765V11.655C27.0663 10.5285 26.7008 9.43402 26.032 8.52749C23.971 5.7221 21.5048 3.23843 18.7139 1.1578Z"
                                                fill="url(#paint0_linear_857_7762)" />
                                            <path
                                                d="M27.0695 11.6775C26.6354 11.6878 26.2004 11.6953 25.7692 11.6953C23.9334 11.6949 22.099 11.5969 20.2736 11.4019H20.2576C19.1701 11.2762 18.1014 10.7362 17.2473 9.88219C16.3933 9.02813 15.8533 7.9575 15.7276 6.86719C15.7272 6.86188 15.7272 6.85655 15.7276 6.85125C15.4853 4.58388 15.3926 2.303 15.4501 0.0234375C16.6281 0.0586121 17.7669 0.454574 18.7126 1.15781C21.5088 3.24366 23.979 5.73406 26.042 8.54719C26.7074 9.44823 27.0686 10.5377 27.0733 11.6578L27.0695 11.6775Z"
                                                fill="url(#paint1_linear_857_7762)" />
                                            <path
                                                d="M7.13086 28.0656C6.5391 28.0641 5.97206 27.8281 5.55398 27.4093C5.31211 27.1672 5.12928 26.8726 5.01968 26.5484C4.91007 26.2242 4.87664 25.8791 4.92198 25.5399C4.96731 25.2007 5.0902 24.8765 5.2811 24.5925C5.47199 24.3085 5.72577 24.0722 6.02273 23.9021C7.54784 23.0304 9.13823 22.2781 10.7796 21.6521C10.879 21.4646 10.9777 21.2721 11.0759 21.0746C11.6859 19.8565 12.1975 18.5915 12.6059 17.2918C11.7753 15.9884 11.1555 14.5621 10.7693 13.0656C10.6535 12.6014 10.6944 12.1119 10.8856 11.6734C11.0768 11.2348 11.4076 10.8718 11.8266 10.6408C12.2455 10.4098 12.7291 10.3237 13.202 10.396C13.6749 10.4683 14.1107 10.6949 14.4415 11.0406C14.656 11.265 14.8201 11.5326 14.9228 11.8256C15.0254 12.1186 15.0643 12.4301 15.0368 12.7393C14.9045 14.1982 14.6381 15.6418 14.2409 17.0518C14.9982 18.1309 15.8869 19.1116 16.8865 19.9712C17.9445 19.7878 19.0122 19.6655 20.0843 19.6046C20.4008 19.5881 20.7169 19.6456 21.0073 19.7725C21.2978 19.8994 21.5547 20.0922 21.7576 20.3357C21.9606 20.5791 22.104 20.8665 22.1765 21.1751C22.2491 21.4837 22.2487 21.8049 22.1754 22.1133C22.1022 22.4217 21.9581 22.7087 21.7546 22.9517C21.551 23.1947 21.2937 23.387 21.0029 23.5132C20.7122 23.6394 20.396 23.6961 20.0795 23.6788C19.763 23.6616 19.4549 23.5708 19.1796 23.4137C18.229 22.8697 17.3219 22.2528 16.4665 21.5687C14.8923 21.8777 13.3442 22.3077 11.8362 22.855C10.9755 24.4073 9.9771 25.8791 8.85304 27.2528C8.6559 27.4927 8.41079 27.6888 8.13342 27.8284C7.85606 27.9681 7.55257 28.0482 7.24242 28.0637C7.20305 28.0656 7.16742 28.0656 7.13086 28.0656ZM9.51961 23.7962C8.62054 24.2031 7.69898 24.67 6.76242 25.2025C6.66526 25.2573 6.58228 25.3341 6.5201 25.4267C6.45791 25.5193 6.41826 25.6252 6.4043 25.7359C6.38353 25.8934 6.41456 26.0534 6.49272 26.1918C6.57087 26.3301 6.6919 26.4392 6.83754 26.5028C6.98319 26.5663 7.14554 26.5807 7.30009 26.5438C7.45464 26.5069 7.593 26.4208 7.69429 26.2984C8.34874 25.4989 8.95812 24.6635 9.51961 23.7962ZM18.5327 21.2425C18.9677 21.5406 19.4299 21.8331 19.924 22.1171C19.9944 22.1581 20.0735 22.1822 20.1548 22.1874C20.2362 22.1926 20.3176 22.1788 20.3927 22.1471C20.4711 22.1152 20.5409 22.0654 20.5967 22.0017C20.6525 21.9381 20.6927 21.8623 20.714 21.7804C20.7354 21.6985 20.7374 21.6128 20.7199 21.53C20.7024 21.4472 20.6658 21.3696 20.613 21.3034C20.5599 21.2361 20.4914 21.1827 20.4132 21.1476C20.335 21.1125 20.2495 21.0968 20.164 21.1018C19.699 21.1243 19.1524 21.1684 18.5327 21.2425ZM13.6802 18.805C13.4093 19.555 13.1112 20.2778 12.798 20.9612C13.593 20.7146 14.3543 20.5112 15.0743 20.3443C14.5788 19.858 14.113 19.3424 13.6793 18.8003L13.6802 18.805ZM12.843 11.875C12.7427 11.8789 12.6445 11.9054 12.5557 11.9525C12.4669 11.9996 12.3899 12.066 12.3302 12.1468C12.2703 12.226 12.2291 12.3178 12.2098 12.4152C12.1904 12.5127 12.1935 12.6132 12.2187 12.7093C12.4406 13.5734 12.7526 14.4117 13.1496 15.2106C13.3331 14.3511 13.4645 13.4814 13.5434 12.6062C13.5526 12.5094 13.5406 12.4118 13.5082 12.3202C13.4758 12.2286 13.4238 12.1451 13.3559 12.0756C13.2902 12.0056 13.21 11.9509 13.1209 11.9152C13.0318 11.8796 12.936 11.8639 12.8402 11.8693L12.843 11.875Z"
                                                fill="url(#paint2_linear_857_7762)" />
                                            <defs>
                                                <linearGradient id="paint0_linear_857_7762" x1="25.0355" y1="29.9016" x2="-0.377345"
                                                    y2="4.48968" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#EF3739" />
                                                    <stop offset="0.54" stop-color="#EF3739" />
                                                    <stop offset="1" stop-color="#FF8C8B" />
                                                </linearGradient>
                                                <linearGradient id="paint1_linear_857_7762" x1="25.7336" y1="13.0134" x2="14.0973"
                                                    y2="1.37719" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#34344F" />
                                                    <stop offset="0.26" stop-color="#3B3B57" />
                                                    <stop offset="0.66" stop-color="#50506D" />
                                                    <stop offset="1" stop-color="#666684" />
                                                </linearGradient>
                                                <linearGradient id="paint2_linear_857_7762" x1="17.7434" y1="26.9743" x2="6.55711"
                                                    y2="15.7881" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#FFD2D2" />
                                                    <stop offset="0.57" stop-color="white" />
                                                    <stop offset="1" stop-color="white" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </span>
                                    <div class="ms-apartments-main__doc__title">
                                        <h6><?php echo esc_attr( $attachment_meta->post_title ); ?></h6>
                                        <span><?php echo esc_attr( $attachment_meta->post_excerpt ); ?></span>
                                    </div>
                                </div>

                                <div class="ms-apartments-main__doc__download-icon">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.4319 3.57268C21.7385 0.879349 15.7652 0.666016 14.0052 0.666016C12.2452 0.666016 6.27187 0.879349 3.57854 3.57268C0.885208 6.26602 0.671875 12.2393 0.671875 13.9993C0.671875 15.7593 0.885208 21.7327 3.57854 24.426C6.27187 27.1193 12.2452 27.3327 14.0052 27.3327C15.7652 27.3327 21.7385 27.1193 24.4319 24.426C27.1252 21.7327 27.3385 15.7593 27.3385 13.9993C27.3385 12.2393 27.1252 6.26602 24.4319 3.57268ZM16.6719 21.9993H11.3385C10.9849 21.9993 10.6458 21.8589 10.3957 21.6088C10.1457 21.3588 10.0052 21.0196 10.0052 20.666C10.0052 20.3124 10.1457 19.9733 10.3957 19.7232C10.6458 19.4732 10.9849 19.3327 11.3385 19.3327H16.6719C17.0255 19.3327 17.3646 19.4732 17.6147 19.7232C17.8647 19.9733 18.0052 20.3124 18.0052 20.666C18.0052 21.0196 17.8647 21.3588 17.6147 21.6088C17.3646 21.8589 17.0255 21.9993 16.6719 21.9993ZM18.9519 13.6127L14.9519 17.6127C14.8279 17.7377 14.6805 17.8368 14.518 17.9045C14.3555 17.9722 14.1812 18.0071 14.0052 18.0071C13.8292 18.0071 13.6549 17.9722 13.4924 17.9045C13.33 17.8368 13.1825 17.7377 13.0585 17.6127L9.05854 13.6127C8.80747 13.3616 8.66642 13.0211 8.66642 12.666C8.66642 12.3109 8.80747 11.9704 9.05854 11.7193C9.30961 11.4683 9.65014 11.3272 10.0052 11.3272C10.3603 11.3272 10.7008 11.4683 10.9519 11.7193L12.6719 13.4527V7.33268C12.6719 6.97906 12.8124 6.63992 13.0624 6.38987C13.3124 6.13983 13.6516 5.99935 14.0052 5.99935C14.3588 5.99935 14.698 6.13983 14.948 6.38987C15.1981 6.63992 15.3385 6.97906 15.3385 7.33268V13.4527L17.0585 11.7193C17.3096 11.4683 17.6501 11.3272 18.0052 11.3272C18.3603 11.3272 18.7008 11.4683 18.9519 11.7193C19.2029 11.9704 19.344 12.3109 19.344 12.666C19.344 13.0211 19.2029 13.3616 18.9519 13.6127Z"
                                            fill="#1B1B1B" />
                                    </svg>
                                </div>
                            </a>
                            <?php } }?>
                        </div>
                        <?php } }?>
                        
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

                    <div class="ms-apartments-main__overview ms-apartments-main__overview--4 ">
                        <ul class="ms-apartments-main__overview__item-list">
                            <?php 
                            $property_id = get_post_meta(get_the_ID(), 'fave_property_id', true);
                            if( $hide_fields['prop_id'] != 1 && !empty($property_id) ) { ?>
                            <li>
                                <p>Property ID</p>
                                <h6><?php echo $property_id; ?></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $sale_price = get_post_meta(get_the_ID(), 'fave_property_price', true);
                            if( @$hide_fields['price'] != 1 && !empty($sale_price) ) { ?>
                            <li>
                                <p>Price</p>
                                <h6><small>Start from </small> <span class="ms-apartments-main__price-value"><?php echo $price_prefix. houzez_get_property_price($sale_price) . (!empty($price_postfix) ? "/" . $price_postfix : ""); ?></span></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $sqft = get_post_meta(get_the_ID(), 'fave_property_size', true);
                            if( @$hide_fields['size'] != 1 && !empty($sqft) ) { ?>
                            <li>
                                <p>Property Size</p>
                                <h6><?php echo $sqft; ?> sqft</h6> 
                            </li>
                            <?php } ?>
                            <?php $land_area = get_post_meta(get_the_ID(), 'fave_property_land', true);
                            if( $hide_fields['land_area'] != 1 && !empty($land_area) ) { ?>
                            <li>
                                <p>Land Area</p>
                                <h6><?php echo $land_area; ?> sqft</h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $bedrooms = get_post_meta(get_the_ID(), 'fave_property_bedrooms', true);
                            if( $hide_fields['bedrooms'] != 1 && !empty($bedrooms) ) { ?>
                            <li>
                                <p>Bedrooms</p>
                                <h6><?php echo $bedrooms; ?></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $bathrooms = get_post_meta(get_the_ID(), 'fave_property_bathrooms', true);
                            if( $hide_fields['bathrooms'] != 1 && !empty($bathrooms) ) { ?>
                            <li>
                                <p>Bathrooms</p>
                                <h6><?php echo $bathrooms; ?></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $garages = get_post_meta(get_the_ID(), 'fave_property_garage', true);
                            if( $hide_fields['garages'] != 1 && !empty($garages) ) { ?>
                            <li>
                                <p>Garages</p>
                                <h6><?php echo $garages; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $garage_size = get_post_meta(get_the_ID(), 'fave_property_garage_size', true);
                            if( @$hide_fields['garage_size'] != 1 && !empty($garage_size) ) { ?>
                            <li>
                                <p>Garage Size</p>
                                <h6><?php echo $garage_size; ?></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            $year_built = get_post_meta(get_the_ID(), 'fave_property_year', true);
                            if( $hide_fields['year_built'] != 1 && !empty($year_built) ) { ?>
                            <li>
                                <p>Year Built</p>
                                <h6><?php echo $year_built; ?></h6>
                            </li>
                            <?php } ?>
                            <?php 
                            if( @$hide_fields['property_type'] != 1 && !empty($property_type) ) { ?>
                            <li>
                                <p>Property Type</p>
                                <h6><?php echo $property_type; ?></h6>
                            </li>
                            <?php } ?>
                            <?php $property_status = houzez_taxonomy_simple('property_status');
                            if( @$hide_fields['property_status'] != 1 && !empty($property_status) ) { ?>
                            <li>
                                <p>Property Status</p>
                                <h6><?php echo $property_status; ?></h6>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- map -->
                <div class="ms-apartments-main__section">
                    <div class="ms-apartments-main__heading">
                        <h4>Map</h4>
                    </div>

                    <div class="ms-apartments-main__map">
                        <div id="houzez-overview-listing-map-detail" style="height: 350px;"></div>
                    </div>
                </div>
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
                if($newprojects == 1) {
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
                                                <?php } else {?>
                                                <li></li>
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
                                            <?php if( isset($plan['fave_plan_size']) && !empty( $plan['fave_plan_size'] ) ) { ?>
                                            <li>
                                                <h6>Size (sqft)</h6>
                                                <p><?php echo esc_attr( $plan['fave_plan_size'] ?? '' ); ?> m2</p>
                                            </li>
                                            <?php } ?>
                                            <?php if( !empty( $plan_image ) ) { ?>
                                            <li>
                                                <h6>Layout</h6>
                                                <?php if($filetype['ext'] != 'pdf' ) {?>
                                                <a target="_blank" href="<?php echo esc_url( $plan['fave_plan_image'] ); ?>" data-rel="lightcase:myCollection3">
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
                if($newprojects == 1) {
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
                if($newprojects == 1) {
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
                <div class="ms-slidebar__wrapper">
                    <!-- sidebar single -->
                    <?php get_template_part('property-details/partials/sidebar-agent-details'); ?>

                    <!-- sidebar single -->
                    <?php
                    if (is_active_sidebar('hz-custom-widget-area-1')) {
                        dynamic_sidebar('hz-custom-widget-area-1');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- start: New Project Details    -->

