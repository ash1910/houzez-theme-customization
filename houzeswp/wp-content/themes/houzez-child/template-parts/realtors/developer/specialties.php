<?php 
global $houzez_local;
$developer_specialties = get_post_meta( get_the_ID(), 'fave_developer_specialties', true );

if( !empty( $developer_specialties ) ) { ?>
	<li>
		<strong><?php echo $houzez_local['specialties_label']; ?>:</strong> 
		<?php echo esc_attr( $developer_specialties ); ?>
	</li>
<?php } ?>