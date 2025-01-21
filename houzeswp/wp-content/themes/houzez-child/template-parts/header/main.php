<?php 
$sticky_header = houzez_option('main-menu-sticky', 0);

$header = houzez_option('header_style'); 
if(empty($header) || houzez_is_splash()) {
	$header = '4';
}

// MEstate Header
if($header == 11){

  if( houzez_option('top_bar') ) {
    get_template_part('template-parts/topbar/top', 'bar');
  }

  get_template_part('template-parts/header/header', $header); 

  if($sticky_header == 1){
    get_template_part('template-parts/header/header', $header, array('sticky_header' => $sticky_header));
  }

  get_template_part('template-parts/header/header-mobile', $header); 

} else{ ?>

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