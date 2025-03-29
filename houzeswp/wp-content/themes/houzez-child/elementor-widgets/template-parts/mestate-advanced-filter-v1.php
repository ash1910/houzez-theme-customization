<?php
$prop_city = array();
houzez_get_terms_array( 'property_city', $prop_city );
$prop_area = array();
houzez_get_terms_array( 'property_area', $prop_area );

$current_page = get_post(get_the_ID());
$page_slug = $current_page->post_name;

$parent_term = get_term_by('slug', 'residential', 'property_type');
$prop_type_residential = array();
if ($parent_term && !is_wp_error($parent_term)) {
    $prop_type_residential = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => false,
        'parent' => $parent_term->term_id
    ));
}

$parent_term = get_term_by('slug', 'commercial', 'property_type');
$prop_type_commercial = array();
if ($parent_term && !is_wp_error($parent_term)) {
    $prop_type_commercial = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => false,
        'parent' => $parent_term->term_id
    ));
}

$parent_term = get_term_by('slug', 'new-projects', 'property_type');
$prop_type_new_projects = array();
if ($parent_term && !is_wp_error($parent_term)) {
    $prop_type_new_projects = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => false,
        'parent' => $parent_term->term_id
    ));
}

$payment_plan = get_Houzez_Fields_Builder_select_options('payment-plan');
$completion = get_Houzez_Fields_Builder_select_options('completion');
$handover = get_Houzez_Fields_Builder_select_options('handover');
$furnish_status = get_Houzez_Fields_Builder_select_options('furnish-status');
$floor_plan = get_Houzez_Fields_Builder_select_options('floor-plan');
$parking = array('1','2','3','4+');
$tour_type = get_Houzez_Fields_Builder_select_options('tour-type');
if (is_string($tour_type)) {
    $tour_type = explode(',', $tour_type);
}

$adv_beds_list = houzez_option('adv_beds_list');
$adv_baths_list = houzez_option('adv_baths_list');

$bed_list = array();
if($adv_beds_list) {
    $bed_list = explode(',', $adv_beds_list);
}

$bath_list = array();
if($adv_baths_list) {
    $bath_list = explode(',', $adv_baths_list);
}
?>    
    
    <!-- start: Advanced Filter Modal   -->
    <div
      class="modal ms-modal fade"
      id="msFilterModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLongTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body ms-filter__modal">
            <!-- modal heading -->
            <div class="ms-filter__modal__heading">
              <h5>Filters</h5>
              <button
                class="ms-filter__modal__close close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <i class="fa-light fa-xmark"></i>
              </button>
            </div>
            <!-- modal content -->
            <div class="ms-filter__modal__filter">
              <!-- tab controllers -->

              <div
                class="ms-filter__modal__filte__controllers ms-tab-controllers--transparent nav nav-tab ms-nav-tab"
                role="tablist"
              >
                <button
                  id="buy-tab"
                  class="<?php echo (isset($_GET['status']) && in_array('buy', $_GET['status']) || $page_slug == 'buy') ? 'active' : ''; ?>"
                  data-target="#modalBuy"
                  data-toggle="tab"
                  data-page-available="<?php echo get_page_by_path('buy') ? '1' : '0'; ?>"
                >
                  Buy
                </button>
                <button 
                  id="rent-tab" 
                  class="<?php echo (isset($_GET['status']) && in_array('rent', $_GET['status']) || $page_slug == 'rent') ? 'active' : ''; ?>"
                  data-target="#modalRent" 
                  data-toggle="tab" 
                  data-page-available="<?php echo get_page_by_path('rent') ? '1' : '0'; ?>"
                >
                  Rent
                </button>
                <button 
                  id="new-project-tab" 
                  class="<?php echo (isset($_GET['type']) && in_array('new-projects', $_GET['type']) || $page_slug == 'new-projects' || $page_slug == 'new-projects-map') ? 'active' : ''; ?>"
                  data-target="#modalNew_project" 
                  data-toggle="tab" 
                  data-page-available="<?php echo get_page_by_path('new-projects') ? '1' : '0'; ?>"
                >
                  New Project
                </button>
                <button
                  id="commercial-tab"
                  class="<?php echo (isset($_GET['type']) && in_array('commercial', $_GET['type']) || $page_slug == 'commercial' || $page_slug == 'commercial-map') ? 'active' : ''; ?>"
                  data-target="#modalCommercial"
                  data-toggle="tab"
                  data-page-available="<?php echo get_page_by_path('commercial') ? '1' : '0'; ?>"
                >
                  Commercial
                </button>
              </div>

              <!-- tab content-->

              <div class="tab-content ms-filter__modal__tab-content">
                <!-- content 1 -->
                <div
                  class="ms-filter__modal__tab-content__single tab-pane fade show active"
                  id="modalBuy-"
                >
                  <form class="ms-filter__modal__form" onsubmit="return false;">
                    <?php if(is_array($prop_area) && count($prop_area) > 0): ?>
                    <div class="ms-input__wrapper">
                      <div class="ms-input__wrapper__inner">
                        <input type="hidden" id="prop_areas_data" value='<?php echo json_encode($prop_area); ?>'>
                        <input type="hidden" id="prop_citys_data" value='<?php echo json_encode($prop_city); ?>'>
                        <div class="ms-input ms-input--serach">
                          <input
                            type="search"
                            name="keyword"
                            placeholder="Search Location"
                            class="ms-hero__search-loaction houzez-keyword-autocomplete-search"
                            id="ms-hero__search-loaction"
                            autocomplete="off"
                            autofocus
                          />
                          <div class="auto-complete-container"></div>
                        </div>
                        <button class="ms-inupt__contoller ms-btn ms-btn--primary add-location-btn">
                          <i class="fa-regular fa-plus"></i> Add
                        </button>
                      </div>
                      <ul class="ms-input__list ms-input__list--search ms-input__list--search-container">
                        <?php 
                        // Get areas from URL parameters
                        $selected_areas = isset($_GET['city_areas']) ? $_GET['city_areas'] : array();
                        $selected_cities = isset($_GET['cities']) ? $_GET['cities'] : array();
                        
                        // If cities exist in URL, add them to the list
                        if(!empty($selected_cities)) {
                          foreach($selected_cities as $city_slug) {
                            if(isset($prop_city[$city_slug])) {
                              echo '<li>
                                      <button class="location-item" data-area="'.$city_slug.'" data-type="city">
                                        '.$prop_city[$city_slug].' <i class="fa-light fa-xmark"></i>
                                      </button>
                                    </li>';
                            }
                          }
                        }

                        // If areas exist in URL, add them to the list
                        if(!empty($selected_areas)) {
                          foreach($selected_areas as $area_slug) {
                            if(isset($prop_area[$area_slug])) {
                              echo '<li>
                                      <button class="location-item" data-area="'.$area_slug.'" data-type="area">
                                        '.$prop_area[$area_slug].' <i class="fa-light fa-xmark"></i>
                                      </button>
                                    </li>';
                            }
                          }
                        }
                        ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($prop_type_residential) && count($prop_type_residential) > 0): ?>
                    <div class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment buy-tab rent-tab">
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="property-type-list-residential">
                        <?php foreach($prop_type_residential as $term): $active = isset($_GET['type']) && in_array($term->slug, $_GET['type']) ? 'active' : ''; ?>
                        <li>
                          <button data-value="<?php echo $term->slug; ?>" class="filter-item <?php echo $active; ?>">
                            <i class="icon-apartment icon-<?php echo $term->slug; ?>"></i> <?php echo $term->name; ?>
                          </button>
                        </li>
                        <?php endforeach; ?>
                        <li>
                          <button class="filter-item"><i class="icon-villa"></i> Any</button>
                        </li>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($prop_type_commercial) && count($prop_type_commercial) > 0): ?>
                    <div class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment commercial-tab initial-hide">
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="property-type-list-commercial">
                        <?php foreach($prop_type_commercial as $term): $active = isset($_GET['type']) && in_array($term->slug, $_GET['type']) ? 'active' : ''; ?>
                        <li>
                          <button data-value="<?php echo $term->slug; ?>" class="filter-item <?php echo $active; ?>">
                            <i class="icon-apartment icon-<?php echo $term->slug; ?>"></i> <?php echo $term->name; ?>
                          </button>
                        </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($prop_type_new_projects) && count($prop_type_new_projects) > 0): ?>
                    <div class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment new-project-tab initial-hide">
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="property-type-list-new-projects">
                        <?php foreach($prop_type_new_projects as $term): $active = isset($_GET['type']) && in_array($term->slug, $_GET['type']) ? 'active' : ''; ?>
                        <li>
                          <button data-value="<?php echo $term->slug; ?>" class="filter-item <?php echo $active; ?>">
                            <i class="icon-apartment icon-<?php echo $term->slug; ?>"></i> <?php echo $term->name; ?>
                          </button>
                        </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($payment_plan) && count($payment_plan) > 0): ?>
                    <div class="ms-input__content__beds rent-tab initial-hide">
                      <h6>Payment Plan</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="payment-plan-list">
                        <?php foreach($payment_plan as $key => $value): $active = isset($_GET['payment_plan']) && $_GET['payment_plan'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $key; ?>" class="filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($completion) && count($completion) > 0): ?>
                    <div class="ms-input__content__beds new-project-tab initial-hide">
                      <h6>Completion</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="completion-list">
                        <?php foreach($completion as $key => $value): $active = isset($_GET['completion']) && $_GET['completion'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $key; ?>" class="filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($handover) && count($handover) > 0): ?>
                    <div class="ms-input__content__beds new-project-tab initial-hide">
                      <h6>Handover</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="handover-list">
                        <?php foreach($handover as $key => $value): $active = isset($_GET['handover']) && $_GET['handover'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $key; ?>" class="filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>
                    <div class="ms-input__price">
                      <h6>Price Range</h6>
                      <div class="price_filter">
                        <div class="price_slider_amount">
                          <div class="ms-input__content__value__wrapper">
                            <span>min</span>
                            <div class="ms-input ms-input--serach" style="width: auto;">
                              <span class="currency-symbol"></span>
                              <input
                                type="text"
                                class="ms-input__content__value ms-input__content__value--min"
                                value="1"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                style="max-width: 120px;"
                              />
                            </div>
                          </div>
                          <div class="ms-input__content__value__wrapper">
                            <span>Max</span>
                            <div class="ms-input ms-input--serach" style="width: auto;">
                              <span class="currency-symbol"></span>
                              <input
                                type="text" 
                                class="ms-input__content__value ms-input__content__value--max"
                                value="1000000"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                style="max-width: 120px;"
                              />
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="ms-min-price" class="ms-min-price-range-hidden range-input" readonly >
                        <input type="hidden" name="ms-max-price" class="ms-max-price-range-hidden range-input" readonly >
                        <div class="slider-range ms-slider-range ms-price-slider-range-advanced-filter"></div>
                      </div>
                    </div>

                    <!--  -->

                    <div class="ms-input__content__beds ms-input__content__area">
                      <h6>Square Footage (sqft)</h6>
                      <div class="ms-input__wrapper__inner">
                        <div>
                          <label for="ms-hero__search-loaction3">Minimum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="text"
                              value="<?php echo @$_GET['min-area'];?>"
                              placeholder="0"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction3"
                              name="ms-min-area"
                              pattern="[0-9]*"
                              inputmode="numeric"
                              onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                          </div>
                        </div>
                        <div>
                          <label for="ms-hero__search-loaction4">Maximum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="text"
                              value="<?php echo @$_GET['max-area'];?>"
                              placeholder=""
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction4"
                              name="ms-max-area"
                              pattern="[0-9]*"
                              inputmode="numeric"
                              onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php if(is_array($furnish_status) && count($furnish_status) > 0): ?>
                    <div class="ms-input__content__beds buy-tab rent-tab commercial-tab">
                      <h6>Furnish Status</h6>
                      <ul class="ms-input__list ms-input__list--auto-width radio_btn_group" id="furnish-status-list">
                        <?php foreach($furnish_status as $key => $value): $active = isset($_GET['furnish-status']) && $_GET['furnish-status'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $key; ?>" class="filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($bed_list) && count($bed_list) > 0): ?>
                    <div class="ms-input__content__beds">
                      <h6>Beds</h6>
                      <ul class="ms-input__list radio_btn_group" id="beds-list">
                        <li><button data-value="" class="w-auto filter-item">Any</button></li>
                        <?php foreach($bed_list as $value): $active = isset($_GET['bedrooms']) && $_GET['bedrooms'] == $value ? 'active' : ''; ?>    
                        <li><button data-value="<?php echo $value; ?>" class="w-auto filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($bath_list) && count($bath_list) > 0): ?>
                    <div class="ms-input__content__beds">
                      <h6>Baths</h6>
                      <ul class="ms-input__list radio_btn_group" id="baths-list">
                        <li><button data-value="" class="w-auto filter-item">Any</button></li>
                        <?php foreach($bath_list as $value): $active = isset($_GET['bathrooms']) && $_GET['bathrooms'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $value; ?>" class="w-auto filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>
                    <?php if(is_array($parking) && count($parking) > 0): ?>
                    <div class="ms-input__content__beds">
                      <h6>Parking</h6>
                      <ul class="ms-input__list radio_btn_group" id="parking-list">
                        <li><button data-value="" class="filter-item">Any</button></li>
                        <?php foreach($parking as $key => $value): $active = isset($_GET['garage']) && $_GET['garage'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $value; ?>" class="filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($tour_type) && count($tour_type) > 0): ?>
                    <div class="ms-input__content__beds">
                      <h6>Tour Type</h6>
                      <ul class="ms-input__list radio_btn_group" id="tour-type-list">
                        <?php foreach($tour_type as $key => $value): $active = isset($_GET['tour-type']) && $_GET['tour-type'] == $value ? 'active' : ''; ?>
                        <?php if($value == '360 Degree'): ?>
                        <li>
                          <button class="w-auto filter-item <?php echo $active; ?>" data-value="<?php echo $value; ?>">
                            <svg
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <g clip-path="url(#clip0_857_1855)">
                                <path
                                  d="M15.1086 18.8276C14.7598 18.8276 14.4572 18.5683 14.4119 18.2132C14.363 17.828 14.6355 17.4761 15.0206 17.427C17.2424 17.1439 19.232 16.5419 20.6228 15.7314C21.8949 14.9904 22.5953 14.1192 22.5953 13.2784C22.5953 12.3517 21.7757 11.5934 21.0879 11.1203C20.7681 10.9002 20.6871 10.4626 20.9072 10.1425C21.1273 9.82264 21.5651 9.74171 21.885 9.9618C23.2696 10.9143 24.0015 12.0611 24.0015 13.2786C24.0015 14.6603 23.0781 15.9286 21.3309 16.9465C19.7582 17.8628 17.6377 18.5113 15.1984 18.8221C15.1681 18.8257 15.1381 18.8276 15.1086 18.8276Z"
                                  fill="#1B1B1B"
                                />
                                <path
                                  d="M11.8286 17.8206L9.95359 15.9456C9.67894 15.671 9.23381 15.671 8.95915 15.9456C8.68468 16.2201 8.68468 16.6654 8.95915 16.9399L9.507 17.4877C7.40642 17.2708 5.4981 16.7817 4.02044 16.071C2.35913 15.2722 1.40625 14.2543 1.40625 13.2787C1.40625 12.4512 2.0885 11.5912 3.3272 10.8569C3.66137 10.659 3.7716 10.2276 3.57366 9.89363C3.37554 9.55946 2.94415 9.44923 2.61016 9.64717C0.452819 10.926 0 12.3278 0 13.2787C0 14.8388 1.21142 16.2805 3.41107 17.3385C5.11834 18.1594 7.32677 18.7092 9.73277 18.922L8.95915 19.6956C8.68468 19.9701 8.68468 20.4154 8.95915 20.6901C9.09648 20.8272 9.27647 20.8959 9.45646 20.8959C9.63627 20.8959 9.81627 20.8272 9.95359 20.6901L11.8286 18.8151C12.1031 18.5404 12.1031 18.0951 11.8286 17.8206Z"
                                  fill="#1B1B1B"
                                />
                                <path
                                  d="M7.36651 11.8694V11.7002C7.36651 11.1035 7.00122 10.9878 6.51141 10.9878C6.20855 10.9878 6.11059 10.7206 6.11059 10.4535C6.11059 10.1862 6.20855 9.919 6.51141 9.919C6.84979 9.919 7.20611 9.87451 7.20611 9.15307C7.20611 8.63653 6.91223 8.51184 6.54693 8.51184C6.11059 8.51184 5.88794 8.61877 5.88794 8.96612C5.88794 9.2688 5.75427 9.47369 5.23773 9.47369C4.5965 9.47369 4.51648 9.34002 4.51648 8.91247C4.51648 8.21795 5.01507 7.31836 6.54693 7.31836C7.67816 7.31836 8.53307 7.72797 8.53307 8.93042C8.53307 9.58044 8.29266 10.1862 7.84734 10.391C8.37286 10.587 8.75573 10.9788 8.75573 11.7002V11.8694C8.75573 13.3301 7.74938 13.8823 6.50244 13.8823C4.97058 13.8823 4.38281 12.9472 4.38281 12.199C4.38281 11.7982 4.552 11.6913 5.04181 11.6913C5.61181 11.6913 5.75427 11.816 5.75427 12.1545C5.75427 12.5731 6.1463 12.6711 6.54693 12.6711C7.15264 12.6711 7.36651 12.4484 7.36651 11.8694Z"
                                  fill="#1B1B1B"
                                />
                                <path
                                  d="M14.1622 11.7002V11.7804C14.1622 13.3123 13.2091 13.8823 11.9801 13.8823C10.7511 13.8823 9.78906 13.3123 9.78906 11.7804V9.42022C9.78906 7.88837 10.7776 7.31836 12.0603 7.31836C13.5654 7.31836 14.1622 8.25348 14.1622 8.99267C14.1622 9.42022 13.9573 9.55371 13.512 9.55371C13.1291 9.55371 12.7905 9.45575 12.7905 9.04614C12.7905 8.70776 12.4344 8.5296 12.0158 8.5296C11.4903 8.5296 11.1785 8.80572 11.1785 9.42022V10.2217C11.4636 9.91003 11.8644 9.82983 12.2919 9.82983C13.3071 9.82983 14.1622 10.2751 14.1622 11.7002ZM11.1785 11.8784C11.1785 12.4929 11.4813 12.7601 11.9801 12.7601C12.4789 12.7601 12.7728 12.4929 12.7728 11.8784V11.7982C12.7728 11.148 12.4789 10.8986 11.9711 10.8986C11.4903 10.8986 11.1785 11.1302 11.1785 11.718V11.8784Z"
                                  fill="#1B1B1B"
                                />
                                <path
                                  d="M15.2344 11.7804V9.42022C15.2344 7.88837 16.1873 7.31836 17.4164 7.31836C18.6454 7.31836 19.6073 7.88837 19.6073 9.42022V11.7804C19.6073 13.3123 18.6454 13.8823 17.4164 13.8823C16.1873 13.8823 15.2344 13.3123 15.2344 11.7804ZM18.2179 9.42022C18.2179 8.80572 17.9152 8.5296 17.4164 8.5296C16.9177 8.5296 16.6238 8.80572 16.6238 9.42022V11.7804C16.6238 12.3949 16.9177 12.6711 17.4164 12.6711C17.9152 12.6711 18.2179 12.3949 18.2179 11.7804V9.42022Z"
                                  fill="#1B1B1B"
                                />
                                <path
                                  d="M21.2969 7.31249C20.1336 7.31249 19.1875 6.3662 19.1875 5.20312C19.1875 4.04004 20.1336 3.09375 21.2969 3.09375C22.46 3.09375 23.4062 4.04004 23.4062 5.20312C23.4062 6.3662 22.46 7.31249 21.2969 7.31249ZM21.2969 4.5C20.9091 4.5 20.5937 4.81549 20.5937 5.20312C20.5937 5.59094 20.9091 5.90624 21.2969 5.90624C21.6845 5.90624 22 5.59094 22 5.20312C22 4.81549 21.6845 4.5 21.2969 4.5Z"
                                  fill="#1B1B1B"
                                />
                              </g>
                              <defs>
                                <clipPath id="clip0_857_1855">
                                  <rect width="24" height="24" fill="white" />
                                </clipPath>
                              </defs>
                            </svg>
                          </button>
                        </li>
                        <?php else: ?>
                        <li><button data-value="<?php echo $value; ?>" class="w-auto filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <?php if(is_array($floor_plan) && count($floor_plan) > 0): ?>
                    <div class="ms-input__content__beds">
                      <h6>Floor Plan</h6>
                      <ul class="ms-input__list radio_btn_group" id="floor-plan-list">
                        <?php foreach($floor_plan as $key => $value): $active = isset($_GET['floor-plan']) && $_GET['floor-plan'] == $value ? 'active' : ''; ?>
                        <li><button data-value="<?php echo $key; ?>" class="w-auto filter-item <?php echo $active; ?>"><?php echo $value; ?></button></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>

                    <div class="ms-input__content__action" style="position: sticky; bottom: 0; background: #fff;">
                      <button class="ms-btn ms-btn--transparent advanced-filter-reset">
                        Reset All
                      </button>
                      <button class="ms-btn ms-btn--primary advanced-filter-apply">Apply</button>
                    </div>
                  </form>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
    var thousandSeparator = (n) => {
      var thousands_separator = houzez_vars.thousands_separator;
        if (typeof n === 'number') {
            n += '';
            var x = n.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + thousands_separator + '$2');
            }
            return x1 + x2;
        } else {
            return n;
        }
    }
    function formatPrice(price) {
        if (price >= 1000000) {
            return (price / 1000000).toFixed(1) + 'M';
        } else if (price >= 1000) {
            return (price / 1000).toFixed(0) + 'K';
        }
        return price;
    }
    function ms_advanced_filter_price_range(price_range_slider) {
      let $form = price_range_slider.closest('form');
      var currency_symb = houzez_vars.currency_symbol;
      // Update all currency symbols in the form
      $form.find('.currency-symbol').each(function() {
        jQuery(this).html(currency_symb);
      });
      var currency_position = houzez_vars.currency_position;
      var min_price_selected = "<?php echo @$_GET['min-price']; ?>";
      var max_price_selected = "<?php echo @$_GET['max-price']; ?>";
      var min_price = <?php echo houzez_option('advanced_search_widget_min_price', 0); ?>;
      var max_price = <?php echo houzez_option('advanced_search_widget_max_price', 2500000); ?>;
      
      var slider = price_range_slider.slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [min_price, max_price],
        slide: function (event, ui) {
          $form.find(".ms-input__content__value--min").val(thousandSeparator(ui.values[0]));
          $form.find(".ms-input__content__value--max").val(thousandSeparator(ui.values[1]));

          $form.find(".ms-min-price-range-hidden").val(ui.values[0]);
          $form.find(".ms-max-price-range-hidden").val(ui.values[1]);
        },
      });

      // Handle manual input for min price
      $form.find(".ms-input__content__value--min").on('change', function() {
        let value = parseInt(jQuery(this).val().replace(/[^0-9]/g, ''));
        if (isNaN(value)) value = min_price;
        if (value > slider.slider("values", 1)) value = slider.slider("values", 1);
        if (value < min_price) value = min_price;
        
        slider.slider("values", 0, value);
        jQuery(this).val(thousandSeparator(value));
        $form.find(".ms-min-price-range-hidden").val(value);
      });

      // Handle manual input for max price
      $form.find(".ms-input__content__value--max").on('change', function() {
        let value = parseInt(jQuery(this).val().replace(/[^0-9]/g, ''));
        if (isNaN(value)) value = max_price;
        if (value < slider.slider("values", 0)) value = slider.slider("values", 0);
        if (value > max_price) value = max_price;
        
        slider.slider("values", 1, value);
        jQuery(this).val(thousandSeparator(value));
        $form.find(".ms-max-price-range-hidden").val(value);
      });

      $form.find(".ms-input__content__value--min").val(thousandSeparator(min_price_selected != "" ? min_price_selected : min_price));
      $form.find(".ms-input__content__value--max").val(thousandSeparator(max_price_selected != "" ? max_price_selected : max_price));
      $form.find(".ms-min-price-range-hidden").val(min_price_selected || "");
      $form.find(".ms-max-price-range-hidden").val(max_price_selected || "");
      slider.slider("values", 0, min_price_selected != "" ? min_price_selected : min_price);
      slider.slider("values", 1, max_price_selected != "" ? max_price_selected : max_price);
    }

    function ms_advanced_filter_functionality(){
        ms_advanced_filter_price_range(jQuery('.ms-price-slider-range-advanced-filter'));

        // Prevent form submission on Enter key in search input
        jQuery('.houzez-keyword-autocomplete-search').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                return false;
            }
        });

        // Get areas data from hidden input
        const areasData = JSON.parse(jQuery('#prop_areas_data').val());
        const citysData = JSON.parse(jQuery('#prop_citys_data').val());
        
        jQuery('.houzez-keyword-autocomplete-search').on('input', function() {
            const searchTerm = jQuery(this).val().toLowerCase();
            const $autocomplete = jQuery(this).siblings('.auto-complete-container');
            
            // Clear previous results
            $autocomplete.empty();
            
            if (searchTerm.length < 2) {
                $autocomplete.hide();
                return;
            }
            
            // Filter areas based on search term
            const areaMatches = Object.entries(areasData).filter(([slug, name]) => 
                name.toLowerCase().includes(searchTerm)
            );
            
            // Filter cities based on search term
            const cityMatches = Object.entries(citysData).filter(([slug, name]) => 
                name.toLowerCase().includes(searchTerm)
            );
            
            if (areaMatches.length > 0 || cityMatches.length > 0) {
                const $ul = jQuery('<ul class="area-autocomplete-list">');

                // Add city matches
                if (cityMatches.length > 0) {
                    cityMatches.forEach(([slug, name]) => {
                        $ul.append(`
                            <li>
                                <a href="#" class="area-suggestion" data-slug="${slug}" data-name="${name}" data-type="city">
                                    ${name}
                                </a>
                            </li>
                        `);
                    });
                }
                
                // Add area matches
                if (areaMatches.length > 0) {
                    areaMatches.forEach(([slug, name]) => {
                        $ul.append(`
                            <li>
                                <a href="#" class="area-suggestion" data-slug="${slug}" data-name="${name}" data-type="area">
                                    ${name}
                                </a>
                            </li>
                        `);
                    });
                }
                
                jQuery($autocomplete).html($ul).show();
            } else {
                jQuery($autocomplete).hide();
            }
        });
        
        // Handle click on suggestion
        jQuery(document).on('click', '.area-suggestion', function(e) {
            e.preventDefault();
            const name = jQuery(this).data('name');
            const slug = jQuery(this).data('slug');
            const type = jQuery(this).data('type');
            
            const $input = jQuery(this).closest('.ms-input').find('.houzez-keyword-autocomplete-search');
            $input.val(name).data('slug', slug).data('type', type);
            
            // Hide the autocomplete container
            jQuery(this).closest('.auto-complete-container').hide();
        });

        // Handle Add button click
        jQuery('.add-location-btn').on('click', function(e) {
            e.preventDefault();
            const locationInput = jQuery(this).closest('.ms-input__wrapper__inner').find('.houzez-keyword-autocomplete-search');
            var locationValue = locationInput.val().trim();
            var locationSlug = locationInput.data('slug');
            var locationType = locationInput.data('type');

            if (locationValue) {
                // Check if location already exists in list
                const exists = jQuery('.ms-input__list--search-container li button').filter(function() {
                    return jQuery(this).text().trim().replace(/×$/, '') === locationValue;
                }).length > 0;
                
                // Check if location exists in either areasData or citysData
                const locationExistsInData = Object.values(areasData).some(name => name === locationValue) || 
                                          Object.values(citysData).some(name => name === locationValue);
                
                if (!exists && locationExistsInData) {
                    // Determine if location exists in areas or cities data
                    const existsInAreas = Object.entries(areasData).some(([slug, name]) => name === locationValue);
                    const existsInCities = Object.entries(citysData).some(([slug, name]) => name === locationValue);
                    
                    // Set location slug and type based on where it exists
                    if (existsInAreas) {
                        locationSlug = Object.entries(areasData).find(([slug, name]) => name === locationValue)[0];
                        locationType = 'area';
                    } else if (existsInCities) {
                        locationSlug = Object.entries(citysData).find(([slug, name]) => name === locationValue)[0];
                        locationType = 'city';
                    }

                    // Hide the autocomplete container
                    jQuery(this).closest('.ms-input__wrapper__inner').find('.auto-complete-container').hide();
                    
                    // Add new location to list with data-area attribute
                    const newLocation = `
                        <li>
                            <button class="location-item" data-area="${locationSlug}" data-type="${locationType}">
                                ${locationValue} <i class="fa-light fa-xmark"></i>
                            </button>
                        </li>
                    `;
                    jQuery('.ms-input__list--search-container').append(newLocation);
                    
                    // Clear input field and stored data
                    locationInput.val('').removeData('slug').removeData('type');
                }
            }
        });
        
        // Handle remove location
        jQuery(document).on('click', '.ms-input__list--search-container .location-item', function(e) {
            e.preventDefault();
            jQuery(this).closest('li').remove();
        });
        
        // Update the autocomplete click handler to store the slug
        jQuery(document).on('click', '.area-suggestion', function(e) {
            e.preventDefault();
            const name = jQuery(this).data('name');
            const slug = jQuery(this).data('slug');
            const type = jQuery(this).data('type');
            const $input = jQuery(this).closest('.ms-input').find('.houzez-keyword-autocomplete-search');
            
            $input.val(name).data('slug', slug).data('type', type); // Store the slug with the input
            
            // Trigger Add button click
            jQuery('.add-location-btn').click();
        });

        jQuery('.radio_btn_group button').on('click', function(e) {
            e.preventDefault();
            if (jQuery(this).hasClass('active')) {
                jQuery(this).removeClass('active');
            } else {
                jQuery(this).closest('ul').find('button').removeClass('active');
                jQuery(this).addClass('active');
            }
        });

        jQuery('[data-toggle="tab"]').on('click', function() {
            jQuery('.buy-tab').hide();
            jQuery('.rent-tab').hide();
            jQuery('.new-project-tab').hide();
            jQuery('.commercial-tab').hide();

            if (jQuery(this).attr('id') === 'buy-tab') {
                jQuery('.buy-tab').show();
            }
            if (jQuery(this).attr('id') === 'rent-tab') {
                jQuery('.rent-tab').show();
            }
            if (jQuery(this).attr('id') === 'new-project-tab') {
                jQuery('.new-project-tab').show();
            }
            if (jQuery(this).attr('id') === 'commercial-tab') {
                jQuery('.commercial-tab').show();
            }
        });

        jQuery('.advanced-filter-apply').on('click', function(e) {
            e.preventDefault();
            const $form = jQuery(this).closest('form');
            
            // Get selected locations
            const locations = [];
            const cities = [];
            $form.find('.ms-input__list--search-container .location-item').each(function() {
                const locationType = jQuery(this).data('type');
                if (locationType === 'city') {
                    cities.push(jQuery(this).data('area'));
                } else {
                    locations.push(jQuery(this).data('area'));
                }
            });

            // Get active property type based on visible tab
            let property_type = '';
            if (jQuery('#commercial-tab').hasClass('active')) {
                property_type = $form.find('#property-type-list-commercial .filter-item.active').data('value');
            }else if (jQuery('#new-project-tab').hasClass('active')) {
                property_type = $form.find('#property-type-list-new-projects .filter-item.active').data('value');
            } else {
                property_type = $form.find('#property-type-list-residential .filter-item.active').data('value');
            }

            // Get price range values
            const min_price = $form.find('.ms-min-price-range-hidden').val();
            const max_price = $form.find('.ms-max-price-range-hidden').val();

            // Get area values
            const min_area = $form.find('input[name="ms-min-area"]').val();
            const max_area = $form.find('input[name="ms-max-area"]').val();

            // Get other filter values
            const furnish_status = $form.find('#furnish-status-list .filter-item.active').data('value');
            const beds = $form.find('#beds-list .filter-item.active').data('value');
            const baths = $form.find('#baths-list .filter-item.active').data('value');
            const parking = $form.find('#parking-list .filter-item.active').data('value');
            const tour_type = $form.find('#tour-type-list .filter-item.active').data('value');
            const floor_plan = $form.find('#floor-plan-list .filter-item.active').data('value');
            const payment_plan = $form.find('#payment-plan-list .filter-item.active').data('value');
            const completion = $form.find('#completion-list .filter-item.active').data('value');
            const handover = $form.find('#handover-list .filter-item.active').data('value');
            
            const params = {
              "type[]": property_type ? [property_type] : [],
              "min-price": min_price || '',
              "max-price": max_price || '',
              "min-area": min_area === '0' ? '' : (min_area || ''),
              "max-area": max_area === 'Any' ? '' : (max_area || ''),
              "furnish-status": furnish_status === 'Any' ? '' : (furnish_status || ''),
              "garage": parking || '',
              "tour-type": tour_type === 'any' || tour_type === 'Any' ? '' : (tour_type || ''),
              "floor-plan": floor_plan === 'any' || floor_plan === 'Any' ? '' : (floor_plan || ''),
              "bedrooms": beds === 'any' || beds === 'Any' ? '' : (beds || ''),
              "bathrooms": baths === 'any' || baths === 'Any' ? '' : (baths || '')
            };

            if (locations.length) {
                params['city_areas[]'] = locations;
            }

            if (cities.length) {
                params['cities[]'] = cities; // Add cities separately
            }

            // Get status based on active tab
            const activeTab = jQuery('.ms-filter__modal__filte__controllers button.active').attr('id');
            let status = '';
            let type = '';
            switch(activeTab) {
                case 'buy-tab':
                    status = 'buy';
                    break;
                case 'rent-tab':
                    status = 'rent';
                    params.payment_plan = payment_plan || '';
                    break;
                case 'new-project-tab':
                    type = 'new-projects';
                    // params['type[]'] = ['new-projects'];
                    // if (property_type) {
                    //     params['type[]'].push(property_type);
                    // }
                    params.handover = handover || '';
                    params.completion = completion || '';
                    params['furnish-status'] = '';
                    break;
                case 'commercial-tab':
                    type = 'commercial';
                    // params['type[]'] = ['commercial'];
                    // if (property_type) {
                    //     params['type[]'].push(property_type);
                    // }
                    break;
            }

            params['status[]'] = status || '';

            const page_available = jQuery('.ms-filter__modal__filte__controllers button.active').data('page-available');
            let url = "<?php echo home_url(); ?>";
            if(status && status !== '' && page_available == '1') {
              url = url + '/' + status + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
            }
            else if(type && type !== '' && page_available == '1') {
              url = url + '/' + type + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
            }
            else {
              url = url + '/search-results<?php echo is_half_map_page() ? '-map' : ''; ?>/';
            }

            // Filter out empty parameters and build the query string
            const queryString = Object.entries(params)
              .flatMap(([key, value]) => 
                  Array.isArray(value) 
                      ? value.map(v => `${encodeURIComponent(key)}=${encodeURIComponent(v)}`) // Handle arrays properly
                      : value ? `${encodeURIComponent(key)}=${encodeURIComponent(value)}` : []
              )
              .join('&');

            window.location.href = url + (queryString ? '?' + queryString : '');
        });

        jQuery('.advanced-filter-reset').on('click', function(e) {
            e.preventDefault();
            const $form = jQuery(this).closest('form');
            
            // Reset location list
            $form.find('.ms-input__list--search-container').empty();
            $form.find('.houzez-keyword-autocomplete-search').val('');
            
            // Reset all radio button groups
            $form.find('.radio_btn_group button').removeClass('active');
            
            // Reset price range slider
            const price_range_slider = $form.find('.ms-price-slider-range-advanced-filter');
            const min_price = <?php echo houzez_option('advanced_search_widget_min_price', 0); ?>;
            const max_price = <?php echo houzez_option('advanced_search_widget_max_price', 2500000); ?>;
            
            price_range_slider.slider('values', [min_price, max_price]);
            $form.find(".ms-input__content__value--min").val(thousandSeparator(min_price));
            $form.find(".ms-input__content__value--max").val(thousandSeparator(max_price));
            $form.find(".ms-min-price-range-hidden").val("");
            $form.find(".ms-max-price-range-hidden").val("");
            
            // Reset area inputs
            $form.find('input[name="ms-min-area"]').val('0');
            $form.find('input[name="ms-max-area"]').val('');

            if (typeof resetHeroFormAllFilters === 'function') {
              resetHeroFormAllFilters();
            }
        });
    }
    
    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
        ms_advanced_filter_functionality();
    <?php } else { ?> 
    jQuery(document).ready(function($) {
      ms_advanced_filter_functionality();
      
      // Show/hide sections based on active tab on page load
      const activeTab = $('.ms-filter__modal__filte__controllers button.active').attr('id');
      if (activeTab) {
        $('.buy-tab, .rent-tab, .new-project-tab, .commercial-tab').hide();
        switch(activeTab) {
          case 'buy-tab':
            $('.buy-tab').show();
            break;
          case 'rent-tab':
            $('.rent-tab').show();
            break;
          case 'new-project-tab':
            $('.new-project-tab').show();
            break;
          case 'commercial-tab':
            $('.commercial-tab').show();
            break;
        }
      } else {
        // If no tab is active, default to buy tab
        $('#buy-tab').addClass('active');
        $('.buy-tab').show();
      }
    });
    <?php } ?>
    </script>