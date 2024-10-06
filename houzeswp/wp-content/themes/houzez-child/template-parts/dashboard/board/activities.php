<?php
if( !class_exists('Fave_Insights')) {
    $msg = esc_html__('Please install and activate Favethemes Insights plugin.', 'houzez');
    wp_die($msg);
}
$user_id = get_current_user_id();
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
$all_post_count = houzez_user_posts_count('any');

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

$activities_stats = houzez_views_user_stats($user_id, $activities_start_date, $activities_end_date);
//echo "<pre>";print_r($activities_stats);exit;
?>
<header class="header-main-wrap dashboard-header-main-wrap">
    <div class="dashboard-header-wrap">
        <div class="d-flex align-items-center">
            <div class="dashboard-header-left flex-grow-1">
                <h1><?php echo houzez_option('dsh_activities', 'Activities'); ?></h1>         
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
                            <div class="col-md-2 col-sm-6 activities_select">
                                <select id="activities_by_month" class="selectpicker form-control " title="<?php esc_html_e( 'Month Year', 'houzez' ); ?>" >
                                    <?php echo $month_options;?>
                                </select><!-- selectpicker -->
                            </div>
                            <div class="col-md-2 col-sm-6 activities_select">
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
                                            <?php include get_stylesheet_directory() . '/assets/images/listing_icon.svg'; ?>
                                        </i>
                                    </div>
                                    <div class="right-text">
                                        <span>Total Listing</span>
                                        <span class="btn-txt-2"><?php echo $all_post_count;?></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon"><?php include get_stylesheet_directory() . '/assets/images/view_icon.svg'; ?></i>
                                    </div>
                                    <div class="right-text">
                                        <span>Total View</span>
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
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon houzez-icon icon-envelope"></i>
                                    </div>
                                    <div class="right-text">
                                        <span>Message</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['message']) ? $activities_stats['message'] : "0"; ?></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon houzez-icon icon-phone-actions-ring"></i>
                                    </div>
                                    <div class="right-text">
                                        <span>Phone</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['phone']) ? $activities_stats['phone'] : "0"; ?></span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-listing activities-view-btn" href="javascript:void(0)">  
                                    <div class="left-icon">
                                        <i class="btn-icon houzez-icon icon-messaging-whatsapp"></i>                                    </div>
                                    <div class="right-text">
                                        <span>WhatsApp</span>
                                        <span class="btn-txt-2"><?php echo !empty($activities_stats['whatsapp']) ? $activities_stats['whatsapp'] : "0"; ?></span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                    </div><!-- dashboard-content-block -->
                </div>
            </div>
        
            <?php if(!empty($activities['data']['results'])) { ?>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="dashboard-content-block dashboard-content-block-ns activities-list-wrap">
                        <h3 class="dashboard-content-block-ns-title"><i class="houzez-icon icon-sign-badge-circle mr-2 primary-text"></i> Messages</h3>
                        <ul class="list-unstyled activities-list">
                            <?php
                            foreach( $activities['data']['results'] as $activity ) {

                                $permalink = $title = '';
                                $meta = maybe_unserialize($activity->meta);
                                $datetime = strtotime($activity->time);
                                $activity_id = $activity->activity_id;

                                $type = isset($meta['type']) ? $meta['type'] : '';
                                $subtype = isset($meta['subtype']) ? $meta['subtype'] : '';
                            ?> 

                            <li class="activitiy-item d-flex">
                                <div class="activitiy-item-close-button" data-id="<?php echo intval($activity_id)?>" data-nonce="<?php echo wp_create_nonce('delete_activity_nonce') ?>">
                                    <i class="houzez-icon icon-close"></i>
                                </div>
                                <div class="activitiy-item-left">
                                    <img class="rounded-circle activities-avatar" src="<?php echo HOUZEZ_IMAGE; ?>lead-avatar.png" width="40" height="40" alt="profile image">
                                </div>
                                <div class="activitiy-item-body">
                                    <div class="activity-time">
                                        <?php printf( __( '%s ago', 'houzez' ), human_time_diff( $datetime, current_time( 'timestamp' ) ) ); ?>
                                    </div>

                                    <?php 
                                    if($type == 'lead') {

                                        $permalink_id = isset($meta['listing_id']) ? $meta['listing_id'] : '';

                                        if(!empty($permalink_id)) {
                                            $permalink = get_permalink($permalink_id);
                                            $title = get_the_title($permalink_id);
                                        }
                                    
                                        
                                    } else if($type == 'lead_agent') {

                                        $permalink_id = isset($meta['agent_id']) ? $meta['agent_id'] : '';
                                        $agent_type = isset($meta['agent_type']) ? $meta['agent_type'] : '';

                                        if(!empty($permalink_id)) {
                                             
                                            if($agent_type == "author_info") {
                                                $permalink = get_author_posts_url( $permalink_id );
                                                $title = get_the_author_meta( 'display_name', $permalink_id );

                                            } else {
                                                $permalink = get_permalink($permalink_id);
                                                $title = get_the_title($permalink_id);
                                            }
                                            
                                        }
                                    } else if($type == 'lead_contact') {

                                        $permalink_id = isset($meta['lead_page_id']) ? $meta['lead_page_id'] : '';
                                        
                                        if(!empty($permalink_id)) {
                                             
                                            $permalink = get_permalink($permalink_id);
                                            $title = get_the_title($permalink_id);
                                            
                                        }
                                    } else if( $type == 'review' ) {

                                        $review_stars = isset($meta['review_stars']) ? $meta['review_stars'] : '';
                                        $review_title = isset($meta['review_title']) ? $meta['review_title'] : '';
                                        $review_link = isset($meta['review_link']) ? $meta['review_link'] : '';
                                        $username = isset($meta['username']) ? $meta['username'] : '';

                                        echo '<p>';
                                            echo wp_kses(__( '<strong>Received a new rating</strong> from', 'houzez' ), $allowed_html_array).' <a href=""><strong>'.esc_attr($username).'</strong></a>';
                                            echo '<span class="rating-score-wrap">
                                                <span class="star">
                                                    '.houzez_get_stars($review_stars, false).'
                                                </span><!-- star -->
                                            </span>';
                                        echo '</p>';

                                        echo '<p><strong>'.esc_attr($review_title).'</strong><br>';
                                        echo $meta['review_content'];
                                        echo '</p>';

                                        if(!empty($review_link)) {
                                            echo '<a target="_blank" href="'.esc_url($review_link).'"><i class="houzez-icon icon-arrow-button-circle-right mr-2"></i> <strong>'.esc_html__('View', 'houzez').'</strong></a>';
                                        }

                                    }

                                    if( $type == 'lead' || $type == 'lead_agent' || $type == "lead_contact") {
                                        echo '<p>';
                                                if( !empty($title)) {
                                                    echo wp_kses(__( '<strong>New lead</strong> from', 'houzez' ), $allowed_html_array);
                                                    echo ' <a href="'.esc_url($permalink).'"><strong>'.esc_attr($title).'</strong></a>';
                                                } else {
                                                    echo wp_kses(__( '<strong>New lead</strong>', 'houzez' ), $allowed_html_array);
                                                } 
                                            echo '</p>';

                                            echo '<ul class="list-unstyled mb-2">';

                                                if(isset($meta['name']) && !empty($meta['name'])) {
                                                    echo '<li>
                                                            <strong>'.esc_html__('Name', 'houzez').':</strong> '.esc_attr($meta['name']).
                                                            '</li>';
                                                }

                                                if(isset($meta['email']) && !empty($meta['email'])) {
                                                    echo '<li>
                                                            <strong>'.esc_html__('Email', 'houzez').':</strong> '.esc_attr($meta['email']).
                                                            '</li>';
                                                }
                                                
                                                if(isset($meta['phone']) && !empty($meta['phone'])) {
                                                    echo '<li>
                                                            <strong>'.esc_html__('Phone', 'houzez').':</strong> '.esc_attr($meta['phone']).
                                                            '</li>';
                                                }

                                                if(isset($meta['user_type']) && !empty($meta['user_type'])) {
                                                    echo '<li>
                                                            <strong>'.esc_html__('Type', 'houzez').':</strong> '.esc_attr($meta['user_type']).
                                                            '</li>';
                                                }
                                            echo '</ul>';

                                            if($subtype == 'schedule_tour') {

                                                $sdate = isset($meta['schedule_date']) ? $meta['schedule_date'] : '';
                                                $stime = isset($meta['schedule_time']) ? $meta['schedule_time'] : '';
                                                $schedule_tour_type = isset($meta['schedule_tour_type']) ? $meta['schedule_tour_type'] : '';


                                                if( $schedule_tour_type != '' ) {
                                                    echo '<ul class="list-unstyled mb-2">';
                                                        echo '<li><strong>'.esc_html__('Tour Type', 'houzez').':</strong></li>';
                                                        echo '<li>'.esc_attr($schedule_tour_type).'</li>';
                                                    echo '</ul>';
                                                }

                                                echo '<ul class="list-unstyled mb-2">';
                                                    echo '<li><strong>'.esc_html__('Desired tour date', 'houzez').':</strong></li>';
                                                    echo '<li><em>'.esc_attr($sdate).' '.esc_html__('at', 'houzez').' '.esc_attr($stime).'</em></li>';
                                                echo '</ul>';
                                            }

                                            if(isset($meta['message']) && !empty($meta['message'])) {
                                                echo '<p>'.esc_html($meta['message']).'</p>';
                                            }
                                    }
                                    ?>
                                </div>
                            </li><!-- activitiy-item -->

                        <?php
                            }
                        ?>
                            
                        </ul><!-- activities-list -->

                        <div class="crm-pagination">
                            <?php
                            echo paginate_links( array(
                                'base' => add_query_arg( 'cpage', '%#%' ),
                                'format' => '',
                                'prev_text' => __('&laquo;'),
                                'next_text' => __('&raquo;'),
                                'total' => ceil($activities['data']['total_records'] / $activities['data']['items_per_page']),
                                'current' => $activities['data']['page']
                            ));
                            ?>
                        </div>
                        
                    </div>
                </div><!-- col-md-6 col-sm-12 -->
                <div class="col-md-6 col-sm-12">
                    
                    <?php get_template_part('template-parts/dashboard/statistics/statistic-leads'); ?>
                    <?php //get_template_part('template-parts/dashboard/statistics/statistic-deals'); ?>
                    
                </div><!-- col-md-6 col-sm-12 -->
            </div><!-- row -->
            <?php } else { ?>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="dashboard-content-block">
                            <?php esc_html_e("Don't have any activity at this moment.", 'houzez'); ?>
                        </div><!-- dashboard-content-block -->
                    </div>
                </div>


            <?php } ?>

        </div><!-- dashboard-content-block-wrap -->
    </div><!-- dashboard-content-inner-wrap -->
</section><!-- dashboard-content-wrap -->
<section class="dashboard-side-wrap">
    <?php get_template_part('template-parts/dashboard/side-wrap'); ?>
</section>