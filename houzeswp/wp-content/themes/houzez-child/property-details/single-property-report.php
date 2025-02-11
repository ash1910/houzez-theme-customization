<!-- start: Report Modal   -->

<div class="modal ms-modal ms-report-modal fade" id="msReportModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body ms-filter__modal">
                <!-- modal heading -->
                <div class="ms-filter__modal__heading">
                    <h5>Filters</h5>
                    <button class="ms-filter__modal__close close" data-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <!-- modal content -->
                <div class="ms-filter__modal__filter">
                    <!-- tab controllers -->

                    <div class="ms-report-modal__content">
                        <form class="ms-filter__modal__form">
                            <div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <select class="ms-nice-select">
                                            <option value="" selected disabled>
                                                Select a Problem
                                            </option>
                                            <option value="braning">Residential</option>
                                            <option value="web">Commercial</option>
                                            <option value="uxui">Land</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <input type="text" placeholder="Name" class="ms-hero__search-loaction"
                                            id="ms-hero__search-loaction" />
                                    </div>
                                </div>
                            </div>
                            <div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <input type="email" placeholder="Email" class="ms-hero__search-loaction"
                                            id="ms-hero__search-loaction" />
                                    </div>
                                </div>
                            </div>
                            <div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <textarea type="text" placeholder="Description" class="ms-hero__search-loaction"
                                            id="ms-hero__search-loaction" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="ms-input__content__action">
                            <button class="ms-btn ms-btn--transparent" data-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                            <button data-target="#msReportSubmit" data-toggle="modal" data-dismiss="modal" aria-label="Close"
                                class="ms-btn ms-btn--primary">
                                Send Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- start: Report Submit Modal   -->

<div class="modal ms-modal ms-report-modal ms-report-modal--submit fade" id="msReportSubmit" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body ms-filter__modal">
                <!-- modal heading -->
                <div class="ms-filter__modal__heading">
                    <h5>Report Sent</h5>
                    <button class="ms-filter__modal__close close" data-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <!-- modal content -->
                <div class="ms-filter__modal__filter">
                    <!-- tab controllers -->
                    <div class="ms-filter__modal__form">
                        <div class="ms-report-modal__content">
                            <div>
                                <img src="./assets/img/apartments/sucess.png" alt="" />
                            </div>
                            <h6>Report sent successfully</h6>
                            <p>
                                Thank you for taking the time to report this property! Our
                                quality assurance team will look into your request and take
                                appropriate action.
                            </p>
                        </div>
                    </div>
                    <div class="ms-input__content__action">
                        <button class="ms-btn ms-btn--primary" data-dismiss="modal" aria-label="Close">
                            Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>