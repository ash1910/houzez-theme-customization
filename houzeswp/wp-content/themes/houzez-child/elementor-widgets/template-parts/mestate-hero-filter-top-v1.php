<?php
  $settings = get_query_var('settings', []);
  $sticky_filter = $settings['sticky_filter'] ?? 'yes';

  $default_currency = houzez_option('default_currency');
  if(empty($default_currency)) {
      $default_currency = 'USD';
  }
?>


<section class="section--wrapper">
    <div class="<?php echo is_half_map_page() ? 'container-fluid container-fluid--lg' : 'container'; ?>">
        <div class="row">
            <!-- filter -->
            <div class="col-12">
                <div class="ms-apartments-main__filter">
                    <?php get_template_part('elementor-widgets/template-parts/mestate-hero-filter-top-item-v1'); ?>
                </div>
                <?php if($sticky_filter == 'yes') { ?>
                <div
                    class="ms-apartments-main__filter ms-header--sticky <?php echo is_half_map_page() ? '' : 'ms-apartments-main__filter--2'; ?>"
                >
                        <?php get_template_part('elementor-widgets/template-parts/mestate-hero-filter-top-item-v1'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>


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
      
      // Find min and max prices from listings
      $listing_price_min_max = jQuery('.listing_price_min_max');
      if($listing_price_min_max.length > 0){
        min_price = parseInt($listing_price_min_max.data('min_price')) > 0 ? parseInt($listing_price_min_max.data('min_price') ) : min_price;
        max_price = parseInt($listing_price_min_max.data('max_price')) > 0 ? parseInt($listing_price_min_max.data('max_price')) : max_price;
      }
      
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

    function updateFilterCount(params) {
        let totalParams = 0;
        
        // Count active bed/bath filters
        if (params['bedrooms'] && params['bedrooms'].length > 0) totalParams++;
        if (params['bathrooms'] && params['bathrooms'].length > 0) totalParams++;
        
        // Count price range filters
        if (params['min-price'] && params['min-price'].length > 0 && params['min-price'] != 0) totalParams++;
        else if (params['max-price'] && params['max-price'].length > 0 && params['max-price'] != 100000000) totalParams++;
        
        // Count property type filter
        if (params['type[]'] && params['type[]'].length > 0) totalParams++;
        
        // Count property status filter
        if (params['status[]'] && params['status[]'].length > 0) totalParams++;
        
        // Count location filters
        if (params['cities[]'] && params['cities[]'].length > 0) totalParams++;
        else if (params['city_areas[]'] && params['city_areas[]'].length > 0) totalParams++;
        //console.log(totalParams);
        // Update the count display
        jQuery('.ms-total-filter-item').text(String(totalParams).padStart(2, '0'));
    }

    function submitFilterForm($form, current_page = 1){
        //const keyword = $form.find('.houzez-keyword-autocomplete').val();
        var property_type = $form.find('.ms-nice-select-property-type').val();
        const min_price = $form.find('.ms-min-price-range-hidden').val();
        const max_price = $form.find('.ms-max-price-range-hidden').val();
        const bedrooms = $form.find('.ms-bed-hidden').val();
        const bathrooms = $form.find('.ms-bath-hidden').val();
        const property_status = $form.find('.ms-nice-select-property-status').val();
        const page_available = $form.find('.ms-nice-select-property-status option:selected').data('page-available');
        const page_available_type = $form.find('.ms-nice-select-property-type option:selected').data('page-available');
        var sortby = jQuery('#ajax_sort_properties').val();
        var ms_page_slug = $form.find('.ms-page-slug').val();
        var cities = [];
        var areas = [];
        
        // Get all data attributes from selected options
        $form.find('.ms-hero__search_city_area option:selected').each(function() {
            const locationType = jQuery(this).data('type');
            const locationValue = jQuery(this).val();
            if (locationType === 'city') {
                cities.push(locationValue);
            }
            else if (locationType === 'area') {
                areas.push(locationValue);
            }
        });

        if( property_type ){

        }
        else if(ms_page_slug === "new-projects" || ms_page_slug === "commercial"){
          property_type = ms_page_slug;
        }
        else if(ms_page_slug === "new-projects-map" || ms_page_slug === "commercial-map"){
          property_type = ms_page_slug.replace('-map', '');
        }
        

        let url = "";
        //let url = "<?php //echo home_url(); ?>";
        if(property_status && property_status !== '' && page_available == '1') {
          url = "<?php echo home_url(); ?>";
          url = url + '/' + property_status + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        }
        // else if(property_type && property_type !== '' && page_available_type == '1') {
        //   url = url + '/' + property_type + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        // }
        // else {
        //   url = url + '/search-results<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        // }
        
        const params = {
            //"keyword": keyword || '',
            "type[]": property_type || '',
            "status[]": property_status || '',
            "min-price": min_price == 0 ? '' : (min_price || ''),
            "max-price": max_price == 100000000 ? '' : (max_price || ''),
            "bedrooms": bedrooms === 'any' ? '' : (bedrooms || ''),
            "bathrooms": bathrooms === 'any' ? '' : (bathrooms || ''),
            "sortby": sortby || '',
            "paged": current_page == 1 ? '' : (current_page || ''),
            "slug": ms_page_slug
        };

        if (cities.length) {
          params['cities[]'] = cities; // Add cities separately
        }
        if (areas.length) {
          params['city_areas[]'] = areas;
        }

        const queryString = Object.entries(params)
          .flatMap(([key, value]) => 
              Array.isArray(value) 
                  ? value.map(v => `${encodeURIComponent(key)}=${encodeURIComponent(v)}`) // Handle arrays properly
                  : value ? `${encodeURIComponent(key)}=${encodeURIComponent(value)}` : []
          )
          .join('&');

        //window.location.href = url + (queryString ? '?' + queryString : '');
        pageUrl = url + (queryString ? '?' + queryString : '');

        window.history.pushState({houzezTheme: true}, '', pageUrl);

        updateFilterCount(params);
        
        mestate_half_map_listings(current_page, queryString);
    }

    /*----------------------------------------------------------
    * Ajax Search
    *----------------------------------------------------------*/
    var mestate_half_map_listings = function(current_page, queryString) {
        var ajaxurl = houzez_vars.admin_url + 'admin-ajax.php';
        var ajax_container = jQuery('#houzez_ajax_container');
        var ajax_location_container = jQuery('#ajax_location_container');
        
        var ajax_map_wrap = jQuery('.map-wrap');

        jQuery.ajax({
            type: 'GET',
            dataType: 'json',
            url: ajaxurl,
            data: queryString + "&action=mestate_half_map_listings",
            beforeSend: function() {
                jQuery('.houzez-map-loading').show();
                ajax_location_container.empty();
                ajax_container.empty().append(''
                    +'<div id="houzez-map-loading" class="houzez-map-loading">'
                    +'<div class="mapPlaceholder">'
                    +'<div class="loader-ripple spinner">'
                    +'<div class="bounce1"></div>'
                    +'<div class="bounce2"></div>'
                    +'<div class="bounce3"></div>'
                    +'</div>'
                    +'</div>'
                    +'</div>'
                );
                ajax_map_wrap.append(''
                    +'<div id="houzez-map-loading" class="houzez-map-loading">'
                    +'<div class="mapPlaceholder">'
                    +'<div class="loader-ripple spinner">'
                    +'<div class="bounce1"></div>'
                    +'<div class="bounce2"></div>'
                    +'<div class="bounce3"></div>'
                    +'</div>'
                    +'</div>'
                    +'</div>'
                );
            },
            success: function(data) { 
    
                // if ( data.query != '' ) {
                //     $( 'input[name="search_args"]' ).val( data.query );
                // }
                // if ( data.search_uri != '' ) {
                //     $( 'input[name="search_URI"]' ).val( data.search_uri );
                // }
                //$('.map-notfound').remove();
                jQuery('.search-no-results-found-wrap').remove();

                if(data.getProperties === true) {
                    if (typeof mestate_Reload_Markers === 'function') {
                        mestate_Reload_Markers();
                        mestate_Add_Markers( data.properties );
                    }
                    ajax_container.empty().html(data.propHtml);
                    ajax_location_container.empty().html(data.locationHtml);
                    functionListingItemImageSlider();
                    functionPropertyLocationShowMore();
                } else { 
                    if (typeof mestate_Reload_Markers === 'function') {
                        mestate_Reload_Markers();
                    }
                    ajax_container.empty().html('<div class="search-no-results-found">No results found</div>');
                }
                return false;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
        return false;
    }

    function functionListingItemImageSlider(){
        // card slider
        const apartmentSlider = jQuery(".ms-aparments-maincardslider");
        if (apartmentSlider?.length) {
          var formSlider = new Swiper(".ms-aparments-maincardslider", { 
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
              renderBullet: function (index, className) {
                var current = this.realIndex; // Get active slide index
                var totalSlides = this.slides.length;
                let bulletsToShow = [];

                if (current <= totalSlides - 3) {
                  // Case 1: If there are at least 2 next slides -> Current, Next 1, Next 2
                  bulletsToShow = [current, current + 1, current + 2];
                } else if (current === totalSlides - 2) {
                  // Case 2: If there is only 1 next slide -> Prev 1, Current, Next 1
                  bulletsToShow = [current - 1, current, current + 1];
                } else if (current === totalSlides - 1) {
                  // Case 3: If no next slides -> Prev 2, Prev 1, Current
                  bulletsToShow = [current - 2, current - 1, current];
                } else if (current >= 2) {
                  // Case 4: If there are at least 2 previous slides -> Prev 2, Prev 1, Current
                  bulletsToShow = [current - 2, current - 1, current];
                } else if (current === 1) {
                  // Case 5: If there is only 1 previous slide -> Prev 1, Current, Next 1
                  bulletsToShow = [current - 1, current, current + 1];
                }

                // Render bullets only if they match the allowed range
                if (bulletsToShow.includes(index)) {
                  return `<span class="${className}" data-index="${index}"></span>`;
                }
                return ""; // Hide other bullets
              },
            },

            // loop: true,
          });
        }
    }

    function ms_hero_filter_functionality(){
        filterBtns();

        ms_hero_filter_price_range();

        jQuery(".ms-hero__search_city_area").select2({
          placeholder: 'Search Location',
          minimumInputLength: 2,
        }).on('change', function(e) {
            const $select = jQuery(this);
            const selectedOptions = $select.find('option:selected');
            
            // Check each selected option
            selectedOptions.each(function() {
                const $option = jQuery(this);
                const optionType = $option.data('type');
                const parentCity = $option.data('city');
                
                // If this is an area
                if (optionType === 'area' && parentCity) {
                    // Find and deselect the parent city if it's selected
                    const $parentCityOption = $select.find(`option[value="${parentCity}"]`);
                    if ($parentCityOption.length && $parentCityOption.is(':selected')) {
                        $parentCityOption.prop('selected', false);
                        $select.trigger('change');
                    }
                }
            });
        });

        jQuery(".ms-nice-select-property-type").niceSelect();
        jQuery(".ms-nice-select-property-status").niceSelect();
        jQuery(".ms-nice-select-property-type, .ms-nice-select-property-status, .ms-hero__search_city_area").on("change", function () {
          // selected funtionality
          const selectParent = jQuery(this).closest(".ms-input");

          if (selectParent?.length) {
            selectParent.addClass("ms-input--selected");
          }

          const $form = jQuery(this).closest('form');
          submitFilterForm($form);
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

              // Search 
              const $form = jQuery(this).closest('form');
              submitFilterForm($form);

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

            const $form = jQuery(this).closest('form');
            submitFilterForm($form);
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

            const $form = jQuery(this).closest('form');
            submitFilterForm($form);
        });

        updateBedBathButtonText();

        jQuery('.ms-btn--search, .ms-btn__apply__price').on('click', function() {
            const $form = jQuery(this).closest('form');
            submitFilterForm($form);
        });
        jQuery('.auto-complete').on( 'click', 'li', function (){
          const $form = jQuery(this).closest('form');
          submitFilterForm($form);
        }).bind();

        jQuery('#houzez_ajax_container').on('click', '.houzez_ajax_pagination a', function(e){
            e.preventDefault();
            current_page = jQuery(this).data('houzepagi');
            //$('.hz-halfmap-paged').val(current_page);
            const $form = jQuery(".ms-apartments-main__filter:not(.ms-header--sticky)").find('form');
            submitFilterForm($form, current_page);
            
            // Smooth scroll to top
            jQuery('html, body').animate({
                scrollTop: 0
            }, 500);
        }).bind();

        jQuery('#ajax_sort_properties').on('change', function() {
            const $form = jQuery(".ms-apartments-main__filter:not(.ms-header--sticky)").find('form');
            submitFilterForm($form);
        });

    }

    function resetHeroFormAllFilters() {
      
      $form = jQuery(".ms-hero__form");

      // Reset bed selection
      $form.find('.ms-bed-hidden').val('');
      $form.find('.ms-bath-hidden').val('');
      $form.find('.ms-bed-btn').removeClass('active');
      $form.find('.ms-bath-btn').removeClass('active');
      $form.find('.ms-bed-btn-text').html("Select beds/baths");

      // Reset nice select filter selection
      const niceSelect = $form.find('.ms-nice-select-filter');
      niceSelect.val('').niceSelect('update');

      // Reset keyword
      $form.find('input.houzez-keyword-autocomplete').val("");

      // Reset price range selection
      const obj = ms_hero_filter_price_range();
      obj.resetSliderValues();

      $form.find(".ms-input--selected").removeClass("ms-input--selected");

      // Search 
      submitFilterForm($form);
    }


    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
      ms_hero_filter_functionality();
    <?php } else { ?>
    jQuery(document).ready(function($) {
      ms_hero_filter_functionality();
    });
    <?php } ?>
  </script>