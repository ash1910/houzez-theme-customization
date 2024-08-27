<?php
global $houzez_local;
$developer_position = get_post_meta( get_the_ID(), 'fave_developer_position', true );
$developer_company = get_post_meta( get_the_ID(), 'fave_developer_company', true );
$developer_agency_id = get_post_meta( get_the_ID(), 'fave_developer_agencies', true );

$href = "";
if(!empty($developer_agency_id)) {
	$href = ' href="'.esc_url(get_permalink($developer_agency_id)).'"';
}

if(!empty($developer_position) || !empty($developer_company)) {
?>
<p class="agent-list-position"> <?php echo esc_attr($developer_position); ?>
	<?php if(!empty($developer_company)) { ?>
		
		<?php echo $houzez_local['at']; ?>
		<a<?php echo $href; ?>>
			<?php echo esc_attr( $developer_company ); ?>		
		</a>

	<?php } ?>
</p>
<?php } ?>