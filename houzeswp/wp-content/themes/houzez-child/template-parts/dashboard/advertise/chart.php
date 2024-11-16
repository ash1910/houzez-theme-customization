<div class="dashboard-content-block dashboard-statistic-block dashboard-content-block-ns">
	<h3 class="dashboard-content-block-ns-title"><i class="houzez-icon icon-sign-badge-circle mr-2 primary-text"></i> <?php esc_html_e('Overview', 'houzez'); ?></h3>
	<div class="chart-nav" style="right: 18px; top: 23px;">
		<ul class="nav nav-pills" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#chart-24h" role="tab">
					<?php esc_html_e('Last 24 Hours', 'houzez'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#chart-7days" role="tab">
					<?php esc_html_e('Last 7 Days', 'houzez'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#chart-30days" role="tab">
					<?php esc_html_e('Last 30 Days', 'houzez'); ?>
				</a>
			</li>
		</ul>	
	</div><!-- chart-nav -->

	<div class="tab-content">
		<div class="tab-pane fade show active" id="chart-24h" role="tabpanel">
			<?php get_template_part('template-parts/dashboard/advertise/24-hours-chart'); ?>
		</div>
		<div class="tab-pane fade" id="chart-7days" role="tabpanel">
			<?php get_template_part('template-parts/dashboard/advertise/7-days-chart'); ?>
		</div>
		<div class="tab-pane fade" id="chart-30days" role="tabpanel">
			<?php get_template_part('template-parts/dashboard/advertise/30-days-chart'); ?>
		</div>
	</div><!-- tab-content -->
	
	
</div><!-- dashboard-statistic-block -->
