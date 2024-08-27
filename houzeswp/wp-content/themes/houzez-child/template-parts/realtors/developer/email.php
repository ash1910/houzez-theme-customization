<?php 
global $houzez_local;
$developer_email = get_post_meta( get_the_ID(), 'fave_developer_email', true );

if(is_author()) {
	global $author_email;
	$developer_email = $author_email;
}

if( !empty( $developer_email ) ) { ?>
    <li class="email">
    	<strong><?php echo $houzez_local['email_colon']; ?></strong> 
    	<a href="mailto:<?php echo esc_attr( $developer_email ); ?>"><?php echo esc_attr( $developer_email ); ?></a>
    </li>
<?php } ?>