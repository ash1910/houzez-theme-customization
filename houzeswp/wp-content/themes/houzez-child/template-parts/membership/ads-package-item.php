<?php 
global $houzez_local; 
//echo "<pre>";print_r($houzez_local);exit;

$currency_symbol = houzez_option( 'currency_symbol' );
$where_currency = houzez_option( 'currency_position' );
if(class_exists('Houzez_Currencies')) {
    $multi_currency = houzez_option('multi_currency');
    $default_currency = houzez_option('default_multi_currency');
    if(empty($default_currency)) {
        $default_currency = 'USD';
    }

    if($multi_currency == 1) {
        $currency = Houzez_Currencies::get_currency_by_code($default_currency);
        $currency_symbol = $currency['currency_symbol'];
    }
}
$payment_page_link = houzez_get_template_link('template/template-payment.php');

$package_type = isset($_GET['package_type']) ? sanitize_text_field($_GET['package_type']) : 'reload';

$package_role_val = "agency";
if( houzez_is_developer() ){
    $package_role_val = "developer";
}

$args = array(
    'post_type'       => 'houzez_ads_packages',
    'posts_per_page'  => -1,
    'meta_query'      =>  array(
        'relation' => 'AND',
        array(
            'key' => 'fave_package_visible',
            'value' => 'yes',
            'compare' => '=',
        ),
        array(
            'key' => 'fave_package_role',
            'value' => $package_role_val,
            'compare' => '=',
        )
    )
);
$fave_qry = new WP_Query($args);

$total_packages = $first_pkg_column = '';
$total_packages = $fave_qry->found_posts;
?>

<script type="text/javascript">

</script>

    <div class="dashboard-content-ads-packages-block">

        <div class="listing-map-button-view">
            <ul class="list-inline">
                <li class="list-inline-item <?php if($package_type == 'reload')echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="?packages=1&package_type=reload">
                        <span>RELOAD</span>
                    </a>
                </li>
                <li class="list-inline-item <?php if($package_type == 'ads')echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="?packages=1&package_type=ads">
                        <span>Premium ADS</span>
                    </a>
                </li>
            </ul>
        </div>


        <div class="dashboard-content-reaload-packages-item">
        </div>
        <div class="dashboard-content-ads-packages-item">

            <select>
<?php $i = 0;
while( $fave_qry->have_posts() ): $fave_qry->the_post(); $i++;

    $pack_price              = get_post_meta( get_the_ID(), 'fave_package_price', true );
    $pack_listings           = get_post_meta( get_the_ID(), 'fave_package_listings', true );
    $pack_featured_listings  = get_post_meta( get_the_ID(), 'fave_package_featured_listings', true );
    $pack_unlimited_listings = get_post_meta( get_the_ID(), 'fave_unlimited_listings', true );
    $pack_billing_period     = get_post_meta( get_the_ID(), 'fave_billing_time_unit', true );
    $pack_billing_frquency   = get_post_meta( get_the_ID(), 'fave_billing_unit', true );
    $fave_package_images        = get_post_meta( get_the_ID(), 'fave_package_images', true );
    $pack_package_tax        = get_post_meta( get_the_ID(), 'fave_package_tax', true );
    $fave_package_popular    = get_post_meta( get_the_ID(), 'fave_package_popular', true );
    $package_custom_link     = get_post_meta( get_the_ID(), 'fave_package_custom_link', true );

    $package_reloads  = get_post_meta( get_the_ID(), 'fave_package_reloads', true );
    $transfer_credit  = get_post_meta( get_the_ID(), 'fave_transfer_credit', true );
    $account_manager  = get_post_meta( get_the_ID(), 'fave_account_manager', true );
    $add_floor_plans  = get_post_meta( get_the_ID(), 'fave_add_floor_plans', true );
    $add_3d_view  = get_post_meta( get_the_ID(), 'fave_add_3d_view', true );

    if( $pack_billing_frquency > 1 ) {
        $pack_billing_period .='s';
    }
    if ( $where_currency == 'before' ) {
        $package_price = $currency_symbol.' '.$pack_price;
    } else {
        $package_price = $pack_price.' '.$currency_symbol;
    }

    if( $fave_package_popular == "yes" ) {
        $is_popular = 'featured';
    } else {
        $is_popular = '';
    }

    $payment_process_link = add_query_arg( 'selected_package', get_the_ID(), $payment_page_link );

    if( $i == 1 && $total_packages == 2 ) {
        $first_pkg_column = 'col-md-offset-2 col-sm-offset-0';
    } else if (  $i == 1 && $total_packages == 1  ) {
        $first_pkg_column = 'col-md-offset-4 col-sm-offset-0';
    } else {
        $first_pkg_column = '';
    }

    if(!empty($package_custom_link)) {
        $payment_process_link = $package_custom_link;
    }
?>



                <option val="<?php echo get_the_ID(); ?>"><?php the_title(); ?> - <?php echo $package_price; ?></option>
                <?php endwhile; ?>
            </select>

        </div>



    </div><!-- dashboard-content-block -->


<?php wp_reset_postdata(); ?>