<?php
global $post, $paged, $listing_founds, $search_qry;

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

// Get the current URL path and extract 'new-projects'
$current_url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($current_url_path, '/'));
$page_type = $path_parts[0]; // This will get 'new-projects'

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
                        if( isset($_GET["status"]) && !empty($_GET["status"]) && !empty($_GET["status"][0]) ){
                            $statuses = [];
                            foreach($_GET["status"] as $status){
                                $term = get_term_by('slug', $status, 'property_status');
                                $statuses[] = $term ? $term->name : $status; 
                            }
                            echo " ".implode(", ", $statuses);
                        }
                        else if( $page_type == "new-projects" ){
                            echo "New Projects";
                        }
                        else if( $page_type == "" ){
                            echo ucwords(str_replace('-', ' ', $page_type)) . ' ';
                        }


                        if( isset($_GET["location"]) && !empty($_GET["location"]) ){
                            $locations = [];
                            foreach($_GET["location"] as $location){
                                $term = get_term_by('slug', $location, 'property_city');
                                $locations[] = $term ? $term->name : $location; 
                            }
                            echo " in ".implode(", ", $locations);
                        }
                        else if( isset($_GET["keyword"]) && !empty($_GET["keyword"]) ){
                            $keyword = $_GET["keyword"];
                            echo " in ". $keyword;
                        }
                        else{
                            echo " in UAE";
                        }
                        ?>
                    </h2>
                    <button class="ms-btn ms-btn--bordered">
                        <i class="icon-notification"></i> Get Alert
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>