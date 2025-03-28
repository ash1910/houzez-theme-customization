<?php
  $settings = get_query_var('settings', []);

  $title = $settings['title'];  
  $description = $settings['description'];
  $filter_button_text = $settings['filter_button_text']; 
  $status_data = $settings['status_data'];
  $type_data = $settings['type_data'];
  $image = $settings['image'];

  $prop_city = array();
  houzez_get_terms_array( 'property_city', $prop_city );
  $prop_area = array();
  houzez_get_terms_array( 'property_area', $prop_area );

  $background_url = "";
  if($image){
    $background_url = "background: url('".$image['url']."') no-repeat top;";
  }

  $default_currency = houzez_option('default_currency');
  if(empty($default_currency)) {
      $default_currency = 'USD';
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


<!-- start: Hero Banner -->
<section class="ms-hero" style="<?php echo $background_url; ?>"> 
  <div class="container">
    <div class="ms-hero__inner">
      <?php if($title): ?>
      <h1 class="ms-hero__title" style="white-space: pre-line;">
        <?php echo $title; ?>
      </h1>
      <?php endif; ?>
      <?php if($description): ?>
      <p class="ms-hero__desc">
        <?php echo $description; ?>
      </p>
      <?php endif; ?>
      <?php if($filter_button_text): ?>
      <div class="ms-hero__action">
        <button
          data-toggle="modal"
          data-target="#msFilterModal"
          class="ms-btn"
        >
          <?php echo $filter_button_text; ?> <i class="fa-regular fa-arrow-right-long"></i>
        </button>
      </div>
      <?php endif; ?>
    </div>
    <div class="ms-hero__filter-tab">    
      <div class="ms-header__filter-tab__inner">
        <!-- tab controllers -->
        <div class="ms-hero__tab-controllers">
          <div
            class="ms-hero__tab-controllers__inner ms-tab-controllers--transparent nav nav-tab ms-nav-tab"
            role="tablist"
          >
            <?php if($status_data): ?>
              <?php $i = 0; foreach($status_data as $status): $i++;
              $tabname = houzez_get_term_by( 'slug', $status, 'property_status' );
              $page_path = get_page_by_path($status);
              $page_available = $page_path ? "1" : "0";
              ?>
                <button class="ms-property-status-btn <?php echo $i == 1 ? 'active' : ''; ?>" data-target="#<?php echo $status; ?>" data-page-available="<?php echo $page_available; ?>" data-toggle="tab" data-type="0">
                  <?php echo $tabname->name; ?>
                </button>
              <?php endforeach; ?>
            <?php endif; ?>
            <?php if($type_data): ?>
              <?php 
              rsort($type_data); // Sort in descending order
              $i = 0; foreach($type_data as $type): $i++;
              $tabname = houzez_get_term_by( 'slug', $type, 'property_type' );
              $page_path = get_page_by_path($type);
              $page_available = $page_path ? "1" : "0";
              ?>
                <button class="ms-property-status-btn" data-target="#<?php echo $type; ?>" data-page-available="<?php echo $page_available; ?>" data-toggle="tab" data-type="1">
                  <?php echo $tabname->name; ?>
                </button>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <!-- tab content-->

        <div class="tab-content ms-hero__filter-tab__content">
          <!-- content 1 -->
          <?php if($status_data || $type_data): ?>
              <div class="ms-hero__tab-content tab-pane fade show active" id="">
              
                <form class="ms-hero__form" onsubmit="return false;">
                  <input type="hidden" name="ms-min-price" class="ms-min-price-range-hidden range-input" readonly >
                  <input type="hidden" name="ms-max-price" class="ms-max-price-range-hidden range-input" readonly >
                  <input type="hidden" name="ms-bed" class="ms-bed-hidden" readonly >
                  <input type="hidden" name="ms-bath" class="ms-bath-hidden" readonly >

                  <div class="ms-input ms-input--serach ms-hero__search-location-container">
                    <select class="ms-hero__search_city_area" multiple="multiple" style="visibility: hidden;">
                        <?php foreach($prop_city as $city_slug => $city_name): ?>
                            <option value="<?php echo $city_slug; ?>" data-type="city" <?php if(in_array($city_slug, $selected_cities)) echo 'selected'; ?>><?php echo $city_name; ?></option>
                        <?php endforeach; ?>
                        <?php foreach($prop_area as $area_slug => $area_name): ?>
                            <option value="<?php echo $area_slug; ?>" data-type="area" <?php if(in_array($area_slug, $selected_areas)) echo 'selected'; ?>><?php echo $area_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="ms-hero__search-loaction"
                        ><i class="icon-search_black"></i
                    ></label>
                    <button class="ms-input__deselect  ms-btn__not-submit">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                  </div>
                  <div class="ms-input type-tab">
                    <select class="ms-nice-select-property-type" name="property_type" style="visibility: hidden;">
                      <option value="" selected disabled>Property type</option>
                      <?php
                      $tax_terms = get_terms('property_type', array(
                          'hide_empty' => false,
                          'parent' => 33,
                      ));
                      foreach($tax_terms as $term): ?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="ms-input__deselect  ms-btn__not-submit">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                  </div>
                  <div class="ms-input commercial-type" style="display: none;">
                    <select class="ms-nice-select-property-type ms-nice-select-property-type__commercial" name="property_type__commercial" style="visibility: hidden;">
                      <option value="" selected disabled>Property type</option>
                      <?php
                      $tax_terms = get_terms('property_type', array(
                          'hide_empty' => false,
                          'parent' => 19,
                      ));
                      foreach($tax_terms as $term): ?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="ms-input__deselect  ms-btn__not-submit">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                  </div>
                  <div class="ms-input ms-input--price d-none d-md-block">
                      <button class="ms-btn ms-input--price-btn ms-btn__not-submit">
                          Select Price <i class=""><?php echo houzez_option('currency_symbol'); ?></i>
                      </button>
                      <!--  -->

                      <div class="ms-input__content">
                          <h6>Price Range</h6>
                          <div class="price_filter">
                              <div class="price_slider_amount" style="gap: 10px;padding: 0;">
                                  <div class="ms-input__content__value__wrapper">
                                      <span>min</span>
                                      <div class="ms-input__content__value__wrap">
                                          <span class="currency-symbol ms-currency-symbol"></span>
                                          <input
                                          type="text"
                                          class="amount ms-input__content__value ms-input__content__value--min"
                                          value="1"
                                          pattern="[0-9]*"
                                          inputmode="numeric"
                                          onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                          oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                          />
                                      </div>
                                  </div>
                                  <div class="ms-input__content__value__wrapper">
                                      <span>Max</span>
                                      <div class="ms-input__content__value__wrap">
                                          <span class="currency-symbol ms-currency-symbol"></span>
                                          <input
                                          type="text" 
                                          class="amount ms-input__content__value ms-input__content__value--max"
                                          value="1000000"
                                          pattern="[0-9]*"
                                          inputmode="numeric"
                                          onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                          oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                          />
                                      </div>
                                  </div>
                              </div>
                              <div class="slider-range ms-slider-range ms-price-slider-range"></div>
                          </div> 

                          <div class="ms-input__content__action">
                              <button class="ms-btn ms-btn--transparent ms-btn__not-submit ms-btn__reset__price">
                                  Reset All
                              </button>
                              <button class="ms-btn ms-btn--primary ms-btn__not-submit ms-btn__apply__price">Apply</button>
                          </div>
                      </div>
                      <button class="ms-input__deselect  ms-btn__not-submit">
                          <i class="fa-light fa-xmark"></i>
                      </button>
                  </div>
                  <div class="ms-input ms-input--bed d-none d-md-block">
                    <button class="ms-btn ms-btn__not-submit">
                      <span class="ms-bed-btn-text">Select beds/baths</span>
                      <svg
                        width="12"
                        height="7"
                        viewBox="0 0 12 7"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          clip-rule="evenodd"
                          d="M10.959 0.744078C11.2845 1.06951 11.2845 1.59715 10.959 1.92259L6.37571 6.50592C6.05028 6.83136 5.52264 6.83136 5.1972 6.50592L0.61387 1.92259C0.288432 1.59715 0.288432 1.06951 0.61387 0.744078C0.939306 0.418641 1.46694 0.418641 1.79238 0.744078L5.78646 4.73816L9.78054 0.744078C10.106 0.418641 10.6336 0.418641 10.959 0.744078Z"
                          fill="#1B1B1B"
                        />
                      </svg>
                    </button>
                    <!--  -->

                    <div class="ms-input__content ms-input__content--bed">
                      <div class="ms-input__content__beds mb-3">
                        <h6>Beds</h6>
                        <ul class="ms-input__list">
                          <li><button class="ms-bed-btn ms-btn__not-submit" data-value="any">Any</button></li>
                          <?php foreach($bed_list as $value): ?>
                          <li><button class="ms-bed-btn ms-btn__not-submit <?php if ($value == 'Studio') echo 'w-auto'; ?>" data-value="<?php echo $value; ?>"><?php echo $value; ?></button></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                      <div class="ms-input__content__beds">
                        <h6>Baths</h6>
                        <ul class="ms-input__list">
                          <li><button class="ms-bath-btn ms-btn__not-submit" data-value="any">Any</button></li>
                          <?php foreach($bath_list as $value): ?>
                          <li><button class="ms-bath-btn ms-btn__not-submit" data-value="<?php echo $value; ?>"><?php echo $value; ?></button></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
                    <button class="ms-input__deselect  ms-btn__not-submit">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                  </div>
                  
                  <div>
                    <button
                      class="ms-btn ms-btn__not-submit"
                      data-toggle="modal"
                      data-target="#msFilterModal"
                    >
                      <i class="fa-regular fa-bars-filter"></i>
                      <span> Filter</span>
                    </button>
                  </div>
                  <div>
                    <button class="ms-btn ms-btn--primary ms-btn--search">
                      <i class="icon-search_black"></i>
                    </button>
                  </div>
                </form>
              </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- end: Hero Banner -->



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
    function ms_hero_filter_price_range() {
      const price_range_slider = jQuery('.ms-price-slider-range');
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

          $form.find(".ms-min-price-range-hidden").val( ui.values[0] );
          $form.find(".ms-max-price-range-hidden").val( ui.values[1] );

          $form.find(".ms-input--price-btn").html('Up to ' + formatPrice(ui.values[1]) + ' ' + currency_symb);

          // selected funftionality
          const priceRangeParent = ui.handle.closest(".ms-input--price");

          if (priceRangeParent) {
            priceRangeParent.classList.add("ms-input--selected");
          }
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

        // selected funftionality
        const priceRangeParent = this.closest(".ms-input--price");
        if (priceRangeParent) {
          priceRangeParent.classList.add("ms-input--selected");
        }
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
        $form.find(".ms-input--price-btn").html('Up to ' + formatPrice(value) + ' ' + currency_symb);

        // selected funftionality
        const priceRangeParent = this.closest(".ms-input--price");
        if (priceRangeParent) {
          priceRangeParent.classList.add("ms-input--selected");
        }
      });

      // Update initial display values based on URL parameters
      $form.find(".ms-input__content__value--min").val(thousandSeparator(min_price_selected != "" ? min_price_selected : min_price));
      $form.find(".ms-input__content__value--max").val(thousandSeparator(max_price_selected != "" ? max_price_selected : max_price));
      $form.find(".ms-min-price-range-hidden").val(min_price_selected || "");
      $form.find(".ms-max-price-range-hidden").val(max_price_selected || "");
      slider.slider("values", 0, min_price_selected != "" ? min_price_selected : min_price);
      slider.slider("values", 1, max_price_selected != "" ? max_price_selected : max_price);
      if(min_price_selected != "" || max_price_selected != "") {
        $form.find(".ms-input--price-btn").html('Up to ' + formatPrice(max_price_selected) + ' ' + currency_symb);
      }

      $form.find('.ms-btn__reset__price').on('click', function() {
        resetSliderValues();
        this.closest('.ms-input--price').classList.remove("ms-input--selected");
      });

      function resetSliderValues() {
        // Reset slider values 
        slider.slider('values', [min_price, max_price]);
        
        // Reset displayed values
        $form.find(".ms-input__content__value--min").val(thousandSeparator(min_price));
        $form.find(".ms-input__content__value--max").val(thousandSeparator(max_price));
        
        // Reset hidden inputs
        $form.find(".ms-min-price-range-hidden").val(min_price);
        $form.find(".ms-max-price-range-hidden").val(max_price);
        
        // Reset button text
        console.log("Reset button text");
        $form.find(".ms-input--price-btn").html('Select Price <i class="">' + currency_symb + '</i>');
      }

      // Return an object with the inner function as a method
      return {
        resetSliderValues: resetSliderValues
      };
    }

    const filterBtns = function(){

      // get all button in form
      const forms = document.querySelectorAll(".ms-hero__form");
      if (forms?.length) {
        const buttonsInForm = document.querySelectorAll(
          ".ms-btn__not-submit:not([data-toggle='modal'])"
        );
        if (buttonsInForm?.length) {
          buttonsInForm?.forEach(button => {
            button.addEventListener("click", function (e) {
              e.preventDefault();
              e.stopPropagation();
              // bed related
              const bedInputParent = jQuery(this).closest(".ms-input--bed");
              const bedInputList = jQuery(this).closest(".ms-input__list");

              // price range related
              const priceRangeParent = jQuery(this).closest(".ms-input--price");

              const isOpen = this.classList.contains("open");
              const isReset = this.classList.contains("ms-btn__reset__price");
              const isApply = this.classList.contains("ms-btn__apply__price");
              const isDeselectBtn = this.classList.contains("ms-input__deselect");

              buttonsInForm?.forEach(button => {
                button.classList.remove("open");
              });

              if (!isOpen && !isDeselectBtn) {
                this.classList.add("open");
                jQuery(".ms-nice-select-filter").removeClass("open");
              }
              // apply price range input

              if (isApply) {
                priceRangeParent.find(".open").removeClass("open");
              }

              // selected funtionality

              if (bedInputList?.length) {
                bedInputParent.addClass("ms-input--selected");
              }
            });

            document?.body?.addEventListener(
              "click",
              function (e) {
                if (!e.target.closest(".ms-input__content")) {
                  button.classList.remove("open");
                }
              },
              false
            );
            const buttonParent = button?.parentNode;

            buttonParent?.parentNode?.addEventListener(
              "click",
              function (e) {
                if (!e.target.closest(".ms-input__content")) {
                  button.classList.remove("open");
                }
              },
              false
            );
          });
        }

        forms?.forEach((form, idx) => {
          // add class ms-inpu on onchange event
          const allInputs = form?.querySelectorAll(".ms-input input");

          if (allInputs?.length) {
            allInputs?.forEach((input, idx) => {
              input.addEventListener("change", function () {
                const inputParent = this.parentNode;

                if (inputParent) {
                  inputParent.classList.add("ms-input--selected");
                }
              });
            });
          }
        });
      }
    }

    function updateBedBathButtonText() {
        var bedValue = jQuery('.ms-bed-hidden').val();
        var bathValue = jQuery('.ms-bath-hidden').val();
        
        var bedText = bedValue ? (bedValue === 'any' ? 'Any' : bedValue) : 'Any';
        var bathText = bathValue ? (bathValue === 'any' ? 'Any' : bathValue) : 'Any';
        
        var displayText = bedText + ' BD / ' + bathText + ' Bath';
        if (!bedValue && !bathValue) {
            displayText = 'Select beds/baths';
        }
        
        jQuery('.ms-bed-btn-text').html(displayText);
    }

    function ms_hero_filter_functionality(){
        filterBtns();

        ms_hero_filter_price_range();

        jQuery(".ms-hero__search_city_area").select2({
          placeholder: 'Search Location',
          minimumInputLength: 2,
        });

        jQuery(".ms-nice-select-property-type").niceSelect();
        jQuery(".ms-nice-select-property-status").niceSelect();
        jQuery(".ms-nice-select-property-type, .ms-nice-select-property-status, .ms-hero__search_city_area").on("change", function () {
          // selected funtionality
          const selectParent = jQuery(this).closest(".ms-input");

          if (selectParent?.length) {
            selectParent.addClass("ms-input--selected");
          }
        });
        	// deselect selected input
        const deselectBtns = document.querySelectorAll(".ms-input__deselect");
        if (deselectBtns?.length) {
          deselectBtns?.forEach(deselectBtn => {
            const deselectParent = deselectBtn.parentNode;
            const selectCommon = deselectParent?.querySelector(".ms-btn");

            const niceSelectCurrent = deselectParent?.querySelector(
              ".ms-nice-select-filter .current"
            );

            const inputText = deselectParent?.querySelector(
              ".ms-hero__search-loaction"
            );

            let selectCommonDefaultValue = "";
            let niceSelectCurrentCommonDefaultValue = "";
            let inputDefaultValue = "";

            if (selectCommon) {
              selectCommonDefaultValue = selectCommon.innerHTML;
            }
            if (niceSelectCurrent) {
              niceSelectCurrentCommonDefaultValue = niceSelectCurrent.textContent;
            }

            const bathSelectDefaultValue = deselectParent?.querySelector(".ms-btn");

            deselectBtn.addEventListener("click", function () {
              const selectedInput = this.closest(".ms-input--selected");
              const msBtn = selectedInput.querySelector(".ms-btn");
              const niceSelect = selectedInput.querySelector(".ms-nice-select-filter");

              // Reset bed selection
              if(selectedInput.classList.contains('ms-input--bed')) {
                jQuery('.ms-bed-hidden').val('');
                jQuery('.ms-bath-hidden').val('');
                jQuery('.ms-bed-btn').removeClass('active');
                jQuery('.ms-bath-btn').removeClass('active');
              }

              // Reset price range selection
              if(selectedInput.classList.contains('ms-input--price')) {
                //jQuery(selectedInput).find('.ms-btn__reset__price').trigger('click');
                const obj = ms_hero_filter_price_range();
                obj.resetSliderValues();
                this.closest('.ms-input--price').classList.remove("ms-input--selected");
              }

              // Reset select2 select filter selection
              if(selectedInput.classList.contains('ms-hero__search-location-container')) {
                const select2Select = jQuery(selectedInput).find('.ms-hero__search_city_area');
                select2Select.val(null).trigger('change');
              }

              
              // Reset nice select filter selection
              if(selectedInput.classList.contains('ms-nice-select-filter-container')) {
                const niceSelect = jQuery(selectedInput).find('.ms-nice-select-filter');
                niceSelect.val('').niceSelect('update');
              }

              if (selectedInput && !selectedInput.classList.contains('ms-input--price')) {
                selectedInput.classList.remove("ms-input--selected");

                if (selectCommonDefaultValue) {
                  selectCommon.innerHTML = selectCommonDefaultValue;
                }
                if (inputText) {
                  inputText.value = "";
                }
              }


            });
          });
        }

        // Handle bed button clicks
        jQuery('.ms-bed-btn').on('click', function() {
            var bedValue = jQuery(this).data('value');
            var currentValue = jQuery('.ms-bed-hidden').val();
            
            // Toggle value if clicking the same button
            if (currentValue == bedValue) {
                jQuery('.ms-bed-hidden').val('');
                jQuery(this).removeClass('active');
            } else {
                // Update hidden input
                jQuery('.ms-bed-hidden').val(bedValue);
                
                // Update active state
                jQuery('.ms-bed-btn').removeClass('active');
                jQuery(this).addClass('active');
            }
            
            updateBedBathButtonText();
        });

        // Handle bath button clicks
        jQuery('.ms-bath-btn').on('click', function() {
            var bathValue = jQuery(this).data('value');
            var currentValue = jQuery('.ms-bath-hidden').val();
            
            // Toggle value if clicking the same button
            if (currentValue == bathValue) {
                jQuery('.ms-bath-hidden').val('');
                jQuery(this).removeClass('active');
            } else {
                // Update hidden input
                jQuery('.ms-bath-hidden').val(bathValue);
                
                // Update active state
                jQuery('.ms-bath-btn').removeClass('active');
                jQuery(this).addClass('active');
            }
            
            updateBedBathButtonText();
        });

        jQuery('.ms-hero__tab-controllers [data-toggle="tab"]').on('click', function() {
            const tab_name = jQuery(this).data('target').replace('#', '');
            const tab_type = jQuery(this).data('type');

            // Reset both tabs first
            jQuery('.type-tab').hide();
            jQuery('.commercial-type').hide();

            // Show appropriate tab based on type and name
            if (tab_type == '1') {
                if (tab_name === 'commercial') {
                    jQuery('.commercial-type').show();
                }
            } else {
                jQuery('.type-tab').show();
            }
        });

        jQuery('.ms-btn--search').on('click', function() {
            //const keyword = jQuery('.ms-hero__form .houzez-keyword-autocomplete').val();
            var property_type = jQuery('.ms-hero__form .ms-nice-select-property-type').val();
            const min_price = jQuery('.ms-hero__form .ms-min-price-range-hidden').val();
            const max_price = jQuery('.ms-hero__form .ms-max-price-range-hidden').val();
            const bedrooms = jQuery('.ms-hero__form .ms-bed-hidden').val();
            const bathrooms = jQuery('.ms-hero__form .ms-bath-hidden').val();
            var cities = [];
            var areas = [];
            
            // Get all data attributes from selected options
            jQuery('.ms-hero__form').find('.ms-hero__search_city_area option:selected').each(function() {
                const locationType = jQuery(this).data('type');
                const locationValue = jQuery(this).val();
                if (locationType === 'city') {
                    cities.push(locationValue);
                }
                else if (locationType === 'area') {
                    areas.push(locationValue);
                }
            });
            
            // Add null check for property status
            const activeStatusBtn = jQuery('.ms-hero__filter-tab .ms-property-status-btn.active');
            const property_status = activeStatusBtn.length ? activeStatusBtn.data('target').replace('#', '') : '';
            const page_available = activeStatusBtn.data('page-available');
            const tab_type = activeStatusBtn.data('type');
            
            let url = "<?php echo home_url(); ?>";
            if(property_status && property_status !== '' && page_available == '1') {
              url = url + '/' + property_status;
            }
            else {
              url = url + '/search-results/';
            }

            if(property_status == 'commercial'){
              property_type = jQuery('.ms-hero__form .ms-nice-select-property-type__commercial').val();
            }
            
            const params = {
                //"keyword": keyword || '',
                "type[]": property_type || '',
                "status[]": property_status || '',
                "min-price": min_price || '',
                "max-price": max_price || '',
                "bedrooms": bedrooms === 'any' ? '' : (bedrooms || ''),
                "bathrooms": bathrooms === 'any' ? '' : (bathrooms || '')
            };

            if(tab_type == '1'){
              //params["type[]"] = property_status || '';
              delete params["status[]"];
            }

            if (cities.length) {
              params['city[]'] = cities; // Add cities separately
            }
            if (areas.length) {
              params['areas[]'] = areas;
            }

            const queryString = Object.entries(params)
              .flatMap(([key, value]) => 
                  Array.isArray(value) 
                      ? value.map(v => `${encodeURIComponent(key)}=${encodeURIComponent(v)}`) // Handle arrays properly
                      : value ? `${encodeURIComponent(key)}=${encodeURIComponent(value)}` : []
              )
              .join('&');

            window.location.href = url + (queryString ? '?' + queryString : '');
        });
    }


    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
      ms_hero_filter_functionality();
    <?php } else { ?>
    jQuery(document).ready(function($) {
      ms_hero_filter_functionality();
    });
    <?php } ?>
  </script>