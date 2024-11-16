<?php
global $insights_stats, $houzez_local, $impression_stats;

$last24hours = $insights_stats['charts']['lastday'];
$last24hours_impression = $impression_stats['lastday'];

//echo "<pre>";print_r($insights_stats);exit;

$views = $impression_views = $labels = array();

foreach ($last24hours as $key => $value) {
	$views[] = $value['views'];
	$impression_views[] = $last24hours_impression[$key]['views'];
	$labels[] = isset($value['label']) ? $value['label'] : '';
}
?>
<canvas id="visits-chart-24h-n" data-labels='<?php echo json_encode($labels); ?>' data-views='<?php echo json_encode($views); ?>' data-unique='<?php echo json_encode($impression_views); ?>' data-visit-label="Clicks" data-unique-label="Impressions" width="500" height="290"></canvas>