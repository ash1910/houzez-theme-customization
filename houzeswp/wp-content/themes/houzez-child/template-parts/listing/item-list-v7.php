<?php 
global $post, $ele_thumbnail_size, $image_size, $listing_agent_info, $buttonsComposer, $prop_featured, $prop_verified_badge, $prop_property_images; 
$listing_agent_info = houzez20_property_contact_form();

$defaultButtons = array(
    'enabled' => array(
        'call' => 'Call',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
        'telegram' => 'Telegram',
        // Add other buttons as needed
    )
);

$listingButtonsComposer = houzez_option('listing_buttons_composer', $defaultButtons);

// Ensure that 'enabled' index exists
$buttonsComposer = isset($listingButtonsComposer['enabled']) ? $listingButtonsComposer['enabled'] : [];

// Remove the 'placebo' element
unset($buttonsComposer['placebo']);


if( houzez_is_fullwidth_2cols_custom_width() ) {
	$image_size = 'houzez-item-image-4';
} else {
	$image_size = 'houzez-item-image-1';
}
?>
<div class="item-listing-wrap hz-item-gallery-js card" data-hz-id="hz-<?php esc_attr_e($post->ID); ?>" <?php houzez_property_gallery($image_size); ?>>
	<div class="item-wrap item-wrap-v8 item-wrap-no-frame h-100">
		<div class="d-flex align-items-center h-100">
			<div class="item-header">
				<?php get_template_part('template-parts/listing/partials/item-featured-label'); ?>

				<?php
				$prop_featured = houzez_get_listing_data('featured');
				$prop_verified_badge = houzez_get_listing_data('verified_badge');
				$prop_property_images = houzez_get_listing_data('property_images');

				$property_images_count = 1;
				$property_images = get_post_meta( $post->ID, 'fave_property_images', false );

				if ( ! empty( $property_images ) ) {
					$property_images_count += count( array_filter( $property_images ) );
				}


				if( $prop_verified_badge == 1 ) {
					$prop_verified_badge_left = empty($prop_featured) ? "10" : "95";
					echo '<span class="label-featured label-verified label" style="left: '.$prop_verified_badge_left.'px;">
					<i class="label-icon">';
						include get_stylesheet_directory() . '/assets/images/verified_icon.svg';
					echo '</i>
					'.houzez_option('cl_verified_label', esc_html__( 'VERIFIED', 'houzez' )).'</span>';
				}
				?>
				<!-- <i class="label-listing-map-icon">
					<?php //include get_stylesheet_directory() . '/assets/images/listing_map_icon.svg';?>
				</i> -->
				<span class="label-listing-camera-icon-wrapper">
					<i class="label-listing-camera-icon"><?php include get_stylesheet_directory() . '/assets/images/camera_icon.svg';?></i>
					<span><?php echo sprintf('%02d', $property_images_count); ?></span>
				</span>

				<?php get_template_part('template-parts/listing/partials/item-labels'); ?>
				<?php get_template_part('template-parts/listing/partials/item-tools'); ?>
				<?php get_template_part('template-parts/listing/partials/item-image'); ?>
				<div class="preview_loader"></div>
			</div><!-- item-header -->	
			<div class="item-body flex-grow-1" onclick="location.href='<?php echo esc_url(get_permalink()); ?>';" style="cursor: pointer; padding-top: 25px; padding-bottom: 25px;">
					
				<?php
				// Agency Picture
				$agent_info = @$listing_agent_info['agent_info'];

				if( !empty( $agent_info[0] )) {
					if( $agent_agency_id = get_post_meta($agent_info[0]['agent_id'], 'fave_agent_agencies', true) ) {

						$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $agent_agency_id ), 'full' );
						if( !empty($thumbnail_src) ) {
							echo '<div class="list-item-agency-profile-pic"><img class="img-fluid" src="' . esc_attr( $thumbnail_src[ 0 ] ) . '"></div>';
						}
					}
					//echo apply_filters("houzez_get_agency_photo_url_by_agent_user_id", $agent_info[0]['agent_id']);
				}
				?>

				<ul class="item-amenities item-amenities-with-icons">
					<?php get_template_part('template-parts/listing/partials/type'); ?>
				</ul>
				<?php get_template_part('template-parts/listing/partials/item-price'); ?>
				<?php get_template_part('template-parts/listing/partials/item-title'); ?>
				<?php get_template_part('template-parts/listing/partials/item-address'); ?>
				<?php get_template_part('template-parts/listing/partials/item-features-v7'); ?>
			</div><!-- item-body -->
		</div><!-- d-flex -->
		<div class="item-footer">
			<div class="item-footer-left-wrap">
				<?php get_template_part('template-parts/listing/partials/item-author-v3'); ?>
				<?php get_template_part('template-parts/listing/partials/item-date'); ?>
				<?php //get_template_part('template-parts/listing/partials/item-author'); ?>
			</div><!-- item-footer-left-wrap -->
			<div class="item-footer-right-wrap">
				<?php get_template_part('template-parts/listing/partials/item-btn-v7'); ?>
			</div><!-- item-footer-right-wrap -->
		</div>
	</div><!-- item-wrap -->
	<?php get_template_part('template-parts/listing/partials/modal-phone-number'); ?>
	<?php get_template_part('template-parts/listing/partials/modal-agent-contact-form'); ?>
</div><!-- item-listing-wrap -->