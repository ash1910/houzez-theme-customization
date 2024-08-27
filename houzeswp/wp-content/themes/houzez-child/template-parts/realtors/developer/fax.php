<?php 
global $houzez_local;
$developer_fax = get_post_meta( get_the_ID(), 'fave_developer_fax', true );

if(is_author()) {
	global $current_author_meta;
	$developer_fax = isset($current_author_meta['fave_author_fax'][0]) ? $current_author_meta['fave_author_fax'][0] : '';
}

$developer_fax_call = str_replace(array('(',')',' ','-'),'', $developer_fax);
if( !empty( $developer_fax ) ) { ?>
	<li>
		<strong><?php echo $houzez_local['fax_colon']; ?></strong> 
		<a href="fax:<?php echo esc_attr($developer_fax_call); ?>">
			<span><?php echo esc_attr( $developer_fax ); ?></span>
		</a>
	</li>
<?php } ?>