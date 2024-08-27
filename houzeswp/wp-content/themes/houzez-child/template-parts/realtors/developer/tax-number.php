<?php 
global $houzez_local;
$developer_tax_no = get_post_meta( get_the_ID(), 'fave_developer_tax_no', true );

if( !empty( $developer_tax_no ) ) { ?>
	<li>
		<strong><?php echo $houzez_local['tax_number']; ?>:</strong> 
		<?php echo esc_attr( $developer_tax_no ); ?>
	</li>
<?php } ?>