<?php 
$developer_website = get_post_meta( get_the_ID(), 'fave_developer_website', true );

if(is_author()) {
	global $author_website;
	$developer_website = $author_website;
}

if( !empty( $developer_website ) ) { ?>
	<li>
		<strong><?php esc_html_e('Website', 'houzez'); ?></strong> 
		<a target="_blank" href="<?php echo esc_url($developer_website); ?>"><?php echo esc_attr( $developer_website ); ?></a>
	</li>
<?php } ?>