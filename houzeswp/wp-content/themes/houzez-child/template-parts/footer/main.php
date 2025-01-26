<footer class="footer-wrap footer-wrap-v1">
	<?php 
	$footer_cols = houzez_option('footer_cols');
	if($footer_cols == 'CTA'){
		get_template_part('template-parts/footer/footer-bottom-CTA');
	}else{
		get_template_part('template-parts/footer/footer');
	}
	?>

	<?php get_template_part('template-parts/footer/footer-bottom-'.houzez_option('ft-bottom')); ?>
</footer>