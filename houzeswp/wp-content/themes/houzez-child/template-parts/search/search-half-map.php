<?php
global $post;
$search_style = houzez_option('halfmap_search_layout', 'v1');
$search_builder = houzez_search_builder();
$layout = $search_builder['enabled'];

if(empty($layout)) {
	$layout = array();
}
unset($layout['placebo']);

if(houzez_is_radius_search() != 1) {
	unset($layout['geolocation']);
}

if(!taxonomy_exists('property_country')) {
    unset($layout['country']);
}

if(!taxonomy_exists('property_state')) {
    unset($layout['state']);
}

if(!taxonomy_exists('property_city')) {
    unset($layout['city']);
}

if(!taxonomy_exists('property_area')) {
    unset($layout['areas']);
}

if(houzez_option('price_range_halfmap')) {
	unset($layout['min-price'], $layout['max-price']);
}

if($search_style != 'v3') {
	unset($layout['price']);
}
$advanced_fields = array_slice($layout, houzez_search_builder_first_row());
?>
<section class="advanced-search advanced-search-half-map">
	<div class="container">
		<form class="houzez-search-form-js houzez-search-filters-js" method="get" autocomplete="off" action="<?php echo esc_url( houzez_get_search_template_link() ); ?>">

		<?php do_action('houzez_search_hidden_fields'); ?>

		<div class="d-flex">
			<?php
			if ($layout) {
				$i = 0;
				foreach ($layout as $key=>$value) { $i++;
					$class_flex_grow = '';
					$common_class = "flex-search";
					if($key == 'keyword' && $i == 1 ) {
						$class_flex_grow = 'full-width';

					} elseif($key == 'geolocation' && $i == 1 ) {
						$class_flex_grow = 'geolocation-width';
					} else if($key == 'geolocation') {
						$class_flex_grow = 'flex-grow-1';
					}

					if(in_array($key, houzez_search_builtIn_fields())) {

						if($key == 'price' || ($key == 'min-price')) {
						
							get_template_part('template-parts/search/fields/currency');
							
						}
						echo '<div class="'.$common_class.' '.$class_flex_grow.'">';
							get_template_part('template-parts/search/fields/'.$key);
						echo '</div>';

						if($key == 'geolocation') {
							
							get_template_part('template-parts/search/fields/distance-range');
							
						}
					} elseif($key == 'rent' ) {
						$min_rate = isset ( $_GET['min_rate'] ) ? esc_attr($_GET['min_rate']) : '';
						$max_rate = isset ( $_GET['max_rate'] ) ? esc_attr($_GET['max_rate']) : '';
						$rate_type = isset ( $_GET['rate_type'] ) ? esc_attr($_GET['rate_type']) : '';
					?>
						<div class="flex-search">
							<div class="form-group">
								<input name="min_rate" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($min_rate); ?>" placeholder="<?php echo houzez_option('srh_min_rent-or-rate', 'Min Price / Sq Ft'); ?>">
							</div><!-- form-group -->
						</div>
						<div class="flex-search">
							<div class="form-group">
								<input name="max_rate" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($max_rate); ?>" placeholder="<?php echo houzez_option('srh_max_rent-or-rate', 'Max Price / Sq Ft'); ?>">
							</div><!-- form-group -->
						</div>
						<div class="flex-search">
							<div class="form-group">
								<select name="rate_type" class="selectpicker <?php houzez_ajax_search(); ?> form-control bs-select-hidden" title="<?php echo houzez_option('srh_rate_type', 'Rate Type'); ?>" data-live-search="false">
									<option value="annual" <?php if($rate_type == "annual") echo "selected"; ?>>Annual</option>
									<option value="monthly" <?php if($rate_type == "monthly") echo "selected"; ?>>Monthly</option>
								</select><!-- selectpicker -->
							</div><!-- form-group -->
						</div>
					<?php
					} elseif($key == 'space-size' ) {
						$min_size = isset ( $_GET['min_size'] ) ? esc_attr($_GET['min_size']) : '';
						$max_size = isset ( $_GET['max_size'] ) ? esc_attr($_GET['max_size']) : '';
						$size_type = isset ( $_GET['size_type'] ) ? esc_attr($_GET['size_type']) : '';
					?>
						<div class="flex-search">
							<div class="form-group">
								<input name="min_size" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($min_size); ?>" placeholder="<?php echo houzez_option('srh_min_size', 'Min Size'); ?>">
							</div><!-- form-group -->
						</div>
						<div class="flex-search">
							<div class="form-group">
								<input name="max_size" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($max_size); ?>" placeholder="<?php echo houzez_option('srh_max_size', 'Max Size'); ?>">
							</div><!-- form-group -->
						</div>
						<div class="flex-search">
							<div class="form-group">
								<select name="size_type" class="selectpicker <?php houzez_ajax_search(); ?> form-control bs-select-hidden" title="<?php echo houzez_option('srh_size_type', 'Size Type'); ?>" data-live-search="false">
									<option value="sf" <?php if($size_type == "sf") echo "selected"; ?>>SF</option>
									<option value="acr" <?php if($size_type == "acr") echo "selected"; ?>>Acreage</option>
								</select><!-- selectpicker -->
							</div><!-- form-group -->
						</div>
					<?php
					} else {

						echo '<div class="'.$common_class.' '.$class_flex_grow.'">';
							houzez_get_custom_search_field($key);
						echo '</div>';
						
					}
				}
			}
			if(houzez_option('price_range_halfmap')) { 
				get_template_part('template-parts/search/fields/currency');
			}
			?>
		</div>

		<?php if(houzez_option('price_range_halfmap')) { ?>
		<div class="d-flex">
			<div class="flex-search-half">
				<?php get_template_part('template-parts/search/fields/price-range'); ?>
			</div>
		</div>
		<?php } ?>

		<div class="half-map-features-list-wrap">
			<?php 
			if(houzez_option('search_other_features_halfmap')) {
				get_template_part('template-parts/search/other-features');
			}
			?>
		</div><!-- half-map-features-list-wrap -->
		
		<div class="d-flex half-map-buttons-wrap">
			<button type="submit" class="btn btn-search half-map-search-js-btn btn-secondary btn-full-width"><?php echo houzez_option('srh_btn_search', 'Search'); ?></button>
			<?php get_template_part('template-parts/search/save-search-btn'); ?>
		</div>
	</form>
	</div><!-- container -->
</section><!-- advanced-search -->