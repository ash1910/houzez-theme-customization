    
    
    <!-- start: Advanced Filter Modal   -->
    <div
      class="modal fade"
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
                  class="active"
                  data-target="#modalBuy"
                  data-toggle="tab"
                >
                  Buy
                </button>
                <button data-target="#modalRent" data-toggle="tab">Rent</button>
                <button data-target="#modalNew_project" data-toggle="tab">
                  New Project
                </button>
                <button
                  id="commercial-tab"
                  data-target="#modalCommercial"
                  data-toggle="tab"
                >
                  Commercial
                </button>
              </div>

              <!-- tab content-->

              <div class="tab-content ms-filter__modal__tab-content">
                <!-- content 1 -->
                <div
                  class="ms-filter__modal__tab-content__single tab-pane fade show active"
                  id="modalBuy"
                >
                  <form class="ms-filter__modal__form">
                    <div class="ms-input__wrapper">
                      <div class="ms-input__wrapper__inner">
                        <div class="ms-input ms-input--serach">
                          <label for="ms-hero__search-loaction"
                            ><i class="icon-search_black"></i
                          ></label>
                          <input
                            type="search"
                            placeholder="Search Location"
                            class="ms-hero__search-loaction"
                            id="ms-hero__search-loaction"
                            autofocus
                          />
                        </div>
                        <button
                          class="ms-inupt__contoller ms-btn ms-btn--primary"
                        >
                          <i class="fa-regular fa-plus"></i> Add
                        </button>
                      </div>
                      <!--  -->
                      <ul class="ms-input__list ms-input__list--search">
                        <li>
                          <button>
                            Dubai Marina <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                        <li>
                          <button>
                            Downtown Dubai <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div
                      class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment"
                    >
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li>
                          <button>
                            <i class="icon-apartment"></i> Apartment
                          </button>
                        </li>
                        <li>
                          <button><i class="icon-apartment"></i> Villa</button>
                        </li>
                        <li>
                          <button><i class="icon-villa"></i> Any</button>
                        </li>
                        <li>
                          <button><i class="icon-duplex"></i> Duplex</button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-penthouse"></i> Penthouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-villa_compound"></i> Villa Compound
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-hotel_apartment"></i> Hotel Apartment
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_plot"></i>Residential
                            Plot
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_floor"></i> Residential
                            Floor
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-townhouse"></i> Townhouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_building"></i>
                            Residential Building
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div class="ms-input__price">
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
                        <div class="slider-range ms-slider-range"></div>
                      </div>
                    </div>

                    <!--  -->

                    <div
                      class="ms-input__content__beds ms-input__content__area"
                    >
                      <h6>Square Footage (sqft)</h6>
                      <div class="ms-input__wrapper__inner">
                        <div>
                          <label for="ms-hero__search-loaction3">Minimum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="0"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction3"
                            />
                          </div>
                        </div>
                        <div>
                          <label for="ms-hero__search-loaction4">Maximum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="Any"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction4"
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Furnish Status</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>All</button></li>
                        <li><button>Furnished</button></li>
                        <li><button>Unfurnished</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Beds</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button class="w-auto">Studio</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Baths</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Parking</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Tour Type</h6>
                      <ul class="ms-input__list">
                        <li><button>Video</button></li>
                        <li>
                          <button class="w-auto">
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
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Floor Plan</h6>
                      <ul class="ms-input__list">
                        <li><button class="w-auto">Included</button></li>
                        <li><button class="w-auto">Not Included</button></li>
                      </ul>
                    </div>
                  </form>
                  <div class="ms-input__content__action">
                    <button class="ms-btn ms-btn--transparent">
                      Reset All
                    </button>
                    <button class="ms-btn ms-btn--primary">Apply</button>
                  </div>
                </div>
                <!-- content 2 -->
                <div
                  class="ms-filter__modal__tab-content__single tab-pane fade"
                  id="modalRent"
                >
                  <form class="ms-filter__modal__form">
                    <div class="ms-input__wrapper">
                      <div class="ms-input__wrapper__inner">
                        <div class="ms-input ms-input--serach">
                          <label for="ms-hero__search-loaction"
                            ><i class="icon-search_black"></i
                          ></label>
                          <input
                            type="search"
                            placeholder="Search Location"
                            class="ms-hero__search-loaction"
                            id="ms-hero__search-loaction"
                            autofocus
                          />
                        </div>
                        <button
                          class="ms-inupt__contoller ms-btn ms-btn--primary"
                        >
                          <i class="fa-regular fa-plus"></i> Add
                        </button>
                      </div>
                      <!--  -->
                      <ul class="ms-input__list ms-input__list--search">
                        <li>
                          <button>
                            Dubai Marina <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                        <li>
                          <button>
                            Downtown Dubai <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div
                      class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment"
                    >
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li>
                          <button>
                            <i class="icon-apartment"></i> Apartment
                          </button>
                        </li>
                        <li>
                          <button><i class="icon-apartment"></i> Villa</button>
                        </li>
                        <li>
                          <button><i class="icon-villa"></i> Any</button>
                        </li>
                        <li>
                          <button><i class="icon-duplex"></i> Duplex</button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-penthouse"></i> Penthouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-villa_compound"></i> Villa Compound
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-hotel_apartment"></i> Hotel Apartment
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_plot"></i>Residential
                            Plot
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_floor"></i> Residential
                            Floor
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-townhouse"></i> Townhouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_building"></i>
                            Residential Building
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Payment Plan</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>Daily</button></li>
                        <li><button>Weekly</button></li>
                        <li><button>Monthly</button></li>
                        <li><button>Yearly</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__price">
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
                        <div class="slider-range ms-slider-range"></div>
                      </div>
                    </div>

                    <!--  -->

                    <div
                      class="ms-input__content__beds ms-input__content__area"
                    >
                      <h6>Square Footage (sqft)</h6>
                      <div class="ms-input__wrapper__inner">
                        <div>
                          <label for="ms-hero__search-loaction3">Minimum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="0"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction3"
                            />
                          </div>
                        </div>
                        <div>
                          <label for="ms-hero__search-loaction4">Maximum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="Any"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction4"
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Furnish Status</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>All</button></li>
                        <li><button>Furnished</button></li>
                        <li><button>Unfurnished</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Beds</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button class="w-auto">Studio</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Baths</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Parking</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Tour Type</h6>
                      <ul class="ms-input__list">
                        <li><button>Video</button></li>
                        <li>
                          <button class="w-auto">
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
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Floor Plan</h6>
                      <ul class="ms-input__list">
                        <li><button class="w-auto">Included</button></li>
                        <li><button class="w-auto">Not Included</button></li>
                      </ul>
                    </div>
                  </form>
                  <div class="ms-input__content__action">
                    <button class="ms-btn ms-btn--transparent">
                      Reset All
                    </button>
                    <button class="ms-btn ms-btn--primary">Apply</button>
                  </div>
                </div>
                <!-- content 3 -->
                <div
                  class="ms-filter__modal__tab-content__single tab-pane fade"
                  id="modalNew_project"
                >
                  <form class="ms-filter__modal__form">
                    <div class="ms-input__wrapper">
                      <div class="ms-input__wrapper__inner">
                        <div class="ms-input ms-input--serach">
                          <label for="ms-hero__search-loaction"
                            ><i class="icon-search_black"></i
                          ></label>
                          <input
                            type="search"
                            placeholder="Search Location"
                            class="ms-hero__search-loaction"
                            id="ms-hero__search-loaction"
                            autofocus
                          />
                        </div>
                        <button
                          class="ms-inupt__contoller ms-btn ms-btn--primary"
                        >
                          <i class="fa-regular fa-plus"></i> Add
                        </button>
                      </div>
                      <!--  -->
                      <ul class="ms-input__list ms-input__list--search">
                        <li>
                          <button>
                            Dubai Marina <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                        <li>
                          <button>
                            Downtown Dubai <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div
                      class="ms-filter__modal__inputs ms-filter__modal__inputs--apartment"
                    >
                      <h6>Property Type</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li>
                          <button>
                            <i class="icon-apartment"></i> Apartment
                          </button>
                        </li>
                        <li>
                          <button><i class="icon-apartment"></i> Villa</button>
                        </li>
                        <li>
                          <button><i class="icon-villa"></i> Any</button>
                        </li>
                        <li>
                          <button><i class="icon-duplex"></i> Duplex</button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-penthouse"></i> Penthouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-villa_compound"></i> Villa Compound
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-hotel_apartment"></i> Hotel Apartment
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_plot"></i>Residential
                            Plot
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_floor"></i> Residential
                            Floor
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-townhouse"></i> Townhouse
                          </button>
                        </li>
                        <li>
                          <button>
                            <i class="icon-residential_building"></i>
                            Residential Building
                          </button>
                        </li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Completion</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>Under construction</button></li>
                        <li><button>Completed</button></li>
                        <li><button>Planned</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Handover</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>Q1 2025</button></li>
                        <li><button>Q2 2025</button></li>
                        <li><button>Q3 2025</button></li>
                        <li><button>Q4 2025</button></li>
                        <li><button>2026</button></li>
                        <li><button>2027</button></li>
                        <li><button>2028</button></li>
                        <li><button>2029</button></li>
                        <li><button>2030</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__price">
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
                        <div class="slider-range ms-slider-range"></div>
                      </div>
                    </div>

                    <!--  -->

                    <div
                      class="ms-input__content__beds ms-input__content__area"
                    >
                      <h6>Square Footage (sqft)</h6>
                      <div class="ms-input__wrapper__inner">
                        <div>
                          <label for="ms-hero__search-loaction3">Minimum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="0"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction3"
                            />
                          </div>
                        </div>
                        <div>
                          <label for="ms-hero__search-loaction4">Maximum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="Any"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction4"
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Beds</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>

                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Baths</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Parking</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Tour Type</h6>
                      <ul class="ms-input__list">
                        <li><button>Video</button></li>
                        <li>
                          <button class="w-auto">
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
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Floor Plan</h6>
                      <ul class="ms-input__list">
                        <li><button class="w-auto">Included</button></li>
                        <li><button class="w-auto">Not Included</button></li>
                      </ul>
                    </div>
                  </form>
                  <div class="ms-input__content__action">
                    <button class="ms-btn ms-btn--transparent">
                      Reset All
                    </button>
                    <button class="ms-btn ms-btn--primary">Apply</button>
                  </div>
                </div>

                <!-- content 4 -->
                <div
                  class="ms-filter__modal__tab-content__single tab-pane fade"
                  id="modalCommercial"
                >
                  <form class="ms-filter__modal__form">
                    <div class="ms-input__wrapper">
                      <div class="ms-input__wrapper__inner">
                        <div class="ms-input ms-input--serach">
                          <label for="ms-hero__search-loaction"
                            ><i class="icon-search_black"></i
                          ></label>
                          <input
                            type="search"
                            placeholder="Search Location"
                            class="ms-hero__search-loaction"
                            id="ms-hero__search-loaction"
                            autofocus
                          />
                        </div>
                        <button
                          class="ms-inupt__contoller ms-btn ms-btn--primary"
                        >
                          <i class="fa-regular fa-plus"></i> Add
                        </button>
                      </div>
                      <!--  -->
                      <ul class="ms-input__list ms-input__list--search">
                        <li>
                          <button>
                            Dubai Marina <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                        <li>
                          <button>
                            Downtown Dubai <i class="fa-light fa-xmark"></i>
                          </button>
                        </li>
                      </ul>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Property Type</h6>
                      <ul
                        class="ms-input__list ms-input__list--auto-width ms-input__list--rounded"
                      >
                        <li><button>Offece</button></li>
                        <li><button>Shop</button></li>
                        <li><button>Warehouse</button></li>
                        <li><button>Labour Camp</button></li>
                        <li><button>Commercial Villa</button></li>
                        <li><button>Bulk Unit</button></li>
                        <li><button>Commercial Plot</button></li>
                        <li><button>Commercial Floor</button></li>
                        <li><button>Commercial Building</button></li>
                        <li><button>Factory</button></li>
                        <li><button>Industrial Land</button></li>

                        <li><button>Mixed Use Land</button></li>
                        <li><button>Showroom</button></li>
                        <li><button>Other Commercial</button></li>
                      </ul>
                    </div>

                    <div class="ms-input__price">
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
                        <div class="slider-range ms-slider-range"></div>
                      </div>
                    </div>

                    <!--  -->

                    <div
                      class="ms-input__content__beds ms-input__content__area"
                    >
                      <h6>Square Footage (sqft)</h6>
                      <div class="ms-input__wrapper__inner">
                        <div>
                          <label for="ms-hero__search-loaction3">Minimum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="0"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction3"
                            />
                          </div>
                        </div>
                        <div>
                          <label for="ms-hero__search-loaction4">Maximum</label>
                          <div class="ms-input ms-input--serach">
                            <input
                              type="search"
                              value="Any"
                              class="ms-hero__search-loaction"
                              id="ms-hero__search-loaction4"
                            />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="ms-input__content__beds">
                      <h6>Furnish Status</h6>
                      <ul class="ms-input__list ms-input__list--auto-width">
                        <li><button>All</button></li>
                        <li><button>Furnished</button></li>
                        <li><button>Unfurnished</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Beds</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button class="w-auto">Studio</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Baths</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Parking</h6>
                      <ul class="ms-input__list">
                        <li><button>Any</button></li>
                        <li><button>1</button></li>
                        <li><button>2</button></li>
                        <li><button>3</button></li>
                        <li><button>4+</button></li>
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Tour Type</h6>
                      <ul class="ms-input__list">
                        <li><button>Video</button></li>
                        <li>
                          <button class="w-auto">
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
                      </ul>
                    </div>
                    <div class="ms-input__content__beds">
                      <h6>Floor Plan</h6>
                      <ul class="ms-input__list">
                        <li><button class="w-auto">Included</button></li>
                        <li><button class="w-auto">Not Included</button></li>
                      </ul>
                    </div>
                  </form>
                  <div class="ms-input__content__action">
                    <button class="ms-btn ms-btn--transparent">
                      Reset All
                    </button>
                    <button class="ms-btn ms-btn--primary">Apply</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>