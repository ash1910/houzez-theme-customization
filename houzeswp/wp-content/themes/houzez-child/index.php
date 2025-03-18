<?php
get_header(); 

$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');
if( $sticky_sidebar['default_sidebar'] != 0 ) { 
    $is_sticky = 'houzez_sticky'; 
}

$blog_layout = houzez_option('blog_pages_s_layout');

if( $blog_layout == 'no-sidebar' ) {
    $content_classes = 'col-lg-12 col-md-12';
} else if( $blog_layout == 'left-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap wrap-order-first';
} else if( $blog_layout == 'right-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
} else {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) { ?>

<!-- start: Hero Banner -->
<section>
    <div class="container-fluid container-fluid--lg">
        <div class="ms-hero__wrapper">
            <div class="ms-hero ms-hero--blog" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blog/bnner.png')">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="ms-hero__content">
                                <h1 class="ms-hero__title">Blog</h1>
                                <div class="ms-apartments-main__breadcrumb ms-apartments-main__breadcrumb--hero">
                                    <ul>
                                        <li><a href="<?php echo home_url(); ?>">Home</a></li>
                                        <li>></li>
                                        <li>
                                            <p>Blog</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<!-- search Mobile -->
                <?php echo houzez_property_blog_search();?>


            </div>
        </div>
    </div>
</section>
<!-- end: Hero Banner -->

<!-- start: blogs  -->
<section class="ms-apartments-main ms-apartments-main--details ms-apartments-main--blog section--wrapper">
	<div class="container">
		<div class="row">
			<!-- blog content -->
			<div class="col-12 col-xl-8 mb-0 mb-md-5 mb-xl-0">
				<!-- blog tab content -->
				<div class="ms-apartments-main__section ms-apartments-main__section--blog">
					<div class="ms-apartments-main__heading">
						<h4>Blogs</h4>
					</div>
					<!-- tab controllers  -->
					<div class="ms-blog-tab__controllers nav nav-tab ms-nav-tab" role="tablist ">
						<button data-target="#ms-blog-grid" data-toggle="tab" class="ms-btn ms-btn-primary active">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52Z"
									fill="white" />
								<path
									d="M22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77Z"
									fill="white" />
								<path
									d="M10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52Z"
									fill="white" />
								<path
									d="M10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
									fill="white" />
							</svg>
						</button>
						<button data-target="#ms-blog-list" data-toggle="tab" class="ms-btn ms-btn-primary">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M10 6H16M10 14H16M10 10H22M10 18H22M4 10C2.89543 10 2 9.10457 2 8C2 6.89543 2.89543 6 4 6C5.10457 6 6 6.89543 6 8C6 9.10457 5.10457 10 4 10ZM4 18C2.89543 18 2 17.1046 2 16C2 14.8954 2.89543 14 4 14C5.10457 14 6 14.8954 6 16C6 17.1046 5.10457 18 4 18Z"
									stroke="#1B1B1B" stroke-width="1.5" stroke-linecap="round" />
							</svg>
						</button>
					</div>
					<div class="tab-content">
						<div id="ms-blog-grid" class="tab-pane fade show active">
							<!-- apartments cards -->
							<div class="ms-apartments-main__card__wrapper ms-apartments-main__card__wrapper--2">
								<?php
								if ( have_posts() ) :

									while ( have_posts() ) : the_post();

										get_template_part('template-parts/blog/blog-post');

									endwhile;

								else :
									// If no content, include the "No posts found" template.
									get_template_part( 'content', 'none' );

								endif;
								?>
							</div>

							<!--start pagination-->
							<?php houzez_pagination( $wp_query->max_num_pages ); ?>
							<!--end pagination-->
						</div>
					</div>
				</div>
			</div>

			<!-- apartment sidebar -->
            <div class="col-12 col-md-7 col-xl-4 pl-3">
                <?php get_template_part('template-parts/blog/blog-sidebar'); ?>
            </div>
		</div>
	</div>
</section>

<?php get_template_part('template-parts/blog/blog-post-recent'); ?>

<?php
}
get_footer();
