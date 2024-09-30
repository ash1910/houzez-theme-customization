<?php
$search_builder = houzez_search_builder();
$search_fields = $search_builder['enabled'];

if(empty($search_fields)) {
	$search_fields = array();
}

$ajax_url_update = "";
$search_class = "";
if( houzez_is_half_map() ) {
	$search_class = 'overly_is_halfmap';
	$ajax_url_update = 'houzez-search-filters-js';
}

unset($search_fields['placebo']);
unset($search_fields['price']);
$multi_currency = houzez_option('multi_currency');

if(!taxonomy_exists('property_country')) {
    unset($search_fields['country']);
}

if(!taxonomy_exists('property_state')) {
    unset($search_fields['state']);
}

if(!taxonomy_exists('property_city')) {
    unset($search_fields['city']);
}

if(!taxonomy_exists('property_area')) {
    unset($search_fields['areas']);
}

if(houzez_is_radius_search() != 1) {
	unset($search_fields['geolocation']);
}

if(houzez_option('price_range_mobile')) {
	unset($search_fields['min-price'], $search_fields['max-price']);
}
?>
<section id="overlay-search-advanced-module" class="overlay-search-advanced-module <?php echo esc_attr($search_class); ?>">
	<div class="search-title">
		<?php esc_html_e('Search', 'houzez'); ?>
		<button type="button" class="btn overlay-search-module-close"><i class="houzez-icon icon-close"></i></button>
	</div>
	<form class="houzez-search-form-js <?php echo esc_attr($ajax_url_update); ?>" method="get" autocomplete="off" action="<?php echo esc_url( houzez_get_search_template_link() ); ?>">

		<?php do_action('houzez_search_hidden_fields'); ?>
		
	<div class="row">
		<?php
		if ($search_fields) {
			$i = 0;
			foreach ($search_fields as $key=>$value) { $i ++;

				$field_class = "col-6";
				if($i == 1) {
					$field_class = "col-12";
				}

				if($key == 'geolocation') {
					$field_class = "col-8";

				}
				if(in_array($key, houzez_search_builtIn_fields())) {

					if($key == 'min-price' && $multi_currency == 1) {
						echo '<div class="'.esc_attr($field_class).'">';
							get_template_part('template-parts/search/fields/currency');
						echo '</div>';
					}

					if($key == 'geolocation') {

						echo '<div class="'.esc_attr($field_class).'">';
							get_template_part('template-parts/search/fields/geolocation', 'mobile');
						echo '</div>';

						echo '<div class="col-4">';
							get_template_part('template-parts/search/fields/distance');
						echo '</div>';

					} else {

						echo '<div class="'.esc_attr($field_class).'">';
							get_template_part('template-parts/search/fields/'.$key);
						echo '</div>';
					}


				} elseif($key == 'rent' ) {
					$min_rate = isset ( $_GET['min_rate'] ) ? esc_attr($_GET['min_rate']) : '';
					$max_rate = isset ( $_GET['max_rate'] ) ? esc_attr($_GET['max_rate']) : '';
					$rate_type = isset ( $_GET['rate_type'] ) ? esc_attr($_GET['rate_type']) : '';
				?>
					<div class="col-4">
						<div class="form-group">
							<input name="min_rate" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($min_rate); ?>" placeholder="<?php echo houzez_option('srh_min_rent-or-rate', 'Min Price / Sq Ft'); ?>">
						</div><!-- form-group -->
					</div>
					<div class="col-4">
						<div class="form-group">
							<input name="max_rate" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($max_rate); ?>" placeholder="<?php echo houzez_option('srh_max_rent-or-rate', 'Max Price / Sq Ft'); ?>">
						</div><!-- form-group -->
					</div>
					<div class="col-4">
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
					<div class="col-4">
						<div class="form-group">
							<input name="min_size" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($min_size); ?>" placeholder="<?php echo houzez_option('srh_min_size', 'Min Size'); ?>">
						</div><!-- form-group -->
					</div>
					<div class="col-4">
						<div class="form-group">
							<input name="max_size" type="text" class="form-control <?php houzez_ajax_search(); ?>" value="<?php echo esc_attr($max_size); ?>" placeholder="<?php echo houzez_option('srh_max_size', 'Max Size'); ?>">
						</div><!-- form-group -->
					</div>
					<div class="col-4">
						<div class="form-group">
							<select name="size_type" class="selectpicker <?php houzez_ajax_search(); ?> form-control bs-select-hidden" title="<?php echo houzez_option('srh_size_type', 'Size Type'); ?>" data-live-search="false">
								<option value="sf" <?php if($size_type == "sf") echo "selected"; ?>>SF</option>
								<option value="acr" <?php if($size_type == "acr") echo "selected"; ?>>Acreage</option>
							</select><!-- selectpicker -->
						</div><!-- form-group -->
					</div>
				<?php
				} else { 

					echo '<div class="'.esc_attr($field_class).'">';
						houzez_get_custom_search_field($key);
					echo '</div>';
					
				}
			}
		}
		
		if(houzez_option('price_range_mobile')) { ?>
		<div class="col-12">
			<?php get_template_part('template-parts/search/fields/currency'); ?>
		</div>
		<div class="col-12">
			<?php get_template_part('template-parts/search/fields/price-range'); ?>
		</div>
		<?php } ?>

		<?php if(houzez_option('search_other_features_mobile')) { ?>
		<div class="col-12">
			<?php get_template_part('template-parts/search/other-features'); ?>
		</div>
		<?php } ?>
		
		<div class="col-12">
			<?php get_template_part('template-parts/search/fields/submit-button'); ?>
		</div>
		<div class="col-12">
			<?php get_template_part('template-parts/search/fields/save-search-btn-mobile'); ?>
		</div>
	
	</div><!-- row -->
	</form>
</section><!-- overlay-search-advanced-module -->