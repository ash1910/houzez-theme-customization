<?php
global $post, $houzez_local;
$developer_position = get_post_meta( get_the_ID(), 'fave_developer_position', true );
$languages = get_post_meta( get_the_ID(), 'fave_developer_language', true );
$properties = developer_properties_count( $post->ID );
$developer_company_logo = get_post_meta( get_the_ID(), 'fave_developer_logo', true );
?>
<div class="agent-grid-wrap agent-grid-wrap-v2">	
	<div class="agent-grid-image-wrap">
		<a class="agent-grid-image" href="<?php the_permalink(); ?>">
			<?php get_template_part('template-parts/realtors/developer/image'); ?>

        	<?php if( !empty( $developer_company_logo ) ) {
            $logo_url = wp_get_attachment_url( $developer_company_logo );
            if( !empty($logo_url) ) {
            ?>
            <div class="agent-company-logo">
                <img class="img-fluid" src="<?php echo esc_url( $logo_url ); ?>" height="50" alt="">
            </div>
            <?php }
            } ?>
		</a>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php if( $developer_position != '' ) { ?>
		<div class="agent-list-position"><?php echo esc_attr($developer_position); ?></div>
		<?php } ?>
	</div>
	<div class="agent-grid-content-wrap">
		<ul class="agent-list-contact list-unstyled">
			<?php if( ! empty($properties) ) { ?>
			<li class="agent-listings-count"><?php echo $houzez_local['properties']?>: <strong><?php echo esc_attr($properties); ?></strong></li>
			<?php } ?>

			 <?php if( !empty( $languages ) ) { ?>
			<li class="agent-languages-list"><?php echo $houzez_local['languages']; ?>: <strong><?php echo esc_attr( $languages ); ?></strong></li>
			<?php } ?>
		</ul>
		<a class="btn btn-primary-outlined btn-full-width" href="<?php the_permalink(); ?>">
			<?php echo $houzez_local['view_profile']; ?></a>
	</div>
</div>