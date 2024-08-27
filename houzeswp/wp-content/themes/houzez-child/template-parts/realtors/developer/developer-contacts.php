<div class="agent-contacts-wrap">
	<h3 class="widget-title"><?php esc_html_e('Contact', 'houzez'); ?></h3>
	<div class="agent-map">
		<?php 
		if( houzez_option('developer_sidebar_map', 1) ) {
			get_template_part('template-parts/realtors/developer/map'); 
		}?>
		<?php get_template_part('template-parts/realtors/developer/address'); ?>
	</div>
	<ul class="list-unstyled">
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

		if( houzez_option('developer_website', 1) ) {
		 	get_template_part('template-parts/realtors/developer/website'); 
		}
		?>
	</ul>

	<?php 
	if( houzez_option('developer_social', 1) ) { 
		get_template_part('template-parts/realtors/developer/social', 'v2'); 
	} ?>
</div><!-- developer-bio-wrap -->