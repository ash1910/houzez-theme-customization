<?php
global $post, $ele_thumbnail_size, $image_size, $listing_agent_info, $buttonsComposer; 

$listing_agent_info = houzez20_property_contact_form();

if( houzez_is_fullwidth_2cols_custom_width() ) {
	$image_size = 'houzez-item-image-4';
} else {
	$image_size = 'houzez-item-image-1';
}

$images = houzez_get_property_gallery_v1($image_size);

$address_composer = houzez_option('listing_address_composer');
$enabled_data = isset($address_composer['enabled']) ? $address_composer['enabled'] : 0;
$temp_array = array();

if ($enabled_data) {
	unset($enabled_data['placebo']);
	foreach ($enabled_data as $key=>$value) {

		
		if( $key == 'address' ) {
			$map_address = houzez_get_listing_data('property_map_address');

			if( $map_address != '' ) {
				$temp_array[] = $map_address;
			}

		} else if( $key == 'streat-address' ) {
			$property_address = houzez_get_listing_data('property_address');

			if( $property_address != '' ) {
				$temp_array[] = $property_address;
			}

		} else if( $key == 'country' ) {
			$country = houzez_taxonomy_simple('property_country');

			if( $country != '' ) {
				$temp_array[] = $country;
			}

		} else if( $key == 'state' ) {
			$state = houzez_taxonomy_simple('property_state');

			if( $state != '' ) {
				$temp_array[] = $state;
			}

		} else if( $key == 'city' ) {
			$city = houzez_taxonomy_simple('property_city');

			if( $city != '' ) {
				$temp_array[] = $city;
			}

		} else if( $key == 'area' ) {
			$area = houzez_taxonomy_simple('property_area');

			if( $area != '' ) {
				$temp_array[] = $area;
			}

		}
		

	}

	$address = join( ", ", $temp_array );
}

if(empty($listing_id)) {
    $listing_id = get_the_ID();
} 

$output = '';
$sale_price     = get_post_meta( $listing_id, 'fave_property_price', true );
$second_price   = get_post_meta( $listing_id, 'fave_property_sec_price', true );
$price_postfix  = get_post_meta( $listing_id, 'fave_property_price_postfix', true );
$price_prefix   = get_post_meta( $listing_id, 'fave_property_price_prefix', true );
$currency_symbol = houzez_get_currency();
$price_separator = houzez_option('currency_separator');

$handover = get_post_meta( $listing_id, 'fave_handover', true );
$completion = get_post_meta( $listing_id, 'fave_completion', true );

$agent_whatsapp = $listing_agent_info['agent_whatsapp'] ?? '';
if(!empty($agent_whatsapp)) {
    $agent_whatsapp_call = $listing_agent_info['agent_whatsapp_call'];
    $agent_whatsapp_link = "https://api.whatsapp.com/send?phone=".$agent_whatsapp_call."&text=".houzez_option('spl_con_interested', "Hello, I am interested in")." [".get_the_title()."] ".get_permalink();
}

// Agency Picture
$agent_info = @$listing_agent_info['agent_info'];
$agency_logo = '';
if( !empty( $agent_info[0] )) {
    if( $agent_agency_id = get_post_meta($agent_info[0]['agent_id'], 'fave_agent_agencies', true) ) {

        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $agent_agency_id ), 'full' );
        if( !empty($thumbnail_src) ) {
            $agency_logo = esc_attr( $thumbnail_src[ 0 ] );
        }
        $agent_agency_link = esc_url(get_permalink($agent_agency_id));
    }
}
elseif( houzez_is_developer($post->post_author) ){
    $developer_logo = get_user_meta($post->post_author, 'fave_author_custom_picture', true);
    if( !empty($developer_logo) ) {
        $agency_logo = esc_attr( $developer_logo );
    }
}

$added_wishlist = '';
$userID = get_current_user_id();
$favorite_ids = get_user_meta( $userID, 'houzez_favorites', true );
if( !empty($favorite_ids) && in_array($listing_id, $favorite_ids) ) {
    $added_wishlist = 'added-wishlist';
}
?>


<!-- card 1 -->
<div
    class="ms-apartments-main__card ms-apartments-main__card--3"
>
    <div class="ms-apartments-main__card__thumbnail">
        <div
            class="ms-aparments-main__card__slider ms-aparments-maincardslider swiper"
        >
            <div class="swiper-wrapper">
                <?php foreach ($images as $image) { ?>
                <div class="swiper-slide">
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <img src="<?php echo $image['image']; ?>" alt="<?php echo $image['alt']; ?>" />
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="ms-apartments-main__card__thumbnail__header">
            <?php if(!empty($completion)) { ?>
            <div class="ms-apartments-main__card__thumbnail__status">
                <?php echo $completion; ?>
            </div>
            <?php } ?>
            <?php if(houzez_option('disable_favorite', 1)) { ?>
            <a
                href="javascript:void(0)"
                class="ms-apartments-main__card__thumbnail__heart add-favorite-js item-favorite <?php echo esc_attr($added_wishlist); ?>" 
                data-listid="<?php echo intval($post->ID)?>"
                ><i class="fa-solid fa-heart"></i>
                <i class="fa-light fa-heart"></i
            ></a>
            <?php } ?>
        </div>
    </div>
    <div class="ms-apartments-main__card__content">
        <?php if(!empty($agency_logo)) { ?>
        <div class="ms-apartments-main__card__logo">
            <a href="<?php echo $agent_agency_link ?? '#'; ?>" target="_blank">
                <img
                    src="<?php echo $agency_logo; ?>"
                    alt=""
            /></a>
        </div>
        <?php } ?>
        <div class="ms-apartments-main__card__heading">
            <h5>
                <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
            </h5>
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <i class="icon-location_grey"></i>
                <?php echo $address; ?></a
            >
        </div>
        <!-- price -->

        <!-- details list -->
        <ul class="ms-apartments-main____card__details-list ms-apartments-main____card__details-list--2">
            <li>
                <?php $property_type = houzez_taxonomy_simple('property_type'); 
                if(!empty($property_type)) {
                    echo '<div><i class="icon-building"> </i> '.$property_type.'</div>';
                }
                ?>
                <?php if(!empty($handover)) { ?>
                <div>
                    <i class="icon-calendar_balck_fill"> </i> <?php echo $handover; ?>
                </div>
                <?php } ?>
            </li>
            <li>
                <hr />
            </li>
            <li>
                <div class="ms-apartments-main____card__price">
                    <h6><?php 
                        // Convert to millions if price is larger than a million
                        if(is_numeric($sale_price) && $sale_price >= 1000000) {
                            $price_in_millions = number_format($sale_price / 1000000, 2);
                            echo $currency_symbol . $price_in_millions . ' M';
                        } else {
                            echo $price_prefix . houzez_get_property_price($sale_price);
                        }
                        ?>
                    </h6>
                </div>
                <p>Starting Price</p>
            </li>
            <li>
                <hr />
            </li>
            <li>
                <!-- card action -->
                <ul class="ms-apartments-main____card__button-list">
                    <?php if(!empty($agent_whatsapp)) { ?>
                    <li>
                        <a
                            href="<?php echo $agent_whatsapp_link; ?>"
                            class="ms-btn ms-btn--bordered"
                            target="_blank"
                        >
                            <svg
                                width="21"
                                height="21"
                                viewBox="0 0 21 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <mask
                                    id="mask0_3124_517"
                                    style="mask-type: luminance"
                                    maskUnits="userSpaceOnUse"
                                    x="0"
                                    y="0"
                                    width="21"
                                    height="21"
                                >
                                    <path
                                        d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z"
                                        fill="white"
                                    />
                                </mask>
                                <g mask="url(#mask0_3124_517)">
                                    <path
                                        d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                        stroke="#1B1B1B"
                                        stroke-width="1.2"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                        fill="#1B1B1B"
                                    />
                                </g>
                            </svg>

                            WhatsApp</a
                        >
                    </li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </div>
</div>