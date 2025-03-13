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
    function ms_hero_filter_price_range(price_range_slider) {
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
        // Reset slider values
        slider.slider('values', [min_price, max_price]);
        
        // Reset displayed values
        $form.find(".ms-input__content__value--min").val(thousandSeparator(min_price));
        $form.find(".ms-input__content__value--max").val(thousandSeparator(max_price));
        
        // Reset hidden inputs
        $form.find(".ms-min-price-range-hidden").val(min_price);
        $form.find(".ms-max-price-range-hidden").val(max_price);
        
        // Reset button text
        $form.find(".ms-input--price-btn").html('Select Price <i class="">' + currency_symb + '</i>');
        this.closest('.ms-input--price').classList.remove("ms-input--selected");
      });
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

    function submitFilterForm($form){
        const keyword = $form.find('.houzez-keyword-autocomplete').val();
        const property_type = $form.find('.ms-nice-select-property-type').val();
        const min_price = $form.find('.ms-min-price-range-hidden').val();
        const max_price = $form.find('.ms-max-price-range-hidden').val();
        const bedrooms = $form.find('.ms-bed-hidden').val();
        const bathrooms = $form.find('.ms-bath-hidden').val();
        const property_status = $form.find('.ms-nice-select-property-status').val();
        const page_available = $form.find('.ms-nice-select-property-status option:selected').data('page-available');
        const page_available_type = $form.find('.ms-nice-select-property-type option:selected').data('page-available');

        let url = "<?php echo home_url(); ?>";
        if(property_status && property_status !== '' && page_available == '1') {
          url = url + '/' + property_status + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        }
        else if(property_type && property_type !== '' && page_available_type == '1') {
          url = url + '/' + property_type + '<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        }
        else {
          url = url + '/search-results<?php echo is_half_map_page() ? '-map' : ''; ?>/';
        }
        
        const params = {
            "keyword": keyword || '',
            "type[]": property_type || '',
            "status[]": property_status || '',
            "min-price": min_price || '',
            "max-price": max_price || '',
            "bedrooms": bedrooms === 'any' ? '' : (bedrooms || ''),
            "bathrooms": bathrooms === 'any' ? '' : (bathrooms || '')
        };

        // Filter out empty parameters and build the query string
        const queryString = Object.entries(params)
            .filter(([_, value]) => value) // Remove empty values
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');

        window.location.href = url + (queryString ? '?' + queryString : '');
    }

    function ms_hero_filter_functionality(){
        filterBtns();

        ms_hero_filter_price_range(jQuery('.ms-price-slider-range'));

        jQuery(".ms-nice-select-property-type").niceSelect();
        jQuery(".ms-nice-select-property-status").niceSelect();
        jQuery(".ms-nice-select-property-type, .ms-nice-select-property-status").on("change", function () {
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
                jQuery(selectedInput).find('.ms-btn__reset__price').trigger('click');
              }

              // Reset nice select filter selection
              if(selectedInput.classList.contains('ms-nice-select-filter-container')) {
                const niceSelect = jQuery(selectedInput).find('.ms-nice-select-filter');
                niceSelect.val('').niceSelect('update');
              }

              if (selectedInput) {
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

        updateBedBathButtonText();

        jQuery('.ms-btn--search').on('click', function() {
            const $form = jQuery(this).closest('form');
            submitFilterForm($form);
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