
<?php
// UPDATE AGENCY AND AGENT - Transfer Listing Credit FROM AGENCY TO AGENT
$userID = get_current_user_id();
$edit_user = isset( $_GET['edit_user'] ) ? sanitize_text_field($_GET['edit_user']) : false;

$package_listings_agency =   get_the_author_meta( 'package_listings' , $userID );
$package_featured_listings_agency =   get_the_author_meta( 'package_featured_listings' , $userID );
$package_id =   get_the_author_meta( 'package_id' , $userID );
$package_activation =   get_the_author_meta( 'package_activation' , $userID );
$id_flag = false;

if ($edit_user) {
    $id_flag = true;
    $package_listings =   get_the_author_meta( 'package_listings' , $edit_user );
    $package_featured_listings =   get_the_author_meta( 'package_featured_listings' , $edit_user );
    $package_listings_max = (int)$package_listings_agency + (int)$package_listings;
    $package_featured_listings_max = (int)$package_featured_listings_agency + (int)$package_featured_listings;
} 
?>
<?php if( $id_flag ) { ?>
<div class="dashboard-content-block">
    <form method="post">
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <h2><?php esc_html_e( 'Transfer Listing Credit', 'houzez' ); ?></h2>
        </div><!-- col-md-3 col-sm-12 -->

        <div class="col-md-9 col-sm-12">
            <div class="row">
                
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label><?php esc_html_e('Listings available','houzez');?></label>
                        <input type="number" name="package_listings" class="form-control" value="<?php echo esc_attr( $package_listings );?>" max="<?php echo esc_attr( $package_listings_max );?>" min="0">
                        <small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?>, Max : <?php echo esc_attr( $package_listings_max );?></small>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label><?php esc_html_e('Featured Listings available','houzez');?></label>
                        <input type="number" name="package_featured_listings" class="form-control" value="<?php echo esc_attr( $package_featured_listings );?>" max="<?php echo esc_attr( $package_featured_listings_max );?>" min="0">
                        <small class="form-text text-muted"><?php echo houzez_option('cl_only_digits', 'Only digits'); ?>, Max : <?php echo esc_attr( $package_featured_listings_max );?></small>
                    </div>
                </div>

            </div><!-- row -->

            <?php wp_nonce_field( 'houzez_profile_ajax_nonce', 'houzez-security-profile' ); ?>
            <input type="hidden" name="action" value="houzez_ajax_transfer_listing_credit_from_agency_to_agent">
            <input type="hidden" id="user_id" name="user_id" value="<?php echo intval($edit_user); ?>">
            <input type="hidden" id="agency_id" name="agency_id" value="<?php echo intval($userID); ?>">
            <input type="hidden" id="package_listings_agency" name="package_listings_agency" value="<?php echo intval($package_listings_agency); ?>">
            <input type="hidden" id="package_featured_listings_agency" name="package_featured_listings_agency" value="<?php echo intval($package_featured_listings_agency); ?>">
            <input type="hidden" id="package_listings_agent" name="package_listings_agent" value="<?php echo intval($package_listings); ?>">
            <input type="hidden" id="package_featured_listings_agent" name="package_featured_listings_agent" value="<?php echo intval($package_featured_listings); ?>">
            <input type="hidden" id="package_id" name="package_id" value="<?php echo intval($package_id); ?>">
            <input type="hidden" id="package_activation" name="package_activation" value="<?php echo $package_activation; ?>">
            
            <button class="houzez_update_profile btn btn-success">
                <?php get_template_part('template-parts/loader'); ?>
                <?php esc_html_e('Update', 'houzez'); ?>
            </button><br/>
            <div class="notify"></div>
        </div><!-- col-md-9 col-sm-12 -->
    </div><!-- row -->
    </form>
</div><!-- dashboard-content-block -->
<?php } ?>