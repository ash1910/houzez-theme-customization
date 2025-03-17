<?php 
global $post, $random_token, $listing_agent_info, $current_user; 
$agent_email = $listing_agent_info['agent_email'] ?? '';
$agent_email = is_email( $agent_email );

$terms_page_id = houzez_option('terms_condition');
$terms_page_id = apply_filters( 'wpml_object_id', $terms_page_id, 'page', true );

$hide_form_fields = houzez_option('hide_prop_contact_form_fields');
$agent_display = houzez_get_listing_data('agent_display_option');
$property_id = houzez_get_listing_data('property_id');
$gdpr_checkbox = houzez_option('gdpr_hide_checkbox', 1);

$user_name = $user_email = '';
if(!houzez_is_admin()) {
    $user_name =  $current_user->display_name;
    $user_email =  $current_user->user_email;
}

if( ! empty( $agent_email ) ) {
?>
<!-- start: Email Modal   -->
<div class="modal ms-form ms-modal mobile-property-form ms-report-modal ms-report-modal--2 fade" id="email-popup-<?php echo esc_attr($post->ID).'-'.$random_token; ?>" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body ms-filter__modal">
                <!-- modal heading -->
                <div class="ms-filter__modal__heading">
                    <h5>Email Agency</h5>
                    <button class="ms-filter__modal__close close" data-dismiss="modal" aria-label="Close">
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
                <!-- modal content -->
                <div class="ms-filter__modal__filter property-form-wrap">
                    <!-- tab controllers -->

                    <div class="ms-report-modal__content">
                        <form class="ms-form__main" method="post" action="#">

                            <div class="ms-input__wrapper">
                                <label class="ms-input__label" for="ms-contact__name-<?php echo esc_attr($post->ID); ?>">Name</label>
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <input type="text" name="name" value="<?php echo esc_attr($user_name); ?>" placeholder="Enter Your Name" class="ms-hero__search-loaction"
                                            id="ms-contact__name-<?php echo esc_attr($post->ID); ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="ms-input__wrapper phone-field-wrapper" style="display: none;">
                                <label class="ms-input__label phone-field-label" for="ms-contact__phone-<?php echo esc_attr($post->ID); ?>">Phone Number</label>
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <select name="country_code" class="ms-nice-select ms-nice-select__country-code ms-nice-select--phone">
                                            <option value="" selected>
                                                +971
                                            </option>
                                        </select>

                                        <svg class="ms-nice-select__index" width="9" height="13" viewBox="0 0 9 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.79501 3.81734L6.73076 1.75309L5.47035 0.486247C4.9366 -0.047499 4.06846 -0.047499 3.53472 0.486247L0.203629 3.81734C-0.233656 4.25462 0.0814466 5.00058 0.692361 5.00058H4.29997H8.30628C8.92362 5.00058 9.2323 4.25462 8.79501 3.81734Z"
                                                fill="#868686" />
                                            <path
                                                d="M8.79501 9.18389L6.73076 11.2481L5.47035 12.515C4.9366 13.0487 4.06846 13.0487 3.53472 12.515L0.203629 9.18389C-0.233656 8.7466 0.0814466 8.00064 0.692361 8.00064H4.29997H8.30628C8.92362 8.00064 9.2323 8.7466 8.79501 9.18389Z"
                                                fill="#868686" />
                                        </svg>
                                    </div>
                                    <div class="ms-input ms-input--serach">
                                        <input type="text" placeholder="Enter Phone Number" class="ms-hero__search-loaction phone-field"
                                            id="ms-contact__phone-<?php echo esc_attr($post->ID); ?>" name="mobile" />
                                    </div>
                                </div>
                            </div>

                            <div class="ms-input__wrapper email-field-wrapper">
                                <label class="ms-input__label" for="ms-contact__email-<?php echo esc_attr($post->ID); ?>">Email Address</label>
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <input type="email" name="email" value="<?php echo esc_attr($user_email); ?>" placeholder="Enter Email Address" class="ms-hero__search-loaction"
                                            id="ms-contact__email-<?php echo esc_attr($post->ID); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="ms-input__wrapper message-field-wrapper" style="display: none;">
                                <label class="ms-input__label" for="ms-contact__message-<?php echo esc_attr($post->ID); ?>">Message</label>
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <textarea type="text" name="message" placeholder="Type your message here..." class="ms-hero__search-loaction"
                                            id="ms-contact__message-<?php echo esc_attr($post->ID); ?>" rows="3"><?php echo houzez_option('spl_con_interested', "Hello, I am interested in"); ?> [<?php echo get_the_title(); ?>]
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-input ms-input--agree">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy" id="msagree-<?php echo esc_attr($post->ID); ?>" checked="">
                                    <label class="form-check-label" for="msagree-<?php echo esc_attr($post->ID); ?>">
                                        <span>
                                            By clicking <span class="accept_text">submit</span> button you accept our <a target="_blank" href="<?php echo esc_url(get_permalink($terms_page_id)); ?>" class="ms-form__forgot-pass">Privacy
                                                Policy</a>.
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <?php if ( $listing_agent_info['is_single_agent'] == true ) : ?>
                                <input type="hidden" name="target_email" value="<?php echo antispambot($agent_email); ?>">
                            <?php endif; ?>
                            <input type="hidden" name="property_agent_contact_security" value="<?php echo wp_create_nonce('property_agent_contact_nonce'); ?>"/>
                            <input type="hidden" name="property_permalink" value="<?php echo esc_url(get_permalink($post->ID)); ?>"/>
                            <input type="hidden" name="property_title" value="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
                            <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
                            <input type="hidden" name="action" value="houzez_property_agent_contact">
                            <input type="hidden" name="listing_id" value="<?php echo intval($post->ID)?>">
                            <input type="hidden" name="is_listing_form" value="yes">
                            <input type="hidden" name="agent_id" value="<?php echo intval($listing_agent_info['agent_id'])?>">
                            <input type="hidden" name="agent_type" value="<?php echo esc_attr($listing_agent_info['agent_type'])?>">
                            <input type="hidden" name="data_type" value="" class="form-data-type">
                            <input type="hidden" value="" class="form-link">
                            
                            <div class="form_messages"></div>
                            <div class="ms-input__content__action">
                                <button class="ms-btn ms-btn--transparent" data-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                                <button type="button" 
                                    class="ms-btn ms-btn--primary houzez_agent_property_form">
                                    <?php get_template_part('template-parts/loader'); ?>
                                    <span class="submit_btn_text">Submit</span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:  Email Modal    -->
<?php } ?>