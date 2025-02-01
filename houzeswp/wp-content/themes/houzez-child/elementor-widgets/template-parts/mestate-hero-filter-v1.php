<?php
  $settings = get_query_var('settings', []);

  $title = $settings['title'];  
  $description = $settings['description'];
  $filter_button_text = $settings['filter_button_text']; 
  $status_data = $settings['status_data'];

  $default_currency = houzez_option('default_currency');
  if(empty($default_currency)) {
      $default_currency = 'USD';
  }
?>


<!-- start: Hero Banner -->
<section class="ms-hero">
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
              ?>
                <button class="<?php echo $i == 1 ? 'active' : ''; ?>" data-target="#<?php echo $status; ?>" data-toggle="tab">
                  <?php echo $tabname->name; ?>
                </button>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <!-- tab content-->

        <div class="tab-content ms-hero__filter-tab__content">
          <!-- content 1 -->
          <?php if($status_data): ?>
              <div class="ms-hero__tab-content tab-pane fade show active" id="">
                <div class="ms-hero__form">
                  <input type="hidden" name="ms-bed" class="ms-bed-hidden" readonly >
                  <input type="hidden" name="ms-bath" class="ms-bath-hidden" readonly >
                  <div class="ms-input ms-input--serach">
                    <input
                      type="search"
                      placeholder="Search Location"
                      class="ms-hero__search-loaction"
                      id="ms-hero__search-loaction"
                      autofocus
                    />

                    <label for="ms-hero__search-loaction"
                      ><i class="icon-search_black"></i
                    ></label>
                  </div>
                  <div class="ms-input">
                    <select class="ms-nice-select" name="property_type">
                      <option value="" selected disabled>Property type</option>
                      <?php
                      $tax_terms = get_terms('property_type', array(
                          'hide_empty' => false,
                          'parent' => 0,
                      ));
                      foreach($tax_terms as $term): ?>
                        <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="ms-input ms-input--price d-none d-md-block">
                    <button class="ms-btn ms-input--price-btn">
                      Select Price <i class=""><?php echo houzez_option('currency_symbol'); ?></i>
                    </button>
                    <!--  -->

                    <div class="ms-input__content">
                      <h6>Price Range</h6>
                      <div class="price_filter">
                        <div class="price_slider_amount">
                          <div class="ms-input__content__value__wrapper">
                            <span>min</span>
                            <span
                              class="ms-input__content__value ms-input__content__value--min"
                            >
                            </span>
                          </div>
                          <div class="ms-input__content__value__wrapper">
                            <span>Max</span>
                            <span
                              class="ms-input__content__value ms-input__content__value--max"
                            >
                            </span>
                          </div>
                        </div>
                        <div class="slider-range ms-price-slider-range"></div>
                      </div>

                      <div class="ms-input__content__action">
                        <input type="hidden" name="ms-min-price" class="ms-min-price-range-hidden range-input" readonly >
                        <input type="hidden" name="ms-max-price" class="ms-max-price-range-hidden range-input" readonly >
                        <button class="ms-btn ms-btn--transparent ms-reset-price-range">
                          Reset All
                        </button>
                        <button class="ms-btn ms-btn--primary" data-toggle="modal" data-target="#msFilterModal">Apply</button>
                      </div>
                    </div>
                  </div>
                  <div class="ms-input ms-input--bed d-none d-md-block">
                    <button class="ms-btn">
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
                          <li><button class="ms-bed-btn" data-value="any">Any</button></li>
                          <li><button class="ms-bed-btn" data-value="1">1</button></li>
                          <li><button class="ms-bed-btn" data-value="2">2</button></li>
                          <li><button class="ms-bed-btn" data-value="3">3</button></li>
                          <li><button class="ms-bed-btn" data-value="4+">4+</button></li>
                        </ul>
                      </div>
                      <div class="ms-input__content__beds">
                        <h6>Baths</h6>
                        <ul class="ms-input__list">
                          <li><button class="ms-bath-btn" data-value="any">Any</button></li>
                          <li><button class="ms-bath-btn" data-value="1">1</button></li>
                          <li><button class="ms-bath-btn" data-value="2">2</button></li>
                          <li><button class="ms-bath-btn" data-value="3">3</button></li>
                          <li><button class="ms-bath-btn" data-value="4+">4+</button></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  
                  <div>
                    <button
                      class="ms-btn"
                      data-toggle="modal"
                      data-target="#msFilterModal"
                    >
                      <i class="fa-regular fa-bars-filter"></i>
                      <span> Filter</span>
                    </button>
                  </div>
                  <div>
                    <button class="ms-btn ms-btn--primary">
                      <i class="icon-search_black"></i>
                    </button>
                  </div>
                </div>
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
    function ms_price_range() {
      var currency_symb = houzez_vars.currency_symbol;
      var currency_position = houzez_vars.currency_position;
      var min_price = <?php echo houzez_option('advanced_search_widget_min_price', 0); ?>;
      var max_price = <?php echo houzez_option('advanced_search_widget_max_price', 2500000); ?>;
      
      var slider = jQuery(".ms-price-slider-range").slider({
        range: true,
        min: min_price,
        max: max_price,
        values: [min_price, max_price],
        slide: function (event, ui) {
          jQuery(".ms-input__content__value--min").html(currency_symb + thousandSeparator(ui.values[0]));
          jQuery(".ms-input__content__value--max").html(currency_symb + thousandSeparator(ui.values[1]));

          jQuery(".ms-input--price-btn").html('Up to ' + formatPrice(ui.values[1]) + ' ' + currency_symb);

          jQuery(".ms-min-price-range-hidden").val( ui.values[0] );
          jQuery(".ms-max-price-range-hidden").val( ui.values[1] );
        },
      });

      jQuery(".ms-input__content__value--min").html(currency_symb + thousandSeparator(min_price));
      jQuery(".ms-input__content__value--max").html(currency_symb + thousandSeparator(max_price));
      jQuery(".ms-min-price-range-hidden").val(min_price);
      jQuery(".ms-max-price-range-hidden").val(max_price);

      // Reset price range when clicking reset button
      jQuery('.ms-reset-price-range').on('click', function() {
        // Reset slider values
        slider.slider('values', [min_price, max_price]);
        
        // Reset displayed values
        jQuery(".ms-input__content__value--min").html(currency_symb + thousandSeparator(min_price));
        jQuery(".ms-input__content__value--max").html(currency_symb + thousandSeparator(max_price));
        
        // Reset hidden inputs
        jQuery(".ms-min-price-range-hidden").val(min_price);
        jQuery(".ms-max-price-range-hidden").val(max_price);
        
        // Reset button text
        jQuery(".ms-input--price-btn").html('Select Price <i class="">' + currency_symb + '</i>');
      });
    }
    <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
      ms_price_range();
    <?php } else { ?>
    jQuery(document).ready(function() {
      ms_price_range();
    });
    <?php } ?>

    jQuery(document).ready(function() {
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
    });
  </script>

<style>
    .ms-bed-btn.active,
    .ms-bath-btn.active {
        background-color: #00a86b;
        color: white;
    }
</style>
