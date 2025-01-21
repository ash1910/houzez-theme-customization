<?php 
global $post;

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

$header = houzez_option('header_style'); 
if(empty($header) || houzez_is_splash()) {
	$header = '4';
}


if($header == 11){ ?>
<!-- MEstate Header -->
    <!-- start: Header Area -->
    <header class="ms-header">
      <div class="container">
        <?php
        if( houzez_option('top_bar') ) {
          get_template_part('template-parts/topbar/top', 'bar');
        }
        ?>
        <div class="ms-header__inner-wrapper">
          <div class="ms-header__inner">
            <!-- logo area -->
            <div class="ms-logo">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php if( !empty( $custom_logo ) ) { ?>
                  <img src="<?php echo esc_url( $custom_logo ); ?>" height="<?php echo esc_attr($logo_height); ?>" width="<?php echo esc_attr($logo_width); ?>" alt="logo">
                <?php } ?>
              </a>
            </div>
            <!-- nav munu main -->
            <nav class="ms-header__nav d-none d-lg-block">
              <ul class="ms-header__nav-list">
                <li><a href="">Buy</a></li>
                <li><a href="">Rent</a></li>
                <li><a href="">Sell</a></li>
              </ul>
            </nav>
            <!-- header right -->
            <div class="ms-header__right">
              <ul class="ms-header__right-list">
                <li class="d-none d-lg-block">
                  <div class="ms-header__right-lang">
                    <label for="input-lang"><i class="icon-world"></i></label>
                    <select class="ms-nice-select" name="input-lang" id="input-lang">
                      <option value="English">English</option>
                      <option value="English (Br)">English (Aus)</option>
                      <option value="Arabic">Arabic</option>
                      <option value="French">French</option>
                    </select>
                  </div>
                </li>
                <li class="d-none d-lg-block">
                  <a href="#" class="ms-header__heart">
                    <i class="fa-light fa-heart"></i>
                  </a>
                </li>
                <li class="ms-header__avatar block d-lg-none">
                  <button
                    class="ms-header__avater__inner ms-mobile-menu__toggler"
                  >
                    <span>
                      <i class="icon-menu_2line"></i>
                    </span>
                    <span>
                      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/header/avatar.png" alt=""
                    /></span>
                  </button>
                </li>
                <li
                  class="ms-header__avatar ms-header__avatar--dropdown d-none d-lg-block"
                >
                  <a href="#" class="ms-header__avater__inner" href="#">
                    <span class="ms-mobile-menu__toggler">
                      <i class="icon-menu_2line"></i>
                    </span>

                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/header/avatar.png" alt="" />
                  </a>
                  <div class="dropdown-menu">
                    <div class="dropdown-menu__inner">
                      <ul>
                        <li><a class="dropdown-item" href="#">Dashboard</a></li>
                        <li>
                          <a class="dropdown-item" href="#">Properties</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">Invoices</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#"
                            >Create a Listing
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">Profile </a>
                        </li>
                      </ul>

                      <button class="ms-btn">Log Out</button>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>

<?php } else{ ?>

<header class="header-main-wrap <?php houzez_transparent(); ?>">
    <?php
		if( houzez_option('top_bar') ) {
			get_template_part('template-parts/topbar/top', 'bar');
		}
    	
	    get_template_part('template-parts/header/header', $header); 
	    get_template_part('template-parts/header/header-mobile'); 
    ?>
</header><!-- .header-main-wrap -->

<?php }?>