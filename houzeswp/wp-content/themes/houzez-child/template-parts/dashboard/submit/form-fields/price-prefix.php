<?php
$prop_price_prefix_val = "";
$readonly = "";
if (houzez_edit_property()) {
    $prop_price_prefix_val = get_post_meta( get_the_ID(), 'property_price_prefix', true );
}

if(houzez_is_developer()) {
    $readonly = "readonly";
    $prop_price_prefix_val = empty($prop_price_prefix_val) ? "Start from" : get_post_meta(get_the_ID(), 'property_price_prefix', true );
}
?>


<div class="form-group">
	<label for="prop_price_prefix"><?php echo houzez_option('cl_price_prefix', 'Price Prefix'); ?></label>

	<input class="form-control" id="prop_price_prefix" name="prop_price_prefix" value="<?php echo $prop_price_prefix_val;?>" placeholder="<?php echo houzez_option('cl_price_prefix_plac', 'Enter the price prefix'); ?>" type="text" <?php echo $readonly;?> >

	<small class="form-text text-muted"><?php echo houzez_option('cl_price_prefix_tooltip', 'For example: Start from'); ?></small>
</div><!-- form-group -->