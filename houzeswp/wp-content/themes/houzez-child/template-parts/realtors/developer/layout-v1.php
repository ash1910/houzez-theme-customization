<?php
global $houzez_local, $paged;
$sticky_sidebar = houzez_option('sticky_sidebar');
$page_content_position = houzez_get_listing_data('listing_page_content_area');

$args = array(
    'post_type' => 'houzez_developer',
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'OR',
            array(
             'key' => 'fave_developer_visible',
             'compare' => 'NOT EXISTS', // works!
             'value' => '' // This is ignored, but is necessary...
            ),
            array(
             'key' => 'fave_developer_visible',
             'value' => 1,
             'type' => 'NUMERIC',
             'compare' => '!=',
            )
    )
);

$is_search = false;
if( isset( $_GET['developer-search'] ) ) {
    $args = apply_filters( 'houzez_developers_search_filter', $args );
    $is_search = true;
} else {
    $args = apply_filters( 'houzez_get_developers', $args );
}

$developers_query = new WP_Query( $args );
$records_found = $developers_query->found_posts;

?>

<section class="listing-wrap developers-template-wrap">
    <div class="container">
        
        <div class="page-title-wrap">
            <?php get_template_part('template-parts/page/breadcrumb'); ?> 
            <?php if( ! $is_search ) { ?>
            <div class="d-flex align-items-center">
                <?php get_template_part('template-parts/page/page-title'); ?> 
            </div><!-- d-flex -->  
            <?php } ?>
        </div><!-- page-title-wrap -->

        <div class="row">
            <div class="col-lg-8 col-md-12 bt-content-wrap right-bt-content-wrap">

                <?php
                if ( $page_content_position !== '1' ) {
                    if ( have_posts() ) {
                        while ( have_posts() ) {
                            the_post();
                            ?>
                            <article <?php post_class(); ?>>
                                <?php the_content(); ?>
                            </article>
                            <?php
                        }
                    } 
                }?>

                <div class="developers-list-view">
                    <?php
                    if ( $developers_query->have_posts() ) :
                    while ( $developers_query->have_posts() ) : $developers_query->the_post();

                        get_template_part('template-parts/realtors/developer/list');

                    endwhile;
                    
                    else:
                        get_template_part('template-parts/realtors/developer/none');
                    endif;
                    ?>
                </div>
                
                <?php houzez_pagination( $developers_query->max_num_pages ); wp_reset_query(); ?>

            </div><!-- bt-content-wrap -->
            <div class="col-lg-4 col-md-12 bt-sidebar-wrap left-bt-sidebar-wrap <?php if( $sticky_sidebar['developer_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
                <?php get_sidebar('houzez_developers'); ?>
            </div><!-- bt-sidebar-wrap -->
        </div><!-- row -->
    </div><!-- container -->
</section><!-- listing-wrap -->

<?php
if ('1' === $page_content_position ) {
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            ?>
            <section class="content-wrap">
                <?php the_content(); ?>
            </section>
            <?php
        }
    }
}
?>