<?php
if( !class_exists('Fave_Insights')) {
    $msg = esc_html__('Please install and activate Favethemes Insights plugin.', 'houzez');
    wp_die($msg);
}

global $houzez_local, $prop_featured, $current_user, $post;

wp_get_current_user();
$userID         = get_current_user_id();
$user_login     = $current_user->user_login;

$package_impressions = get_user_meta( $userID, 'package_impressions', true );

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
                <h1><?php echo houzez_option('dsh_advertise', 'Advertise'); ?></h1>           
            </div><!-- dashboard-header-left -->
        </div><!-- d-flex -->
    </div><!-- dashboard-header-wrap -->
</header><!-- .header-main-wrap -->
<section class="dashboard-content-wrap">
    <div class="dashboard-content-inner-wrap" style="padding-top : 0;">
        <div class="dashboard-content-block-wrap">

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 offset-lg-6 activities_select">
                    <div class="dashboard-property-search" style="padding-right: 0;">
                        <?php get_template_part('template-parts/dashboard/property/search'); ?>
                    </div>
                </div>
            </div>

            <?php
            $prop_qry = new WP_Query($args); 
            if( $prop_qry->have_posts() ): ?>
                <div id="dash-prop-msg"></div>

            <div class="dashboard-table-insight-wrap dashboard-table-advertise-wrap">
                <table class="dashboard-table dashboard-table-insight table-lined table-hover responsive-table">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Properties Photo & Name', 'houzez'); ?></th>
                        <th><?php echo esc_html__('Credits Overview', 'houzez'); ?></th>
                        <th><?php echo esc_html__('Promote', 'houzez'); ?></th>
                        <th class="action-col"><?php echo esc_html__('Edit Ads', 'houzez'); ?></th>
                        <th class="action-col"><?php echo esc_html__('States', 'houzez'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($prop_qry->have_posts()): $prop_qry->the_post(); 
                        $listing_id = get_the_ID();
                        $fave_impressions = get_post_meta( $listing_id, 'fave_impressions', true );
                        $fave_impressions_included = get_post_meta( $listing_id, 'fave_impressions_included', true );
                        $fave_advertise = get_post_meta( $listing_id, 'fave_advertise', true );
                        $fave_spent = (int)$fave_impressions_included - (int)$fave_impressions;
                    ?>

                    <tr>
                        <td class="property-table-thumbnail" data-label="<?php echo esc_html__('Thumbnail', 'houzez'); ?>">
                            <div class="table-property-thumb">
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php
                                if( has_post_thumbnail() && get_the_post_thumbnail($listing_id) != '') {
                                    the_post_thumbnail(array('64', '64'));
                                } else {
                                    echo '<img src="http://via.placeholder.com/64x64">';
                                }
                                ?>
                                    <span><?php the_title(); ?></span>
                                </a>	
                            </div>
                        </td>
                        <td>
                            <?php echo (int)$fave_impressions;?> Allocated <br>
                            <?php echo $fave_spent;?> Spent <br>
                            <?php echo (int)$fave_impressions_included;?> Remaining 
                        </td>
                        <td>  
                            <input type="checkbox" <?php echo $fave_advertise ? "checked" : "";?> data-toggle="toggle" data-size="lg" class="hz-enable-advertise" data-listing_id="<?php echo $listing_id;?>">
                        </td>
                        <td>
                            <a class="btn btn-primary btn-advance-state hz-add-impression-popup-js" data-listing_id="<?php echo $listing_id;?>">Edit Spended Credits</a>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-advance-state" href="/advertise?listing_id=<?php echo $listing_id;?>">Advance States</a>
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

<script type="text/javascript">
jQuery(window).load(function() {
    jQuery('.hz-add-impression-popup-js').on('click', function() {
        var listing_id = jQuery(this).data('listing_id');
        jQuery("#add-impression-popup #listing_id").val(listing_id);
        //alert(listing_id);
        jQuery('#add-impression-popup').modal("show");
    });
    jQuery('.hz-enable-advertise').on('change', function() {
        var listing_id = jQuery(this).data('listing_id');
        var fave_advertise_type = "remove_advertise";
        //jQuery("#add-impression-popup #listing_id").val(listing_id);
        if(jQuery(this).prop('checked')){
            fave_advertise_type = "set_advertise";
        }

            var ajaxurl = houzez_vars.admin_url+ 'admin-ajax.php';

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'JSON',
                data: {
                    'action' : 'houzez_property_actions_child',
                    'propid' : listing_id,
                    'type': fave_advertise_type
                },
                success: function ( res ) {
                    if( res.success ) {
                        alert("Updated Successfully");
                        //window.location.reload(true);
                    } else {
                        //alert(response.msg);
                        alert("Failed");
                    }
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }

            });//end ajax
    });
    jQuery( '.houzez_add_impression_form').on('click', function(e) {
        e.preventDefault();
        var ajaxurl = houzez_vars.admin_url+ 'admin-ajax.php';
        var $result;
        var $this = jQuery(this);
        var $form = $this.parents( 'form' );
        const package_impressions = "<?php echo $package_impressions;?>";
        const add_impression_value = jQuery('input[name="add_impression_value"]').val();

        if( add_impression_value == "" || parseInt(add_impression_value) < 1 || parseInt(add_impression_value) > parseInt(package_impressions) ){
            alert("Please Add Credit");
            return;
        }

        jQuery.ajax({
            url: ajaxurl,
            data: $form.serialize(),
            method: $form.attr('method'),
            dataType: "JSON",

            beforeSend: function( ) {
                $this.find('.houzez-loader-js').addClass('loader-show');
            },
            success: function(response) {
                if( response.success ) {
                    alert(response.msg);
                    window.location.reload(true);
                } else {
                    alert(response.msg);
                }
                $this.find('.houzez-loader-js').removeClass('loader-show');
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
            },
            complete: function(){
                $this.find('.houzez-loader-js').removeClass('loader-show');
            }
        });
    });
});
</script>
<div class="modal fade mobile-property-form" id="add-impression-popup" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="modal-body">
                <div class="property-form-wrap">

                    <div class="property-form clearfix">
                        <form method="post" action="#">
                            
                            <div class="agent-details">Total Available Impression Credits : <strong id="total_impression_value"><?php echo $package_impressions;?></strong></div>
                            <div class="form-group">
                                <input class="form-control" name="add_impression_value" value="" type="text" placeholder="Add Credit">
                            </div><!-- form-group -->
                            <input type="hidden" name="action" value="houzez_property_add_impression">
                            <input type="hidden" name="listing_id" id="listing_id" value="">
                                
                            <button type="button" class="houzez_add_impression_form btn btn-secondary btn-full-width">
                                <span class="btn-loader houzez-loader-js"></span> Add Credit                            
                            </button>
                            
                        </form>
                    </div><!-- property-form -->
                    
            </div><!-- property-form-wrap -->
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>