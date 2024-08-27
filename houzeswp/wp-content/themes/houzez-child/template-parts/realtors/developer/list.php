<?php
global $houzez_local;
$developer_position = get_post_meta( get_the_ID(), 'fave_developer_position', true );
$developer_company = get_post_meta( get_the_ID(), 'fave_developer_company', true );
?>
<div class="agent-list-wrap">
	<div class="d-flex">
		
		<div class="agent-list-image">
			<a href="<?php the_permalink(); ?>">
				<?php get_template_part('template-parts/realtors/developer/image'); ?>
			</a>
		</div>
		
		<div class="agent-list-content flex-grow-1">
			<div class="d-flex xxs-column">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php 
				if( houzez_option( 'developer_review', 0 ) != 0 ) { 
					get_template_part('template-parts/realtors/rating'); 
				}?>
			</div>
			
			<?php get_template_part('template-parts/realtors/developer/position'); ?>

			<ul class="agent-list-contact list-unstyled">
				
				<?php
				if( houzez_option('developer_phone', 1) ) {
					get_template_part('template-parts/realtors/developer/office-phone'); 
				} 

				if( houzez_option('developer_mobile', 1) ) {
					get_template_part('template-parts/realtors/developer/mobile'); 
				}

				if( houzez_option('developer_fax', 1) ) {
					get_template_part('template-parts/realtors/developer/fax'); 
				} 

				if( houzez_option('developer_email', 1) ) {
					get_template_part('template-parts/realtors/developer/email'); 
				}
				?>
			</ul>

			<div class="d-flex sm-column">
				<div class="agent-social-media flex-grow-1">
					<?php 
					if( houzez_option('developer_social', 1) ) {
						get_template_part('template-parts/realtors/developer/social'); 
					}?>
				</div>
				<a class="agent-list-link" href="<?php the_permalink(); ?>"><strong><?php echo $houzez_local['view_my_prop']; ?></strong></a>
			</div>
			
		</div>
	</div>
</div>