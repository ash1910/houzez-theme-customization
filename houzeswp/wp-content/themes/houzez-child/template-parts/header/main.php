<?php 
$header = houzez_option('header_style'); 
if(empty($header) || houzez_is_splash()) {
	$header = '4';
}

if($header == 11){ ?>
<!-- MEstate Header -->
<header class="ms-header">
 <div class="container">
	<div class="ms-header__inner-wrapper">
		<div class="ms-header__inner">
		<!-- logo area -->
		<div class="ms-logo">
			<a href="/index.html">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" alt="" />
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
				<select name="input-lang" id="input-lang">
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