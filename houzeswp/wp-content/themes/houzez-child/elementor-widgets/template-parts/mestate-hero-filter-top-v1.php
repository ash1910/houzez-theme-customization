<?php
  $settings = get_query_var('settings', []);
  $sticky_filter = $settings['sticky_filter'] ?? 'yes';

  $default_currency = houzez_option('default_currency');
  if(empty($default_currency)) {
      $default_currency = 'USD';
  }
?>


<section class="section--wrapper">
    <div class="container">
        <div class="row">
            <!-- filter -->
            <div class="col-12">
                <div class="ms-apartments-main__filter">
                    <?php get_template_part('elementor-widgets/template-parts/mestate-hero-filter-top-item-v1'); ?>
                </div>
                <?php if($sticky_filter == 'yes') { ?>
                <div
                    class="ms-apartments-main__filter ms-apartments-main__filter--2 ms-header--sticky"
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
      var currency_position = houzez_vars.currency_position;
      var min_price = <?php echo houzez_option('advanced_search_widget_min_price', 0); ?>;
      var max_price = <?php echo houzez_option('advanced_search_widget_max_price', 2500000); ?>;
      
      // Get URL parameters for min and max price
      var min_price_selected_value = <?php echo isset($_GET['min-price']) && $_GET['min-price'] !== '' ? $_GET['min-price'] : 'min_price'; ?>;
      var max_price_selected_value = <?php echo isset($_GET['max-price']) && $_GET['max-price'] !== '' ? $_GET['max-price'] : 'max_price'; ?>;
      
      var slider = price_range_slider.slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [min_price_selected_value, max_price_selected_value],
        slide: function (event, ui) {
          $form.find(".ms-input__content__value--min").html(currency_symb + thousandSeparator(ui.values[0]));
          $form.find(".ms-input__content__value--max").html(currency_symb + thousandSeparator(ui.values[1]));

          $form.find(".ms-min-price-range-hidden").val( ui.values[0] );
          $form.find(".ms-max-price-range-hidden").val( ui.values[1] );

          $form.find(".ms-input--price-btn").html('Up to ' + formatPrice(ui.values[1]) + ' ' + currency_symb);
        },
      });

      // Update initial display values based on URL parameters
      $form.find(".ms-input__content__value--min").html(currency_symb + thousandSeparator(min_price_selected_value));
      $form.find(".ms-input__content__value--max").html(currency_symb + thousandSeparator(max_price_selected_value));
      
      
      // Update the price button text if URL parameters exist
      if (min_price_selected_value !== min_price || max_price_selected_value !== max_price) {
        $form.find(".ms-min-price-range-hidden").val(min_price_selected_value);
        $form.find(".ms-max-price-range-hidden").val(max_price_selected_value);
        $form.find(".ms-input--price-btn").html('Up to ' + formatPrice(max_price_selected_value) + ' ' + currency_symb);
      }

      $form.find('.ms-reset-price-range').on('click', function() {
        // Reset slider values
        slider.slider('values', [min_price, max_price]);
        
        // Reset displayed values
        $form.find(".ms-input__content__value--min").html(currency_symb + thousandSeparator(min_price));
        $form.find(".ms-input__content__value--max").html(currency_symb + thousandSeparator(max_price));
        
        // Reset hidden inputs
        $form.find(".ms-min-price-range-hidden").val(min_price);
        $form.find(".ms-max-price-range-hidden").val(max_price);
        
        // Reset button text
        $form.find(".ms-input--price-btn").html('Select Price <i class="">' + currency_symb + '</i>');
      });

      $form.find(".ms-btn--apply").on('click', function() {
        //console.log('apply');
        $form.find(".ms-input--price-btn").removeClass('open');
      });
    }

    const filterBtns = function(){
        // get all button in form
        const forms = document.querySelectorAll(".ms-hero__form");
        if (forms?.length) {
          forms?.forEach((form, idx) => {
            form.addEventListener("submit", function (e) {
              e.preventDefault();
            });
            const buttonsInForm = form.querySelectorAll(
              "button:not([data-toggle='modal'])"
            );
            if (buttonsInForm?.length) {
              buttonsInForm?.forEach((button) => {
                button.addEventListener("click", function (e) {
                  e.preventDefault();
                  e.stopPropagation();
                  this.classList.toggle("open");
                });

                document.body?.addEventListener(
                  "click",
                  function () {
                    button.classList.remove("open");
                  },
                  false
                );
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
        let url = "<?php echo home_url(); ?>";
        if(property_status && property_status !== '' && page_available == '1') {
          url = url + '/' + property_status;
        }
        else {
          url = url + '/search-results/';
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