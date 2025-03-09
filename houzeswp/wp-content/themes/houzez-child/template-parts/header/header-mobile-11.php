<?php 
global $post, $current_user, $houzez_local;
wp_get_current_user();
$userID  =  $current_user->ID;

$user_login     = $current_user->user_login;
$fav_ids = 'houzez_favorites-'.$userID;
$fav_ids = get_option( $fav_ids );


$user_custom_picture    =   get_the_author_meta( 'fave_author_custom_picture' , $userID );
$author_picture_id      =   get_the_author_meta( 'fave_author_picture_id' , $userID );
$display_name = get_the_author_meta( 'display_name' , $userID );

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

// Add this before wp_nav_menu
add_filter('nav_menu_link_attributes', function($atts) {
    $atts['class'] = 'dropdown-item';
    return $atts;
});
?>

<!-- mobile menu -->
<div class="ms-mobile-menu">
    <div class="ms-mobile-menu__overlay ms-mobile-menu__toggler"></div>

      <div class="ms-mobile-menu__inner form-wizard">
        <!-- logo -->
        <div>
            <?php if ( is_page_template( 'template/template-splash.php' ) || ($fave_main_menu_trans == 'yes' && houzez_option('header_style') == '4' ) && !wp_is_mobile() ) { ?>
                <a href="<?php echo esc_url( $splash_logo_link ); ?>" class="ms-mobile-menu__logo">
                <?php if( !empty( $splash_logo ) ) { ?>
                    <img src="<?php echo esc_url( $splash_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
                <?php } ?>
                </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ms-mobile-menu__logo">
                <?php if( !empty( $custom_logo ) ) { ?>
                    <img src="<?php echo esc_url( $custom_logo ); ?>" alt="logo">
                <?php } ?>
                </a>
            <?php } ?>
        </div>

        <!-- main menu -->
        <div
          class="ms-mobile-menu__nav__wrapper ms-mobile-menu__nav__wrapper-common wizard-fieldset show"
        >
          <div class="sidebar-toggler">
            <button class="ms-mobile-menu__toggler">
              <svg
                width="10"
                height="20"
                viewBox="0 0 10 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M1.7207 18.1968L8.27808 10.0001L1.7207 1.80333"
                  stroke="#00A86B"
                  stroke-width="2.45902"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </button>
          </div>
          <!-- navigations -->
          <?php
            if ( has_nav_menu( 'top-menu' ) ) :
            wp_nav_menu( array (
                'theme_location' => 'top-menu',
                'container' => '',
                'container_class' => '',
                'menu_class' => 'ms-mobile-menu__nav',
            ));
            endif;
            ?>
          <ul class="ms-mobile-menu__nav">
            <?php if( !empty( $dashboard_favorites ) ) { ?>
            <li>
              <a class="dropdown-item dropdown-item--heart" href="<?php echo esc_url( $dashboard_favorites ); ?>"
                ><i class="fa-light fa-heart"></i> Favorite
                <span class="dropdown-item__status"><?php 
								if(is_user_logged_in()) {
									$favorite_ids = get_user_meta( $userID, 'houzez_favorites', true );
									echo str_pad(count($favorite_ids), 2, '0', STR_PAD_LEFT);
								} else {
									echo '00';
								}
							?></span>
              </a>
            </li>
            <?php } ?>
          </ul>
          <!-- language -->
          <div class="ms-mobile-menu__language">
            <?php 
						if (shortcode_exists('language-switcher')) {
							echo '<h6>Languages</h6>';
							echo do_shortcode('[language-switcher]'); 
						}?>
            <script>
            document.addEventListener('DOMContentLoaded', () => {
              const languageContainerDiv = document.querySelector('.ms-mobile-menu__language');
              const languageDiv = languageContainerDiv.querySelector('.trp-ls-shortcode-language');
                if (languageDiv) {
                    const links = languageDiv.querySelectorAll('a');
                    const ul = document.createElement('ul');

                    links.forEach(link => {
                        const li = document.createElement('li');
                        const newLink = document.createElement('a');

                        newLink.href = link.getAttribute('href');
                        newLink.textContent = link.textContent;

                        if (link.hasAttribute('onclick')) {
                            newLink.setAttribute('onclick', link.getAttribute('onclick'));
                        }

                        li.appendChild(newLink);
                        ul.appendChild(li);
                    });

                    // Replace the original div with the new <ul>
                    languageContainerDiv.querySelector('.trp_language_switcher_shortcode').replaceWith(ul);
                }
            });
            </script>
          </div>
          <!-- profile -->
          <div class="ms-mobile-menu__profile-button">
            <a href="javascript:;" class="form-wizard-next-btn ms-btn">
              <span
                >
                <?php
                if( !empty( $author_picture_id ) ) {
                    $author_picture_id = intval( $author_picture_id );
                    if ( $author_picture_id ) {
                        echo wp_get_attachment_image( $author_picture_id, 'medium', "" );
                    }
                } else {
                    print '<img src="'.esc_url( $user_custom_picture ).'" alt="user image" >';
                }
                if( !empty( $display_name ) ) {
                    echo " ".$display_name;
                }
                else{
                    echo " Login";
                }
                ?>
                </span
              >
              <span class="ms-mobile-menu__arrow">
                <svg
                  width="6"
                  height="10"
                  viewBox="0 0 6 10"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M0.640765 0.836835C0.485555 0.99297 0.398437 1.20418 0.398437 1.42434C0.398437 1.64449 0.485555 1.8557 0.640765 2.01184L3.59076 5.0035L0.640765 7.9535C0.485555 8.10964 0.398437 8.32085 0.398437 8.541C0.398437 8.76116 0.485555 8.97237 0.640765 9.1285C0.718234 9.20661 0.810401 9.2686 0.911951 9.31091C1.0135 9.35322 1.12242 9.375 1.23243 9.375C1.34244 9.375 1.45136 9.35322 1.55291 9.31091C1.65446 9.2686 1.74663 9.20661 1.8241 9.1285L5.35743 5.59517C5.43554 5.5177 5.49753 5.42553 5.53984 5.32398C5.58215 5.22243 5.60393 5.11351 5.60393 5.0035C5.60393 4.89349 5.58215 4.78457 5.53984 4.68302C5.49753 4.58147 5.43554 4.4893 5.35743 4.41184L1.8241 0.836835C1.74663 0.758728 1.65446 0.696733 1.55291 0.654426C1.45136 0.612119 1.34244 0.590336 1.23243 0.590336C1.12242 0.590336 1.0135 0.612119 0.911951 0.654426C0.810401 0.696733 0.718234 0.758728 0.640765 0.836835Z"
                    fill="#1B1B1B"
                  />
                </svg>
              </span>
            </a>
          </div>
        </div>
        <!-- logout -->
        <div
          class="ms-mobile-menu__nav__wrapper ms-mobile-menu__nav__wrapper-profile wizard-fieldset"
        >
          <div class="sidebar-toggler">
            <button href="javascript:;" class="form-wizard-previous-btn">
              <svg
                width="10"
                height="20"
                viewBox="0 0 10 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M8.2793 18.1968L1.72192 10.0001L8.2793 1.80333"
                  stroke="#00A86B"
                  stroke-width="2.45902"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </button>
          </div>
          <!-- navigations -->
          <ul class="ms-mobile-menu__nav ms-mobile-menu__nav--logout">
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

          <!-- profile -->
          <div class="ms-mobile-menu__profile-button">
            <?php if( is_user_logged_in() ) { ?>
                <a class="form-wizard-next-btn ms-btn justify-content-center" href="<?php echo wp_logout_url( home_url() ); ?>"><span>Log Out</span></a>
            <?php } else { ?>
                <?php if( houzez_option('header_login') ) { ?>
                    <a class="form-wizard-next-btn ms-btn justify-content-center" href="/login"><span><?php esc_html_e('Login', 'houzez'); ?></span></a>
                <?php } ?>
            <?php } ?>
          </div>
        </div>
    </div>
</div>