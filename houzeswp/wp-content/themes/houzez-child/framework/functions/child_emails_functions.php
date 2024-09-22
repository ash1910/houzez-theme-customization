<?php

/* -------------------------------------------------------------------------- */
/*     // Overiide to save multiple prop type , state ,and city in inquiry    */
/* -------------------------------------------------------------------------- */
// Unhook the parent theme's action
function remove_parent_action_hook() {
    remove_action('admin_action_houzez_expire_listing', array('Houzez_Post_Type_Property', 'houzez_expire_listing'));
}
add_action('init', 'remove_parent_action_hook');

// Add your custom action
function custom_houzez_expire_listing() {
    if (!(isset($_GET['listing_id']) || isset($_POST['listing_id']) || (isset($_REQUEST['action']) && 'houzez_expire_listing' == $_REQUEST['action']))) {
        wp_die('No property exists');
    }

    // Custom logic here
    $listing_id = isset($_GET['listing_id']) ? $_GET['listing_id'] : $_POST['listing_id'];
    $post_id = absint($listing_id);

    $listing_data = array(
        'ID' => $post_id,
        'post_status' => 'expired'
    );
    wp_update_post($listing_data);

    update_post_meta($post_id, 'fave_featured', '0');

    $author_id = get_post_field('post_author', $post_id);
    $user = get_user_by('id', $author_id);
    $user_email = $user->user_email;
    $user_firstname = get_user_meta( $author_id, 'first_name', true );

    $args = array(
        'user_firstname' =>$user_firstname,
        'listing_title' => get_the_title($post_id),
        'listing_url' => get_permalink($post_id)
    );

    // Custom email logic if needed
    houzez_email_type($user_email, 'listing_expired', $args);

    wp_redirect(admin_url('edit.php?post_type=property'));
    exit;
}
add_action('admin_action_houzez_expire_listing', 'custom_houzez_expire_listing');

/* -----------------------------------------------------------------------------------------------------------
 *  Houzez property actions
 -------------------------------------------------------------------------------------------------------------*/
 add_action( 'wp_ajax_houzez_property_actions_custom', 'houzez_property_actions_custom' );

 if( !function_exists('houzez_property_actions_custom') ):
     function  houzez_property_actions_custom(){
         $userID = get_current_user_id();
 
         $packageUserId = $userID;
         $agent_agency_id = houzez_get_agent_agency_id( $userID );
         if( $agent_agency_id ) {
             $packageUserId = $agent_agency_id;
         }
 
 
         $prop_id = intval( $_POST['propid'] );
         $type = $_POST['type'];
 
         if( $type == 'set_featured' ) {
             update_post_meta( $prop_id, 'fave_featured', 1 );
         } else if ( $type == 'remove_featured' ) {
             update_post_meta( $prop_id, 'fave_featured', 0 );
 
         } else if ( $type == 'approve' ) {
 
             $listing_status = get_post_status($prop_id); // get listing status before publish.
 
             $listing_data = array(
                 'ID' => $prop_id,
                 'post_status' => 'publish'
             );
             wp_update_post($listing_data);
 
             $author_id  = get_post_field ('post_author', $prop_id);
             $user       = get_user_by('id', $author_id );
             $user_email = $user->user_email;
 
             $args = array(
                 'listing_title' => get_the_title($prop_id),
                 'listing_url' => get_permalink($prop_id)
             );
             houzez_email_type( $user_email,'listing_approved', $args );
 
             if( $listing_status == 'disapproved' && houzez_get_remaining_listings($author_id) > 0 ) {
                 houzez_update_package_listings($author_id);
             }
 
         } else if ( $type == 'disapprove' ) {
 
             $listing_data = array(
                 'ID' => $prop_id,
                 'post_status' => 'disapproved'
             );
             wp_update_post($listing_data);
 
             $author_id  = get_post_field ('post_author', $prop_id);
             $user       = get_user_by('id', $author_id );
             $user_email = $user->user_email;
 
             $args = array(
                 'listing_title' => get_the_title($prop_id),
                 'listing_url' => get_permalink($prop_id)
             );
             houzez_email_type( $user_email,'listing_disapproved', $args );
 
             $package_id = get_the_author_meta('package_id', $author_id );
             $user_package_listings = get_the_author_meta('package_listings', $author_id );
             $packagelistings = get_post_meta($package_id, 'fave_package_listings', true);
 
             if( $user_package_listings < $packagelistings ) {
                 update_user_meta( $author_id, 'package_listings', $user_package_listings+1 );
             }
 
         } else if ( $type == 'expire' ) {
 
             $listing_data = array(
                 'ID' => $prop_id,
                 'post_status' => 'expired'
             );
             wp_update_post($listing_data);
 
             houzez_listing_expire_meta($prop_id);
 
             $author_id   = get_post_field ('post_author', $prop_id);
             $user        = get_user_by('id', $author_id );
             $user_email  = $user->user_email;
             $user_firstname = get_user_meta( $author_id, 'first_name', true );
 
             $args = array(
                 'user_firstname' =>$user_firstname,
                 'listing_title' => get_the_title($prop_id),
                 'listing_url' => get_permalink($prop_id)
             );
             houzez_email_type( $user_email,'listing_expired', $args );
 
         } else if ( $type == 'publish' ) {
 
             $listing_data = array(
                 'ID' => $prop_id,
                 'post_status' => 'publish'
             );
             wp_update_post($listing_data);
             update_post_meta($prop_id, 'fave_featured', '0');
         }
 
         echo json_encode(array('success' => true, 'msg' => ''));
         wp_die();
 
     }
 endif; // end
