<?php global $is_multi_steps; 

$add_verification = 0;
if(isset($_GET['add_verification']) && $_GET['add_verification'] == 1) {
    $add_verification = 1;
}
?>
<div id="attachments" class="dashboard-content-block-wrap <?php echo esc_attr($is_multi_steps);?>">
	<h2><?php echo houzez_option('cls_documents', 'Property Documents'); ?></h2>

<?php if($add_verification == 1){?>

	<!--  Form A  -->
	<h2><?php echo houzez_option('cls_documents_form_a', 'Form A'); ?></h2>
	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_decuments_form_a_text', 'Upload Form A'); ?></p>
		<p><a href="#" id="select_attachments_form_a" class="btn btn-primary"><?php echo houzez_option('cl_attachment_form_a_btn', 'Select Form A Attachment'); ?></a></p>
		<p id="houzez_atach_errors"></p>

		<table class="dashboard-table draggable-table">
			<thead>
				<tr>
					<td colspan="4">
						<label><?php echo houzez_option('cl_uploaded_attachments_form_a', 'Uploaded Form A Attachments'); ?></label>
					</td>
				</tr>
			</thead>
			<tbody id="houzez_attachments_form_a_container" class="houzez_attachments_form_container">
				<?php
				if(houzez_edit_property()) { 
					global $property_data;
					$property_attachs = get_post_meta( $property_data->ID, 'fave_attachments_form_a', false );
                    $property_attachs = array_unique($property_attachs);

                    if( !empty($property_attachs[0])) {
                        foreach ($property_attachs as $prop_attach_id) {

                            $fullimage_url  = wp_get_attachment_url( $prop_attach_id );
                            $attachment_title = get_the_title($prop_attach_id);
                            
                            echo '<tr class="attach-thumb">
								<td class="table-full-width table-cell-title">
									<span>'.esc_attr($attachment_title).'</span>
								</td>
								<td>
									<a href="'.$fullimage_url.'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>
								</td>
								<td>
									<button data-meta_name="fave_attachments_form_a" data-attach-id="' . intval($property_data->ID) . '"  data-attachment-id="' . intval($prop_attach_id) . '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>
								</td>
								<td class="sort-attachment">
									<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>
								</td>
								<input type="hidden" class="propperty-attach-id" name="propperty_attachment_form_a_ids[]" value="' . intval($prop_attach_id) . '"/>
							</tr>';
							

                        }
                    }
				}
				?>
			</tbody>
		</table>
		
	</div>
	<!--  Form A  -->

	<!--  title_deed  -->
	<h2><?php echo houzez_option('cls_documents_title_deed', 'Title Deed'); ?></h2>
	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_decuments_title_deed_text', 'Upload Title Deed'); ?></p>
		<p><a href="#" id="select_attachments_title_deed" class="btn btn-primary"><?php echo houzez_option('cl_attachment_title_deed_btn', 'Select Title Deed Attachment'); ?></a></p>
		<p id="houzez_atach_errors"></p>

		<table class="dashboard-table draggable-table">
			<thead>
				<tr>
					<td colspan="4">
						<label><?php echo houzez_option('cl_uploaded_attachments_title_deed', 'Uploaded Title Deed Attachments'); ?></label>
					</td>
				</tr>
			</thead>
			<tbody id="houzez_attachments_title_deed_container" class="houzez_attachments_form_container">
				<?php
				if(houzez_edit_property()) { 
					global $property_data;
					$property_attachs = get_post_meta( $property_data->ID, 'fave_attachments_title_deed', false );
                    $property_attachs = array_unique($property_attachs);

                    if( !empty($property_attachs[0])) {
                        foreach ($property_attachs as $prop_attach_id) {

                            $fullimage_url  = wp_get_attachment_url( $prop_attach_id );
                            $attachment_title = get_the_title($prop_attach_id);
                            
                            echo '<tr class="attach-thumb">
								<td class="table-full-width table-cell-title">
									<span>'.esc_attr($attachment_title).'</span>
								</td>
								<td>
									<a href="'.$fullimage_url.'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>
								</td>
								<td>
									<button data-meta_name="fave_attachments_title_deed" data-attach-id="' . intval($property_data->ID) . '"  data-attachment-id="' . intval($prop_attach_id) . '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>
								</td>
								<td class="sort-attachment">
									<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>
								</td>
								<input type="hidden" class="propperty-attach-id" name="propperty_attachment_title_deed_ids[]" value="' . intval($prop_attach_id) . '"/>
							</tr>';
							

                        }
                    }
				}
				?>
			</tbody>
		</table>
		
	</div>
	<!--  title_deed  -->

	<!--  Passport  -->
	<h2><?php echo houzez_option('cls_documents_passport', 'Passport'); ?></h2>
	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_decuments_passport_text', 'Upload Passport'); ?></p>
		<p><a href="#" id="select_attachments_passport" class="btn btn-primary"><?php echo houzez_option('cl_attachment_passport_btn', 'Select Passport Attachment'); ?></a></p>
		<p id="houzez_atach_errors"></p>

		<table class="dashboard-table draggable-table">
			<thead>
				<tr>
					<td colspan="4">
						<label><?php echo houzez_option('cl_uploaded_attachments_passport', 'Uploaded Passport Attachments'); ?></label>
					</td>
				</tr>
			</thead>
			<tbody id="houzez_attachments_passport_container" class="houzez_attachments_form_container">
				<?php
				if(houzez_edit_property()) { 
					global $property_data;
					$property_attachs = get_post_meta( $property_data->ID, 'fave_attachments_passport', false );
                    $property_attachs = array_unique($property_attachs);

                    if( !empty($property_attachs[0])) {
                        foreach ($property_attachs as $prop_attach_id) {

                            $fullimage_url  = wp_get_attachment_url( $prop_attach_id );
                            $attachment_title = get_the_title($prop_attach_id);
                            
                            echo '<tr class="attach-thumb">
								<td class="table-full-width table-cell-title">
									<span>'.esc_attr($attachment_title).'</span>
								</td>
								<td>
									<a href="'.$fullimage_url.'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>
								</td>
								<td>
									<button data-meta_name="fave_attachments_passport" data-attach-id="' . intval($property_data->ID) . '"  data-attachment-id="' . intval($prop_attach_id) . '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>
								</td>
								<td class="sort-attachment">
									<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>
								</td>
								<input type="hidden" class="propperty-attach-id" name="propperty_attachment_passport_ids[]" value="' . intval($prop_attach_id) . '"/>
							</tr>';
							

                        }
                    }
				}
				?>
			</tbody>
		</table>
		
	</div>
	<!--  Passport  -->

	<h2><?php echo houzez_option('cls_documents_other', 'Other Documents'); ?></h2>

<?php }?>

	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_decuments_text', 'You can attach PDF files, Map images OR other documents.'); ?></p>
		<p><a href="#" id="select_attachments" class="btn btn-primary"><?php echo houzez_option('cl_attachment_btn', 'Select Attachment'); ?></a></p>
		<p id="houzez_atach_errors"></p>

		<table class="dashboard-table draggable-table">
			<thead>
				<tr>
					<td colspan="4">
						<label><?php echo houzez_option('cl_uploaded_attachments', 'Uploaded Attachments'); ?></label>
					</td>
				</tr>
			</thead>
			<tbody id="houzez_attachments_container">
				<?php
				if(houzez_edit_property()) { 
					global $property_data;
					$property_attachs = get_post_meta( $property_data->ID, 'fave_attachments', false );
                    $property_attachs = array_unique($property_attachs);

                    if( !empty($property_attachs[0])) {
                        foreach ($property_attachs as $prop_attach_id) {

                            $fullimage_url  = wp_get_attachment_url( $prop_attach_id );
                            $attachment_title = get_the_title($prop_attach_id);
                            
                            echo '<tr class="attach-thumb">
								<td class="table-full-width table-cell-title">
									<span>'.esc_attr($attachment_title).'</span>
								</td>
								<td>
									<a href="'.$fullimage_url.'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>
								</td>
								<td>
									<button data-attach-id="' . intval($property_data->ID) . '"  data-attachment-id="' . intval($prop_attach_id) . '" class="attachment-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>
								</td>
								<td class="sort-attachment">
									<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>
								</td>
								<input type="hidden" class="propperty-attach-id" name="propperty_attachment_ids[]" value="' . intval($prop_attach_id) . '"/>
							</tr>';
							

                        }
                    }
				}
				?>
			</tbody>
		</table>

	</div><!-- dashboard-content-block -->

<?php if($add_verification == 1){ ?>

	<?php
	$checked_verified_check1 = '';
	$checked_verified_check2 = '';
	$checked_verified_check3 = '';
	$checked_verified_check4 = '';
	$checked_verified_check5 = '';

	if (houzez_edit_property()) {
		$verified_check1 = houzez_get_field_meta('verified_check1');
		$verified_check2 = houzez_get_field_meta('verified_check2');
		$verified_check3 = houzez_get_field_meta('verified_check3');
		$verified_check4 = houzez_get_field_meta('verified_check4');
		$verified_check5 = houzez_get_field_meta('verified_check5');

		if( $verified_check1 ) {
			$checked_verified_check1 = 'checked';
		}
		if( $verified_check2 ) {
			$checked_verified_check2 = 'checked';
		}
		if( $verified_check3 ) {
			$checked_verified_check3 = 'checked';
		}
		if( $verified_check4 ) {
			$checked_verified_check4 = 'checked';
		}
		if( $verified_check5 ) {
			$checked_verified_check5 = 'checked';
		}
	}
	?>


	<h2><?php echo houzez_option('cls_guidelines', 'Guidelines'); ?></h2>
	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_guidelines_text', 'Check the below guidelines to get varified batch.'); ?></p>

		<label class="control control--checkbox hz-price-placeholder">
			<input type="checkbox" id="verified_check1" name="verified_check1" <?php echo $checked_verified_check1; ?>>
			<span class="control__indicator"></span>
			<?php echo houzez_option('cl_verified_check1', 'All images are of supper quality'); ?>
		</label>

		<label class="control control--checkbox hz-price-placeholder">
			<input type="checkbox" id="verified_check2" name="verified_check2" <?php echo $checked_verified_check2; ?>>
			<span class="control__indicator"></span>
			<?php echo houzez_option('cl_verified_check2', 'All images are genuine images of the property showing interior and exterior views'); ?>
		</label>

		<label class="control control--checkbox hz-price-placeholder">
			<input type="checkbox" id="verified_check3" name="verified_check3" <?php echo $checked_verified_check3; ?>>
			<span class="control__indicator"></span>
			<?php echo houzez_option('cl_verified_check3', 'The Watermark is subtle and represents your company logo'); ?>
		</label>

		<label class="control control--checkbox hz-price-placeholder">
			<input type="checkbox" id="verified_check4" name="verified_check4" <?php echo $checked_verified_check4; ?>>
			<span class="control__indicator"></span>
			<?php echo houzez_option('cl_verified_check4', 'Images have no footers with extra details'); ?>
		</label>

		<label class="control control--checkbox hz-price-placeholder">
			<input type="checkbox" id="verified_check5" name="verified_check5" <?php echo $checked_verified_check5; ?>>
			<span class="control__indicator"></span>
			<?php echo houzez_option('cl_verified_check5', 'Images are not collages or include people'); ?>
		</label>

	</div>

<?php }?>



</div><!-- dashboard-content-block-wrap -->