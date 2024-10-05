<?php
$leads_count = Houzez_Leads::get_leads_stats();

$lastday = $leads_count['leads_count']['lastday'];
$lasttwo = $leads_count['leads_count']['lasttwo'];
$lasttwo = $lasttwo - $lastday;

$lastweek = $leads_count['leads_count']['lastweek'];
$last2week = $leads_count['leads_count']['last2week'];
$last2week = $last2week - $lastweek;

$lastmonth = $leads_count['leads_count']['lastmonth'];
$last2month = $leads_count['leads_count']['last2month'];
$last2month = $last2month - $lastweek;

?>
<div class="dashboard-content-block dashboard-statistic-block dashboard-content-block-ns">
	<h3 class="dashboard-content-block-ns-title"><i class="houzez-icon icon-sign-badge-circle mr-2 primary-text"></i> <?php esc_html_e('Leads', 'houzez'); ?></h3>
	<div class="row">
		<div class="col-md-4 col-sm-4 col-4">
			<div class="views-data-wrap">
				<div class="views-data">
					<?php echo number_format_i18n($lastday); ?>
				</div><!-- views-data -->
				
				<?php houzez_views_percentage($lasttwo, $lastday); ?>

				<div class="views-text">
					From Last <strong>24</strong> Hours
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
		<div class="col-md-4 col-sm-4 col-4">
			<div class="views-data-wrap">
				<div class="views-data views-data-red">
					<?php echo number_format_i18n($leads_count['leads_count']['lastweek']); ?>
				</div><!-- views-data -->
				
				<?php houzez_views_percentage($last2week, $lastweek); ?>

				<div class="views-text">
					From Last <strong>07</strong> Days
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
		<div class="col-md-4 col-sm-4 col-4">
			<div class="views-data-wrap">
				<div class="views-data">
					<?php echo number_format_i18n($leads_count['leads_count']['lastmonth']); ?>
				</div><!-- views-data -->
				
				<?php houzez_views_percentage($last2month, $lastmonth); ?>

				<div class="views-text">
					From Last <strong>30</strong> Days
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
	</div>
</div><!-- dashboard-statistic-block -->
