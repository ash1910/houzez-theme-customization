<?php
global $houzez_local;
?>
<!-- card 1 -->
<div class="ms-apartments-main__card ms-apartments-main__card--2 ms-apartments-main__card--blog">
	<div class="ms-apartments-main__card__thumbnail">
		<a href="<?php echo esc_url(get_permalink()); ?>"> 
			<?php the_post_thumbnail('medium', array('class' => '')); ?>
		</a>
	</div>
	<div class="ms-apartments-main__card__content">
		<p class="ms-apartments-main__card__date">
			<?php echo get_the_date(); ?>
		</p>
		<h5>
			<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
		</h5>
		<p>
			<?php echo houzez_clean_excerpt( 80, 'false' ); ?>
		</p>

		<div class="ms-apartments-main__card__reademore">
			<a href="<?php echo esc_url(get_permalink()); ?>">Read more
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<path d="M20.5 22H3.5" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
						stroke-linecap="round" stroke-linejoin="round" />
					<path d="M19 3.5L5 17.5" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
						stroke-linecap="round" stroke-linejoin="round" />
					<path d="M19 13.77V3.5H8.73" stroke="#1B1B1B" stroke-width="1.5" stroke-miterlimit="10"
						stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</a>
		</div>
	</div>
</div>