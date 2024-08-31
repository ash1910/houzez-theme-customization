<?php
global $current_user, $houzez_local, $hide_prop_fields, $required_fields, $is_multi_steps;
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
			<?php if( $hide_prop_fields['prop_type'] != 1 ) { ?>
			<div class="col-md-4 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/type'); ?>
			</div>
			<?php } ?>

			<?php if( $hide_prop_fields['prop_status'] != 1 ) { ?>
			<div class="col-md-4 col-sm-12">
				<?php get_template_part('template-parts/dashboard/submit/form-fields/status'); ?>
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

					<select name="prop_construction_status" id="prop_construction_status" <?php houzez_required_field_2('construction_status'); ?> class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_energy_cls_plac', 'Select'); ?>" data-live-search="false" data-selected-text-format="count" data-actions-box="true">
						<option value=""><?php echo houzez_option('cl_con_st_plac', 'Select Construction Status'); ?></option>
						<?php
						$construction_status_array = houzez_option('construction_status_data', 'Under Construction, Ready for Delivey'); 
						$construction_status_array = explode(',', $construction_status_array);

						if( ! empty( $construction_status_array ) ) {
							foreach ($construction_status_array as $e_class) { 
								$e_class = trim($e_class);
								?>
								<option <?php selected(houzez_get_field_meta('prop_construction_status'), esc_attr($e_class)); ?> value="<?php echo esc_attr($e_class);?>"><?php echo esc_attr($e_class);?></option>
							<?php
							}
						}
						?>
					</select><!-- selectpicker -->
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_number_of_buildings">
						<?php echo houzez_option('cl_no_buildings', 'Number of buildings').houzez_required_field('no_buildings'); ?>
					</label>

					<input class="form-control" id="prop_number_of_buildings" <?php houzez_required_field_2('no_buildings'); ?> name="prop_number_of_buildings" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('prop_number_of_buildings');
					}
					?>" placeholder="<?php echo houzez_option('cl_no_buildings_plac', 'Enter number of buildings'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_construction_status">
						<?php echo houzez_option('cl_handover', 'Handover Date' ).houzez_required_field('cl_handover_req'); ?>
					</label>
					<div class="row">
						<div class="col-md-6">
							<select name="prop_handover_q" id="prop_handover_q" <?php houzez_required_field_2('prop_handover_q'); ?> class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_prop_handover_q_plac', 'Select'); ?>" data-live-search="false" data-selected-text-format="count" data-actions-box="true">
								<option value=""><?php echo houzez_option('cl_prop_handover_q_plac', 'Select'); ?></option>
								<?php
								$prop_handover_q_array = houzez_option('prop_handover_q_data', 'Q1, Q2, Q3, Q4'); 
								$prop_handover_q_array = explode(',', $prop_handover_q_array);

								if( ! empty( $prop_handover_q_array ) ) {
									foreach ($prop_handover_q_array as $e_class) { 
										$e_class = trim($e_class);
										?>
										<option <?php selected(houzez_get_field_meta('prop_handover_q'), esc_attr($e_class)); ?> value="<?php echo esc_attr($e_class);?>"><?php echo esc_attr($e_class);?></option>
									<?php
									}
								}
								?>
							</select><!-- selectpicker -->
						</div>
						<div class="col-md-6">
							<select name="prop_handover_y" id="prop_handover_y" <?php houzez_required_field_2('prop_handover_y'); ?> class="selectpicker form-control bs-select-hidden" title="<?php echo houzez_option('cl_prop_handover_q_plac', 'Select'); ?>" data-live-search="false" data-selected-text-format="count" data-actions-box="true">
								<option value=""><?php echo houzez_option('cl_prop_handover_q_plac', 'Select'); ?></option>
								<?php
								$prop_handover_y_array = houzez_option('prop_handover_y_data', '2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035'); 
								$prop_handover_y_array = explode(',', $prop_handover_y_array);

								if( ! empty( $prop_handover_y_array ) ) {
									foreach ($prop_handover_y_array as $e_class) { 
										$e_class = trim($e_class);
										?>
										<option <?php selected(houzez_get_field_meta('prop_handover_y'), esc_attr($e_class)); ?> value="<?php echo esc_attr($e_class);?>"><?php echo esc_attr($e_class);?></option>
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

					<input class="form-control" id="prop_payment_plan_down" <?php houzez_required_field_2('payment_plan_down'); ?> name="prop_payment_plan_down" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('prop_payment_plan_down');
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

					<input class="form-control" id="prop_payment_plan_during_construction" <?php houzez_required_field_2('payment_plan_during_construction'); ?> name="prop_payment_plan_during_construction" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('prop_payment_plan_during_construction');
					}
					?>" placeholder="<?php echo houzez_option('cl_payment_plan_during_construction', 'Enter During Construction (%)'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
					<small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?></small>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<div class="form-group">
					<label for="prop_payment_plan_on_handover">
						<?php echo houzez_option('cl_payment_plan_on_handover', 'On Handover (%)').houzez_required_field('payment_plan_on_handover'); ?>
					</label>

					<input class="form-control" id="prop_payment_plan_on_handover" <?php houzez_required_field_2('payment_plan_on_handover'); ?> name="prop_payment_plan_on_handover" value="<?php
					if (houzez_edit_property()) {
						houzez_field_meta('prop_payment_plan_on_handover');
					}
					?>" placeholder="<?php echo houzez_option('cl_payment_plan_on_handover', 'Enter On Handover (%)'); ?>" <?php houzez_input_attr_for_bbr(); ?>>
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

			<?php if( $hide_prop_fields['price_postfix'] != 1 ) { ?>
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

