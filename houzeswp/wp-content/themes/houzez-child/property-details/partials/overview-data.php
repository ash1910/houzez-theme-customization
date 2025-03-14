<?php
$overview_data_composer = houzez_option('overview_data_composer');
$overview_data = $overview_data_composer['enabled'];
//$return_array = houzez20_property_contact_form(false);
//$agent_id = intval($return_array['agent_id']);
$property_id = houzez_get_listing_data('property_id');
$author_id  = get_post_field ('post_author', $property_id);

$i = 0;
if ($overview_data) {
	unset($overview_data['placebo']);

if ( houzez_is_developer($author_id ) ) {
	$property_type = houzez_taxonomy_simple('property_type');
	if(!empty($property_type)) {
		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong>'.esc_attr( $property_type ).'</strong></li>
				<li class="hz-meta-label property-overview-type">'.houzez_option('spl_prop_type', 'Property Type').'</li>
			</ul>';
	}

	$prop_price = houzez_get_listing_data('property_price');
	if(!empty($prop_price)) {
		$houzez_listing_price = houzez_listing_price();
		if( strpos($houzez_listing_price, "Start from") ){
			$houzez_listing_price = explode("Start from", $houzez_listing_price)[1];
		}

		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong>'.$houzez_listing_price.'</strong></li>
				<li class="hz-meta-label property-overview-type">'.houzez_option('spl_st_from', 'Starting From').'</li>
			</ul>';
	}

	$completion  = houzez_get_listing_data('completion');
	if(!empty($completion)) {
		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong>'.esc_attr( $completion ).'</strong></li>
				<li class="hz-meta-label property-overview-type">'.houzez_option('cl_con_st', 'Construction Status').'</li>
			</ul>';
	}

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
	if(!empty($prop_payment_val)) {
		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><a href="#property-payment-plan-wrap"><strong>'.esc_attr( $prop_payment_val ).'</strong></a></li>
				<li class="hz-meta-label property-overview-type"><a href="#property-payment-plan-wrap">'.houzez_option('cls_payment_plan', 'Payment Plan').'</a></li>
			</ul>';
	}

	$prop_number_of_buildings  = houzez_get_listing_data('number-of-buildings'); 
	if(!empty($prop_number_of_buildings)) {
		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong style="margin:0 auto;">'.esc_attr( $prop_number_of_buildings ).'</strong></li>
				<li class="hz-meta-label property-overview-type">'.houzez_option('cl_no_buildings', 'Number of buildings').'</li>
			</ul>';
	}

	$handover  = houzez_get_listing_data('handover');
	if(!empty($handover)) {
		echo '<ul class="list-unstyled flex-fill">
				<li class="property-overview-item"><strong>'.esc_attr( $handover ).'</strong></li>
				<li class="hz-meta-label property-overview-type">'.houzez_option('cl_handover', 'Handover').'</li>
			</ul>';
	}

}
else{

	foreach ($overview_data as $key => $value) { $i ++;
		if(in_array($key, houzez_overview_composer_fields())) {

			get_template_part('property-details/partials/overview/'.$key);

		} else {
			
			$meta_type = false;
			$custom_field_value = get_post_meta( get_the_ID(), 'fave_'.$key, $meta_type );

			if( ! empty( $custom_field_value ) ) {
				$field_title = houzez_wpml_translate_single_string($value);
	            if( is_array($custom_field_value) ) {
	            	$custom_field_value = houzez_array_to_comma($custom_field_value);
	            } else {
	                $custom_field_value = houzez_wpml_translate_single_string($custom_field_value);
	            } 

				$output = '';
				$output .= '<ul class="list-unstyled flex-fill">';
					$output .= '<li class="property-overview-item">';
						
						if(houzez_option('icons_type') == 'font-awesome') {
							$output .= '<i class="'.houzez_option('fa_'.$key).' mr-1"></i>';

						} elseif (houzez_option('icons_type') == 'custom') {
							$cus_icon = houzez_option($key);
							if(!empty($cus_icon['url'])) {

								$alt = isset($cus_icon['title']) ? $cus_icon['title'] : '';
								$output .= '<img class="img-fluid mr-1" src="'.esc_url($cus_icon['url']).'" width="16" height="16" alt="'.esc_attr($alt).'">';
							}
						}

						$output .= '<strong>'.esc_attr($custom_field_value).'</strong>';
						
					$output .= '</li>';
					$output .= '<li>'.esc_attr($field_title).'</li>';
				$output .= '</ul>';

				echo $output;
			}

		}
	}
}
}
