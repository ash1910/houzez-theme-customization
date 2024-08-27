<?php 
global $houzez_local;
$developer_mobile = get_post_meta( get_the_ID(), 'fave_developer_mobile', true );

if(is_author()) {
	global $current_author_meta;
	$developer_mobile = isset($current_author_meta['fave_author_mobile'][0]) ? $current_author_meta['fave_author_mobile'][0] : '';
}

$developer_mobile_call = str_replace(array('(',')',' ','-'),'', $developer_mobile);
if( !empty( $developer_mobile ) ) { ?>
	<li>
		<strong><?php echo $houzez_local['mobile_colon']; ?></strong> 
		<a href="tel:<?php echo esc_attr($developer_mobile_call); ?>">
			<span class="agent-phone <?php houzez_show_phone(); ?>"><?php echo esc_attr( $developer_mobile ); ?></span>
		</a>
	</li>
<?php } ?>