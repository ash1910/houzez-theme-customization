<?php
global $post, $top_area, $hide_fields;

wp_enqueue_script('houzez-overview-listing-map-banner',  get_stylesheet_directory_uri().'/js/single-property-osm-overview-map-banner.js', array('jquery'), '1.0.0', true);

$virtual_tour = houzez_get_listing_data('virtual_tour');
$prop_video_img = '';
$prop_video_url = houzez_get_listing_data('video_url');
if( !empty( $prop_video_url ) ) {

    if ( empty( $prop_video_img ) ) :

        $prop_video_img = wp_get_attachment_url( get_post_thumbnail_id( $post ) );

    endif;
}


if ( houzez_site_width() != '1210px' && $top_area != 'v3' ) {
    $image_size = 'full';
} else {
    $image_size = 'houzez-gallery';
}

$properties_images = rwmb_meta( 'fave_property_images', 'type=plupload_image&size='.$image_size, $post->ID );
$gallery_caption = houzez_option('gallery_caption', 0); 
$property_gallery_popup_type = houzez_get_popup_gallery_type(); 
?>

<!-- start: Hero Banner -->
<section>
    <div class="container-fluid container-fluid--lg">
        <div class="ms-hero__wrapper">
            <!-- controller -->
            <div class="ms-hero__btn-list nav nav-tab ms-nav-tab" role="tablist">
                <button class="ms-btn ms-btn--gallery ms-btn--2 active " data-target="#ms-gallery" data-toggle="tab">
                    <i class="fa-solid fa-image"></i> Gallery
                </button>
                <button class="ms-btn ms-btn--2" data-target="#ms-map" data-toggle="tab">
                    <i class="icon-location_fill"></i> Map
                </button>
                <?php if( !empty( $prop_video_url ) ) { ?>
                <button class="ms-btn ms-btn--2" data-target="#ms-video" data-toggle="tab">
                    <i class="icon-playbutton_white"></i> Request Video
                </button>
                <?php } ?>
                <?php if( !empty( $virtual_tour ) ) { ?>
                <button class="ms-btn ms-btn--2" data-target="#ms-360" data-toggle="tab">
                    <svg width="48" height="26" viewBox="0 0 48 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M38.5498 5.5313C40.0616 5.5313 41.2872 4.30571 41.2872 2.79397C41.2872 1.28223 40.0616 0.0566406 38.5498 0.0566406C37.0381 0.0566406 35.8125 1.28223 35.8125 2.79397C35.8142 4.30495 37.0387 5.52946 38.5498 5.5313ZM38.5498 1.97948C38.999 1.97948 39.3632 2.34368 39.3632 2.79289C39.3632 3.24209 38.999 3.60629 38.5498 3.60629C38.1006 3.60629 37.7364 3.24209 37.7364 2.79289C37.737 2.3439 38.1008 1.98002 38.5498 1.97948Z"
                            fill="white" />
                        <path
                            d="M41.2705 6.68075C40.7958 6.44225 40.2177 6.63374 39.9791 7.10845C39.7406 7.58317 39.9321 8.16133 40.4068 8.39994C40.416 8.4046 40.4252 8.40905 40.4347 8.4134C44.0688 10.1684 46.0782 12.3682 46.0782 14.6128C46.0782 16.9439 43.8251 19.2943 39.8982 21.0612C35.6688 22.9637 30.023 24.0112 24.0022 24.0112C17.9814 24.0112 12.3368 22.9637 8.10626 21.0612C4.17818 19.2954 1.92621 16.945 1.92621 14.6128C1.92621 12.3682 3.93035 10.1706 7.56978 8.4134C8.05198 8.19032 8.26193 7.61856 8.03885 7.13635C7.81576 6.65415 7.244 6.4442 6.7618 6.66728C6.75247 6.67163 6.74313 6.67608 6.7339 6.68075C2.39212 8.77358 0 11.59 0 14.6106C0 20.9596 10.5421 25.9318 24.0001 25.9318C37.458 25.9318 48.0001 20.9585 48.0001 14.6106C48.0033 11.59 45.6122 8.77358 41.2705 6.68075Z"
                            fill="white" />
                        <path
                            d="M10.466 17.1281C11.2662 17.8628 12.3519 18.23 13.7228 18.23C15.0289 18.23 16.0878 17.8738 16.8994 17.1612C17.7111 16.4486 18.1165 15.5223 18.1158 14.3822C18.1158 13.5983 17.9124 12.9518 17.5055 12.4422C17.0986 11.9327 16.5286 11.6121 15.7953 11.4802C16.4003 11.2928 16.8606 10.9917 17.1763 10.577C17.4992 10.1397 17.666 9.60684 17.6498 9.06352C17.6498 8.13223 17.3057 7.3947 16.6173 6.85095C15.929 6.3072 14.9855 6.03863 13.787 6.04503C12.443 6.04503 11.3978 6.34899 10.6509 6.95679C9.90415 7.56459 9.52008 8.41577 9.49869 9.51023H12.3419C12.3525 9.10836 12.4705 8.79767 12.6956 8.57817C12.9208 8.35867 13.2368 8.24892 13.6438 8.24892C13.9876 8.2359 14.3229 8.35694 14.579 8.58664C14.8225 8.81124 14.956 9.13094 14.9445 9.46203C14.9445 9.91166 14.7998 10.2544 14.5106 10.4903C14.2213 10.7262 13.8062 10.844 13.2653 10.844H13.1008V12.7316C13.1435 12.7316 13.1905 12.7316 13.2408 12.7316C13.2911 12.7316 13.3648 12.7316 13.462 12.7316C14.0613 12.7316 14.5017 12.8588 14.7832 13.1132C15.0646 13.3675 15.205 13.7652 15.2043 14.306C15.2043 14.7876 15.0731 15.1558 14.811 15.4101C14.5487 15.6644 14.1715 15.7917 13.679 15.7917C13.208 15.7917 12.8517 15.6591 12.6102 15.394C12.3687 15.1289 12.2472 14.7367 12.2457 14.2172V14.0248H9.26562V14.1371C9.26562 15.3952 9.66576 16.3921 10.466 17.1281Z"
                            fill="white" />
                        <path
                            d="M24.0509 18.2315C25.4212 18.2315 26.5146 17.8421 27.3312 17.0632C28.1478 16.2843 28.5562 15.2487 28.5562 13.956C28.5562 12.88 28.2294 11.9926 27.576 11.2935C26.9226 10.5945 26.0982 10.2449 25.1027 10.2449C24.8882 10.2443 24.6738 10.2589 24.4614 10.2888C24.2625 10.3171 24.0663 10.3615 23.8745 10.4213L26.6215 6.27734H23.3444L21.6973 8.82546C20.8351 10.175 20.269 11.2029 19.9989 11.9091C19.7382 12.5664 19.6009 13.266 19.5938 13.973C19.5938 15.2742 19.9953 16.3102 20.7984 17.0813C21.6015 17.8524 22.6856 18.2357 24.0509 18.2315ZM22.7203 12.7802C23.0388 12.456 23.4713 12.2939 24.0178 12.2939C24.5643 12.2939 24.9972 12.456 25.3165 12.7802C25.635 13.1045 25.7942 13.5448 25.7942 14.1014C25.7942 14.6579 25.635 15.0943 25.3165 15.4108C24.998 15.7321 24.5654 15.8928 24.0189 15.8928C23.4724 15.8928 23.0395 15.7336 22.7203 15.4151C22.4018 15.0938 22.2425 14.6573 22.2425 14.1057C22.2425 13.5541 22.4018 13.1123 22.7203 12.7802Z"
                            fill="white" />
                        <path
                            d="M34.3008 18.2318C35.6825 18.2318 36.7681 17.6974 37.5576 16.6285C38.3471 15.5597 38.7419 14.0989 38.7419 12.2463C38.7419 10.2817 38.3525 8.75718 37.5736 7.6726C36.7948 6.58803 35.7039 6.04612 34.3008 6.04688C32.9191 6.04688 31.8311 6.5877 31.0366 7.66934C30.242 8.75099 29.8444 10.2396 29.8438 12.135C29.8438 14.0361 30.2414 15.5286 31.0366 16.6125C31.8318 17.6963 32.9199 18.236 34.3008 18.2318ZM33.1603 9.30473C33.3962 8.71831 33.7764 8.42511 34.3008 8.42511C34.8096 8.42511 35.1829 8.72504 35.421 9.32503C35.6589 9.92502 35.778 10.8592 35.778 12.1276C35.778 13.4102 35.6591 14.3472 35.421 14.9386C35.183 15.53 34.8096 15.8272 34.3008 15.8301C33.7814 15.8301 33.4026 15.5344 33.1647 14.943C32.9267 14.3516 32.8076 13.4131 32.8076 12.1276C32.8069 10.8343 32.9245 9.8944 33.1603 9.30799V9.30473Z"
                            fill="white" />
                    </svg>
                </button>
                <?php } ?>
            </div>
            <!-- tab content -->
            <div class="tab-content">
                <!-- content 1 -->
                <div class="tab-pane fade show active" id="ms-gallery">
                    <!-- slider -->
                    <div class="ms-hero__slider">
                        <?php if( !empty($properties_images) && count($properties_images) ) {
                            foreach( $properties_images as $prop_image_id => $prop_image_meta ) {
                                ?>
                                <a class="ms-hero ms-hero--2 ms-lightcase-gallery" style="
                                    background-image: url('<?php echo esc_url( $prop_image_meta['url'] ); ?>');
                                " data-rel="lightcase:myCollection2" href="<?php echo esc_url( $prop_image_meta['url'] ); ?>"></a>
                        <?php } 
                                } ?>
                    </div>

                    <!-- thumbs slider -->
                    <div class="ms-hero__slider__pagination-count-img-slide-arrow">
                        <div class="ms-hero__slider__pagination-count-img-slide-arrow-inner">
                            <div class="ms-hero__slider__thumbs">
                            <?php if( !empty($properties_images) && count($properties_images) ) {
                                foreach( $properties_images as $prop_image_id => $prop_image_meta ) {
                                $thumb = houzez_get_image_by_id($prop_image_id, 'houzez-item-image-1');
                                ?>
                                <div>
                                    <div class="image-slide-item">
                                        <a href="<?php echo esc_url( $thumb[0] ); ?>" data-rel="lightcase:myCollection2"> 
                                            <img src="<?php echo esc_url( $thumb[0] ); ?>" alt="<?php echo esc_attr( $prop_image_meta['alt'] ); ?>" title="<?php echo esc_attr( $prop_image_meta['title'] ); ?>">
                                        </a>
                                    </div>
                                </div>
                                <?php } }?>
                            </div>
                            <!-- slider-4-slide-item-count -->
                            <div class="ms-hero__slider__pagination-count-slide-item-count">
                                <span class="count"></span>
                                <span class="total"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content 2 -->
                <div class="tab-pane fade" id="ms-map">
                    <div id="houzez-overview-listing-map-banner" class="ms-hero__map"></div>
                </div>
                <!-- content 3 -->
                <?php if( !empty( $prop_video_url ) ) { 
                    $embed_url = convertYoutubeUrl($prop_video_url);
                ?>
                <div class="tab-pane fade" id="ms-video">
                    <iframe class="ms-hero__video" width="560" height="315"
                        src="<?php echo esc_url($embed_url); ?>" title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <?php } ?>
                <!-- content 4 -->
                <?php if( !empty( $virtual_tour ) ) { ?>
                <div class="tab-pane fade" id="ms-360">
                    <?php 
                    // Check if the content contains either <iframe> or <embed> tags
                    if (strpos($virtual_tour, '<iframe') !== false || strpos($virtual_tour, '<embed') !== false) {
                        $virtual_tour = houzez_ensure_iframe_closing_tag($virtual_tour);
                        echo $virtual_tour;
                    } else { 
                        $virtual_tour = '<iframe width="853" height="480" src="'.$virtual_tour.'" frameborder="0" allowfullscreen="allowfullscreen" class="ms-hero__360" loading="lazy"></iframe>';
                        echo $virtual_tour;
                    }
                    ?>
                    <script>
                    jQuery(document).ready(function($) {
                        var iframe360Loaded = false;
                        $('button[data-target="#ms-360"]').on('click', function() {
                            if (!iframe360Loaded) {
                                var iframe = $('#ms-360 iframe');
                                var currentSrc = iframe.attr('src');
                                iframe.attr('src', 'about:blank').promise().done(function() {
                                    setTimeout(function() {
                                        iframe.attr('src', currentSrc);
                                        iframe360Loaded = true;
                                    }, 100);
                                });
                            }
                        });
                    });
                    </script>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<!-- end: Hero Banner -->