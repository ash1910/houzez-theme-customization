<?php 
global $houzez_local;
$developer_licenses = get_post_meta( get_the_ID(), 'fave_developer_license', true );

if( !empty( $developer_licenses ) ) { ?>
	<li>
		<strong><?php echo $houzez_local['developer_license']; ?>:</strong> 
		<?php echo esc_attr( $developer_licenses ); ?>
	</li>
<?php } ?>