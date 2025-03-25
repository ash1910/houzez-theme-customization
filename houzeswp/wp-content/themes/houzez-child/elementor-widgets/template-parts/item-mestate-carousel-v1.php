<?php
global $post, $ele_thumbnail_size, $image_size;

$settings = get_query_var('settings', []);
$author_id = get_the_author_meta('ID');
$prop_handover_val = "";
$button_text = $settings['button_text'];

if ( houzez_is_developer($author_id ) ) {
    $prop_price = houzez_get_listing_data('property_price');
    if(!empty($prop_price)) {
        $houzez_listing_price = houzez_listing_price();
        if( strpos($houzez_listing_price, "Start from") ){
            
            $houzez_listing_price = str_replace("Start from", "",$houzez_listing_price);
            $houzez_listing_price = '<p>Starting Price <span>'.$houzez_listing_price.'</span></p>';
        }
    }

    $prop_handover_val = "";
    $prop_handover_q  = houzez_get_listing_data('prop_handover_q');
    $prop_handover_y  = houzez_get_listing_data('prop_handover_y');
    if(!empty($prop_handover_q)) {
        $prop_handover_val = $prop_handover_q;
    }
    if(!empty($prop_handover_y)) {
        $prop_handover_val .= " ".$prop_handover_y;
    }
    if(!empty($prop_handover_val)) {
        $prop_handover_val = '<li class="ms-new-projects__list__item">
                    <p>
                    <i class="icon-calendar_balck_fill"></i>
                    <span>'.esc_attr( $prop_handover_val ).'</span>
                    </p>
                </li>';
    }
}
else{
    $houzez_listing_price = '<p><span>'.houzez_listing_price_v5().'</span></p>';
}

$property_type = houzez_taxonomy_simple('property_type');
if(!empty($property_type)) {
    $property_type = '<li class="ms-new-projects__list__item">
        <p>
        <i class="icon-building"></i>
        <span>'.esc_attr($property_type) .'</span>
        </p>
    </li>';
}

$thumbnail_size = !empty($ele_thumbnail_size) ? $ele_thumbnail_size : $image_size;
?>

<div class="ms-new-projects__thumb">
    <?php
    if( has_post_thumbnail( $post->ID ) && get_the_post_thumbnail($post->ID) != '' ) {
        the_post_thumbnail( $thumbnail_size, array('class' => '') );
    }else{
        houzez_image_placeholder( $thumbnail_size );
    }
    ?>
</div>

<div class="ms-new-projects__content">
    <div class="ms-new-projects__heading">
        <h5><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo get_the_title(); ?></a></h5>
        
        <?php get_template_part('template-parts/listing/partials/item-address'); ?>
    </div>
    <!-- list -->
    <ul class="ms-new-projects__list">
        <?php echo $property_type . $prop_handover_val; ?>
    </ul>
    <!-- portfolio bottom -->
    <div class="ms-new-projects__bottom">
        <?php echo $houzez_listing_price; ?>
        <div><a href="<?php echo esc_url(get_permalink()); ?>" class="ms-btn ms-btn--small"><?php echo $button_text;?></a></div>
    </div>
</div>

<?php wp_reset_postdata(); ?>