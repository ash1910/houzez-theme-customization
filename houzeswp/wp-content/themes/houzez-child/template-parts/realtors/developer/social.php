<?php
$developer_facebook = get_post_meta( get_the_ID(), 'fave_developer_facebook', true );
$developer_twitter = get_post_meta( get_the_ID(), 'fave_developer_twitter', true );
$developer_linkedin = get_post_meta( get_the_ID(), 'fave_developer_linkedin', true );
$developer_googleplus = get_post_meta( get_the_ID(), 'fave_developer_googleplus', true );
$developer_youtube = get_post_meta( get_the_ID(), 'fave_developer_youtube', true );
$developer_pinterest = get_post_meta( get_the_ID(), 'fave_developer_pinterest', true );
$developer_instagram = get_post_meta( get_the_ID(), 'fave_developer_instagram', true );
$developer_vimeo = get_post_meta( get_the_ID(), 'fave_developer_vimeo', true );
$developer_skype = get_post_meta( get_the_ID(), 'fave_developer_skype', true );
$developer_mobile = get_post_meta( get_the_ID(), 'fave_developer_mobile', true );
$developer_whatsapp = get_post_meta( get_the_ID(), 'fave_developer_whatsapp', true );
$developer_line_id = get_post_meta( get_the_ID(), 'fave_developer_line_id', true );
$developer_tiktok = get_post_meta( get_the_ID(), 'fave_developer_tiktok', true );
$developer_telegram = get_post_meta( get_the_ID(), 'fave_developer_telegram', true );
$developer_zillow = get_post_meta( get_the_ID(), 'fave_developer_zillow', true );
$developer_realtor_com = get_post_meta( get_the_ID(), 'fave_developer_realtor_com', true );


if(is_author()) {
	global $current_author_meta;

	$developer_facebook = $current_author_meta['fave_author_facebook'][0] ?? "";
	$developer_twitter = $current_author_meta['fave_author_twitter'][0] ?? '';
	$developer_linkedin = $current_author_meta['fave_author_linkedin'][0] ?? '';
	$developer_googleplus = $current_author_meta['fave_author_googleplus'][0] ?? '';
	$developer_youtube = $current_author_meta['fave_author_youtube'][0] ?? '';
	$developer_pinterest = $current_author_meta['fave_author_pinterest'][0] ?? '';
	$developer_instagram = $current_author_meta['fave_author_instagram'][0] ?? '';
	$developer_vimeo = $current_author_meta['fave_author_vimeo'][0] ?? '';
	$developer_skype = $current_author_meta['fave_author_skype'][0] ?? '';
	$developer_mobile = $current_author_meta['fave_author_mobile'][0] ?? '';
	$developer_whatsapp = $current_author_meta['fave_author_whatsapp'][0] ?? '';
	$developer_line_id = $current_author_meta['fave_author_line_id'][0] ?? '';
	$developer_tiktok = $current_author_meta['fave_developer_tiktok'][0] ?? '';
	$developer_telegram = $current_author_meta['fave_author_telegram'][0] ?? '';
	$developer_zillow = $current_author_meta['fave_author_zillow'][0] ?? '';
	$developer_realtor_com = $current_author_meta['fave_author_realtor_com'][0] ?? '';
}
$developer_mobile_call = str_replace(array('(',')',' ','-'),'', $developer_mobile);
$developer_whatsapp_call = str_replace(array('(',')',' ','-'),'', $developer_whatsapp);
?>

<?php if( !empty( $developer_skype ) ) { ?>
<span>
	<a class="btn-skype" target="_blank" href="skype:<?php echo esc_attr( $developer_skype ); ?>?chat">
		<i class="houzez-icon icon-video-meeting-skype mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_facebook ) ) { ?>
<span>
	<a class="btn-facebook" target="_blank" href="<?php echo esc_url( $developer_facebook ); ?>">
		<i class="houzez-icon icon-social-media-facebook mr-2"></i>
	</a>
</span>
<?php } ?>

 <?php if( !empty( $developer_instagram ) ) { ?>
<span>
	<a class="btn-instagram" target="_blank" href="<?php echo esc_url( $developer_instagram ); ?>">
		<i class="houzez-icon icon-social-instagram mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_twitter ) ) { ?>
<span>
	<a class="btn-twitter" target="_blank" href="<?php echo esc_url( $developer_twitter ); ?>">
		<i class="houzez-icon icon-x-logo-twitter-logo-2 mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_linkedin ) ) { ?>
<span>
	<a class="btn-linkedin" target="_blank" href="<?php echo esc_url( $developer_linkedin ); ?>">
		<i class="houzez-icon icon-professional-network-linkedin mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_googleplus ) ) { ?>
<span>
	<a class="btn-googleplus" target="_blank" href="<?php echo esc_url( $developer_googleplus ); ?>">
		<i class="houzez-icon icon-social-media-google-plus-1 mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_youtube ) ) { ?>
<span>
	<a class="btn-youtube" target="_blank" href="<?php echo esc_url( $developer_youtube ); ?>">
		<i class="houzez-icon icon-social-video-youtube-clip mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_tiktok ) ) { ?>
<span>
	<a class="btn-tiktok" target="_blank" href="<?php echo esc_url( $developer_tiktok ); ?>">
		<i class="houzez-icon icon-tiktok-1-logos-24 mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_pinterest ) ) { ?>
<span>
	<a class="btn-pinterest" target="_blank" href="<?php echo esc_url( $developer_pinterest ); ?>">
		<i class="houzez-icon icon-social-pinterest mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_vimeo ) ) { ?>
<span>
	<a class="btn-vimeo" target="_blank" href="<?php echo esc_url( $developer_vimeo ); ?>">
		<i class="houzez-icon icon-social-video-vimeo mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_telegram ) ) { ?>
<span>
	<a class="btn-telegram" target="_blank" href="<?php echo houzezStandardizeTelegramURL($developer_telegram); ?>">
		<i class="houzez-icon icon-telegram-logos-24 mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_line_id ) ) { ?>
<span>
	<a class="btn-lineapp" target="_blank" href="https://line.me/ti/p/~<?php echo esc_attr( $developer_line_id ); ?>">
		<i class="houzez-icon icon-lineapp-5 mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_realtor_com ) ) { ?>
<span>
	<a class="btn-realtor-com" target="_blank" href="<?php echo esc_url( $developer_realtor_com ); ?>">
		<i class="houzez-icon icon-realtor-com mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_zillow ) ) { ?>
<span>
	<a class="btn-zillow" target="_blank" href="<?php echo esc_url( $developer_zillow ); ?>">
		<i class="houzez-icon icon-zillow mr-2"></i>
	</a>
</span>
<?php } ?>

<?php if( !empty( $developer_whatsapp ) ) { ?>
<span class="agent-whatsapp">
	<a class="btn-whatsapp" target="_blank" href="https://wa.me/<?php echo esc_attr($developer_whatsapp_call); ?>">
		<i class="houzez-icon icon-messaging-whatsapp mr-2"></i>
	</a>
</span>
<?php } ?>