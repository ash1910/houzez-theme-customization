<?php 
    $footer_logo = houzez_option( 'footer_logo', false, 'url' ); 
    $google_app_logo = houzez_option( 'google_app_logo', false, 'url' );
    $ios_app_logo = houzez_option( 'ios_app_logo', false, 'url' );
?>    

    <!-- start: Footer   -->
    <footer>
      <div class="ms-footer <?php if ( is_home() || is_front_page() ){}else{ echo 'ms-footer--2'; } ?>">
        <div class="container">
          <div class="row">
            <!-- footer about -->
            <div class="col-12 col-lg-6">
              <!-- footer logo -->
              <div class="ms-logo">
                <?php if(!empty($footer_logo)) { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                  ><img src="<?php echo esc_url($footer_logo); ?>" alt=""
                /></a>	
                <?php } ?>
              </div>
              <?php if( houzez_option('footer_description') != '' ) { ?>
              <p class="ms-footer__desc">
                <?php echo houzez_option('footer_description'); ?>
              </p>
              <?php } ?>
              <!-- subscription -->
              <form class="ms-footer__form" onsubmit="return validateInput()" action="<?php echo esc_url(home_url('/')); ?>/search-results/" method="get">
                
                <div class="ms-input ms-input--white">
                  <?php get_template_part('template-parts/search/fields/keyword'); ?>
                </div>

                <div>
                  <button class="ms-btn ms-btn--primary" onclick="return validateInput()">
                    <i class="fa-regular fa-arrow-right-long"></i>
                  </button>
                </div>
                <script>
                  function validateInput() {
                    const textInput = document.querySelector('.ms-footer__form .houzez-keyword-autocomplete').value.trim();
                    if (textInput.length === 0) {
                      alert('Please enter at least one character.');
                      return false; // Prevent form submission
                    }
                    return true; // Allow form submission
                  }
                </script>
              </form>
              <!-- socials media -->
              <?php if( houzez_option('social-footer') != '0' ) { ?>
              <ul class="ms-social-media">
                <?php if( houzez_option('fs-facebook') != '' ){ ?>
                <li>
                  <a
                    class="ms-social-media__link"
                    href="<?php echo esc_url(houzez_option('fs-facebook')); ?>"
                    ><i class="fa-brands fa-facebook-f"></i
                  ></a>
                </li>
                <?php } ?>
                <?php if( houzez_option('fs-instagram') != '' ){ ?>
                <li>
                  <a
                    class="ms-social-media__link"
                    href="<?php echo esc_url(houzez_option('fs-instagram')); ?>"
                    ><i class="fa-brands fa-instagram"></i
                  ></a>
                </li>
                <?php } ?>
                <?php if( houzez_option('fs-linkedin') != '' ){ ?>
                <li>
                  <a
                    class="ms-social-media__link"
                    href="<?php echo esc_url(houzez_option('fs-linkedin')); ?>"
                    ><i class="fa-brands fa-linkedin-in"></i
                  ></a>
                </li>
                <?php } ?>
                <?php if( houzez_option('fs-twitter') != '' ){ ?>
                <li>
                  <a class="ms-social-media__link" href="<?php echo esc_url(houzez_option('fs-twitter')); ?>"
                    ><i class="fa-brands fa-x-twitter"></i
                  ></a>
                </li>
                <?php } ?>
              </ul>
              <?php } ?>
              <!-- location -->
              <div class="ms-footer__location" style="visibility: hidden;">
                <label for="footer-location">
                  Country:
                  <img style="visibility: hidden;" class="ms-footer__location__flag" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/footer/saudi-arabia.png" alt=""
                /></label>
                <?php 
                if (shortcode_exists('language-switcher')) {
                  echo do_shortcode('[language-switcher]'); 
                }?>
              <script>
							document.addEventListener('DOMContentLoaded', () => {
								const languageContainerDiv = document.querySelector('.ms-footer__location');
								const languageDiv = languageContainerDiv.querySelector('.trp-ls-shortcode-language');
								if (languageDiv) {
									const links = languageDiv.querySelectorAll('a');
									const select = document.createElement('select');
									select.className = 'ms-nice-select';
									select.name = 'input-lang';
									select.id = 'input-lang';

									links.forEach(link => {
										const option = document.createElement('option');
										option.value = link.getAttribute('href');
										option.textContent = link.textContent;

                    let flagURL = '';
                    if (link.querySelector('.trp-flag-image')) {
                      flagURL = link.querySelector('.trp-flag-image').getAttribute('src');
                      option.setAttribute("data-flag", flagURL);
                    }

										// If the link has "onclick" to prevent default, disable the option
										if (link.getAttribute('onclick')) {
											option.disabled = true;
											option.selected = true;

                      const languageContainerFlag = languageContainerDiv.querySelector(".ms-footer__location__flag");
                      if (flagURL  && languageContainerFlag) {
                        languageContainerFlag.setAttribute('src', flagURL);
                        languageContainerFlag.style.visibility = 'visible';
                      }
										}
										select.appendChild(option);
									});

									// Replace the original div with the new <select>
									languageContainerDiv.querySelector('.trp_language_switcher_shortcode').replaceWith(select);
									if (typeof jQuery !== 'undefined') {
										jQuery(select).niceSelect();
                    languageContainerDiv.style.visibility = 'visible';

										// Add event listener to handle redirection
										jQuery(select).on('change', function () {
											const selectedUrl = jQuery(this).val();
                      const selectedFlag = jQuery(this).find(':selected').data('flag');
											if (selectedUrl && selectedUrl !== '#') {
                        languageContainerDiv.querySelector(".ms-footer__location__flag").setAttribute('src', selectedFlag);
												window.location.href = selectedUrl;
											}
										});
									}
								}
							});
						</script>
              </div>
            </div>

            <!-- footer navigations -->
            <div class="col-12 col-lg-6">
              <div class="ms-footer__nav__wrapper">
                <div class="row">
                  <div class="col-12 col-xl-6">
                    <?php if( has_nav_menu( 'main-menu-right' ) ) { ?>
                    <h6>Quick Links</h6>
                    <div class="ms-footer__nav">
                      <?php
                      add_filter('nav_menu_link_attributes', function($atts) {
                          $atts['class'] = 'ms-footer__nav__link';
                          return $atts;
                      });
                      wp_nav_menu( array (
                        'theme_location' => 'main-menu-right',
                        'container' => '',
                        'container_class' => '',
                        'menu_class' => 'ms-footer__nav__list ms-footer__nav__list--2',
                      ));
                      ?>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="col-12 col-xl-6">
                    <?php if( houzez_option('footer_contact_text') != '' ) { ?>
                    <h6><?php echo houzez_option('footer_contact_text'); ?></h6>
                    <?php }?>
                    <div class="ms-footer__nav">
                      <ul class="ms-footer__nav__list">
                        <?php if( houzez_option('footer_contact_phone') != '' ) { ?>
                        <li>
                          <a class="ms-footer__nav__link" href="tel:<?php echo houzez_option('footer_contact_phone'); ?>">
                            <svg
                              width="25"
                              height="25"
                              viewBox="0 0 25 25"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M21.7669 20.0097C21.7669 20.3697 21.6869 20.7397 21.5169 21.0997C21.3469 21.4597 21.1269 21.7997 20.8369 22.1197C20.3469 22.6597 19.8069 23.0497 19.1969 23.2997C18.5969 23.5497 17.9469 23.6797 17.2469 23.6797C16.2269 23.6797 15.1369 23.4397 13.9869 22.9497C12.8369 22.4597 11.6869 21.7997 10.5469 20.9697C9.39688 20.1297 8.30687 19.1997 7.26687 18.1697C6.23687 17.1297 5.30687 16.0397 4.47687 14.8997C3.65687 13.7597 2.99688 12.6197 2.51688 11.4897C2.03688 10.3497 1.79688 9.25969 1.79688 8.21969C1.79688 7.53969 1.91687 6.88969 2.15687 6.28969C2.39687 5.67969 2.77688 5.11969 3.30687 4.61969C3.94687 3.98969 4.64688 3.67969 5.38688 3.67969C5.66688 3.67969 5.94688 3.73969 6.19688 3.85969C6.45688 3.97969 6.68688 4.15969 6.86688 4.41969L9.18688 7.68969C9.36688 7.93969 9.49687 8.16969 9.58687 8.38969C9.67687 8.59969 9.72688 8.80969 9.72688 8.99969C9.72688 9.23969 9.65688 9.47969 9.51688 9.70969C9.38688 9.93969 9.19687 10.1797 8.95687 10.4197L8.19687 11.2097C8.08687 11.3197 8.03687 11.4497 8.03687 11.6097C8.03687 11.6897 8.04688 11.7597 8.06688 11.8397C8.09688 11.9197 8.12688 11.9797 8.14688 12.0397C8.32688 12.3697 8.63687 12.7997 9.07687 13.3197C9.52687 13.8397 10.0069 14.3697 10.5269 14.8997C11.0669 15.4297 11.5869 15.9197 12.1169 16.3697C12.6369 16.8097 13.0669 17.1097 13.4069 17.2897C13.4569 17.3097 13.5169 17.3397 13.5869 17.3697C13.6669 17.3997 13.7469 17.4097 13.8369 17.4097C14.0069 17.4097 14.1369 17.3497 14.2469 17.2397L15.0069 16.4897C15.2569 16.2397 15.4969 16.0497 15.7269 15.9297C15.9569 15.7897 16.1869 15.7197 16.4369 15.7197C16.6269 15.7197 16.8269 15.7597 17.0469 15.8497C17.2669 15.9397 17.4969 16.0697 17.7469 16.2397L21.0569 18.5897C21.3169 18.7697 21.4969 18.9797 21.6069 19.2297C21.7069 19.4797 21.7669 19.7297 21.7669 20.0097Z"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-miterlimit="10"
                              />
                              <path
                                d="M18.2969 10.6797C18.2969 10.0797 17.8269 9.15969 17.1269 8.40969C16.4869 7.71969 15.6369 7.17969 14.7969 7.17969"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                              <path
                                d="M21.7969 10.6797C21.7969 6.80969 18.6669 3.67969 14.7969 3.67969"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                            </svg>
                            <?php echo houzez_option('footer_contact_phone'); ?>
                          </a>
                        </li>
                        <?php }?>
                        <?php if( houzez_option('footer_contact_address') != '' ) { ?>
                        <li>
                          <p class="ms-footer__nav__link" style="white-space: pre-wrap;">
                            <svg
                              width="24"
                              height="25"
                              viewBox="0 0 24 25"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M12.0028 14.4089C13.7259 14.4089 15.1228 13.0121 15.1228 11.2889C15.1228 9.56582 13.7259 8.16895 12.0028 8.16895C10.2797 8.16895 8.88281 9.56582 8.88281 11.2889C8.88281 13.0121 10.2797 14.4089 12.0028 14.4089Z"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                              />
                              <path
                                d="M3.61776 9.46852C5.58776 0.808518 18.4178 0.818518 20.3778 9.47852C21.5278 14.5585 18.3678 18.8585 15.5978 21.5185C13.5878 23.4585 10.4078 23.4585 8.38776 21.5185C5.62776 18.8585 2.46776 14.5485 3.61776 9.46852Z"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                              />
                            </svg><?php echo houzez_option('footer_contact_address'); ?></p>
                        </li>
                        <?php }?>
                        <?php if( houzez_option('footer_contact_email') != '' ) { ?>
                        <li>
                          <a class="ms-footer__nav__link" href="mailto:<?php echo houzez_option('footer_contact_email'); ?>">
                            <svg
                              width="24"
                              height="25"
                              viewBox="0 0 24 25"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M17 20.9785H7C4 20.9785 2 19.4785 2 15.9785V8.97852C2 5.47852 4 3.97852 7 3.97852H17C20 3.97852 22 5.47852 22 8.97852V15.9785C22 19.4785 20 20.9785 17 20.9785Z"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-miterlimit="10"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                              <path
                                d="M17 9.47852L13.87 11.9785C12.84 12.7985 11.15 12.7985 10.12 11.9785L7 9.47852"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-miterlimit="10"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                            </svg>
                            <?php echo houzez_option('footer_contact_email'); ?>
                            </a>
                        </li>
                        <?php }?>
                        <?php if( houzez_option('footer_contact_time') != '' ) { ?>
                        <li>
                          <p class="ms-footer__nav__link" style="white-space: pre-wrap;">
                            <svg
                              width="24"
                              height="25"
                              viewBox="0 0 24 25"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M22 12.9785C22 18.4985 17.52 22.9785 12 22.9785C6.48 22.9785 2 18.4985 2 12.9785C2 7.45852 6.48 2.97852 12 2.97852C17.52 2.97852 22 7.45852 22 12.9785Z"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                              <path
                                d="M15.7128 16.1583L12.6128 14.3083C12.0728 13.9883 11.6328 13.2183 11.6328 12.5883V8.48828"
                                stroke="#1B1B1B"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                            </svg><?php echo houzez_option('footer_contact_time'); ?></p>
                        </li>
                        <?php }?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- footer barnd -->
              <?php if( houzez_option('mobile_app') != '0' ) { ?>
              <div class="ms-footer__brand">
                <?php if( houzez_option('mobile_app_text') != '' ){ ?>
                <h6><?php echo houzez_option('mobile_app_text'); ?></h6>
                <?php }?>
                <ul class="ms-footer__brand__list">
                  <?php if( houzez_option('google_app_url') != '' && $google_app_logo != '' ){ ?> 
                  <li>
                    <a href="<?php echo esc_url(houzez_option('google_app_url')); ?>"
                      ><img src="<?php echo esc_url($google_app_logo); ?>" alt=""
                    /></a>
                  </li>
                  <?php }?>
                  <?php if( houzez_option('ios_app_url') != '' && $ios_app_logo != '' ){ ?>
                  <li>
                    <a href="<?php echo esc_url(houzez_option('ios_app_url')); ?>"
                      ><img src="<?php echo esc_url($ios_app_logo); ?>" alt=""
                    /></a>
                  </li>
                  <?php }?>
                </ul>
              </div>
              <?php }?>
            </div>
          </div>
        </div>

        <!-- footer copyright -->
        <div class="ms-footer__copyright__wrapper">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="ms-footer__copyright">
                  <div class="ms-footer__copyright__inner">
                  <?php if( houzez_option('copy_rights') != '' ) { ?>
                    <div class="ms-footer__copyright__text">
                      &copy; <?php echo houzez_option('copy_rights'); ?>
                    </div>
                  <?php } ?>
                  <?php
                    if ( has_nav_menu( 'footer-menu' ) ) :
                    add_filter('nav_menu_link_attributes', function($atts) {
                        $atts['class'] = 'ms-footer__nav__link';
                        return $atts;
                    });
                    wp_nav_menu( array (
                      'theme_location' => 'footer-menu',
                      'container' => '',
                      'container_class' => '',
                      'menu_class' => 'ms-footer__nav__list ms-footer__nav__list--copyright',
                    ));
                    endif;
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- end:  Footer  -->