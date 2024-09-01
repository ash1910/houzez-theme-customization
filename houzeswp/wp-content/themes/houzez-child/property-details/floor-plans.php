<?php
global $post, $ele_settings;

$section_title = isset($ele_settings['section_title']) && !empty($ele_settings['section_title']) ? $ele_settings['section_title'] : houzez_option('sps_floor_plans', 'Floor Plans');

$show_header = isset($ele_settings['section_header']) ? $ele_settings['section_header'] : true;
$default_open = isset($ele_settings['show_open']) && $ele_settings['show_open'] == 'true' ? 'show' : '';


$floor_plans = get_post_meta( get_the_ID(), 'floor_plans', true );

if( isset($floor_plans[0]['fave_plan_title']) && !empty( $floor_plans[0]['fave_plan_title'] ) ) {
?>
<div class="property-floor-plans-wrap property-section-wrap" id="property-floor-plans-wrap">
	<div class="block-wrap">

		<?php if( $show_header ) { ?>
		<div class="block-title-wrap d-flex justify-content-between align-items-center">
			<h2><?php echo $section_title; ; ?></h2>
		</div><!-- block-title-wrap -->
		<?php } ?>

		<div class="block-content-wrap">
			<div class="accordion">
				<?php 
				$i = 0;
				foreach( $floor_plans as $plan ):
					$i++;
		            $price_postfix = '';
		            if( !empty( $plan['fave_plan_price_postfix'] ) ) {
		                $price_postfix = ' / '.$plan['fave_plan_price_postfix'];
		            }

		            $plan_image = isset($plan['fave_plan_image']) ? $plan['fave_plan_image'] : '';
		            $filetype = wp_check_filetype($plan_image);

		            $plan_title = isset($plan['fave_plan_title']) ? esc_attr($plan['fave_plan_title']) : '';
	            ?>
				
				<div class="accordion-tab floor-plan-wrap">
					<div class="accordion-header" data-toggle="collapse" data-target="#floor-<?php echo esc_attr($i); ?>" aria-expanded="false">
						<div class="d-flex align-items-center" id="floor-plans-<?php echo esc_attr($i); ?>">
							<div class="accordion-title flex-grow-1">
								<?php echo esc_attr( $plan_title ); ?>
							</div><!-- accordion-title -->
							<ul class="floor-information list-unstyled list-inline">
								<?php if( isset($plan['fave_plan_size']) && !empty( $plan['fave_plan_size'] ) ) { ?>
			                        <li class="list-inline-item fp-size">
			                            <?php esc_html_e( 'Size', 'houzez' ); ?>: 
			                            <strong> <?php echo esc_attr( $plan['fave_plan_size'] ); ?></strong>
			                        </li>
			                    <?php } ?>

			                    <?php if( isset($plan['fave_plan_rooms']) && !empty( $plan['fave_plan_rooms'] ) ) { ?>
			                        <li class="list-inline-item fp-room">
			                        	<i class="houzez-icon icon-hotel-double-bed-1 mr-1"></i>
			                        	<strong><?php echo esc_attr( $plan['fave_plan_rooms'] ); ?></strong>
			                        </li>
			                    <?php } ?>

			                    <?php if( isset($plan['fave_plan_bathrooms']) && !empty( $plan['fave_plan_bathrooms'] ) ) { ?>
			                        <li class="list-inline-item fp-bath">
			                        	<i class="houzez-icon icon-bathroom-shower-1 mr-1"></i>
			                        	<strong><?php echo esc_attr( $plan['fave_plan_bathrooms'] ); ?></strong>
			                        </li>
			                    <?php } ?>

			                    <?php if( isset($plan['fave_plan_price']) && !empty( $plan['fave_plan_price'] ) ) { ?>
			                        <li class="list-inline-item fp-price">
			                        	<?php echo houzez_option('spl_price', 'Price'); ?>: 
			                        	<strong><?php echo houzez_get_property_price( $plan['fave_plan_price'] ).$price_postfix; ?></strong>
			                        </li>
			                    <?php } ?>
							</ul>
						</div><!-- d-flex -->
					</div><!-- accordion-header -->
					<div id="floor-<?php echo esc_attr($i); ?>" class="collapse <?php echo esc_attr($default_open); ?>" data-parent="#floor-plans-<?php echo esc_attr($i); ?>">
						<div class="accordion-body">
							<?php if( !empty( $plan_image ) ) { ?>
                    
			                        <?php if($filetype['ext'] != 'pdf' ) {?>
			                        <a target="_blank" href="<?php echo esc_url( $plan['fave_plan_image'] ); ?>" data-lightbox="roadtrip">
			                            <img class="img-fluid" src="<?php echo esc_url( $plan['fave_plan_image'] ); ?>" alt="image">
			                        </a>
			                        <?php } else { 
			                            
			                            $path = $plan_image;
			                            $file = basename($path); 
			                            $file = basename($path, ".pdf");
			                            echo '<a href="'.esc_url( $plan_image ).'" download>';
			                            echo $file;
			                            echo '</a>';
			                        } ?>
			                    
			                <?php } ?>
							
							<div class="floor-plan-description">
								<p><strong><?php echo esc_html__('Description', 'houzez'); ?>:</strong><br>
									<?php 
									if( isset($plan['fave_plan_description']) && !empty( $plan['fave_plan_description'] ) ) { 
										echo wp_kses_post( $plan['fave_plan_description'] ); 
									} 
									?>
								</p>
							</div><!-- floor-plan-description -->
						</div><!-- accordion-body -->
					</div><!-- collapse -->
				</div><!-- accordion-tab -->


				<?php endforeach; ?>
			</div><!-- accordion -->
		</div><!-- block-content-wrap -->
	</div><!-- block-wrap -->
</div><!-- property-floor-plans-wrap -->
<?php } ?>

<!-- property-payment-plan-wrap -->
<?php 
$return_array = houzez20_property_contact_form(false);
$agent_id = intval($return_array['agent_id']);

if ( houzez_is_developer($agent_id ) ) {
	$prop_payment_val = "";
	$prop_payment_plan_down  = houzez_get_listing_data('prop_payment_plan_down');
	$prop_payment_plan_during_construction  = houzez_get_listing_data('prop_payment_plan_during_construction');
	$prop_payment_plan_on_handover  = houzez_get_listing_data('prop_payment_plan_on_handover');
	if(!empty($prop_payment_plan_down)) {
		$prop_payment_val = $prop_payment_plan_down;
	}
	if(!empty($prop_payment_plan_during_construction)) {
		$prop_payment_val .= "/".$prop_payment_plan_during_construction;
	}
	if(!empty($prop_payment_plan_on_handover)) {
		$prop_payment_val .= "/".$prop_payment_plan_on_handover;
	}
	if(!empty($prop_payment_val)) {
?>
<div class="property-description-wrap property-section-wrap" id="property-payment-plan-wrap">
	<div class="block-wrap">
		<div class="block-title-wrap">
			<h2><?php echo houzez_option('cls_payment_plan', 'Payment Plan'); ?></h2>	
		</div>
		<div class="block-content-wrap">
			<ul class="payment-plan-wrap">
			<?php 
				
				if(!empty($prop_payment_plan_down)) {
					echo '<li class="payment-plan"><strong>'.$prop_payment_plan_down."%<br>".houzez_option('spl_down_payment', 'Down payment').'</strong><br><span>'.houzez_option('spl_sales_launch', 'At sales launch').'</span></li>';
				}
				if(!empty($prop_payment_plan_during_construction)) {
					echo '<li class="payment-plan"><strong>'.$prop_payment_plan_during_construction."%<br>".houzez_option('spl_during_construction', 'During construction').'</strong><br></li>';
				}
				if(!empty($prop_payment_plan_on_handover)) {
					echo '<li class="payment-plan"><strong>'.$prop_payment_plan_on_handover."%<br>".houzez_option('spl_on_handover', 'On handover').'</strong><br></li>';
				}
			?>

			</ul>

		</div>
	</div>
</div>
<?php 
	} 
}
?>