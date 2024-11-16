<?php 
global $post; 
$listing_agent_info = houzez20_property_contact_form();

$agent_info = @$listing_agent_info['agent_info'];

if(!empty( $agent_info[0] )) { ?>
<div class="item-author-v3">
	<i class="author-star-icon">
		<?php include get_stylesheet_directory() . '/assets/images/author_star_icon.svg'; ?>
	</i>
	<img class="img-fluid" src="<?php echo $agent_info[0]['picture']; ?>" alt=""> 
</div><!-- item-author -->
<?php } ?>