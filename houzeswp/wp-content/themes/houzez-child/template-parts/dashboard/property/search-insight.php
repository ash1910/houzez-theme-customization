<form method="get" action="">
	<div class="d-flex">
	    <div class="form-group flex-grow-1">
	    	<div class="search-icon">
	    		<input class="form-control" name="keyword" value="<?php echo isset($_GET['keyword']) ? esc_attr($_GET['keyword']) : '';?>" placeholder="<?php echo esc_html__('Search', 'houzez'); ?>" type="text">
	    	</div>
	    </div>
	    <input type="hidden" name="activities_by_day" value="<?php echo isset($_GET['activities_by_day']) ? esc_attr($_GET['activities_by_day']) : '';?>">
		<input type="hidden" name="activities_by_month" value="<?php echo isset($_GET['activities_by_month']) ? esc_attr($_GET['activities_by_month']) : '';?>">
	    <button class="btn btn-search btn-secondary" type="submit"><?php esc_html_e('Search', 'houzez'); ?></button>
	</div>
</form>