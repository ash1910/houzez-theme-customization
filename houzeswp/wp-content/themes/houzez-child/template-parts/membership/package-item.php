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

$time_period = isset($_GET['time_period']) ? sanitize_text_field($_GET['time_period']) : '6';

$package_role_val = "agency";
if( houzez_is_developer() ){
    $package_role_val = "developer";
}

$args = array(
    'post_type'       => 'houzez_packages',
    'posts_per_page'  => -1,
    'meta_query'      =>  array(
        'relation' => 'AND',
        array(
            'key' => 'fave_package_visible',
            'value' => 'yes',
            'compare' => '=',
        ),
        array(
            'key' => 'fave_billing_unit',
            'value' => $time_period,
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
    jQuery(window).load(function() {
        var total = jQuery('.price-items').data('total');
        var width = jQuery( window ).width();
        jQuery('.btn-price-scroll-right').click(function() {
            var index = jQuery('.price-items > li').first().data('index');
            if( index <= total - 4 || width < 992 )
            jQuery('.price-items > li').first().remove().insertAfter(jQuery('.price-items > li').last());
        })
        jQuery('.btn-price-scroll-left').click(function() {
            var index = jQuery('.price-items > li').last().data('index');
            if( index <= total - 4 || width < 992 )
            jQuery('.price-items > li').last().remove().insertBefore(jQuery('.price-items > li').first());
        })
    });
</script>

    <div class="dashboard-content-pricing-block">

        <?php if( $total_packages > 0 ) {?>
        <a href="javascript:void(0)" class="btn btn-primary btn-price-scroll btn-price-scroll-left">
            <i class="houzez-icon icon-arrow-left-1"></i>
        </a>
        <a href="javascript:void(0)" class="btn btn-primary btn-price-scroll btn-price-scroll-right">
            <i class="houzez-icon icon-arrow-right-1"></i>
        </a>
        <?php }?>

        <ul class="price-items-heading">
            <li>
                <h3>Package Duration</h3>

                <div class="listing-map-button-view">
                    <ul class="list-inline">
                        <li class="list-inline-item <?php if($time_period == 6)echo 'active';?>">
                            <a class="btn btn-primary-outlined btn-listing" href="?packages=1&time_period=6">
                                <span>6 Months</span>
                            </a>
                        </li>
                        <li class="list-inline-item <?php if($time_period == 12)echo 'active';?>">
                            <a class="btn btn-primary-outlined btn-listing" href="?packages=1&time_period=12">
                                <span>12 Months</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <ul class="dashboard-content-pricing-items dashboard-content-pricing-items-title">
                    <li><?php echo !empty($houzez_local['total_listings']) ? $houzez_local['total_listings'] : "Total Listings"; ?></li>
                    <li><?php echo $houzez_local['featured_listings']; ?></li>
                    <li><?php echo !empty($houzez_local['reloads']) ? $houzez_local['reloads'] : "Reloads"; ?></li>
                    <li><?php echo !empty($houzez_local['transfer_credit']) ? $houzez_local['transfer_credit'] : "Transfer Credit"; ?></li>
                    <li><?php echo !empty($houzez_local['account_manager']) ? $houzez_local['account_manager'] : "Account Manager"; ?></li>
                    <li><?php echo !empty($houzez_local['add_floor_plans']) ? $houzez_local['add_floor_plans'] : "Add Floor Plans"; ?></li>
                    <li><?php echo !empty($houzez_local['add_3d_view']) ? $houzez_local['add_3d_view'] : "Add 3D View"; ?></li>
                </ul>
            </li>
        </ul>
        <ul class="price-items" data-total="<?php echo esc_attr( $total_packages ); ?>">

<?php
$i = 0;
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

            <li class="<?php echo esc_attr( $is_popular ); ?>" data-index="<?php echo esc_attr( $i ); ?>">
                <h3><?php the_title(); ?></h3>
                <h4><?php echo $package_price; ?></h4>
                <a href="<?php echo esc_url($payment_process_link); ?>">Get Package</a>
                <ul class="dashboard-content-pricing-items">
                    <li>
                        <?php if( $pack_unlimited_listings == 1 ) { 
                            echo $houzez_local['unlimited_listings']; 
                        } else { 
                            echo esc_attr( $pack_listings );
                        } ?>
                    </li>
                    <li><?php echo !empty($pack_featured_listings) ? esc_attr( $pack_featured_listings ) : "0"; ?></li>
                    <li><?php echo !empty($package_reloads) ? esc_attr( $package_reloads ) : "0"; ?></li>
                    <li>
                        <?php if( !empty($transfer_credit) ){ ?>
                            <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/check.svg'; ?></i>
                        <?php }?>
                    </li>
                    <li>
                        <?php if( !empty($account_manager) ){ ?>
                            <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/check.svg'; ?></i>
                        <?php }?>
                    </li>
                    <li>
                        <?php if( !empty($add_floor_plans) ){ ?>
                            <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/check.svg'; ?></i>
                        <?php }?>
                    </li>
                    <li>
                        <?php if( !empty($add_3d_view) ){ ?>
                            <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/check.svg'; ?></i>
                        <?php }?>
                    </li>
                </ul>
            </li>

<?php endwhile; ?>
        </ul>
    </div><!-- dashboard-content-block -->


<?php wp_reset_postdata(); ?>