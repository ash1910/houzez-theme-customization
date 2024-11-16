<?php
if( !class_exists('Fave_Insights')) {
    $msg = esc_html__('Please install and activate Favethemes Insights plugin.', 'houzez');
    wp_die($msg);
}
$user_id = get_current_user_id();

$listing_id = isset($_GET['listing_id']) ? $_GET['listing_id'] : '';

$activities_start_date = $activities_end_date = '';

$activities = Houzez_Activities::get_activities();
$allowed_html_array = array(
    'i' => array(
        'class' => array()
    ),
    'strong' => array(),
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array()
    )
);
//$all_post_count = houzez_user_posts_count('any');

$activities_by_month = isset($_GET['activities_by_month']) ? sanitize_text_field($_GET['activities_by_month']) : '';
$activities_by_day = isset($_GET['activities_by_day']) ? sanitize_text_field($_GET['activities_by_day']) : '';

if( $activities_by_day !='' ) {
    $activities_start_date = date( 'Y-m-d 00:00:00', strtotime($activities_by_day) );
    $activities_end_date = date( 'Y-m-d 11:59:59', strtotime($activities_by_day) );
}
if( $activities_by_month == "" &&  $activities_by_day == ''){
    $activities_by_month = date("m-Y");
}
if( $activities_by_month !='' ) {
    $activities_start_date = date( 'Y-m-01 00:00:00', strtotime("01-".$activities_by_month) );
    $activities_end_date = date( 'Y-m-t 11:59:59', strtotime("01-".$activities_by_month) );
}

$month_options = $selected = "";
for ($i = 0; $i <= 18; $i++) {
    $mV = strtotime( date( 'Y-m-01' )." -$i months");
    //$months[date("m-Y", $mV)] = date("F Y", $mV);
    $selected = "";
    if( $activities_by_month ===  date("m-Y", $mV))$selected = "selected";
    $month_options .= '<option value="'.date("m-Y", $mV).'" '.$selected.'>'.date("F Y", $mV).'</option>';
}

$activities_stats = houzez_views_ads_stats($user_id, $activities_start_date, $activities_end_date, $listing_id);
//echo "<pre>";print_r($activities_stats);exit;
?>
<header class="header-main-wrap dashboard-header-main-wrap">
    <div class="dashboard-header-wrap">
        <div class="d-flex align-items-center">
            <div class="dashboard-header-left flex-grow-1">
                <h1><?php echo houzez_option('dsh_advance_states', 'Advance States'); ?>
                    <?php if(!empty($listing_id)) {
                        echo "of '". get_the_title($listing_id) . "'"; 
                    }?>
                 </h1>         
            </div><!-- dashboard-header-left -->
            <div class="dashboard-header-right">

            </div><!-- dashboard-header-right -->
        </div><!-- d-flex -->
    </div><!-- dashboard-header-wrap -->
</header><!-- .header-main-wrap -->
<section class="dashboard-content-wrap">
    <div class="dashboard-content-inner-wrap" style="padding-top : 0;">
        <div class="dashboard-content-block-wrap">

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="dashboard-content-activities-block">

                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 activities_select">
                                <select id="activities_by_month" class="selectpicker form-control " title="<?php esc_html_e( 'Month Year', 'houzez' ); ?>" >
                                    <?php echo $month_options;?>
                                </select><!-- selectpicker -->
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 activities_select">
                                <div class="input-group date">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="houzez-icon icon-calendar-3"></i></div>
                                    </div>
                                    <input id="activities_by_day" type="text" class="form-control db_input_date" placeholder="<?php echo esc_html__('Choose the Day', 'houzez'); ?>" value="<?php echo $activities_by_day; ?>" readonly>
                                </div>
                            </div>
                        </div>


                        <ul class="activities-view-btn-wrap">
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon">
                                            <?php include get_stylesheet_directory() . '/assets/images/view_icon.svg'; ?>
                                        </i>
                                    </div>
                                    <div class="right-text">
                                        <span>Total Impressions</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['impressions']) ? $activities_stats['impressions'] : "0"; ?></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon fas fa-mouse-pointer"></i>
                                    </div>
                                    <div class="right-text">
                                        <span>Total Click</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['views']) ? $activities_stats['views'] : "0"; ?></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/conversion_icon.svg'; ?></i>
                                    </div>
                                    <div class="right-text">
                                        <span>Conversation Rate</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['conversation']) ? $activities_stats['conversation'] : "0"; ?>%</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                    </div><!-- dashboard-content-block -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">

                    <?php get_template_part('template-parts/dashboard/advertise/chart'); ?>

                </div><!-- col-md-6 col-sm-12 -->
                <div class="col-md-6 col-sm-12">
                    
                    <?php get_template_part('template-parts/dashboard/advertise/views'); ?>
                    
                </div><!-- col-md-6 col-sm-12 -->
            </div><!-- row -->

        </div><!-- dashboard-content-block-wrap -->
    </div><!-- dashboard-content-inner-wrap -->
</section><!-- dashboard-content-wrap -->
<section class="dashboard-side-wrap">
    <?php get_template_part('template-parts/dashboard/side-wrap'); ?>
</section>