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

    <div class="col-md-12">
        <div class="listing-map-button-view" style="width: 250px; float: right; margin-bottom: 30px;">
            <ul class="list-inline">
                <li class="list-inline-item <?php if($time_period == 6)echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="/packages/?time_period=6">
                        <i class="btn-icon">
                            <?php include get_stylesheet_directory() . '/assets/images/list_icon.svg'; ?>
                        </i>
                        <span>06 Months</span>
                    </a>
                </li>
                <li class="list-inline-item <?php if($time_period == 12)echo 'active';?>">
                    <a class="btn btn-primary-outlined btn-listing" href="/packages/?time_period=12">
                        <i class="btn-icon icon-map">
                            <?php include get_stylesheet_directory() . '/assets/images/list_icon.svg'; ?>
                        </i> 
                        <span>12 Months</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>


<?php
if( $total_packages == 3 ) {
    $pkg_classes = 'col-md-4 col-sm-4 col-xs-12';
} else if( $total_packages == 4 ) {
    $pkg_classes = 'col-md-3 col-sm-6';
} else if( $total_packages == 2 ) {
    $pkg_classes = 'col-md-6 col-sm-6';
} else if( $total_packages == 1 ) {
    $pkg_classes = 'col-md-4 col-sm-12';
} else {
    $pkg_classes = 'col-md-3 col-sm-6';
}
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
        $package_price = '<span class="price-table-currency">'.$currency_symbol.'</span><span class="price-table-price">'.$pack_price.'</span>';
    } else {
        $package_price = '<span class="price-table-price">'.$pack_price.'</span><span class="price-table-currency">'.$currency_symbol.'</span>';
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
    <div class="<?php echo esc_attr( $pkg_classes ); ?>">
        <div class="price-table-module <?php echo esc_attr( $is_popular ); ?>">
            <div class="price-table-title">
                <?php the_title(); ?>
            </div><!-- price-table-title -->
            <div class="price-table-price-wrap">
                <div class="d-flex align-items-start justify-content-center">
                    <?php echo $package_price; ?>
                </div><!-- d-flex -->
            </div><!-- price-table-price-wrap -->
            <div class="price-table-description">
                <ul class="list-unstyled">
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo $houzez_local['time_period']; ?>: 
                        <strong><?php echo esc_attr( $pack_billing_frquency ).' '.HOUZEZ_billing_period( $pack_billing_period ); ?></strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo houzez_option('cl_properties', 'Properties'); ?>: 
                        <?php if( $pack_unlimited_listings == 1 ) { ?>
                            <strong><?php echo $houzez_local['unlimited_listings']; ?></strong>
                        <?php } else { ?>
                            <strong><?php echo esc_attr( $pack_listings ); ?></strong>
                        <?php } ?>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo $houzez_local['featured_listings']; ?>: 
                        <strong><?php echo esc_attr( $pack_featured_listings ); ?></strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo !empty($houzez_local['reloads']) ? $houzez_local['reloads'] : "Reloads"; ?>: 
                        <strong><?php echo !empty($package_reloads) ? esc_attr( $package_reloads ) : "0"; ?></strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo !empty($houzez_local['transfer_credit']) ? $houzez_local['transfer_credit'] : "Transfer Credit"; ?>: 
                        <strong>
                            <?php echo !empty($transfer_credit) ? "Yes" : "No"; ?>
                        </strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo !empty($houzez_local['account_manager']) ? $houzez_local['account_manager'] : "Account Manager"; ?>: 
                        <strong>
                            <?php echo !empty($account_manager) ? "Yes" : "No"; ?>
                        </strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo !empty($houzez_local['add_floor_plans']) ? $houzez_local['add_floor_plans'] : "Add Floor Plans"; ?>: 
                        <strong>
                            <?php echo !empty($add_floor_plans) ? "Yes" : "No"; ?>
                        </strong>
                    </li>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo !empty($houzez_local['add_3d_view']) ? $houzez_local['add_3d_view'] : "Add 3D View"; ?>: 
                        <strong>
                            <?php echo !empty($add_3d_view) ? "Yes" : "No"; ?>
                        </strong>
                    </li>

                    <?php if($fave_package_images != "") { ?>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php echo esc_html_e('Images', 'houzez'); ?>: 
                        <strong><?php echo esc_attr( $fave_package_images ); ?></strong>
                    </li>
                    <?php } ?>

                    <?php if($pack_package_tax != "") { ?>
                    <li>
                        <i class="houzez-icon icon-check-circle-1 primary-text mr-1"></i> 
                        <?php esc_html_e('Taxes', 'houzez'); ?>: 
                        <strong><?php echo esc_attr( $pack_package_tax ).'%'; ?></strong>
                    </li>
                    <?php } ?>
                </ul>
            </div><!-- price-table-description -->
            <div class="price-table-button">
                <?php if( houzez_is_woocommerce() && $pack_price > 0) { ?>
                    <a class="houzez-woocommerce-package btn btn-primary" data-packid="<?php echo get_the_ID(); ?>" href="#">
                        <i class="houzez-icon icon-check-circle-1 mr-1"></i> <?php echo $houzez_local['get_started']; ?>
                    </a>
                <?php } else { ?>
                    <a class="btn btn-primary" href="<?php echo esc_url($payment_process_link); ?>">
                        <i class="houzez-icon icon-check-circle-1 mr-1"></i> <?php echo $houzez_local['get_started']; ?>
                    </a>
                <?php } ?>
            </div><!-- price-table-button -->
        </div><!-- taxonomy-grids-module -->
    </div>
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>