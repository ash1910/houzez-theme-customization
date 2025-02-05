<?php
global $post, $paged, $listing_founds, $search_qry;

$search_uri = '';
$get_search_uri = $_SERVER['REQUEST_URI'];
$get_search_uri = explode( '/?', $get_search_uri );
if(isset($get_search_uri[1]) && $get_search_uri[1] != "") {
    $search_uri = $get_search_uri[1];
}
$settings = get_query_var('settings', []);

$search_num_posts = houzez_option('search_num_posts');
$enable_save_search = houzez_option('enable_disable_save_search');

$number_of_prop = $search_num_posts;
if(!$number_of_prop){
    $number_of_prop = 9;
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if ( is_front_page()  ) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
}

// Advertise
$advertise_qry = array(
    'post_type' => 'property',
    'posts_per_page' => 10,
    'orderby' => 'rand',
);
$advertise_qry = apply_filters( 'houzez20_search_filters_advertise', $advertise_qry );
$advertise_qry = apply_filters( 'houzez_sold_status_filter', $advertise_qry );
$advertise_query = new WP_Query( $advertise_qry );

$advertise_post_ids = wp_list_pluck($advertise_query->posts, 'ID');
$number_of_prop_first = $number_of_prop - count($advertise_post_ids);
if($number_of_prop_first < 0)$number_of_prop_first = 0;
// End Advertise

// are we on page one?
if(1 == $paged) {

    set_advertise_to_posts($advertise_query->posts);

    $search_qry = array(
        'post_type' => 'property',
        'posts_per_page' => $number_of_prop_first,
        //'paged' => $paged,
        'post_status' => 'publish',
        'post__not_in'   => $advertise_post_ids,
        'offset'         => 0
    );

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );
    $search_query = new WP_Query( $search_qry );

    // Combine the results
    $combined_posts = array_merge($advertise_query->posts, $search_query->posts);

    // Shuffle the combined array for random placement of advertise posts within 12 spots
    //shuffle($combined_posts);
}
else{
    $offset = ($paged - 1) * $number_of_prop - count($advertise_post_ids);

    $search_qry = array(
        'post_type' => 'property',
        'posts_per_page' => $number_of_prop,
        //'paged' => $paged,
        'post_status' => 'publish',
        'post__not_in'   => $advertise_post_ids,
        'offset'         => $offset
    );

    $search_qry = apply_filters( 'houzez20_search_filters', $search_qry );
    $search_qry = apply_filters( 'houzez_sold_status_filter', $search_qry );
    $search_qry = houzez_prop_sort ( $search_qry );
    $search_query = new WP_Query( $search_qry );
}

//echo "<pre>";print_r($search_query);exit;

$total_records = $search_query->found_posts + count($advertise_post_ids);

$record_found_text = esc_html__('Result Found', 'houzez');
if( $total_records > 1 ) {
    $record_found_text = esc_html__('Results Found', 'houzez');
}

?>

<!-- start: Apartments section  -->
<section class="ms-apartments-main">
    <div class="container">
        <div class="row">

            <!-- heading -->
            <div class="col-12">
                <div class="ms-apartments-main__heading">
                    <h2>
                        <?php       
                        if( isset($_GET["status"]) && !empty($_GET["status"]) && in_array("rent", $_GET["status"]) ){
                            echo "Properties for rent in UAE";
                        }
                        else if( isset($_GET["status"]) && !empty($_GET["status"]) && in_array("buy", $_GET["status"]) ){
                            echo "Properties for buy in UAE";
                        }
                        else{
                            echo "New Projects in UAE";
                        }
                        ?>
                    </h2>
                    <?php
                    if( houzez_option('enable_disable_save_search', 0) ) {  ?> 
                    <button class="ms-btn ms-btn--bordered save_search_click save-search-btn">
                        <input type="hidden" name="search_args" value='<?php print base64_encode( serialize( $search_qry ) ); ?>'>
                        <input type="hidden" name="search_URI" value="<?php echo esc_attr($search_uri); ?>">
                        <input type="hidden" name="search_geolocation" value="<?php echo isset($_GET['search_location']) ? esc_attr($_GET['search_location']) : ''; ?>">
                        <input type="hidden" name="houzez_save_search_ajax" value="<?php echo wp_create_nonce('houzez-save-search-nounce')?>">
                        <?php get_template_part('template-parts/loader'); ?>
                        <i class="icon-notification"></i> Get Alert
                    </button>
                    <?php }?> 
                </div>
            </div>


            <!-- apartments content -->
            <div class="col-12 col-xl-8 mb-2 mb-md-5 mb-xl-0">
                <!-- locations -->
                <?php 
                if( $total_records > 1 ) {
                    $type_list = apply_filters("houzez_after_search__get_property_type_list", $search_qry);

                    if( $type_list !== "" ){
                        echo $type_list;
                    }
                }
                ?>

                <!-- button list -->

                <ul class="ms-apartments-main__button-list">
                    <li>
                        <a
                            href="new-projects.html"
                            class="ms-btn ms-btn--bordered ms-btn--list active"
                        >
                            <svg
                                width="22"
                                height="14"
                                viewBox="0 0 22 14"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M1 2C1 1.44772 1.44772 1 2 1H4C4.55228 1 5 1.44772 5 2V4C5 4.55228 4.55228 5 4 5H2C1.44772 5 1 4.55228 1 4V2Z"
                                    fill="#868686"
                                />
                                <path
                                    d="M1 10C1 9.44772 1.44772 9 2 9H4C4.55228 9 5 9.44772 5 10V12C5 12.5523 4.55228 13 4 13H2C1.44772 13 1 12.5523 1 12V10Z"
                                    fill="#868686"
                                />
                                <path
                                    d="M9 1H15M9 9H15M9 5H21M9 13H21M2 5H4C4.55228 5 5 4.55228 5 4V2C5 1.44772 4.55228 1 4 1H2C1.44772 1 1 1.44772 1 2V4C1 4.55228 1.44772 5 2 5ZM2 13H4C4.55228 13 5 12.5523 5 12V10C5 9.44772 4.55228 9 4 9H2C1.44772 9 1 9.44772 1 10V12C1 12.5523 1.44772 13 2 13Z"
                                    stroke="#868686"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            List</a
                        >
                    </li>
                    <li>
                        <a
                            href="new-projects-map.html"
                            class="ms-btn ms-btn--bordered"
                        >
                            <svg
                                width="16"
                                height="21"
                                viewBox="0 0 16 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M15.5 7.5C15.5 11.4274 10.625 17 8 17C5.375 17 0.5 11.0385 0.5 7.11111C0.5 3 4.13401 0 8 0C11.866 0 15.5 3 15.5 7.5ZM10.5 7C10.5 8.38071 9.38071 9.5 8 9.5C6.61929 9.5 5.5 8.38071 5.5 7C5.5 5.61929 6.61929 4.5 8 4.5C9.38071 4.5 10.5 5.61929 10.5 7ZM2 19.25C1.58579 19.25 1.25 19.5858 1.25 20C1.25 20.4142 1.58579 20.75 2 20.75H14C14.4142 20.75 14.75 20.4142 14.75 20C14.75 19.5858 14.4142 19.25 14 19.25H2Z"
                                    fill="#868686"
                                />
                            </svg>

                            Map</a
                        >
                    </li>
                    <li class="ms-dropdown">
                        <a href="#" class="ms-btn ms-btn--bordered">
                            <svg
                                width="20"
                                height="14"
                                viewBox="0 0 20 14"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M0.0625 1C0.0625 0.482233 0.482233 0.0625 1 0.0625H19C19.5178 0.0625 19.9375 0.482233 19.9375 1C19.9375 1.51777 19.5178 1.9375 19 1.9375H1C0.482233 1.9375 0.0625 1.51777 0.0625 1Z"
                                    fill="#868686"
                                />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M0.0625 7C0.0625 6.48223 0.482233 6.0625 1 6.0625H19C19.5178 6.0625 19.9375 6.48223 19.9375 7C19.9375 7.51777 19.5178 7.9375 19 7.9375H1C0.482233 7.9375 0.0625 7.51777 0.0625 7Z"
                                    fill="#868686"
                                />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M0.0625 13C0.0625 12.4822 0.482233 12.0625 1 12.0625H19C19.5178 12.0625 19.9375 12.4822 19.9375 13C19.9375 13.5178 19.5178 13.9375 19 13.9375H1C0.482233 13.9375 0.0625 13.5178 0.0625 13Z"
                                    fill="#868686"
                                />
                            </svg>

                            Popular
                            <svg
                                width="12"
                                height="7"
                                viewBox="0 0 12 7"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M10.959 0.744078C11.2845 1.06951 11.2845 1.59715 10.959 1.92259L6.37571 6.50592C6.05028 6.83136 5.52264 6.83136 5.1972 6.50592L0.61387 1.92259C0.288432 1.59715 0.288432 1.06951 0.61387 0.744078C0.939306 0.418641 1.46694 0.418641 1.79238 0.744078L5.78646 4.73816L9.78054 0.744078C10.106 0.418641 10.6336 0.418641 10.959 0.744078Z"
                                    fill="#868686"
                                />
                            </svg>
                        </a>

                        <div class="dropdown-menu">
                            <div class="dropdown-menu__inner">
                                <ul>
                                    <li>
                                        <a class="dropdown-item" href="#">Appartments</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Vllies</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">House</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Locations </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- apartments cards -->
                <div class="ms-apartments-main__card__wrapper">
                    <!-- card 1 -->
                    <div
                        class="ms-apartments-main__card ms-apartments-main__card--3"
                    >
                        <div class="ms-apartments-main__card__thumbnail">
                            <div
                                class="ms-aparments-main__card__slider ms-aparments-maincardslider swiper"
                            >
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/1.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/2.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/3.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="ms-apartments-main__card__thumbnail__header">
                                <div class="ms-apartments-main__card__thumbnail__status">
                                    Upcoming
                                </div>
                                <a
                                    href="#"
                                    class="ms-apartments-main__card__thumbnail__heart"
                                    ><i class="fa-solid fa-heart"></i>
                                    <i class="fa-light fa-heart"></i
                                ></a>
                            </div>
                        </div>
                        <div class="ms-apartments-main__card__content">
                            <div class="ms-apartments-main__card__logo">
                                <a href="#">
                                    <img
                                        src="./assets/img/new-projects/logo/logo-1.png"
                                        alt=""
                                /></a>
                            </div>
                            <div class="ms-apartments-main__card__heading">
                                <h5>
                                    <a href="new-projects-details.html">Verdant Haven</a>
                                </h5>
                                <a href="#">
                                    <i class="icon-location_grey"></i>
                                    Ajman, United Arab Emirates</a
                                >
                            </div>
                            <!-- price -->

                            <!-- details list -->
                            <ul class="ms-apartments-main____card__details-list">
                                <li>
                                    <div><i class="icon-building"> </i> Townhouses</div>
                                    <div>
                                        <i class="icon-calendar_balck_fill"> </i> Q4 2024
                                    </div>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <div class="ms-apartments-main____card__price">
                                        <h6>AED 650.00</h6>
                                    </div>
                                    <p>Starting Price</p>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <!-- card action -->
                                    <ul class="ms-apartments-main____card__button-list">
                                        <li>
                                            <a
                                                href="https://wa.me/1234567890"
                                                class="ms-btn ms-btn--bordered"
                                            >
                                                <svg
                                                    width="21"
                                                    height="21"
                                                    viewBox="0 0 21 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <mask
                                                        id="mask0_3124_517"
                                                        style="mask-type: luminance"
                                                        maskUnits="userSpaceOnUse"
                                                        x="0"
                                                        y="0"
                                                        width="21"
                                                        height="21"
                                                    >
                                                        <path
                                                            d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z"
                                                            fill="white"
                                                        />
                                                    </mask>
                                                    <g mask="url(#mask0_3124_517)">
                                                        <path
                                                            d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                                            stroke="#1B1B1B"
                                                            stroke-width="1.2"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                                            fill="#1B1B1B"
                                                        />
                                                    </g>
                                                </svg>

                                                WhatsApp</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- card 2 -->
                    <div
                        class="ms-apartments-main__card ms-apartments-main__card--3"
                    >
                        <div class="ms-apartments-main__card__thumbnail">
                            <div
                                class="ms-aparments-main__card__slider ms-aparments-maincardslider swiper"
                            >
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/2.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/3.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/4.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="ms-apartments-main__card__thumbnail__header">
                                <div class="ms-apartments-main__card__thumbnail__status">
                                    Under Construction
                                </div>
                                <a
                                    href="#"
                                    class="ms-apartments-main__card__thumbnail__heart"
                                    ><i class="fa-solid fa-heart"></i>
                                    <i class="fa-light fa-heart"></i
                                ></a>
                            </div>
                        </div>
                        <div class="ms-apartments-main__card__content">
                            <div class="ms-apartments-main__card__logo">
                                <a href="#">
                                    <img
                                        src="./assets/img/new-projects/logo/logo-2.png"
                                        alt=""
                                /></a>
                            </div>
                            <div class="ms-apartments-main__card__heading">
                                <h5>
                                    <a href="new-projects-details.html"
                                        >Riverstone Villas</a
                                    >
                                </h5>
                                <a href="#">
                                    <i class="icon-location_grey"></i>
                                    Ajman, United Arab Emirates</a
                                >
                            </div>
                            <!-- price -->

                            <!-- details list -->
                            <ul class="ms-apartments-main____card__details-list">
                                <li>
                                    <div><i class="icon-building"> </i> Townhouses</div>
                                    <div>
                                        <i class="icon-calendar_balck_fill"> </i> Q4 2024
                                    </div>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <div class="ms-apartments-main____card__price">
                                        <h6>AED 450.00</h6>
                                    </div>
                                    <p>Starting Price</p>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <!-- card action -->
                                    <ul class="ms-apartments-main____card__button-list">
                                        <li>
                                            <a
                                                href="https://wa.me/1234567890"
                                                class="ms-btn ms-btn--bordered"
                                            >
                                                <svg
                                                    width="21"
                                                    height="21"
                                                    viewBox="0 0 21 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <mask
                                                        id="mask0_3124_517"
                                                        style="mask-type: luminance"
                                                        maskUnits="userSpaceOnUse"
                                                        x="0"
                                                        y="0"
                                                        width="21"
                                                        height="21"
                                                    >
                                                        <path
                                                            d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z"
                                                            fill="white"
                                                        />
                                                    </mask>
                                                    <g mask="url(#mask0_3124_517)">
                                                        <path
                                                            d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                                            stroke="#1B1B1B"
                                                            stroke-width="1.2"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                                            fill="#1B1B1B"
                                                        />
                                                    </g>
                                                </svg>

                                                WhatsApp</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- card 3 -->
                    <div
                        class="ms-apartments-main__card ms-apartments-main__card--3"
                    >
                        <div class="ms-apartments-main__card__thumbnail">
                            <div
                                class="ms-aparments-main__card__slider ms-aparments-maincardslider swiper"
                            >
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/3.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/4.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/1.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="ms-apartments-main__card__thumbnail__header">
                                <div class="ms-apartments-main__card__thumbnail__status">
                                    Upcoming
                                </div>
                                <a
                                    href="#"
                                    class="ms-apartments-main__card__thumbnail__heart"
                                    ><i class="fa-solid fa-heart"></i>
                                    <i class="fa-light fa-heart"></i
                                ></a>
                            </div>
                        </div>
                        <div class="ms-apartments-main__card__content">
                            <div class="ms-apartments-main__card__logo">
                                <a href="#">
                                    <img
                                        src="./assets/img/new-projects/logo/logo-3.png"
                                        alt=""
                                /></a>
                            </div>
                            <div class="ms-apartments-main__card__heading">
                                <h5>
                                    <a href="new-projects-details.html"
                                        >Urban Elite Villas</a
                                    >
                                </h5>
                                <a href="#">
                                    <i class="icon-location_grey"></i>
                                    Ajman, United Arab Emirates</a
                                >
                            </div>
                            <!-- price -->

                            <!-- details list -->
                            <ul class="ms-apartments-main____card__details-list">
                                <li>
                                    <div><i class="icon-building"> </i> Townhouses</div>
                                    <div>
                                        <i class="icon-calendar_balck_fill"> </i> Q4 2024
                                    </div>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <div class="ms-apartments-main____card__price">
                                        <h6>AED 450.00</h6>
                                    </div>
                                    <p>Starting Price</p>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <!-- card action -->
                                    <ul class="ms-apartments-main____card__button-list">
                                        <li>
                                            <a
                                                href="https://wa.me/1234567890"
                                                class="ms-btn ms-btn--bordered"
                                            >
                                                <svg
                                                    width="21"
                                                    height="21"
                                                    viewBox="0 0 21 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <mask
                                                        id="mask0_3124_517"
                                                        style="mask-type: luminance"
                                                        maskUnits="userSpaceOnUse"
                                                        x="0"
                                                        y="0"
                                                        width="21"
                                                        height="21"
                                                    >
                                                        <path
                                                            d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z"
                                                            fill="white"
                                                        />
                                                    </mask>
                                                    <g mask="url(#mask0_3124_517)">
                                                        <path
                                                            d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                                            stroke="#1B1B1B"
                                                            stroke-width="1.2"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                                            fill="#1B1B1B"
                                                        />
                                                    </g>
                                                </svg>

                                                WhatsApp</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- card 4 -->
                    <div
                        class="ms-apartments-main__card ms-apartments-main__card--3"
                    >
                        <div class="ms-apartments-main__card__thumbnail">
                            <div
                                class="ms-aparments-main__card__slider ms-aparments-maincardslider swiper"
                            >
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/4.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/1.png" alt="" />
                                        </a>
                                    </div>
                                    <div class="swiper-slide">
                                        <a href="new-projects-details.html">
                                            <img src="./assets/img/new-projects/2.png" alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="ms-apartments-main__card__thumbnail__header">
                                <div class="ms-apartments-main__card__thumbnail__status">
                                    Upcoming
                                </div>
                                <a
                                    href="#"
                                    class="ms-apartments-main__card__thumbnail__heart"
                                    ><i class="fa-solid fa-heart"></i>
                                    <i class="fa-light fa-heart"></i
                                ></a>
                            </div>
                        </div>
                        <div class="ms-apartments-main__card__content">
                            <div class="ms-apartments-main__card__logo">
                                <a href="#">
                                    <img
                                        src="./assets/img/new-projects/logo/logo-4.png"
                                        alt=""
                                /></a>
                            </div>
                            <div class="ms-apartments-main__card__heading">
                                <h5>
                                    <a href="new-projects-details.html">Azure Meadows</a>
                                </h5>
                                <a href="#">
                                    <i class="icon-location_grey"></i>
                                    Ajman, United Arab Emirates</a
                                >
                            </div>
                            <!-- price -->

                            <!-- details list -->
                            <ul class="ms-apartments-main____card__details-list">
                                <li>
                                    <div><i class="icon-building"> </i> Townhouses</div>
                                    <div>
                                        <i class="icon-calendar_balck_fill"> </i> Q4 2024
                                    </div>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <div class="ms-apartments-main____card__price">
                                        <h6>AED 450.00</h6>
                                    </div>
                                    <p>Starting Price</p>
                                </li>
                                <li>
                                    <hr />
                                </li>
                                <li>
                                    <!-- card action -->
                                    <ul class="ms-apartments-main____card__button-list">
                                        <li>
                                            <a
                                                href="https://wa.me/1234567890"
                                                class="ms-btn ms-btn--bordered"
                                            >
                                                <svg
                                                    width="21"
                                                    height="21"
                                                    viewBox="0 0 21 21"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <mask
                                                        id="mask0_3124_517"
                                                        style="mask-type: luminance"
                                                        maskUnits="userSpaceOnUse"
                                                        x="0"
                                                        y="0"
                                                        width="21"
                                                        height="21"
                                                    >
                                                        <path
                                                            d="M0.5 0.500002H20.5V20.5H0.5V0.500002Z"
                                                            fill="white"
                                                        />
                                                    </mask>
                                                    <g mask="url(#mask0_3124_517)">
                                                        <path
                                                            d="M3.23145 14.5612C2.5567 13.3608 2.17188 11.9757 2.17188 10.5007C2.17188 5.91735 5.92194 2.16732 10.5052 2.16732C15.0885 2.16732 18.8385 5.91735 18.8385 10.5007C18.8385 15.0839 15.0885 18.834 10.5052 18.834C9.03017 18.834 7.64504 18.4491 6.4447 17.7744L2.17188 18.834L3.23145 14.5612Z"
                                                            stroke="#1B1B1B"
                                                            stroke-width="1.2"
                                                            stroke-miterlimit="10"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        />
                                                        <path
                                                            d="M9.77238 11.2335C9.52594 10.9862 8.53679 9.88854 8.89672 9.52861C8.99565 9.42968 9.39121 9.15948 9.54553 9.00516C10.0711 8.4796 9.46811 7.82891 9.08144 7.44224C9.04986 7.41065 8.45463 6.77004 7.6644 7.5603C6.2516 8.9731 8.2654 11.5064 8.87943 12.1264C9.49948 12.7405 12.0327 14.7543 13.4456 13.3415C14.2358 12.5512 13.5952 11.956 13.5636 11.9244C13.177 11.5378 12.5262 10.9348 12.0007 11.4603C11.8464 11.6147 11.5762 12.0102 11.4772 12.1091C11.1173 12.4691 10.0196 11.4799 9.77238 11.2335Z"
                                                            fill="#1B1B1B"
                                                        />
                                                    </g>
                                                </svg>

                                                WhatsApp</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- paginations -->
                <ul class="ms-paginations">
                    <li class="ms-paginations__item ms-paginations__item--2">
                        <button><i class="icon-double_arrow_left"></i></button>
                    </li>
                    <li class="ms-paginations__item ms-paginations__item--2">
                        <button><i class="icon-arrow_left"></i></button>
                    </li>
                    <li class="ms-paginations__item">
                        <button class="active">1</button>
                    </li>
                    <li class="ms-paginations__item">
                        <button>2</button>
                    </li>
                    <li class="ms-paginations__item">
                        <button>3</button>
                    </li>
                    <li class="ms-paginations__item">
                        <button>4</button>
                    </li>
                    <li class="ms-paginations__item ms-paginations__item--2">
                        <button><i class="icon-arrow_rigth"></i></button>
                    </li>
                    <li class="ms-paginations__item ms-paginations__item--2">
                        <button><i class="icon-double_arrow-rigth"></i></button>
                    </li>
                </ul>
            </div>


        </div>
    </div>
</section>


<script>
function functionPropertyLocationShowMore(){
	// controll location
	const locationList = document.querySelector(
		"ul.ms-apartments-main__location"
	);

	if (locationList) {
		const viewAllBtn = locationList.querySelector(
			".ms-apartments-main__location__all"
		);
		const viewLessBtn = locationList.querySelector(
			".ms-apartments-main__location__less"
		);
		function msControlLoaction(isAll) {
			const allItems = locationList.querySelectorAll("li");
			allItems?.forEach((item, idx) => {
				item?.classList?.remove("ms-show");
			});
			if (isAll) {
				allItems?.forEach((item2, idx2) => {
					item2?.classList?.add("ms-show");
				});
				viewAllBtn?.classList?.remove("ms-show");
				viewLessBtn?.classList?.add("ms-show");
			} else {
				allItems?.forEach((item2, idx2) => {
					if (idx2 < 6) {
						item2?.classList?.add("ms-show");
					}
				});
				viewAllBtn?.classList?.add("ms-show");
			}
		}
		msControlLoaction(false);
		viewAllBtn?.addEventListener("click", function () {
			msControlLoaction(true);
		});
		viewLessBtn?.addEventListener("click", function () {
			msControlLoaction(false);
		});
	}
}

        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {?>
            functionPropertyLocationShowMore();
        <?php } else { ?>
          jQuery(document).ready(function($) {
            functionPropertyLocationShowMore();
          });
        <?php } ?>
</script>