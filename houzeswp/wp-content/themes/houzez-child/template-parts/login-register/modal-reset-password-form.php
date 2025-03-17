<div class="modal ms-form ms-modal ms-report-modal ms-report-modal--2 fade reset-password-form" id="reset-password-form" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body ms-filter__modal">
                <!-- modal heading -->
                <div class="ms-filter__modal__heading">
                    <h5><?php esc_html_e( 'Reset Password', 'houzez' ); ?></h5>
                    <button class="ms-filter__modal__close close" data-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <!-- modal content -->
                <div class="ms-filter__modal__filter property-form-wrap">
                    <!-- tab controllers -->

                    <div class="ms-report-modal__content">
                        <div id="reset_pass_msg"></div>
                        <p><?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'houzez' ); ?></p>
                        
                        <form class="ms-form__main" method="post" action="#">

                            <div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <input type="text" name="user_login_forgot" value="<?php echo esc_attr($user_name); ?>" placeholder="<?php esc_html_e( 'Enter your username or email', 'houzez' ); ?>" class="ms-hero__search-loaction forgot-password"
                                            id="user_login_forgot" />
                                    </div>
                                </div>
                            </div>

                            <?php wp_nonce_field( 'fave_resetpassword_nonce', 'fave_resetpassword_security' ); ?>

                            <div class="ms-input__content__action">
                                <button class="ms-btn ms-btn--transparent" data-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                                <button type="button" 
                                    class="ms-btn ms-btn--primary" id="houzez_forgetpass">
                                    <?php get_template_part('template-parts/loader'); ?>
                                    <?php esc_html_e( 'Get new password', 'houzez' ); ?>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>