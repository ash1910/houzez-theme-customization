<?php
    $settings = get_query_var('settings', []);

    $section_heading = $settings['section_heading'];
    $section_heading_description = $settings['section_heading_description'];
    $city_list = $settings['city_list'];
    $city_list_rent = $settings['city_list_rent'];
    $map_options = $settings['map_options'];

    // wp_enqueue_style( 'leaflet', HOUZEZ_JS_DIR_URI . '/vendors/leaflet/leaflet.css', array(), '1.9.3' );
    // wp_enqueue_script( 'leaflet', HOUZEZ_JS_DIR_URI . '/vendors/leaflet/leaflet.js', array(), '1.9.3', true );
    // wp_enqueue_style('leafletMarkerCluster', HOUZEZ_JS_DIR_URI . '/vendors/leafletCluster/MarkerCluster.css', array(), '1.4.0', 'all');
    // wp_enqueue_style('leafletMarkerClusterDefault', HOUZEZ_JS_DIR_URI . '/vendors/leafletCluster/MarkerCluster.Default.css', array(), '1.4.0', 'all');
    // wp_enqueue_script('leafletMarkerCluster', HOUZEZ_JS_DIR_URI . 'vendors/leafletCluster/leaflet.markercluster.js', array('leaflet'), '1.4.0', false);

    //houzez_enqueue_osm_location_js();

    wp_enqueue_script('leaflet');
    wp_enqueue_style('leaflet');
    wp_enqueue_script('leafletMarkerCluster');
    wp_enqueue_style('leafletMarkerCluster');
    wp_enqueue_style('leafletMarkerClusterDefault');
    wp_enqueue_script('houzez-elementor-osm-scripts');

?>
      
      <!-- start: Loaction  -->
      <section class="section--wrapper">
        <div class="container-fluid">
          <div class="row r">
            <!-- section heading -->

            <div class="col-12">
              <div class="ms-section-heading">
                <?php if($section_heading): ?>
                <h2><?php echo $section_heading; ?></h2>
                <?php endif; ?>
                <?php if($section_heading_description): ?>
                <p>
                  <?php echo $section_heading_description; ?>
                </p>
                <?php endif; ?>
              </div>
              <!-- content -->
              <div class="col-12">
                <div>
                  <!-- tab controller -->
                  <div class="ms-tab-controllers__wrapper">
                    <div
                      class="ms-tab-controllers nav nav-tab ms-nav-tab"
                      role="tablist"
                    >
                      <button
                        class="ms-btn ms-btn--transparent active"
                        data-target="#location-buy"
                        data-toggle="tab"
                      >
                        Buy
                      </button>
                      <button
                        class="ms-btn ms-btn--transparent"
                        data-target="#location-rent"
                        data-toggle="tab"
                      >
                        Rent
                      </button>
                    </div>
                  </div>
                  <div class="tab-content ms-location__tab__contents">
                    <div id="location-buy" class="tab-pane fade show active">
                      <div
                        class="ms-location__tab__controllers ms-tab-controllers--transparent nav nav-tab ms-nav-tab"
                        role="tablist"
                      >
                        <?php $i = 0; foreach($city_list as $city): 
                          $city_name = get_city_name_by_slug($city['city_slug']);
                          $i++;
                          $map_id = 'houzez-osm-map-' . $city['city_slug'];
                          ?>
                        <button
                          class="<?php echo $i == 1 ? 'active' : ''; ?>"
                          data-target="#<?php echo $city['city_slug']; ?>"
                          data-toggle="tab"
                        >
                          <?php echo $city_name; ?>
                        </button>
                        <?php endforeach; ?>
                      </div>

                      <div class="tab-content ms-location__tab__content">
                        <?php $i = 0; foreach($city_list as $city): 
                          $city_name = get_city_name_by_slug($city['city_slug']);
                          $properties_data = $city['properties_data'];
                          $i++; 
                          $map_id = 'houzez-osm-map-' . $city['city_slug'];
                          ?>
                        <div id="<?php echo $city['city_slug']; ?>" class="tab-pane fade <?php echo $i == 1 ? 'show active' : ''; ?>">
                          <!-- location card -->
                          <?php if($city['highest_price'] || $city['average_price'] || $city['lowest_price']): ?>
                          <div class="ms-location__card">
                            <h5><?php echo $city_name; ?> Prices</h5>
                            <!-- list -->
                            <ul class="ms-location__card__list">
                              <?php if($city['highest_price']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Highest:</p>
                                <h6><?php echo $city['highest_price']; ?></h6>
                              </li>
                              <?php endif; ?>
                              <?php if($city['average_price']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Average:</p>
                                <h6><?php echo $city['average_price']; ?></h6>
                              </li>
                              <?php endif; ?>
                              <?php if($city['lowest_price']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Lowest:</p>
                                <h6><?php echo $city['lowest_price']; ?></h6>
                              </li>
                              <?php endif; ?>
                            </ul>
                          </div>
                          <?php endif; ?>

                          <div class="houzez-elementor-map-wrap">
                            <div id="<?php echo $map_id; ?>" class="h-properties-map-for-elementor map-rounded" style="height: 600px; width: 100%;"></div>
                          </div>

                          <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                            <script type="application/javascript">
                                <?php if($i == 1): ?>
                                if (jQuery('#<?php echo $city['city_slug']; ?>').hasClass('active')) {
                                    houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                }
                                <?php else: ?>
                                  jQuery('[data-target="#<?php echo $city['city_slug']; ?>"]').one('shown.bs.tab', function() {
                                    houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                });
                                <?php endif; ?>
                            </script>
                          <?php } else { ?> 
                            <script type="application/javascript">
                                jQuery(document).bind("ready", function () {
                                    <?php if($i == 1): ?>
                                    if (jQuery('#<?php echo $city['city_slug']; ?>').hasClass('active')) {
                                        houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                    }
                                    <?php else: ?>
                                      jQuery('[data-target="#<?php echo $city['city_slug']; ?>"]').one('shown.bs.tab', function() {
                                        houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                    });
                                    <?php endif; ?>
                                });
                            </script>
                          <?php } ?>

                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <div id="location-rent" class="tab-pane fade">
                      <div
                        class="ms-location__tab__controllers ms-tab-controllers--transparent nav nav-tab ms-nav-tab"
                        role="tablist"
                      >
                        <?php $i = 0; foreach($city_list_rent as $city): 
                          $city_name = get_city_name_by_slug($city['city_slug_rent']);
                          $i++;
                          ?>
                        <button
                          class="<?php echo $i == 1 ? 'active' : ''; ?>"
                          data-target="#rent-<?php echo $city['city_slug_rent']; ?>"
                          data-toggle="tab"
                        >
                          <?php echo $city_name; ?>
                        </button>
                        <?php endforeach; ?>
                      </div>

                      <div class="tab-content ms-location__tab__content">
                        <?php $i = 0; foreach($city_list_rent as $city): 
                          $city_name = get_city_name_by_slug($city['city_slug_rent']);
                          $properties_data = $city['properties_data'];
                          $i++;
                          $map_id = 'houzez-osm-map-rent-' . $city['city_slug_rent'];
                          ?>
                        <div id="rent-<?php echo $city['city_slug_rent']; ?>" class="tab-pane fade <?php echo $i == 1 ? 'show active' : ''; ?>">
                          <!-- location card -->
                          <?php if($city['highest_price_rent'] || $city['average_price_rent'] || $city['lowest_price_rent']): ?>
                          <div class="ms-location__card">
                            <h5><?php echo $city_name; ?> Prices</h5>
                            <!-- list -->
                            <ul class="ms-location__card__list">
                              <?php if($city['highest_price_rent']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Highest:</p>
                                <h6><?php echo $city['highest_price_rent']; ?></h6>
                              </li>
                              <?php endif; ?>
                              <?php if($city['average_price_rent']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Average:</p>
                                <h6><?php echo $city['average_price_rent']; ?></h6>
                              </li>
                              <?php endif; ?>
                              <?php if($city['lowest_price_rent']): ?>
                              <li class="ms-location__card__list__item">
                                <p><span></span> Lowest:</p>
                                <h6><?php echo $city['lowest_price_rent']; ?></h6>
                              </li>
                              <?php endif; ?>
                            </ul>
                          </div>
                          <?php endif; ?>

                          <div class="houzez-elementor-map-wrap">
                            <div id="<?php echo $map_id; ?>" class="h-properties-map-for-elementor map-rounded" style="height: 600px; width: 100%;"></div>
                          </div>

                          <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                            <script type="application/javascript">
                                <?php if($i == 1): ?>
                                jQuery('[data-target="#location-rent"]').one('shown.bs.tab', function() {
                                  if (jQuery('#rent-<?php echo $city['city_slug_rent']; ?>').hasClass('active')) {
                                    houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                  }
                                });
                                <?php else: ?>
                                jQuery('[data-target="#rent-<?php echo $city['city_slug_rent']; ?>"]').one('shown.bs.tab', function() {
                                    houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                });
                                <?php endif; ?>
                            </script>
                          <?php } else { ?> 
                            <script type="application/javascript">
                                jQuery(document).ready(function() {
                                    <?php if($i == 1): ?>
                                    jQuery('[data-target="#location-rent"]').one('shown.bs.tab', function() {
                                      if (jQuery('#rent-<?php echo $city['city_slug_rent']; ?>').hasClass('active')) {
                                        houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                      }
                                    });
                                    <?php else: ?>
                                    jQuery('[data-target="#rent-<?php echo $city['city_slug_rent']; ?>"]').one('shown.bs.tab', function() {
                                        houzezOpenStreetMapElementor("<?php echo esc_attr($map_id); ?>", <?php echo json_encode($properties_data); ?>, <?php echo json_encode($map_options); ?>);
                                    });
                                    <?php endif; ?>
                                });
                            </script>
                          <?php } ?>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- end:  Loaction  -->

<style>
  .ms-location__tab__contents:has(.ms-location__card) img {
    width: auto;
    border-radius: initial;
    display: initial;
  }
  .ms-location__tab__contents:has(.ms-location__card) img:last-child {
		display: initial;
	}
</style>