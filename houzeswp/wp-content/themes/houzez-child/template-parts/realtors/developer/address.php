<address>
<?php 
$developer_address = get_post_meta( get_the_ID(), 'fave_developer_address', true );
if(!empty($developer_address)) {
	echo '<i class="houzez-icon icon-pin"></i> '.$developer_address;
}
?>
</address>