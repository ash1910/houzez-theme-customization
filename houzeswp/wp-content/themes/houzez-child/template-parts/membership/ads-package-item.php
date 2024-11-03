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

$i = 0; $ads_packages_options = "";
while( $fave_qry->have_posts() ): $fave_qry->the_post(); $i++;

    $pack_price              = get_post_meta( get_the_ID(), 'fave_package_price', true );
    $pack_listings           = get_post_meta( get_the_ID(), 'fave_package_listings', true );

    if ( $where_currency == 'before' ) {
        $package_price = $currency_symbol.' '.$pack_price;
    } else {
        $package_price = $pack_price.' '.$currency_symbol;
    }

    $ads_packages_options .= '<option value="'.get_the_ID().'">'.get_the_title() .' - '. $package_price.'</option>';

endwhile;

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

            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 activities_select">
                    <select id="ads_packages" class="selectpicker form-control " title="<?php esc_html_e( 'Select ADS Packages', 'houzez' ); ?>" >
                        <?php echo $ads_packages_options;?>
                    </select><!-- selectpicker -->
                </div>
            </div

        </div>
    </div><!-- dashboard-content-block -->


<?php wp_reset_postdata(); ?>