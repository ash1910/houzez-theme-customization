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
    'order'     => 'ASC',
    'meta_key' => 'fave_package_impressions',
    'orderby'   => 'meta_value_num',
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

$i = 0; $ads_packages_options = ""; $ads_packages_options_price = ""; $ads_packages_impressions = "";
while( $fave_qry->have_posts() ): $fave_qry->the_post(); $i++;
    $ads_packages_selected = "";
    $pack_price              = get_post_meta( get_the_ID(), 'fave_package_price', true );
    $pack_impressions           = get_post_meta( get_the_ID(), 'fave_package_impressions', true );

    if ( $where_currency == 'before' ) {
        $package_price = $currency_symbol.' '.$pack_price;
    } else {
        $package_price = $pack_price.' '.$currency_symbol;
    }

    if($i == 1){
        $ads_packages_selected = "selected";
        $ads_packages_options_price = $package_price;
        $ads_packages_impressions = $pack_impressions;
    }

    $ads_packages_options .= '<option data-price="'.$package_price.'" value="'.get_the_ID().'" '.$ads_packages_selected.' >'.$pack_impressions.'</option>';

endwhile;

$total_packages = $first_pkg_column = '';
$total_packages = $fave_qry->found_posts;

$reload_credits_price_per_unit = 2;
$reload_package_id = "";
$reload_search_term = "Reload";
$reload_package_args = array(
    'post_type'       => 'houzez_packages',
    's'           => $reload_search_term,
    'search_columns' => ['post_title'],
    'posts_per_page'  => 1,
    'meta_query'      =>  array(
        'relation' => 'AND',
        array(
            'key' => 'fave_package_role',
            'value' => $package_role_val,
            'compare' => '=',
        )
    )
);
$reload_package_qry = new WP_Query($reload_package_args);
while( $reload_package_qry->have_posts() ): $reload_package_qry->the_post();
    $reload_package_id = get_the_ID();
    $reload_credits_price_per_unit = get_post_meta( get_the_ID(), 'fave_package_price', true );

    if ( $where_currency == 'before' ) {
        $reload_credits_price_per_unit_with_curr = $currency_symbol.' '.$reload_credits_price_per_unit;
    } else {
        $reload_credits_price_per_unit_with_curr = $reload_credits_price_per_unit.' '.$currency_symbol;
    }
endwhile;
?>

<script type="text/javascript">
    jQuery(window).load(function() {

        jQuery("#ads_packages").on("change", function(){
            const package_price = jQuery("#ads_packages").find(':selected').data('price');
            const package_impression = jQuery("#ads_packages").find(':selected').html();
            //alert(package_price); 
            jQuery("#ads_packages_price").val(package_price);
            jQuery("#ads_packages_price_total").html(package_price);
            jQuery("#ads_packages_impressions_total").html(package_impression);
        });

        const payment_page_link = "<?php echo $payment_page_link;?>";
        const reload_credits_price_per_unit = "<?php echo $reload_credits_price_per_unit;?>";
        const currency_symbol = "<?php echo $currency_symbol;?>";

        jQuery('.dashboard-content-ads-packages-item button').click(function() {
            const package_id = jQuery("#ads_packages").val();
            if(package_id){
                window.location.href = payment_page_link + "?selected_package=" + package_id;
            }
            else{
                alert("Please select a ads package.");
            }
        });
        jQuery('#reload_credits').on("input", function() {
            var reload_credits = jQuery(this).val();
            if(reload_credits == ""){
                reload_credits = 0;
            }
            const reload_credits_price_total = parseInt(reload_credits) * reload_credits_price_per_unit;
            //console.log(reload_credits_price_total);
            jQuery("#reload_credits_price_total").html(currency_symbol + " " +reload_credits_price_total);
            jQuery("#reload_credits_total").html(reload_credits);
            
        });
        jQuery('.dashboard-content-reaload-packages-item button').click(function() {
            const package_id = "<?php echo $reload_package_id;?>";
            var reload_credits = jQuery("#reload_credits").val();
            const reload_credits_price_total = parseInt(reload_credits) * reload_credits_price_per_unit;
            //const reload_credits_price_total = jQuery("#reload_credits_price_total").html();
            if( package_id == "" ){
                alert("There are no reload packages.");
                return;
            }
            if(package_id && reload_credits_price_total > 0){
                window.location.href = payment_page_link + "?selected_package=" + package_id + "&reload_package_total=" + reload_credits_price_total;
            }
            else{
                alert("Please enter reload credits.");
            }
        });

        
    });
    var changePackageType = function(packageType){
        //e.preventDefault();
        //alert(packageType);
        if( packageType == "reload" ){
            jQuery(".packageTypeReload").addClass("active");
            jQuery(".packageTypeAds").removeClass("active");
        }
        if( packageType == "ads" ){
            jQuery(".packageTypeReload").removeClass("active");
            jQuery(".packageTypeAds").addClass("active");
        }
    }
    
</script>

    <div class="dashboard-content-ads-packages-block"> 

        <div class="listing-map-button-view">
            <ul class="list-inline">
                <li class="list-inline-item packageTypeReload <?php if($package_type == 'reload')echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="javascript:changePackageType('reload')">
                        <span>RELOAD</span>
                    </a>
                </li>
                <li class="list-inline-item packageTypeAds <?php if($package_type == 'ads')echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="javascript:changePackageType('ads')">
                        <span>Premium ADS</span>
                    </a>
                </li>
            </ul>
        </div>


        <div class="dashboard-content-reaload-packages-item packageTypeReload <?php if($package_type == 'reload')echo 'active';?>">
            <div class="row">
                <div class="col-xl-12">
                    <h4>Ran out of Reload credits? Add more credits to your account to continue refreshing your listings effortlessly!</h4>
                    <br><br>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <p>Credits</p>
                    <input id="reload_credits" type="text" class="form-control" placeholder="Enter Credits" value="1">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <p>Price per unit</p>
                    <input id="reload_credits_price_per_unit" type="text" class="form-control" value="<?php echo $reload_credits_price_per_unit_with_curr;?>" readonly="">
                    <br>
                </div>
            </div>
            <div class="row dashboard-content-ads-packages-item-total">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <p><strong>Total Credits: &copy; <span id="reload_credits_total">1</span></strong></p>
                    <p><strong>Your purchased credits will remain valid for 6 months, giving you ample time to make the most of them!</strong></p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                    Total   
                    <h4 id="reload_credits_price_total"><?php echo $reload_credits_price_per_unit_with_curr;?></h4>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    <button type="submit" class="btn btn-primary-outlined">Proceed to Payment</button>
                </div>
            </div>
        </div>
        <div class="dashboard-content-ads-packages-item packageTypeAds <?php if($package_type == 'ads')echo 'active';?>">
            <div class="row">
                <div class="col-xl-12">
                    <h4>Out of Ads credit? Add more credits to promot your listings and enhance their visibility at the top of search results!</h4>
                    <br><br>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 activities_select">
                    <p>Impressions</p>
                    <select id="ads_packages" class="selectpicker form-control " title="<?php esc_html_e( 'Select', 'houzez' ); ?>" >
                        <?php echo $ads_packages_options;?>
                    </select><!-- selectpicker -->
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <p>Price</p>
                    <input id="ads_packages_price" type="text" class="form-control" value="<?php echo $ads_packages_options_price;?>" readonly="">
                </div>
            </div>
            <div class="row dashboard-content-ads-packages-item-total">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <p><strong>Total Impressions: &copy; <span id="ads_packages_impressions_total"><?php echo $ads_packages_impressions;?></span></strong></p>
                    <p><strong>Your purchased Ads credits will remain valid for 6 months, allowing you to maximize your advertising potential!</strong></p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                    Total   
                    <h4 id="ads_packages_price_total"><?php echo $ads_packages_options_price;?></h4>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                    <button type="submit" class="btn btn-primary-outlined">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div><!-- dashboard-content-block -->


<?php wp_reset_postdata(); ?>