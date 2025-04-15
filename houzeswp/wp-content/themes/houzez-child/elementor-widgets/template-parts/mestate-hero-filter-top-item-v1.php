<?php

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : array();
$type = isset($_GET['type']) ? $_GET['type'] : array();
$min_price = isset($_GET['min-price']) ? $_GET['min-price'] : '';
$max_price = isset($_GET['max-price']) ? $_GET['max-price'] : '';
$bed = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$bath = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';
$selected_areas = isset($_GET['city_areas']) ? $_GET['city_areas'] : array();
$selected_cities = isset($_GET['cities']) ? $_GET['cities'] : array();

$prop_city = array();
houzez_get_terms_array( 'property_city', $prop_city );
$prop_area = array();

// Get property areas with parent city information
$area_terms = get_terms('property_area', array(
    'hide_empty' => false,
));

foreach($area_terms as $term) {
    $term_id = $term->term_id;
    $term_meta = get_option('_houzez_property_area_' . $term_id);
    $parent_city = isset($term_meta['parent_city']) ? $term_meta['parent_city'] : '';
    
    $prop_area[$term->slug] = array(
        'name' => $term->name,
        'parent_city' => $parent_city
    );
}


// Get current page slug from URL
$current_page = get_post(get_the_ID());
$page_slug = $current_page->post_name;
if( empty($_GET["status"]) ){
    if ($page_slug) {
        $status = getStatusFromCurrentPageSlug($page_slug);
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

$property_type_parent_id = 33;
if(in_array("new-projects", $status) || $page_slug == 'new-projects' || $page_slug == 'new-projects-map'){
    $property_type_parent_id = 96;
}
elseif(in_array("commercial-buy", $status) || $page_slug == 'commercial-buy' || $page_slug == 'commercial-buy-map'){
    $property_type_parent_id = 19;
}
elseif(in_array("commercial-rent", $status) || $page_slug == 'commercial-rent' || $page_slug == 'commercial-rent-map'){
    $property_type_parent_id = 19;
}
?>

<form class="ms-hero__form" onsubmit="return false;">
    <input type="hidden" name="ms-page-slug" value="<?php echo $page_slug; ?>" class="ms-page-slug" readonly >
    <input type="hidden" name="ms-min-price" value="<?php echo $min_price; ?>" class="ms-min-price-range-hidden range-input" readonly >
    <input type="hidden" name="ms-max-price" value="<?php echo $max_price; ?>" class="ms-max-price-range-hidden range-input" readonly >
    <input type="hidden" name="ms-bed" value="<?php echo $bed; ?>" class="ms-bed-hidden" readonly >
    <input type="hidden" name="ms-bath" value="<?php echo $bath; ?>" class="ms-bath-hidden" readonly >
    <div class="ms-input ms-nice-select-filter-container <?php echo count($status) > 0 ? 'ms-input--selected' : ''; ?>" >
        <select class="ms-nice-select-filter ms-nice-select-property-status" style="visibility: hidden;">
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
        <button class="ms-input__deselect  ms-btn__not-submit">
            <i class="fa-light fa-xmark"></i>
        </button>
    </div>
    <div class="ms-input ms-input--serach ms-hero__search-location-container <?php echo count($selected_cities) > 0 || count($selected_areas) > 0 ? 'ms-input--selected' : ''; ?>">
        <select class="ms-hero__search_city_area" multiple="multiple" style="visibility: hidden;">
            <?php foreach($prop_city as $city_slug => $city_name): ?>
                <option value="<?php echo $city_slug; ?>" data-type="city" <?php if(in_array($city_slug, $selected_cities)) echo 'selected'; ?>><?php echo $city_name; ?></option>
            <?php endforeach; ?>
            <?php foreach($prop_area as $area_slug => $area_data): 
                $area_name = is_array($area_data) ? $area_data['name'] : $area_data;
                $parent_city = is_array($area_data) ? $area_data['parent_city'] : '';
            ?>
                <option value="<?php echo $area_slug; ?>" data-type="area" data-city="<?php echo $parent_city; ?>" <?php if(in_array($area_slug, $selected_areas)) echo 'selected'; ?>><?php echo $area_name; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="ms-hero__search-loaction"
            ><i class="icon-search_black"></i
        ></label>
        <button class="ms-input__deselect  ms-btn__not-submit">
            <i class="fa-light fa-xmark"></i>
        </button>
    </div>
    <div class="ms-input ms-nice-select-filter-container <?php echo count($type) > 0 ? 'ms-input--selected' : ''; ?>">
        <select class="ms-nice-select-filter ms-nice-select-property-type" style="visibility: hidden;">
            <option value="" selected disabled>Property type</option>
            <?php
            $type_disabled = "disabled";
            if($property_type_parent_id == 33)$type_disabled = "";

            $tax_terms = get_terms('property_type', array(
                'hide_empty' => false,
                'parent' => 33,
            ));
            foreach($tax_terms as $term): 
            $page_path = get_page_by_path($term->slug);
            $selected = in_array($term->slug, $type) ? 'selected' : '';
            ?>
            <option data-parent-type="residential" value="<?php echo $term->slug; ?>" <?php echo $selected; ?> <?php echo $type_disabled; ?> ><?php echo $term->name; ?></option>
            <?php endforeach; ?>
            
            <?php
            $type_disabled = "disabled";
            if($property_type_parent_id == 96)$type_disabled = "";

            $tax_terms = get_terms('property_type', array(
                'hide_empty' => false,
                'parent' => 96,
            ));
            foreach($tax_terms as $term): 
            $page_path = get_page_by_path($term->slug);
            $selected = in_array($term->slug, $type) ? 'selected' : '';
            ?>
            <option data-parent-type="new-projects" value="<?php echo $term->slug; ?>" <?php echo $selected; ?> <?php echo $type_disabled; ?> ><?php echo $term->name; ?></option>
            <?php endforeach; ?>

            <?php
            $type_disabled = "disabled";
            if($property_type_parent_id == 19)$type_disabled = "";

            $tax_terms = get_terms('property_type', array(
                'hide_empty' => false,
                'parent' => 19,
            ));
            foreach($tax_terms as $term): 
            $page_path = get_page_by_path($term->slug);
            $selected = in_array($term->slug, $type) ? 'selected' : '';
            ?>
            <option data-parent-type="commercial" value="<?php echo $term->slug; ?>" <?php echo $selected; ?> <?php echo $type_disabled; ?> ><?php echo $term->name; ?></option>
            <?php endforeach; ?>
        </select>
        <button class="ms-input__deselect  ms-btn__not-submit">
            <i class="fa-light fa-xmark"></i>
        </button>
    </div>
    <div class="ms-input ms-input--price d-none d-md-block <?php if($min_price || $max_price)echo 'ms-input--selected'; ?>">
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
    <div class="ms-input ms-input--bed d-none d-md-block <?php if($bed || $bath)echo 'ms-input--selected'; ?>">
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
            <!-- <span class="ms-total-filter-item">05</span> -->
            <span class="ms-total-filter-item">
                <?php
                $total_params = 0;
                foreach($_GET as $param => $value) {
                    if (!empty($value)) {
                        $total_params++;
                    }
                }
                echo sprintf("%02d", max(1, $total_params));
                ?>
            </span>
        </button>
    </div>
    <div style="display: none;">
        <button class="ms-btn ms-btn--primary ms-btn--search">
        <i class="icon-search_black"></i>
        </button>
    </div>
</form>