<?php
    $settings = get_query_var('settings', []);

    $section_heading = $settings['section_heading'];
    $section_heading_description = $settings['section_heading_description'];
    $city_list = $settings['city_list'];
    $city_list_rent = $settings['city_list_rent'];
    $map_options = $settings['map_options'];
    $properties_data = $settings['properties_data'];

    $id = 'abu-dhabi';

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
                          $i++; 
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
                            <div id="houzez-osm-map-<?php echo $city['city_slug']; ?>" class="h-properties-map-for-elementor" style="height: 600px; width: 100%;"></div>
                          </div>

                          <?php
                          if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>

                          <script type="application/javascript">
                              houzezOpenStreetMapElementor("<?php echo 'houzez-osm-map-' . esc_attr($city['city_slug']); ?>", <?php echo json_encode( $properties_data );?> , <?php echo json_encode($map_options);?> );
                          </script>

                          <?php    
                          } else { ?> 
                          <script type="application/javascript">
                              jQuery(document).bind("ready", function () {
                                  houzezOpenStreetMapElementor("<?php echo 'houzez-osm-map-' . esc_attr($city['city_slug']); ?>", <?php echo json_encode( $properties_data );?> , <?php echo json_encode($map_options);?> );
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
                          $i++;
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

                          <div class="ms-location__card-action">
                            <a href="#" class="ms-btn ms-btn--bordered">
                              3214 properties
                              <i class="fa-regular fa-arrow-right-long"></i>
                            </a>
                          </div>
                          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/location/1.png" alt="" />
                          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/location/2.png" alt="" />
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

