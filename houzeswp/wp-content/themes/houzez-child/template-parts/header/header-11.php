<?php 
global $post, $current_user, $houzez_local;
wp_get_current_user();
$userID  =  $current_user->ID;

$user_custom_picture    =   get_the_author_meta( 'fave_author_custom_picture' , $userID );
$author_picture_id      =   get_the_author_meta( 'fave_author_picture_id' , $userID );
if($user_custom_picture =='' ) {
    $user_custom_picture = HOUZEZ_IMAGE. 'profile-avatar.png';
}

$dash_profile_link = houzez_get_template_link_2('template/user_dashboard_profile.php');
$dashboard_insight = houzez_get_template_link_2('template/user_dashboard_insight.php');
$dashboard_crm = houzez_get_template_link_2('template/user_dashboard_crm.php');
$dashboard_listings = houzez_get_template_link_2('template/user_dashboard_properties.php');
$dashboard_add_listing = houzez_get_template_link_2('template/user_dashboard_submit.php');
$dashboard_favorites = houzez_get_template_link_2('template/user_dashboard_favorites.php');
$dashboard_search = houzez_get_template_link_2('template/user_dashboard_saved_search.php');
$dashboard_invoices = houzez_get_template_link_2('template/user_dashboard_invoices.php');
$dashboard_msgs = houzez_get_template_link_2('template/user_dashboard_messages.php');
$dashboard_membership = houzez_get_template_link_2('template/user_dashboard_membership.php');
$dashboard_gdpr = houzez_get_template_link_2('template/user_dashboard_gdpr.php');
$dashboard_seen_msgs = add_query_arg( 'view', 'inbox', $dashboard_msgs );
$dashboard_unseen_msgs = add_query_arg( 'view', 'sent', $dashboard_msgs );
$home_link = home_url('/');
$enable_paid_submission = houzez_option('enable_paid_submission');
$create_lisiting_enable = houzez_option('create_lisiting_enable');
$header_style = houzez_option('header_style');

$agency_agents = add_query_arg( 'agents', 'list', $dash_profile_link );

$header_create_listing_template = $dashboard_add_listing;

$create_listing_title = houzez_option('dsh_create_listing', 'Create a Listing');

$fave_main_menu_trans = '';
if( houzez_postid_needed() ) {
	$fave_main_menu_trans = get_post_meta($post->ID, 'fave_main_menu_trans', true);
}
$splash_logo = houzez_option( 'custom_logo_splash', false, 'url' );
$custom_logo = houzez_option( 'custom_logo', false, 'url' );
$splash_logolink_type = houzez_option('splash-logolink-type');
$splash_logolink = houzez_option('splash-logolink');

if( is_page_template( 'template/template-splash.php' ) ) {
	if($splash_logolink_type == 'custom') {
		$splash_logo_link = $splash_logolink;
	} else {
		$splash_logo_link = home_url( '/' );
	}
} else {
	$splash_logo_link = home_url( '/' );
}

$logo_height = houzez_option('retina_logo_height');
$logo_width = houzez_option('retina_logo_width');

$sticky_class = "";
if(isset($args['sticky_header']) && $args['sticky_header'] == '1'){
    $sticky_class = 'ms-header--sticky header-sticky ms-header-sticky';
}
?>
<!-- start: Header Area -->
<header class="ms-header <?php echo esc_attr($sticky_class); ?>">
    <div class="container">
		<div class="ms-header__inner-wrapper">
			<div class="ms-header__inner">
				<!-- logo area -->
				<div class="ms-logo">

				<?php if ( is_page_template( 'template/template-splash.php' ) || ($fave_main_menu_trans == 'yes' && houzez_option('header_style') == '4' ) && !wp_is_mobile() ) { ?>
					<a href="<?php echo esc_url( $splash_logo_link ); ?>">
					<?php if( !empty( $splash_logo ) ) { ?>
						<img src="<?php echo esc_url( $splash_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
					<?php } ?>
					</a>
				<?php } else { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if( !empty( $custom_logo ) ) { ?>
						<img src="<?php echo esc_url( $custom_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
					<?php } ?>
					</a>
				<?php } ?>

				</div>
				<!-- nav munu main -->
				<nav class="ms-header__nav d-none d-lg-block">
					<?php
					if ( has_nav_menu( 'top-menu' ) ) :
					wp_nav_menu( array (
						'theme_location' => 'top-menu',
						'container' => '',
						'container_class' => '',
						'menu_class' => 'ms-header__nav-list',
					));
					endif;
					?>
				</nav>
				<!-- header right -->
				<div class="ms-header__right">
					<ul class="ms-header__right-list">
					<li class="d-none d-lg-block">
						<div class="ms-header__right-lang">
						<label for="input-lang"><i class="icon-world"></i></label>
						
						<?php echo do_shortcode('[language-switcher]'); ?>
						<script>
							document.addEventListener('DOMContentLoaded', () => {
								const languageContainerDiv = document.querySelector('<?php echo empty($sticky_class)? ".ms-header__right-lang" : ".ms-header--sticky .ms-header__right-lang";?>');
								const languageDiv = languageContainerDiv.querySelector('.trp-ls-shortcode-language');
								if (languageDiv) {
									const links = languageDiv.querySelectorAll('a');
									const select = document.createElement('select');
									select.className = 'ms-nice-select';
									select.name = 'input-lang';
									select.id = 'input-lang';

									links.forEach(link => {
										const option = document.createElement('option');
										option.value = link.getAttribute('href');
										option.textContent = link.textContent;

										// If the link has "onclick" to prevent default, disable the option
										if (link.getAttribute('onclick')) {
											option.disabled = true;
											option.selected = true;
										}
										select.appendChild(option);
									});

									// Replace the original div with the new <select>
									languageContainerDiv.querySelector('.trp_language_switcher_shortcode').replaceWith(select);
									if (typeof jQuery !== 'undefined') {
										jQuery(select).niceSelect();
										// Add event listener to handle redirection
										jQuery(select).on('change', function () {
											const selectedUrl = jQuery(this).val();
											if (selectedUrl && selectedUrl !== '#') {
												window.location.href = selectedUrl;
											}
										});
									}
								}
							});
						</script>
						</div>
					</li>

					<?php if( !empty( $dashboard_favorites ) ) { ?>
					<li class="d-none d-lg-block">
						<a href="<?php echo esc_url( $dashboard_favorites ); ?>" class="ms-header__heart">
						<i class="fa-light fa-heart"></i>
						</a>
					</li>
					<?php } ?>

					<li class="ms-header__avatar block d-lg-none">
						<button
						class="ms-header__avater__inner ms-mobile-menu__toggler"
						>
						<span>
							<i class="icon-menu_2line"></i>
						</span>
						<span>
							<?php
							if( !empty( $author_picture_id ) ) {
								$author_picture_id = intval( $author_picture_id );
								if ( $author_picture_id ) {
									echo wp_get_attachment_image( $author_picture_id, 'medium', "" );
								}
							} else {
								print '<img src="'.esc_url( $user_custom_picture ).'" alt="user image" >';
							}
							?>
						</span>
						</button>
					</li>
					
					<li
						class="ms-header__avatar ms-header__avatar--dropdown d-none d-lg-block"
					>
						<a href="#" class="ms-header__avater__inner" href="#">
						<span class="ms-mobile-menu__toggler">
							<i class="icon-menu_2line"></i>
						</span>

						<?php
						if( !empty( $author_picture_id ) ) {
							$author_picture_id = intval( $author_picture_id );
							if ( $author_picture_id ) {
								echo wp_get_attachment_image( $author_picture_id, 'medium', "" );
							}
						} else {
							print '<img src="'.esc_url( $user_custom_picture ).'" alt="user image" >';
						}
						?>
						</a>
						<div class="dropdown-menu">
						<div class="dropdown-menu__inner">
							<ul>
							<?php if( is_user_logged_in() ) { ?>

								<?php if( !empty( $dashboard_crm ) && houzez_check_role() ) { ?>
									<li>
									<a class="dropdown-item" href="<?php echo esc_url( $dashboard_crm ); ?>">Dashboard</a>
									</li>
								<?php } ?>
								
								<?php if( !empty( $dashboard_listings ) && houzez_check_role() ) { ?>
								<li>
									<a class="dropdown-item" href="<?php echo esc_url( $dashboard_listings ); ?>">Properties</a>
								</li>
								<?php } ?>

								<?php if( !empty( $dashboard_invoices ) && houzez_check_role() ) { ?>
								<li>
									<a class="dropdown-item" href="<?php echo esc_url( $dashboard_invoices ); ?>">Invoices</a>
								</li>
								<?php } ?>

								<?php if( $create_lisiting_enable != 0 && houzez_check_role() ) { ?>
								<li>
									<a class="dropdown-item" href="<?php echo esc_url( $header_create_listing_template ); ?>">Create a Listing</a>
								</li>
								<?php } ?>

								<?php if( !empty( $dash_profile_link ) ) { ?>
								<li>
									<a class="dropdown-item" href="<?php echo esc_url( $dash_profile_link ); ?>">Profile </a>
								</li>
								<?php } ?>
							<?php } else { ?>
								<?php if( houzez_option('header_register') ) { ?>
								<li>
									<a class="dropdown-item" href="/register"><?php esc_html_e('Register', 'houzez'); ?> </a>
								</li>
								<?php } ?>
							<?php } ?>

							</ul>
							<?php if( is_user_logged_in() ) { ?>
								<a class="ms-btn" href="<?php echo wp_logout_url( home_url() ); ?>">Log Out</a>
							<?php } else { ?>
								<?php if( houzez_option('header_login') ) { ?>
									<a class="ms-btn" href="/login"><?php esc_html_e('Login', 'houzez'); ?></a>
								<?php } ?>
							<?php } ?>
						</div>
						</div>
					</li>
					

					</ul>
				</div>
			</div>
		</div>

	</div>
</header>