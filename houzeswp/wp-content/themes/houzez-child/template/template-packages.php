<?php
/**
 * Template Name: Packages
 */
global $houzez_local;

$paid_submission_type = esc_html ( houzez_option('enable_paid_submission','') );
if( $paid_submission_type != 'membership' ) {
    wp_redirect( home_url() );
}

get_header();

if(houzez_check_is_elementor()) {

    if( have_posts() ):
        while( have_posts() ): the_post();
            $content = get_the_content();
        endwhile;
     endif;
    wp_reset_postdata();
    if( !empty($content) ) { 
        the_content();

    } else {
        the_content();
        ?>
        <section class="frontend-submission-page">
            <div class="container">
                <div class="dashboard-content-block-wrap">
                    <div class="row row-no-padding">
                        <?php get_template_part('template-parts/membership/package-item'); ?>
                    </div>
                </div><!-- dashboard-content-block-wrap -->
            </div><!-- container -->
        </section><!-- frontend-submission-page -->
        <?php
    }

} else { ?>

    <section class="frontend-submission-page">
        <div class="container">
            <div class="dashboard-content-block-wrap">
                <div class="row">
                    <div class="col-md-12">
                        <div class="listing-map-button-view" style="width: 250px; float: right; margin-bottom: 15px;">
                            <ul class="list-inline">
                                <li class="list-inline-item <?php if($_GET['time_period'] == 6)echo 'active';?>">
                                    <a class="btn btn-primary-outlined btn-listing" href="/packages/?time_period=6">
                                        <i class="btn-icon">
                                            <?php include get_stylesheet_directory() . '/assets/images/list_icon.svg'; ?>
                                        </i>
                                        <span>06 Months</span>
                                    </a>
                                </li>
                                <li class="list-inline-item <?php if($_GET['time_period'] == 12)echo 'active';?>">
                                    <a class="btn btn-primary-outlined btn-listing" href="/packages/?time_period=12">
                                        <i class="btn-icon icon-map">
                                             <?php include get_stylesheet_directory() . '/assets/images/list_icon.svg'; ?>
                                        </i> 
                                        <span>12 Months</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
                if( have_posts() ):
                    while( have_posts() ): the_post();
                        $content = get_the_content();
                    endwhile;
                 endif;
                wp_reset_postdata();
                if( !empty($content) ) { 
                    the_content();

                } else {
                    echo '<div class="row row-no-padding">';
                    get_template_part('template-parts/membership/package-item');
                    echo '<div>';
                }
                ?>
            </div><!-- dashboard-content-block-wrap -->
            
        </div><!-- container -->
    </section>

<?php
}
?>

<?php get_footer(); ?>