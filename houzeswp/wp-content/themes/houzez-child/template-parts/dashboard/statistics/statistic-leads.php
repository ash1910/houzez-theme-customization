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

				<?php
				$percentage_icon_name = "ic-trending-up.svg";
				$percentage_icon_class = "";

				if( $lasttwo > $lastday ) { 
					$percentage_icon_name = "ic-trending-down.svg";
					$percentage_icon_class = "text-danger";
				}
				?>
				<div class="views-data <?php echo $percentage_icon_class;?>">
					<?php echo number_format_i18n($lastday); ?>
				</div><!-- views-data -->

				<div class="views-percentage <?php echo $percentage_icon_class;?>">
					<i class="btn-icon">
						<?php include get_stylesheet_directory() . '/assets/images/'.$percentage_icon_name; ?>
					</i>
					<?php echo houzez_views_percentage_ns($lasttwo, $lastday);?>
				</div>

				<div class="views-text">
					From Last <strong>24</strong> Hours
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
		<div class="col-md-4 col-sm-4 col-4">
			<div class="views-data-wrap">

				<?php
				$percentage_icon_name = "ic-trending-up.svg";
				$percentage_icon_class = "";

				if( $last2week > $lastweek ) { 
					$percentage_icon_name = "ic-trending-down.svg";
					$percentage_icon_class = "text-danger";
				}
				?>
				<div class="views-data <?php echo $percentage_icon_class;?>">
					<?php echo number_format_i18n($leads_count['leads_count']['lastweek']); ?>
				</div><!-- views-data -->

				<div class="views-percentage <?php echo $percentage_icon_class;?>">
					<i class="btn-icon">
						<?php include get_stylesheet_directory() . '/assets/images/'.$percentage_icon_name; ?>
					</i>
					<?php echo houzez_views_percentage_ns($last2week, $lastweek);?>
				</div>

				<div class="views-text">
					From Last <strong>07</strong> Days
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
		<div class="col-md-4 col-sm-4 col-4">
			<div class="views-data-wrap">

				<?php
				$percentage_icon_name = "ic-trending-up.svg";
				$percentage_icon_class = "";

				if( $last2month > $lastmonth ) { 
					$percentage_icon_name = "ic-trending-down.svg";
					$percentage_icon_class = "text-danger";
				}
				?>
				<div class="views-data <?php echo $percentage_icon_class;?>">
					<?php echo number_format_i18n($leads_count['leads_count']['lastmonth']); ?>
				</div><!-- views-data -->

				<div class="views-percentage <?php echo $percentage_icon_class;?>">
					<i class="btn-icon">
						<?php include get_stylesheet_directory() . '/assets/images/'.$percentage_icon_name; ?>
					</i>
					<?php echo houzez_views_percentage_ns($last2month, $lastmonth);?>
				</div>

				<div class="views-text">
					From Last <strong>30</strong> Days
				</div><!-- views-text -->
			</div><!-- views-data-wrap -->
		</div><!-- col-md-4 col-sm-12 -->
	</div>
</div><!-- dashboard-statistic-block -->
