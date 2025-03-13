<?php
global $post, $random_token; 

$agent_display = houzez_get_listing_data('agent_display_option');

if ($agent_display != 'none') { 

$agent_array = houzez20_property_contact_form();
$agent_array = $agent_array['agent_info'][0] ?? '';

$agent_name = isset($agent_array['agent_name']) ? $agent_array['agent_name'] : '';
$agent_mobile_call = isset($agent_array['agent_mobile_call']) ? $agent_array['agent_mobile_call'] : '';
$agent_whatsapp_call = isset($agent_array['agent_whatsapp_call']) ? $agent_array['agent_whatsapp_call'] : '';
$agent_number_call = isset($agent_array['agent_mobile_call']) ? $agent_array['agent_mobile_call'] : '';
$agent_picture = $agent_array['picture'] ?? '';
if( empty($agent_number_call) ) {
	$agent_number_call = isset($agent_array['agent_phone_call']) ? $agent_array['agent_phone_call'] : '';
}
$total_ratings = get_post_meta($agent_array['agent_id'], 'houzez_total_rating', true); 

if( empty( $total_ratings ) ) {
	$total_ratings = 0;
}

$types = get_the_terms(get_the_ID(), 'property_type'); 
$newprojects = 0;
foreach ($types as $type) {
    if($type->slug == "new-projects") {
        $newprojects = 1;
    }
}
?>
<?php if($newprojects == 1) { ?>
<!-- mobile-property-contact -->
<div class="ms-mobile-property-contact ms-mobile-property-contact--2  mobile-property-contact  visible-on-mobile">
	<div class="ms-mobile-property-contact__inner d-flex justify-content-between">
		<div class="agent-details flex-grow-1">
			<div class="ms-mobile-property-contact__author d-flex align-items-center">
				<div class="ms-mobile-property-contact__author__img agent-image">
					<a href="<?php echo esc_url($agent_array[ 'link' ]); ?>" target="_blank">
						<img class="rounded" src="<?php echo esc_url($agent_picture); ?>" width="60" height="60"
						alt="<?php echo esc_attr($agent_name); ?>">
					</a>
				</div>
				<ul class="ms-mobile-property-contact__author__info agent-information list-unstyled">
					<li class="ms-mobile-property-contact__author__name agent-name">
						<a href="<?php echo esc_url($agent_array[ 'link' ]); ?>" target="_blank"><?php echo esc_attr($agent_name); ?></a> 
					</li>
					<li>
						<span class="ms-mobile-property-contact__author__rating">
							<svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M13.9635 5.87072C13.8719 5.58724 13.6204 5.38589 13.3229 5.35908L9.28216 4.99217L7.68432 1.25229C7.56651 0.9782 7.29819 0.800781 7.00007 0.800781C6.70195 0.800781 6.43364 0.9782 6.31582 1.25293L4.71798 4.99217L0.67656 5.35908C0.379616 5.38653 0.128816 5.58724 0.0366355 5.87072C-0.0555452 6.15421 0.0295858 6.46514 0.254216 6.66115L3.30857 9.33984L2.40791 13.3072C2.34201 13.5989 2.45523 13.9005 2.69727 14.0754C2.82737 14.1694 2.97958 14.2173 3.13307 14.2173C3.26542 14.2173 3.39669 14.1816 3.51451 14.1111L7.00007 12.0279L10.4844 14.1111C10.7393 14.2645 11.0607 14.2505 11.3022 14.0754C11.5444 13.9 11.6575 13.5983 11.5916 13.3072L10.6909 9.33984L13.7453 6.66168C13.9699 6.46514 14.0557 6.15474 13.9635 5.87072Z"
									fill="#FFC107" />
							</svg>
							<?php echo sprintf("%.2f", $total_ratings); ?> </span>
					</li>
				</ul>
			</div><!-- d-flex -->
		</div><!-- agent-details -->



		<a href="javascript:void(0)" data-link="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> " class="ms-btn ms-btn--black hz-call-popup-js tracking_view" data-type="w" data-prop_id="<?php echo esc_attr($post->ID);?>" data-model-id="email-popup-<?php echo esc_attr($post->ID).'-'.$random_token; ?>">
			<svg
				width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_3124_517" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="21"
					height="21">
					<path d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z" fill="white"></path>
				</mask>
				<g mask="url(#mask0_3124_517)">
					<path
						d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
						stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
					</path>
					<path
						d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
						fill="#1B1B1B"></path>
				</g>
			</svg> WhatsApp</a>



	</div><!-- d-flex -->
</div>
<?php } else { ?>
<!-- mobile-property-contact -->
<div class="ms-mobile-property-contact  mobile-property-contact  visible-on-mobile">
	<div class="ms-mobile-property-contact__inner d-flex justify-content-between">
		<div class="agent-details flex-grow-1">
			<div class="ms-mobile-property-contact__author d-flex align-items-center">
				<div class="ms-mobile-property-contact__author__img agent-image">
					<a href="<?php echo esc_url($agent_array[ 'link' ]); ?>" target="_blank">
						<img class="rounded" src="<?php echo esc_url($agent_picture); ?>" width="60" height="60"
						alt="<?php echo esc_attr($agent_name); ?>">
					</a>
				</div>
				<ul class="ms-mobile-property-contact__author__info agent-information list-unstyled">
					<li class="ms-mobile-property-contact__author__name agent-name">
						<a href="<?php echo esc_url($agent_array[ 'link' ]); ?>" target="_blank"><?php echo esc_attr($agent_name); ?></a> </li>
					<li>
						<span class="ms-mobile-property-contact__author__rating">
							<svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M13.9635 5.87072C13.8719 5.58724 13.6204 5.38589 13.3229 5.35908L9.28216 4.99217L7.68432 1.25229C7.56651 0.9782 7.29819 0.800781 7.00007 0.800781C6.70195 0.800781 6.43364 0.9782 6.31582 1.25293L4.71798 4.99217L0.67656 5.35908C0.379616 5.38653 0.128816 5.58724 0.0366355 5.87072C-0.0555452 6.15421 0.0295858 6.46514 0.254216 6.66115L3.30857 9.33984L2.40791 13.3072C2.34201 13.5989 2.45523 13.9005 2.69727 14.0754C2.82737 14.1694 2.97958 14.2173 3.13307 14.2173C3.26542 14.2173 3.39669 14.1816 3.51451 14.1111L7.00007 12.0279L10.4844 14.1111C10.7393 14.2645 11.0607 14.2505 11.3022 14.0754C11.5444 13.9 11.6575 13.5983 11.5916 13.3072L10.6909 9.33984L13.7453 6.66168C13.9699 6.46514 14.0557 6.15474 13.9635 5.87072Z"
									fill="#FFC107" />
							</svg>
							<?php echo sprintf("%.2f", $total_ratings); ?></span>
					</li>
				</ul>
			</div><!-- d-flex -->
		</div><!-- agent-details -->
		<a href="javascript:void(0)" data-link="tel:<?php echo esc_attr($agent_mobile_call); ?>" class="ms-btn ms-btn--bordered hz-call-popup-js tracking_view" data-type="c" data-prop_id="<?php echo esc_attr($post->ID);?>" data-model-id="email-popup-<?php echo esc_attr($post->ID).'-'.$random_token; ?>">
			<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M17.3057 16.7743C17.3057 17.0743 17.2391 17.3827 17.0974 17.6827C16.9557 17.9827 16.7724 18.266 16.5307 18.5327C16.1224 18.9827 15.6724 19.3077 15.1641 19.516C14.6641 19.7243 14.1224 19.8327 13.5391 19.8327C12.6891 19.8327 11.7807 19.6327 10.8224 19.2243C9.86406 18.816 8.90573 18.266 7.95573 17.5743C6.9974 16.8743 6.08906 16.0993 5.2224 15.241C4.36406 14.3743 3.58906 13.466 2.8974 12.516C2.21406 11.566 1.66406 10.616 1.26406 9.67435C0.864063 8.72435 0.664062 7.81602 0.664062 6.94935C0.664062 6.38268 0.764062 5.84102 0.964062 5.34102C1.16406 4.83268 1.48073 4.36602 1.9224 3.94935C2.45573 3.42435 3.03906 3.16602 3.65573 3.16602C3.88906 3.16602 4.1224 3.21602 4.33073 3.31602C4.5474 3.41602 4.73906 3.56602 4.88906 3.78268L6.8224 6.50768C6.9724 6.71602 7.08073 6.90768 7.15573 7.09102C7.23073 7.26602 7.2724 7.44102 7.2724 7.59935C7.2724 7.79935 7.21406 7.99935 7.0974 8.19102C6.98906 8.38268 6.83073 8.58268 6.63073 8.78268L5.9974 9.44102C5.90573 9.53268 5.86406 9.64102 5.86406 9.77435C5.86406 9.84102 5.8724 9.89935 5.88906 9.96602C5.91406 10.0327 5.93906 10.0827 5.95573 10.1327C6.10573 10.4077 6.36406 10.766 6.73073 11.1993C7.10573 11.6327 7.50573 12.0743 7.93906 12.516C8.38906 12.9577 8.8224 13.366 9.26406 13.741C9.6974 14.1077 10.0557 14.3577 10.3391 14.5077C10.3807 14.5243 10.4307 14.5493 10.4891 14.5743C10.5557 14.5993 10.6224 14.6077 10.6974 14.6077C10.8391 14.6077 10.9474 14.5577 11.0391 14.466L11.6724 13.841C11.8807 13.6327 12.0807 13.4743 12.2724 13.3743C12.4641 13.2577 12.6557 13.1993 12.8641 13.1993C13.0224 13.1993 13.1891 13.2327 13.3724 13.3077C13.5557 13.3827 13.7474 13.491 13.9557 13.6327L16.7141 15.591C16.9307 15.741 17.0807 15.916 17.1724 16.1243C17.2557 16.3327 17.3057 16.541 17.3057 16.7743Z"
					stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10"></path>
				<path
					d="M14.4167 8.9987C14.4167 8.4987 14.025 7.73203 13.4417 7.10703C12.9083 6.53203 12.2 6.08203 11.5 6.08203"
					stroke="#1B1B1B" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
				<path d="M17.3333 8.99935C17.3333 5.77435 14.725 3.16602 11.5 3.16602" stroke="#1B1B1B" stroke-width="1.2"
					stroke-linecap="round" stroke-linejoin="round"></path>
			</svg>
		</a>
		<a href="javascript:void(0)" class="ms-btn ms-btn--bordered hz-email-popup-js" data-model-id="email-popup-<?php echo esc_attr($post->ID).'-'.$random_token; ?>">
			<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M14.6719 17.5827H6.33854C3.83854 17.5827 2.17188 16.3327 2.17188 13.416V7.58268C2.17188 4.66602 3.83854 3.41602 6.33854 3.41602H14.6719C17.1719 3.41602 18.8385 4.66602 18.8385 7.58268V13.416C18.8385 16.3327 17.1719 17.5827 14.6719 17.5827Z"
					stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
				</path>
				<path d="M14.6615 8L12.0531 10.0833C11.1948 10.7667 9.78645 10.7667 8.92812 10.0833L6.32812 8"
					stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
				</path>
			</svg>
		</a>

		<a href="javascript:void(0)" data-link="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $agent_whatsapp_call ); ?>&text=<?php echo houzez_option('spl_con_interested', "Hello, I am interested in").' ['.get_the_title().'] '.get_permalink(); ?> " class="ms-btn ms-btn--bordered hz-call-popup-js tracking_view" data-type="w" data-prop_id="<?php echo esc_attr($post->ID);?>" data-model-id="email-popup-<?php echo esc_attr($post->ID).'-'.$random_token; ?>">
			<svg
				width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_3124_517" style="mask-type: luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="21"
					height="21">
					<path d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z" fill="white"></path>
				</mask>
				<g mask="url(#mask0_3124_517)">
					<path
						d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
						stroke="#1B1B1B" stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round">
					</path>
					<path
						d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
						fill="#1B1B1B"></path>
				</g>
			</svg></a>



	</div><!-- d-flex -->
</div>
<?php } ?>
<?php } ?>