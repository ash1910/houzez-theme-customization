<?php
/**
 * Add attachments metabox tab
 *
 * @param $metabox_tabs
 *
 * @return array
 */
function houzez_attachments_metabox_tab( $metabox_tabs ) {
	if ( is_array( $metabox_tabs ) ) {

		$metabox_tabs['attachments'] = array(
			'label' => houzez_option('cls_documents', 'Property Documents'),
            'icon' => 'dashicons-book',
		);

	}
	return $metabox_tabs;
}
add_filter( 'houzez_property_metabox_tabs', 'houzez_attachments_metabox_tab', 70 );


/**
 * Add attachments metaboxes fields
 *
 * @param $metabox_fields
 *
 * @return array
 */
function houzez_attachments_metabox_fields( $metabox_fields ) {
	$houzez_prefix = 'fave_';

	$fields = array(
		array(
            'type' => 'heading',
            'name' => houzez_option('cl_all_prop_docs', 'All Property Documents'),
            'columns' => 12,
            'desc' => "",
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}attachments_form_a",
            'name' => houzez_option('cls_attachments_form_a', 'Form A'),
            'desc' => houzez_option('cl_attachments_form_a_text', 'Upload Form A'),
            'type' => 'file_input',
            'mime_type' => '',
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}attachments_title_deed",
            'name' => houzez_option('cls_attachments_title_deed', 'Title Deed'),
            'desc' => houzez_option('cl_attachments_title_deed_text', 'Upload Title Deed'),
            'type' => 'file_input',
            'mime_type' => '',
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}attachments_passport",
            'name' => houzez_option('cls_attachments_passport', 'Passport'),
            'desc' => houzez_option('cl_attachments_passport_text', 'Upload Passport'),
            'type' => 'file_input',
            'mime_type' => '',
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}attachments",
            'name' => houzez_option('cls_documents_other', 'Other Documents'),
            'desc' => houzez_option('cl_decuments_text', 'You can attach PDF files, Map images OR other documents.'),
            'type' => 'file_advanced',
            'mime_type' => '',
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'type' => 'heading',
            'name' => houzez_option('cl_guidelines', 'Guidelines'),
            'columns' => 12,
            'desc' => "",
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_check1",
            'name' => houzez_option('cl_verified_check1', ''),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_check1', 'All images are of supper quality'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_check2",
            'name' => houzez_option('cl_verified_check2', ''),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_check2', 'All images are genuine images of the property showing interior and exterior views'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_check3",
            'name' => houzez_option('cl_verified_check3', ''),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_check3', 'The Watermark is subtle and represents your company logo'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_check4",
            'name' => houzez_option('cl_verified_check4', ''),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_check4', 'Images have no footers with extra details'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_check5",
            'name' => houzez_option('cl_verified_check5', ''),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_check5', 'Images are not collages or include people'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
		array(
            'type' => 'heading',
            'name' => houzez_option('cl_varified_badge', 'Verified Badge'),
            'columns' => 12,
            'desc' => "",
            'tab' => 'attachments',
        ),
		array(
            'id' => "{$houzez_prefix}verified_badge",
            'name' => houzez_option('cl_verified_badge', 'Enable Verified Badge'),
            'type' => 'checkbox',
            'desc' => houzez_option('cl_verified_badge_text', 'Review these documents and enable the verified badge'),
            'std' => "",
            'columns' => 12,
            'tab' => 'attachments',
        ),
	);

	return array_merge( $metabox_fields, $fields );

}
add_filter( 'houzez_property_metabox_fields', 'houzez_attachments_metabox_fields', 70 );
