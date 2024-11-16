<?php
global $insights_stats, $houzez_local, $impression_stats;

$lastweek = $insights_stats['charts']['lastweek'];
$lastweek_impression = $impression_stats['lastweek'];

$views = $impression_views = $labels = array();

foreach ($lastweek as $key => $value) {
	$views[] = $value['views'] ?? '';
	$impression_views[] = $lastweek_impression[$key]['views'];
	$labels[] = $value['label'] ?? '';
}
?>
<canvas id="visits-chart-7d-n" data-labels='<?php echo json_encode($labels); ?>' data-views='<?php echo json_encode($views); ?>' data-unique='<?php echo json_encode($impression_views); ?>' data-visit-label="Clicks" data-unique-label="Impressions" width="500" height="290"></canvas>