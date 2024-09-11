<?php global $is_multi_steps; ?>
<div id="attachments" class="dashboard-content-block-wrap <?php echo esc_attr($is_multi_steps);?>">
	<h2><?php echo houzez_option('cls_documents', 'Property Documents'); ?></h2>

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
	<h2><?php echo houzez_option('cls_documents_title_deed', 'Form A'); ?></h2>
	<div class="dashboard-content-block">
		<p><?php echo houzez_option('cl_decuments_title_deed_text', 'Upload Form A'); ?></p>
		<p><a href="#" id="select_attachments_title_deed" class="btn btn-primary"><?php echo houzez_option('cl_attachment_title_deed_btn', 'Select Form A Attachment'); ?></a></p>
		<p id="houzez_atach_errors"></p>

		<table class="dashboard-table draggable-table">
			<thead>
				<tr>
					<td colspan="4">
						<label><?php echo houzez_option('cl_uploaded_attachments_title_deed', 'Uploaded Form A Attachments'); ?></label>
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

	<h2><?php echo houzez_option('cls_documents_other', 'Other Documents'); ?></h2>
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
</div><!-- dashboard-content-block-wrap -->