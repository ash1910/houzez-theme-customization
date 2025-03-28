<?php
/**
 * The Template for displaying all single posts
 * @since Houzez 1.0
 */

get_header();
$is_sticky = '';
$sticky_sidebar = houzez_option('sticky_sidebar');
if( $sticky_sidebar['default_sidebar'] != 0 ) { 
    $is_sticky = 'houzez_sticky'; 
}
$blog_author_box = houzez_option('blog_author_box');

$blog_layout = houzez_option('blog_single_layout');

if( $blog_layout == 'no-sidebar' ) {
    $content_classes = 'col-lg-12 col-md-12';
} else if( $blog_layout == 'left-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap wrap-order-first';
} else if( $blog_layout == 'right-sidebar' ) {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
} else {
    $content_classes = 'col-lg-8 col-md-12 bt-content-wrap';
}


if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
<!-- start: Hero Banner -->
<section>
    <div class="container-fluid container-fluid--lg">
        <div class="ms-hero__wrapper">
            <?php if (is_active_sidebar('blog-detail-ads')) : ?>
				<?php dynamic_sidebar('blog-detail-ads'); ?>
			<?php else: ?>
            <div class="ms-hero ms-hero--blog" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blog/bnner.png')">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="ms-hero__content">
                                <h1 class="ms-hero__title">
                                    <?php the_title(); ?>
                                </h1>
                                <div class="ms-apartments-main__breadcrumb ms-apartments-main__breadcrumb--hero">
                                    <ul>
                                        <li><a href="<?php echo home_url(); ?>">Home</a></li>
                                        <li>></li>
                                        <li>
                                            <a href="<?php echo home_url(); ?>/blog">Blog</a>
                                        </li>
                                        <li>></li>
                                        <li>
                                            <p>
                                                <?php the_title(); ?>
                                            </p>
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
            <?php endif; ?>
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
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post(); ?>
                <!-- blog tab content -->
                <div
                    class="ms-apartments-main__section ms-apartments-main__section--blog ms-apartments-main__section--blog-details">
                    <!-- blog thumbnail -->
                    <div class="ms-blog-details__thumbnail">
                        <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                    </div>
                    <!-- blog title -->
                    <div class="ms-apartments-main__heading">
                        <h4><?php the_title(); ?></h4>
                        <p class=""><?php the_date(); ?></p>
                    </div>
                    <div class="ms-blog-details__author__wrapper">
                        <div class="ms-blog-details__author">
                            <div>
                                <a href="javascript:void(0)">
                                    <?php $author_pic = get_the_author_meta('author_pic');
                                    $fave_author_custom_picture = get_the_author_meta('fave_author_custom_picture');
                                    ?>
                                    <?php if ($author_pic){ ?>
                                        <img style="width: 44px; height: 44px;" src="<?php echo $author_pic; ?>" alt="" />
                                    <?php } elseif ($fave_author_custom_picture){ ?>
                                        <img style="width: 44px; height: 44px;" src="<?php echo $fave_author_custom_picture; ?>" alt="" />
                                    <?php } else { ?>
                                        <img style="width: 44px; height: 44px;" src="https://secure.gravatar.com/avatar/fc8b51dba5ead4d601afd21724635fde?s=80&d=mm&r=g" alt="" />
                                    <?php } ?>
                                </a> 
                            </div>
                            <div class="ms-blog-details__author__desc">
                                <p>Written by</p>
                                <h6><?php the_author(); ?></h6>
                            </div>
                        </div>
                        <!-- share -->
                        <div class="ms-blog-details__author__share">
                            <p>
                                <?php echo calculate_reading_time(get_the_ID()); ?> 
                            </p>
                            <div class="social-share">
                                <?php 
                                $share_url = urlencode(get_permalink());
                                $share_title = urlencode(get_the_title());
                                ?>
                                <div class="share-dropdown">
                                    <button class="share-dropdown-btn">
                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M20.3322 15.1668C19.5761 15.1674 18.8315 15.352 18.1627 15.7046C17.4939 16.0572 16.9209 16.5673 16.4932 17.1908L9.9842 14.2518C10.4465 13.1354 10.4483 11.8814 9.9892 10.7638L16.4892 7.80976C17.1231 8.72652 18.0645 9.38572 19.1428 9.66781C20.2211 9.94989 21.3647 9.83616 22.3663 9.34726C23.3679 8.85836 24.1611 8.02666 24.602 7.00303C25.043 5.97939 25.1024 4.83163 24.7696 3.76793C24.4367 2.70423 23.7337 1.79506 22.7879 1.20531C21.8422 0.615573 20.7164 0.384332 19.6147 0.553535C18.5131 0.722739 17.5086 1.28118 16.7834 2.12756C16.0583 2.97394 15.6605 4.05221 15.6622 5.16676C15.6664 5.43056 15.6932 5.69352 15.7422 5.95276L8.8322 9.09276C8.16854 8.47098 7.33771 8.0566 6.44177 7.90052C5.54584 7.74445 4.62383 7.85348 3.78901 8.21422C2.95419 8.57496 2.24293 9.1717 1.7426 9.93112C1.24227 10.6905 0.974666 11.5796 0.972668 12.489C0.970669 13.3984 1.23436 14.2886 1.73135 15.0503C2.22834 15.8119 2.93697 16.4117 3.7702 16.7761C4.60342 17.1405 5.52495 17.2536 6.42156 17.1015C7.31817 16.9493 8.15081 16.5386 8.8172 15.9198L15.7452 19.0478C15.6971 19.3068 15.6707 19.5694 15.6662 19.8328C15.666 20.7559 15.9396 21.6583 16.4523 22.426C16.9651 23.1936 17.6939 23.792 18.5468 24.1453C19.3996 24.4987 20.338 24.5912 21.2434 24.4111C22.1488 24.2311 22.9805 23.7866 23.6333 23.1338C24.286 22.4811 24.7305 21.6494 24.9106 20.744C25.0906 19.8386 24.9981 18.9001 24.6448 18.0473C24.2914 17.1945 23.6931 16.4656 22.9254 15.9529C22.1578 15.4401 21.2553 15.1666 20.3322 15.1668ZM20.3322 2.49976C20.8597 2.49956 21.3755 2.65581 21.8142 2.94874C22.2529 3.24168 22.5949 3.65814 22.7969 4.14545C22.9989 4.63276 23.0519 5.16904 22.9491 5.68645C22.8463 6.20387 22.5924 6.67917 22.2194 7.05226C21.8465 7.42535 21.3712 7.67945 20.8539 7.78244C20.3365 7.88543 19.8002 7.83267 19.3128 7.63084C18.8254 7.42901 18.4088 7.08718 18.1157 6.64857C17.8226 6.20996 17.6662 5.69428 17.6662 5.16676C17.6667 4.45976 17.9478 3.78186 18.4476 3.28184C18.9474 2.78183 19.6252 2.50055 20.3322 2.49976ZM5.6662 15.1668C5.13867 15.167 4.62294 15.0107 4.18422 14.7178C3.7455 14.4248 3.40351 14.0084 3.2015 13.5211C2.99949 13.0338 2.94653 12.4975 3.04932 11.9801C3.15212 11.4627 3.40605 10.9873 3.77899 10.6143C4.15194 10.2412 4.62715 9.98707 5.14453 9.88408C5.6619 9.78109 6.1982 9.83385 6.68559 10.0357C7.17297 10.2375 7.58956 10.5793 7.88266 11.018C8.17576 11.4566 8.3322 11.9722 8.3322 12.4998C8.33141 13.2067 8.0503 13.8844 7.55053 14.3844C7.05076 14.8843 6.37312 15.1657 5.6662 15.1668ZM20.3322 22.4998C19.8047 22.4998 19.2891 22.3433 18.8505 22.0503C18.4119 21.7572 18.0701 21.3407 17.8682 20.8534C17.6664 20.366 17.6135 19.8298 17.7164 19.3125C17.8194 18.7951 18.0734 18.3199 18.4463 17.9469C18.8193 17.5739 19.2945 17.3199 19.8119 17.217C20.3292 17.1141 20.8655 17.1669 21.3528 17.3688C21.8401 17.5706 22.2567 17.9125 22.5497 18.3511C22.8428 18.7896 22.9992 19.3053 22.9992 19.8328C22.9987 20.5399 22.7175 21.218 22.2175 21.718C21.7174 22.2181 21.0394 22.4992 20.3322 22.4998Z"
												fill="#1B1B1B" />
										</svg>
                                    </button>
                                    <div class="share-dropdown-content">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="social-share-link">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.5 14h1.5l.5-3h-2v-2c0-.9.3-1.5 1.5-1.5h1.5v-2.5c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 4v2.2h-2.5v3h2.5v8h3v-8z" fill="#1B1B1B"/>
                                            </svg>
                                            <span>Facebook</span>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="social-share-link">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18.8 8.2c-.6.3-1.2.5-1.9.6.7-.4 1.2-1 1.4-1.8-.6.4-1.3.6-2.1.8-.6-.6-1.4-1-2.4-1-1.8 0-3.3 1.5-3.3 3.3 0 .3 0 .5.1.7-2.7-.1-5.1-1.4-6.7-3.4-.3.5-.4 1-.4 1.7 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.5-.4 0 1.6 1.1 2.9 2.6 3.2-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3.1 2.3-1.1.9-2.5 1.4-4.1 1.4h-.8c1.5.9 3.2 1.5 5 1.5 6 0 9.3-5 9.3-9.3v-.4c.7-.5 1.3-1.1 1.7-1.8z" fill="#1B1B1B"/>
                                            </svg>
                                            <span>Twitter</span>
                                        </a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" target="_blank" class="social-share-link">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.7 9.7H4.8v9.8h2.9V9.7zM6.3 8.5c.9 0 1.7-.8 1.7-1.7S7.2 5 6.3 5s-1.7.8-1.7 1.7.7 1.8 1.7 1.8zm10.9 4.7c0-2.4-1.9-2.9-2.9-2.9-1.4 0-2.2.8-2.6 1.5V9.7H9v9.8h2.9v-5.2c0-1.2.9-2.2 2.2-2.2s2.2 1 2.2 2.2v5.2h2.9v-6.3z" fill="#1B1B1B"/>
                                            </svg>
                                            <span>LinkedIn</span>
                                        </a>
                                        <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" class="social-share-link">
                                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 5H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V7l8 5 8-5v2z" fill="#1B1B1B"/>
                                            </svg>
                                            <span>Email</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- blog description -->
                    <div class="ms-blog-details__desc ms-blog-details__desc__single">
                            <?php 
                            $content = get_the_content();
                            
                            if (has_shortcode($content, 'gallery')) {
                                $pattern = get_shortcode_regex(['gallery']);
                                preg_match('/'.$pattern.'/s', $content, $matches);
                                $gallery_shortcode = isset($matches[0]) ? $matches[0] : '';
                                
                                // Get gallery IDs from shortcode
                                $gallery = get_post_gallery(get_the_ID(), false);
                                $gallery_ids = isset($gallery['ids']) ? explode(',', $gallery['ids']) : array();
                                
                                // Split content at gallery shortcode position
                                $content_parts = explode($gallery_shortcode, $content);
                                
                                // Display content before gallery
                                if (!empty($content_parts[0])) {
                                    echo apply_filters('the_content', $content_parts[0]);
                                }
                                
                                // Display custom gallery layout
                                if (!empty($gallery_ids)) {
                                    echo '<div class="ms-blog-details__grid">';
                                    foreach($gallery_ids as $index => $image_id) {
                                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                        
                                        echo '<div class="ms-blog-details__grid__item">';
                                        if ($index === 1) {
                                            $image_url_desktop = wp_get_attachment_image_url($image_id, 'full');
                                            $image_url_mobile = wp_get_attachment_image_url($image_id, 'full');
                                            echo '<img src="' . esc_url($image_url_desktop) . '" alt="' . esc_attr($image_alt) . '" class="d-none d-md-block" />';
                                            echo '<img src="' . esc_url($image_url_mobile) . '" alt="' . esc_attr($image_alt) . '" class="d-block d-md-none" />';
                                        } else {
                                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
                                        }
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                
                                // Display content after gallery
                                if (!empty($content_parts[1])) {
                                    echo apply_filters('the_content', $content_parts[1]);
                                }
                            } else {
                                // If no gallery, just display the content
                                echo apply_filters('the_content', $content);
                            }
                            ?>
                    </div>

                    <?php get_template_part('template-parts/blog/blog-comment'); ?>
                    
                </div>
                <?php endwhile; ?>
            </div>

            <!-- apartment sidebar -->
            <div class="col-12 col-md-7 col-xl-4 pl-3">
                <?php get_template_part('template-parts/blog/blog-sidebar'); ?>
            </div>
        </div>
    </div>
</section>
<!-- start: New Project Details    -->

<?php get_template_part('template-parts/blog/blog-post-recent'); ?>
 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shareBtn = document.querySelector('.share-dropdown-btn');
        const dropdownContent = document.querySelector('.share-dropdown-content');
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.share-dropdown')) {
                dropdownContent.style.display = 'none';
            }
        });

        // Toggle dropdown on button click
        shareBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';
        });
    });
</script>

<?php
}
get_footer();
