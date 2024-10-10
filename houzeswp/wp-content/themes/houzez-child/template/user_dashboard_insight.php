<?php
/**
 * Template Name: User Dashboard Insight
 * Author: Waqas Riaz.
 */
if ( !is_user_logged_in() ) {
    wp_redirect(  home_url() );
}
get_header(); 

if( !class_exists('Fave_Insights')) {
    $msg = esc_html__('Please install and activate Favethemes Insights plugin.', 'houzez');
    wp_die($msg);
}

global $houzez_local, $prop_featured, $current_user, $post;

wp_get_current_user();
$userID         = get_current_user_id();
$user_login     = $current_user->user_login;

$activities_start_date = $activities_end_date = '';
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

// Get 'sortby' parameter if set
$sortby = isset($_GET['sortby']) ? $_GET['sortby'] : '';

// Default number of properties and page number
$no_of_prop = 12;
$paged = get_query_var('paged') ?: get_query_var('page') ?: 1;

// Define the initial args for the WP query
$args = [
    'post_type'      => 'property',
    'paged'          => $paged,
    'posts_per_page' => $no_of_prop,
    'post_status'    => ['any'],
    'suppress_filters' => false
];

$args = houzez_prop_sort ( $args );

if( houzez_is_admin() || houzez_is_editor() ) {
    if( isset( $_GET['user'] ) && $_GET['user'] != '' ) {
        $args['author'] = intval($_GET['user']);

    } else if( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'mine' ) {
        $args['author'] = $userID;
    }
} else if( houzez_is_agency() ) {

    $agents = houzez_get_agency_agents($userID);
    
    if( isset( $_GET['user'] ) && $_GET['user'] != '' ) {
        $args['author'] = intval($_GET['user']);

    } else if( isset( $_GET['prop_status'] ) && $_GET['prop_status'] == 'mine' ) {
        $args['author'] = $userID;

    } else if( $agents ) {
        if (!in_array($userID, $agents)) {
            $agents[] = $userID;
        }
        $args['author__in'] = $agents;
    } else {
        $args['author'] = $userID;
    }

} else {
    $args['author'] = $userID;
}


// Add keyword search to args if set
if (!empty($_GET['keyword'])) {
    $args['s'] = trim($_GET['keyword']);
}
?>

<header class="header-main-wrap dashboard-header-main-wrap">
    <div class="dashboard-header-wrap">
        <div class="d-flex align-items-center">
            <div class="dashboard-header-left flex-grow-1">
                <h1><?php echo houzez_option('dsh_insight', 'Insight'); ?></h1>           
            </div><!-- dashboard-header-left -->
        </div><!-- d-flex -->
    </div><!-- dashboard-header-wrap -->
</header><!-- .header-main-wrap -->
<section class="dashboard-content-wrap">
    <div class="dashboard-content-inner-wrap" style="padding-top : 0;">
        <div class="dashboard-content-block-wrap">

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
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 offset-xl-3 activities_select">
                    <div class="dashboard-property-search" style="padding-right: 0;">
                        <?php get_template_part('template-parts/dashboard/property/search-insight'); ?>
                    </div>
                </div>
            </div>

            <?php
            $prop_qry = new WP_Query($args); 
            if( $prop_qry->have_posts() ): ?>
                <div id="dash-prop-msg"></div>

            <div class="dashboard-table-insight-wrap">
                <table class="dashboard-table dashboard-table-insight table-lined table-hover responsive-table">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Properties Photo & Name', 'houzez'); ?></th>
                        <th><?php echo esc_html__('Impression', 'houzez'); ?></th>
                        <th><?php echo esc_html__('Message', 'houzez'); ?></th>
                        <th><?php echo esc_html__('Phone', 'houzez'); ?></th>
                        <th><?php echo esc_html__('WhatsApp', 'houzez'); ?></th>
                        <th class="action-col"><?php echo esc_html__('States', 'houzez'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($prop_qry->have_posts()): $prop_qry->the_post(); 
                        $activities_stats = houzez_views_user_stats($userID, $activities_start_date, $activities_end_date, get_the_ID());
                    ?>

                    <tr>
                        
                        <td class="property-table-thumbnail" data-label="<?php echo esc_html__('Thumbnail', 'houzez'); ?>">
                            <div class="table-property-thumb">
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php
                                if( has_post_thumbnail() && get_the_post_thumbnail(get_the_ID()) != '') {
                                    the_post_thumbnail(array('64', '64'));
                                } else {
                                    echo '<img src="http://via.placeholder.com/64x64">';
                                }
                                ?>
                                    <span><?php the_title(); ?></span>
                                </a>	
                            </div>
                        </td>
                        <td><?php echo !empty($activities_stats['views']) ? $activities_stats['views'] : "0"; ?></td>
                        <td><?php echo !empty($activities_stats['message']) ? $activities_stats['message'] : "0"; ?></td>
                        <td><?php echo !empty($activities_stats['phone']) ? $activities_stats['phone'] : "0"; ?></td>
                        <td><?php echo !empty($activities_stats['whatsapp']) ? $activities_stats['whatsapp'] : "0"; ?></td>
                        <td>
                            <a class="btn btn-primary btn-advance-state" href="">Advance States</a>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>
                </table><!-- dashboard-table -->
            </div>
            <div class="dashboard-insight-pagination-wrap">
                <?php houzez_pagination( $prop_qry->max_num_pages ); ?>
            </div>

            <?php    
            else: 

                if(isset($_GET['keyword'])) {

                    echo '<div class="dashboard-content-block">
                        '.esc_html__("No results found", 'houzez').'
                    </div>';

                } else {
                    echo '<div class="dashboard-content-block">
                        '.esc_html__("You don't have any property listed.", 'houzez').' <a href="'.esc_url($dashboard_add_listing).'"><strong>'.esc_html__('Create a listing', 'houzez').'</strong></a>
                    </div>';
                }
                

            endif;
            ?>
        
        </div><!-- dashboard-content-block-wrap -->
    </div><!-- dashboard-content-inner-wrap -->
</section><!-- dashboard-content-wrap -->
<section class="dashboard-side-wrap">
    <?php get_template_part('template-parts/dashboard/side-wrap'); ?>
</section>

<?php get_footer(); ?>