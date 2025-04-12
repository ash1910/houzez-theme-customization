<?php
global $current_user, $houzez_local, $hide_prop_fields, $required_fields, $is_multi_steps, $property_data;
$is_multi_currency = houzez_option('multi_currency');
$default_multi_currency = get_the_author_meta( 'fave_author_currency' , $current_user->ID );
if(empty($default_multi_currency)) {
    $default_multi_currency = houzez_option('default_multi_currency');
}
?>
<div id="description-price" class="dashboard-content-block-wrap <?php echo esc_attr($is_multi_steps);?>">
	<h2><?php echo houzez_option('cls_description', 'Description'); ?></h2>

	<div class="dashboard-content-block">
		<?php get_template_part('template-parts/dashboard/submit/form-fields/title'); ?>
		
		<?php get_template_part('template-parts/dashboard/submit/form-fields/description'); ?>

		<div class="row">
			<?php if ( houzez_is_developer() ) { ?>
			<div class="col-md-4 col-sm-12" style="display: none;">
				<div class="form-group">
					<label for="prop_status">
						<?php echo houzez_option('cl_prop_status', 'Property Status').houzez_required_field('prop_status'); ?>
					</label>
					<select name="prop_status[]" data-size="5" <?php houzez_required_field_2('prop_status'); ?> id="prop_status" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-live-search="true"  data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_status', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_statuses', 'Statuses'); ?>">
						<option value="">Select</option>	
						<?php $property_status_terms = get_terms (
							array(
								"property_status"
							),
							array(
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => false,
								'parent' => 0
							)
						);
						if(!empty($property_status_terms)) {
							foreach($property_status_terms as $status) {
								if($status->name != 'New Projects') continue;
								echo '<option value="' . esc_attr($status->term_id) . '" selected="selected">' . esc_html($status->name) . '</option>';
							}
						}
						?>
					</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_type">
						<?php echo houzez_option('cl_prop_type', 'Property Type').houzez_required_field('prop_type'); ?>		
					</label>
					<select name="prop_type[]" data-size="5" <?php houzez_required_field_2('prop_type'); ?> id="prop_type" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-live-search-normalize="true" data-live-search="true" data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_type', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_types', 'Types'); ?>">
						<option value="">Select</option>	
						<?php 
						$taxonomy_terms_ids= array();
						$taxonomy_terms = get_the_terms( $property_data->ID, "property_type" );
				
						if ( $taxonomy_terms && ! is_wp_error( $taxonomy_terms ) ) {
							foreach( $taxonomy_terms as $term ) {
								$taxonomy_terms_ids[] = intval( $term->term_id );
							}
						}
						
						$property_status_terms = get_terms (
							array(
								"property_type"
							),
							array(
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => false,
								'parent' => 96
							)
						);

						if(!empty($property_status_terms)) {
							foreach($property_status_terms as $status) {
								$selected = "";
								if ( in_array( $status->term_id, $taxonomy_terms_ids ) ) {
									$selected = 'selected="selected"';
								}
								echo '<option value="' . esc_attr($status->term_id) . '" '.$selected.' >' . esc_html($status->name) . '</option>';
							}
						}
						?>
					</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div>

			<?php } else{ ?>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_status">
						<?php echo houzez_option('cl_prop_status', 'Property Status').houzez_required_field('prop_status'); ?>
					</label>
					<select name="prop_status[]" data-size="5" <?php houzez_required_field_2('prop_status'); ?> id="prop_status" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-live-search="true"  data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_status', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_statuses', 'Statuses'); ?>">
						<option value="">Select</option>	
						<?php 
						$taxonomy_terms_ids= array();
						$taxonomy_terms = get_the_terms( $property_data->ID, "property_status" );
				
						if ( $taxonomy_terms && ! is_wp_error( $taxonomy_terms ) ) {
							foreach( $taxonomy_terms as $term ) {
								$taxonomy_terms_ids[] = intval( $term->term_id );
							}
						}
						
						$property_status_terms = get_terms (
							array(
								"property_status"
							),
							array(
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => false,
								'parent' => 0
							)
						);

						if(!empty($property_status_terms)) {
							foreach($property_status_terms as $status) {
								if($status->name == 'New Projects') continue;
								$selected = "";
								if ( in_array( $status->term_id, $taxonomy_terms_ids ) ) {
									$selected = 'selected="selected"';
								}
								echo '<option value="' . esc_attr($status->term_id) . '" '.$selected.' >' . esc_html($status->name) . '</option>';
							}
						}
						?>
					</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group prop_type_container">
					<label for="prop_type">
						<?php echo houzez_option('cl_prop_type', 'Property Type').houzez_required_field('prop_type'); ?>		
					</label>
					<select name="prop_type[]" data-size="5" <?php houzez_required_field_2('prop_type'); ?> id="prop_type" class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_select', 'Select'); ?>" data-selected-text-format="count > 2" data-live-search-normalize="true" data-live-search="true" data-actions-box="true" <?php houzez_multiselect(houzez_option('ams_type', 0)); ?> data-select-all-text="<?php echo houzez_option('cl_select_all', 'Select All'); ?>" data-deselect-all-text="<?php echo houzez_option('cl_deselect_all', 'Deselect All'); ?>" data-none-results-text="<?php echo houzez_option('cl_no_results_matched', 'No results matched');?> {0}" data-count-selected-text="{0} <?php echo houzez_option('cl_prop_types', 'Types'); ?>">
						<option value="">Select</option>	
						<?php 
						$taxonomy_terms_ids= array();
						$taxonomy_terms = get_the_terms( $property_data->ID, "property_type" );
				
						if ( $taxonomy_terms && ! is_wp_error( $taxonomy_terms ) ) {
							foreach( $taxonomy_terms as $term ) {
								$taxonomy_terms_ids[] = intval( $term->term_id );
							}
						}
						
						$property_status_terms = get_terms (
							array(
								"property_type"
							),
							array(
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => false,
								'parent' => 33 // residential
							)
						);

						if(!empty($property_status_terms)) {
							foreach($property_status_terms as $status) {
								$selected = "";
								if ( in_array( $status->term_id, $taxonomy_terms_ids ) ) {
									$selected = 'selected="selected"';
								}
								echo '<option data-type="residential" value="' . esc_attr($status->term_id) . '" '.$selected.' >' . esc_html($status->name) . '</option>';
							}
						}

						$property_status_terms = get_terms (
							array(
								"property_type"
							),
							array(
								'orderby' => 'name',
								'order' => 'ASC',
								'hide_empty' => false,
								'parent' => 19 // commercial
							)
						);

						if(!empty($property_status_terms)) {
							foreach($property_status_terms as $status) {
								$selected = "";
								if ( in_array( $status->term_id, $taxonomy_terms_ids ) ) {
									$selected = 'selected="selected"';
								}
								echo '<option data-type="commercial" value="' . esc_attr($status->term_id) . '" '.$selected.' >' . esc_html($status->name) . '</option>';
							}
						}
						?>
					</select><!-- selectpicker -->
				</div><!-- form-group -->
			</div>
			<?php } ?>

			<?php if( $hide_prop_fields['prop_label'] != 1 ) { ?>
			<div class="col-md-4 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/label'); ?>
			</div>
			<?php } ?>

			<?php if ( houzez_is_developer() ) { ?>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_construction_status">
						<?php echo houzez_option('cl_con_st', 'Construction Status' ).houzez_required_field('cl_con_req'); ?>
					</label>

					<select name="completion" id="completion" <?php houzez_required_field_2('completion'); ?> class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_con_st_plac', 'Select'); ?>" data-live-search="false" data-selected-text-format="count" data-actions-box="true">
						<option value=""><?php echo houzez_option('cl_con_st_plac', 'Select Construction Status'); ?></option>
						<?php
						$completion = get_Houzez_Fields_Builder_select_options('completion');

						if( ! empty( $completion ) ) {
							foreach ($completion as $key => $value) { 
								?>
								<option <?php selected(houzez_get_field_meta('completion'), esc_attr($key)); ?> value="<?php echo esc_attr($key);?>"><?php echo esc_attr($value);?></option>
							<?php
							}
						}
						?>
					</select><!-- selectpicker -->
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="number-of-buildings">
						<?php echo houzez_option('cl_number-of-buildings', 'Number of buildings').houzez_required_field('number-of-buildings'); ?>
					</label>

					<input class="form-control" id="number-of-buildings" <?php houzez_required_field_2('number-of-buildings'); ?> name="number-of-buildings" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('number-of-buildings');
					}
					?>" placeholder="<?php echo houzez_option('cl_number-of-buildings_plac', 'Enter number of buildings'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_construction_status">
						<?php echo houzez_option('cl_handover', 'Handover Date' ).houzez_required_field('cl_handover_req'); ?>
					</label>
					<div class="row">
						<div class="col-md-12">
							<select name="handover" id="handover" <?php houzez_required_field_2('handover'); ?> class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_handover', 'Select'); ?>" data-live-search="false" data-selected-text-format="count" data-actions-box="true">
								<option value=""><?php echo houzez_option('cl_handover_plac', 'Select'); ?></option>
								<?php
								$handover = get_Houzez_Fields_Builder_select_options('handover');

								if( ! empty( $handover ) ) {
									foreach ($handover as $key => $value) { ?>
										<option <?php selected(houzez_get_field_meta('handover'), esc_attr($key)); ?> value="<?php echo esc_attr($key);?>"><?php echo esc_attr($value);?></option>
									<?php
									}
								}
								?>
							</select><!-- selectpicker -->
						</div>
					</div>
				</div>
			</div>
			<?php  } ?>
		</div>
	</div><!-- dashboard-content-block -->

	<?php if ( houzez_is_developer() ) { ?>
	<h2><?php echo houzez_option('cls_payment_plan', 'Payment Plan'); ?></h2>
	<div class="dashboard-content-block">
		<div class="row">
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_payment_plan_down">
						<?php echo houzez_option('cl_payment_plan_down', 'Down Payment (%)').houzez_required_field('payment_plan_down'); ?>
					</label>

					<input class="form-control" id="down-payment" <?php houzez_required_field_2('down-payment'); ?> name="down-payment" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('down-payment');
					}
					?>" placeholder="<?php echo houzez_option('cl_payment_plan_down', 'Enter Down Payment (%)'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_payment_plan_during_construction">
						<?php echo houzez_option('cl_payment_plan_during_construction', 'During Construction (%)').houzez_required_field('payment_plan_during_construction'); ?>
					</label>

					<input class="form-control" id="during-construction" <?php houzez_required_field_2('during-construction'); ?> name="during-construction" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('during-construction');
					}
					?>" placeholder="<?php echo houzez_option('cl_during-construction', 'Enter During Construction (%)'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_payment_plan_on_handover">
						<?php echo houzez_option('cl_payment_plan_on_handover', 'On Handover (%)').houzez_required_field('payment_plan_on_handover'); ?>
					</label>

					<input class="form-control" id="on-handover" <?php houzez_required_field_2('on-handover'); ?> name="on-handover" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('on-handover');
					}
					?>" placeholder="<?php echo houzez_option('cl_on-handover', 'Enter On Handover (%)'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>
		</div>
	</div>
	<?php  } ?>

	<h2><?php echo houzez_option('cls_price', 'Price'); ?></h2>
	<div class="dashboard-content-block">
		<div class="row">
			
			<?php get_template_part('template-parts/dashboard/submit/form-fields/currency'); ?>

			<?php if( $hide_prop_fields['sale_rent_price'] != 1 ) { ?>
			<div class="col-md-6 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/sale-price'); ?>
			</div><!-- col-md-6 col-sm-12 -->
			<?php } ?>

			<?php if( $hide_prop_fields['sale_rent_price'] != 1 && isset( $hide_prop_fields['price_placeholder'] ) && $hide_prop_fields['price_placeholder'] != 1 ) { ?>
			<div id="price-plac-js" class="col-md-6 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/price-placeholder'); ?>
			</div><!-- col-md-6 col-sm-12 -->
			<?php } ?>

			<?php if( $hide_prop_fields['second_price'] != 1 ) { ?>
			<div class="col-md-6 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/second-price'); ?>
			</div><!-- col-md-6 col-sm-12 -->
			<?php } ?>

			<?php if( $hide_prop_fields['price_postfix'] != 1 && !houzez_is_developer() ) { ?>
			<div class="col-md-6 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/after-price-label'); ?>
			</div><!-- col-md-6 col-sm-12 -->
			<?php } ?>

			<?php if( $hide_prop_fields['price_prefix'] != 1 ) { ?>
			<div class="col-md-6 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/price-prefix'); ?>
			</div><!-- col-md-6 col-sm-12 -->
			<?php } ?>
			
		</div><!-- row -->
	</div><!-- dashboard-content-block -->
</div><!-- dashboard-content-block-wrap -->

<script>
jQuery(document).ready(function($) {
    // Function to handle property type visibility
    function handlePropertyTypeVisibility() {
        var selectedStatus = $('#prop_status').val();
        var propTypeSelect = $('.prop_type_container #prop_type');
        var dropdownMenu = propTypeSelect.parent().find('.dropdown-menu.inner');
        
        propTypeSelect.find('option').each(function() {
            var optionValue = $(this).val();
            var dropdownItem = dropdownMenu.find('a#bs-select-1-' + optionValue).parent('li');
            
            if(selectedStatus && (selectedStatus.includes('109') || selectedStatus.includes('110'))) {
                if($(this).data('type') === 'commercial') {
                    $(this).prop('disabled', false);
                    dropdownItem.removeClass('disabled');
                    dropdownItem.find('a').removeClass('disabled').attr('aria-disabled', 'false');
                } else {
                    $(this).prop('disabled', true);
                    dropdownItem.addClass('disabled');
                    dropdownItem.find('a').addClass('disabled').attr('aria-disabled', 'true');
                }
            } else {
                if($(this).data('type') === 'residential') {
                    $(this).prop('disabled', false);
                    dropdownItem.removeClass('disabled');
                    dropdownItem.find('a').removeClass('disabled').attr('aria-disabled', 'false');
                } else {
                    $(this).prop('disabled', true);
                    dropdownItem.addClass('disabled');
                    dropdownItem.find('a').addClass('disabled').attr('aria-disabled', 'true');
                }
            }
        });
        
        // Deselect options that are now disabled
        var selectedOptions = propTypeSelect.val() || [];
        var newSelectedOptions = selectedOptions.filter(function(value) {
            return !propTypeSelect.find('option[value="' + value + '"]').prop('disabled');
        });
        
        propTypeSelect.val(newSelectedOptions);
        propTypeSelect.selectpicker('refresh');
    }

    // Initial check with a delay to ensure selectpicker is initialized
    setTimeout(function() {
        handlePropertyTypeVisibility();
    }, 500);

    // Listen for status changes
    $('#prop_status').on('changed.bs.select', function() {
        setTimeout(function() {
            handlePropertyTypeVisibility();
        }, 100);
    });
});
</script>
<style>
.prop_type_container .bootstrap-select .dropdown-menu li.disabled {
    display: none;
}
</style>

