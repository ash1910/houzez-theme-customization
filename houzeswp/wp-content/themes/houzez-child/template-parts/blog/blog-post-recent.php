<!-- start: blog slider -->
<section class="ms-apartments section--wrapper">
    <div class="container-fluid">
        <div class="row">
            <!-- section heading -->

            <div class="col-12">
                <div class="ms-section-heading">
                    <?php if ( is_active_sidebar( 'recent-blog-header' ) ) : ?>
                        <?php dynamic_sidebar( 'recent-blog-header' ); ?>
                    <?php else : ?>
                        <h2>Recent Blog</h2>
                        <p>
                            Stay ahead of the market with a quick glance at the most
                            popular searches in your favorite regions. Explore top
                            listings and find out what properties are capturing attention
                            in your area.
                        </p>
                    <?php endif; ?>
                </div>
                <!-- content -->
                <div class="col-12">
                    <div class="ms-location__tab">
                        <div class="swiper ms-blog-slider">
                            <div class="swiper-wrapper">
                                <?php
                                $args = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 6,
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                );
                                $query = new WP_Query($args);
                                if ($query->have_posts()) {
                                    while ($query->have_posts()) {
                                        $query->the_post();
                                ?>
                                <div class="swiper-slide">
                                    <div class="ms-apartments-main__card ms-apartments-main__card--2 ms-apartments-main__card--blog">
                                        <div class="ms-apartments-main__card__thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('class' => '')); ?>
                                            </a>
                                        </div>
                                        <div class="ms-apartments-main__card__content">
                                            <p class="ms-apartments-main__card__date">
                                                <?php echo get_the_date(); ?>
                                            </p>
                                            <h5>
                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 9, '...'); ?></a>
                                            </h5>
                                            <p>
                                                <?php echo houzez_clean_excerpt( 80, 'false' ); ?>
                                            </p>

                                            <div class="ms-apartments-main__card__reademore">
                                                <a href="<?php the_permalink(); ?>">Read more
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M20.5 22H3.5" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M19 3.5L5 17.5" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M19 13.77V3.5H8.73" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                wp_reset_postdata();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end: blogs slider -->