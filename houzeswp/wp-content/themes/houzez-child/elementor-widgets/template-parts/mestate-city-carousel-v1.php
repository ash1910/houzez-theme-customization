<?php
    $settings = get_query_var('settings', []);

    $section_heading = $settings['section_heading'];
    $section_heading_description = $settings['section_heading_description'];
    $city_list = $settings['city_list'];
    $city_list_rent = $settings['city_list_rent'];

?>

      
      <!-- start: apartments  -->
      <section class="ms-apartments section--wrapper">
        <div class="container-fluid">
          <div class="row">
            <!-- section heading -->

            <div class="col-12">
              <div class="ms-section-heading">
                <h2><?php echo $section_heading; ?></h2>
                <?php echo $section_heading_description; ?>
              </div>
              <!-- content -->
              <div class="col-12">
                <div class="ms-location__tab">
                  <!-- tab controller -->
                  <div class="ms-tab-controllers__wrapper">
                    <div
                      class="ms-tab-controllers ms-tab-controllers--white nav nav-tab ms-nav-tab"
                      role="tablist"
                    >
                      <button
                        class="ms-btn ms-btn--transparent active"
                        data-target="#apartment-location-buy"
                        data-toggle="tab"
                      >
                        Buy
                      </button>
                      <button
                        class="ms-btn ms-btn--transparent"
                        data-target="#apartment-location-rent"
                        data-toggle="tab"
                      >
                        Rent
                      </button>
                    </div>
                  </div>
                  <div class="tab-content ms-location__tab__contents">
                    <div id="apartment-location-buy" class="tab-pane fade show active">
                      <div class="swiper ms-apartments__slider">
                        <div class="swiper-wrapper">
                          <?php foreach ($city_list as $city) : ?>
                          <div class="swiper-slide">
                            <div class="ms-apartments__card">
                              <?php if ( ! empty( $city['city_image'] ) ) : ?>
                              <div class="ms-apartments__card__img">
                                <a href="/city/<?php echo $city['city_slug']; ?>">
                                  <img
                                    src="<?php echo $city['city_image']['url']; ?>"
                                    alt="<?php echo $city['city_image']['alt']; ?>"
                                  />
                                </a>
                              </div>
                              <?php endif; ?>
                              <?php if ( ! empty( $city['city_slug'] ) ) : ?>
                              <div class="ms-apartments__card__content">
                                <div class="ms-apartments__card__heading">
                                  <h5><a href="/city/<?php echo $city['city_slug']; ?>"><?php echo get_city_name_by_slug($city['city_slug']); ?></a></h5>
                                  <span></span>
                                </div>
                                <?php if ( ! empty( $city['city_description'] ) ) : ?>
                                <div class="ms-apartments__card__list">
                                  <?php echo $city['city_description']; ?>
                                </div>
                                <?php endif; ?>
                              </div>
                              <?php endif; ?>
                            </div>
                          </div>
                          <?php endforeach; ?>

                        </div>
                      </div>
                    </div>
                    <div id="apartment-location-rent" class="tab-pane fade">
                      <div class="swiper ms-apartments__slider">
                        <div class="swiper-wrapper">
                        <?php foreach ($city_list_rent as $city) : ?>
                          <div class="swiper-slide">
                            <div class="ms-apartments__card">
                              <?php if ( ! empty( $city['city_image_rent'] ) ) : ?>
                              <div class="ms-apartments__card__img">
                                <a href="/city/<?php echo $city['city_slug_rent']; ?>">
                                  <img
                                    src="<?php echo $city['city_image_rent']['url']; ?>"
                                    alt="<?php echo $city['city_image_rent']['alt']; ?>"
                                  />
                                </a>
                              </div>
                              <?php endif; ?>
                              <?php if ( ! empty( $city['city_slug_rent'] ) ) : ?>
                              <div class="ms-apartments__card__content">
                                <div class="ms-apartments__card__heading">
                                  <h5><a href="/city/<?php echo $city['city_slug_rent']; ?>"><?php echo get_city_name_by_slug($city['city_slug_rent']); ?></a></h5>
                                  <span></span>
                                </div>
                                <?php if ( ! empty( $city['city_description_rent'] ) ) : ?>
                                <div class="ms-apartments__card__list">
                                  <?php echo $city['city_description_rent']; ?>
                                </div>
                                <?php endif; ?>
                              </div>
                              <?php endif; ?>
                            </div>
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
        </div>
      </section>
      <!-- end: apartments -->

      <script>
      // apartment
      function useApartmentSlider() {
        const apartmentSlider = new Swiper(".ms-apartments__slider", {
          slidesPerView: 1.5,
          spaceBetween: 20,
          centeredSlides: true,
          loop: true,
          autoplay: {
            delay: 5000,
          },
          breakpoints: {
            1024: {
              slidesPerView: 3.5,
              spaceBetween: 30,
            },
          },
        });
      }
      function useApartmentSliderTab() {
        const tabs = document.querySelectorAll('.nav-tab button[data-toggle="tab"]');
        tabs.forEach(tab => {
          tab.addEventListener("shown.bs.tab", e => {
            const target = e.target.getAttribute("data-target");
            useApartmentSlider(); // Update Swiper when the tab is shown
          });
        });
      }

	
      <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {?>
        useApartmentSlider();
        useApartmentSliderTab();
      <?php } else { ?>
        jQuery(document).ready(function($) {
          useApartmentSlider();
          useApartmentSliderTab();
        });
      <?php } ?>
      </script>