<?php

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : array();
$type = isset($_GET['type']) ? $_GET['type'] : array();
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$bed = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$bath = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';

// Get current page slug from URL
$current_page = get_post(get_the_ID());
$page_slug = $current_page->post_name;
if( empty($_GET["status"]) ){
    if ($page_slug) {
        $status = array($page_slug);
    }
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

<form class="ms-hero__form" onsubmit="return false;">
    <input type="hidden" name="ms-min-price" value="<?php echo $min_price; ?>" class="ms-min-price-range-hidden range-input" readonly >
    <input type="hidden" name="ms-max-price" value="<?php echo $max_price; ?>" class="ms-max-price-range-hidden range-input" readonly >
    <input type="hidden" name="ms-bed" value="<?php echo $bed; ?>" class="ms-bed-hidden" readonly >
    <input type="hidden" name="ms-bath" value="<?php echo $bath; ?>" class="ms-bath-hidden" readonly >
    <div class="ms-input" <?php if(in_array("new-projects", $type) || $page_slug == 'new-projects') echo 'style="display: none;"'; ?>>
        <select class="ms-nice-select-property-status" style="visibility: hidden;">
            <option value="" selected disabled>Select</option>
            <?php
            $tax_terms = get_terms('property_status', array(
                'hide_empty' => false,
                'parent' => 0,
            ));
            foreach($tax_terms as $term): 
                $page_path = get_page_by_path($term->slug);
                $page_available = $page_path ? "1" : "0";
            ?>
                <option data-page-available="<?php echo $page_available; ?>" value="<?php echo $term->slug; ?>" <?php if(in_array($term->slug, $status)) echo 'selected'; ?>><?php echo $term->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="ms-input ms-input--serach" style="width: 280px;">
        <input
            type="search"
            placeholder="Search Location"
            class="ms-hero__search-loaction houzez-keyword-autocomplete"
            id="ms-hero__search-loaction"
            autofocus
            autocomplete="off"
            value="<?php echo $keyword; ?>"
        />
        <div id="auto_complete_ajax" class="auto-complete" style="top: 100%;"></div>
        <label for="ms-hero__search-loaction"
            ><i class="icon-search_black"></i
        ></label>
    </div>
    <div class="ms-input">
        <select class="ms-nice-select-property-type" style="visibility: hidden;">
            <option value="" selected disabled>Property type</option>
            <?php
            $tax_terms = get_terms('property_type', array(
                'hide_empty' => false,
                'parent' => 0,
            ));
            foreach($tax_terms as $term): 
            $page_path = get_page_by_path($term->slug);
            $page_available = $page_path ? "1" : "0";
            $selected = in_array($term->slug, $type) ? 'selected' : '';
            $active = in_array($term->slug, $status) ? 'selected' : ''; // here page slug is in status array
            ?>
                <option data-page-available="<?php echo $page_available; ?>" value="<?php echo $term->slug; ?>" <?php echo $selected; ?> <?php echo $active; ?>><?php echo $term->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="ms-input ms-input--price d-none d-md-block">
        <button class="ms-btn ms-input--price-btn ms-btn__not-submit">
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
                            $200
                        </span>
                    </div>
                    <div class="ms-input__content__value__wrapper">
                        <span>Max</span>
                        <span
                            class="ms-input__content__value ms-input__content__value--max"
                        >
                            $1500
                        </span>
                    </div>
                </div>
                <div class="slider-range ms-slider-range ms-price-slider-range"></div>
            </div>

            <div class="ms-input__content__action">
                <button class="ms-btn ms-btn--transparent ms-btn__not-submit ms-reset-price-range">
                    Reset All
                </button>
                <button class="ms-btn ms-btn--primary ms-btn__not-submit ms-btn--apply">Apply</button>
            </div>
        </div>
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
                    <li><button class="ms-bed-btn ms-btn__not-submit <?php if ($value == 'Studio') echo 'w-auto'; ?> <?php if ($value == $bed) echo 'active'; ?>" data-value="<?php echo $value; ?>"><?php echo $value; ?></button></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ms-input__content__beds">
                <h6>Baths</h6>
                <ul class="ms-input__list">
                    <li><button class="ms-bath-btn ms-btn__not-submit" data-value="any">Any</button></li>
                    <?php foreach($bath_list as $value): ?>
                    <li><button class="ms-bath-btn ms-btn__not-submit <?php if ($value == $bath) echo 'active'; ?>" data-value="<?php echo $value; ?>"><?php echo $value; ?></button></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div>
        <button
            class="ms-btn ms-btn__not-submit"
            data-toggle="modal"
            data-target="#msFilterModal"
        >
            <i class="fa-regular fa-bars-filter"></i>
            <span> Filter</span>
            <!-- <span class="ms-total-filter-item">05</span> -->
        </button>
    </div>
    <div>
        <button class="ms-btn ms-btn--primary ms-btn--search">
        <i class="icon-search_black"></i>
        </button>
    </div>
</form>