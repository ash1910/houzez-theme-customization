      <?php 
        $settings = get_query_var('settings');
        $logo = $settings['logo'];
        $title = $settings['title'];
        $description = $settings['description'];
        $slides = $settings['slides'];
      ?>
      
      <!-- start: Login  -->
      <section>
        <div class="ms-form">
          <div class="container-fluid container-fluid--sm">
            <div class="row align-items-center">
              <!-- form -->
              <div class="col-12 col-md-5">
                <div class="ms-form__inner">
                <?php if( !is_user_logged_in() || \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                  <div class="ms-form__heading">
                    <!-- logo -->
                    <?php if ( ! empty( $logo ) ) : ?>
                      <a href="<?php echo home_url(); ?>" class="ms-logo"
                        ><img src="<?php echo $logo['url']; ?>" alt=""
                      /></a>
                    <?php endif; ?>
                    <?php if ( ! empty( $title ) ) : ?>
                      <?php echo $title; ?>
                    <?php else : ?>
                      <h2>welcome <span>back!</span></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $description ) ) : ?>
                      <?php echo $description; ?>
                    <?php endif; ?>
                  </div>
        
                  <div id="hz-login-messages" class="hz-social-messages"></div>
                  <form class="ms-form__main">
                    <div class="ms-input ms-input--serach username-field">
                      <input type="email" name="username" placeholder="<?php esc_html_e('Username or Email','houzez'); ?>" />
                    </div>
                    <div class="ms-input ms-input--search">
                      <input type="password" class="ms-input__password" name="password" placeholder="<?php esc_html_e('Password','houzez'); ?>" />

                      <span class="ms-input__password-toggler">
                        <!-- Eye Open (Show Password) -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="24" width="24"
                          class="ms-hide-icon">
                          <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" stroke="#8B8B8B"
                            d="M12 4C7 4 3 8 1.5 12C3 16 7 20 12 20C17 20 21 16 22.5 12C21 8 17 4 12 4Z"></path>
                          <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" stroke="#8B8B8B"
                            d="M12 16C14.21 16 16 14.21 16 12C16 9.79 14.21 8 12 8C9.79 8 8 9.79 8 12C8 14.21 9.79 16 12 16Z">
                          </path>
                        </svg>

                        <!-- Eye Slash (Hide Password) -->
                        <svg class="ms-show-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          xmlns="http://www.w3.org/2000/svg" style="display: none;">
                          <path
                            d="M14.5319 9.46992L9.47188 14.5299C8.82188 13.8799 8.42188 12.9899 8.42188 11.9999C8.42188 10.0199 10.0219 8.41992 12.0019 8.41992C12.9919 8.41992 13.8819 8.81992 14.5319 9.46992Z"
                            stroke="#8B8B8B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path
                            d="M17.8198 5.76998C16.0698 4.44998 14.0698 3.72998 11.9998 3.72998C8.46984 3.72998 5.17984 5.80998 2.88984 9.40998C1.98984 10.82 1.98984 13.19 2.88984 14.6C3.67984 15.84 4.59984 16.91 5.59984 17.77"
                            stroke="#8B8B8B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path
                            d="M8.42188 19.5299C9.56187 20.0099 10.7719 20.2699 12.0019 20.2699C15.5319 20.2699 18.8219 18.1899 21.1119 14.5899C22.0119 13.1799 22.0119 10.8099 21.1119 9.39993C20.7819 8.87993 20.4219 8.38993 20.0519 7.92993"
                            stroke="#8B8B8B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path d="M15.5114 12.7C15.2514 14.11 14.1014 15.26 12.6914 15.52" stroke="#8B8B8B"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          <path d="M9.47 14.53L2 22" stroke="#8B8B8B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                          <path d="M22.0013 2L14.5312 9.47" stroke="#8B8B8B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        </svg>
                      </span>
                    </div>
                    <div class="ms-input ms-input--agree">
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          value=""
                          id="msagree"
                          checked
                        />
                        <label class="form-check-label" for="msagree">
                          Remember me
                        </label>
                      </div>

                      <a class="ms-form__forgot-pass"
                        href="#" data-toggle="modal" data-target="#reset-password-form" data-dismiss="modal"
                        >Forgot Password?</a
                      >
                    </div>
                    <?php get_template_part('template-parts/google', 'reCaptcha'); ?>
                    <div class="ms-form__submit">
                      <?php wp_nonce_field( 'houzez_login_nonce', 'houzez_login_security' ); ?>
                      <input type="hidden" name="action" id="login_action" value="houzez_login">
                      <input type="hidden" name="redirect_to" value="<?php echo esc_url(houzez_after_login_redirect()); ?>">
                      <button type="submit" class="ms-btn" id="houzez-login-btn">
                        Login
                        <i class="fa-regular fa-arrow-right-long"></i>
                      </button>
                    </div>
                  </form>

                  <!-- other sign in -->
                  <div class="ms-form__other-login">
                    
                    <?php if( houzez_option('facebook_login') == 'yes' || houzez_option('google_login') == 'yes' ) { ?>
                      <p class="ms-form__other-login__title">Or Sign In With</p>
                      <div class="ms-fomr__other-login__buttons">
                      <?php if( houzez_option('google_login') == 'yes' ) { ?>
                      <button class="ms-btn ms-btn--bordered">
                        <svg
                          width="33"
                          height="32"
                          viewBox="0 0 33 32"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M15.4141 8.12011C17.265 8.11897 19.0565 8.77304 20.4713 9.96643L24.4342 6.19259C22.913 4.78987 21.0857 3.76104 19.0974 3.18773C17.1092 2.61442 15.0147 2.51243 12.9801 2.88983C10.9456 3.26724 9.02699 4.11367 7.37664 5.36194C5.72629 6.6102 4.3896 8.22595 3.47266 10.081L7.94031 13.5246C8.46134 11.9534 9.46351 10.586 10.8049 9.61601C12.1462 8.64604 13.7588 8.1227 15.4141 8.12011Z"
                            fill="#D94F3D"
                          />
                          <path
                            d="M7.53946 15.9975C7.54058 15.1569 7.67684 14.322 7.94302 13.5247L3.47537 10.0811C2.56151 11.9194 2.08594 13.9445 2.08594 15.9975C2.08594 18.0505 2.56151 20.0756 3.47537 21.914L7.94302 18.4704C7.67684 17.6731 7.54058 16.8381 7.53946 15.9975Z"
                            fill="#F2C042"
                          />
                          <path
                            d="M28.2015 13.5737H15.4766V19.0273H22.6873C22.2584 20.5692 21.2756 21.8988 19.9272 22.7611L24.3604 26.1787C27.1932 23.6361 28.8577 19.5017 28.2015 13.5737Z"
                            fill="#5085ED"
                          />
                          <path
                            d="M19.9235 22.7614C18.5535 23.5456 16.9916 23.9313 15.4141 23.8752C13.7588 23.8726 12.1462 23.3492 10.8049 22.3792C9.46351 21.4093 8.46134 20.0419 7.94031 18.4707L3.47266 21.9143C4.57617 24.1388 6.27847 26.0113 8.38807 27.3211C10.4977 28.631 12.9309 29.3262 15.4141 29.3287C18.6816 29.4174 21.8665 28.2952 24.3566 26.1778L19.9235 22.7614Z"
                            fill="#57A75C"
                          />
                        </svg>
                        Google
                      </button>
                      <?php } ?>
                      <?php if( houzez_option('facebook_login') == 'yes' ) { ?>
                      <button class="ms-btn ms-btn--bordered">
                        <svg
                          width="33"
                          height="32"
                          viewBox="0 0 33 32"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g clip-path="url(#clip0_1361_1114)">
                            <path
                              d="M22.7474 0C21.0414 0.117999 19.0474 1.20999 17.8854 2.63198C16.8254 3.92198 15.9534 5.83797 16.2934 7.69995C18.1574 7.75795 20.0834 6.63996 21.1994 5.19397C22.2434 3.84798 23.0334 1.94399 22.7474 0Z"
                              fill="#1B1B1B"
                            />
                            <path
                              d="M29.4891 10.7362C27.8511 8.68223 25.5492 7.49023 23.3752 7.49023C20.5052 7.49023 19.2912 8.86423 17.2972 8.86423C15.2412 8.86423 13.6792 7.49423 11.1972 7.49423C8.75925 7.49423 6.16326 8.98423 4.51727 11.5322C2.20329 15.1202 2.59928 21.8661 6.34926 27.6121C7.69125 29.6681 9.48324 31.9801 11.8272 32.0001C13.9132 32.0201 14.5012 30.6621 17.3272 30.6481C20.1532 30.6321 20.6892 32.0181 22.7712 31.9961C25.1172 31.9781 27.0071 29.4161 28.3491 27.3601C29.3111 25.8861 29.6691 25.1441 30.4151 23.4801C24.9892 21.4142 24.1192 13.6982 29.4891 10.7362Z"
                              fill="#1B1B1B"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1361_1114">
                              <rect
                                width="32"
                                height="32"
                                fill="white"
                                transform="translate(0.75)"
                              />
                            </clipPath>
                          </defs>
                        </svg>
                        Apple
                      </button>
                      <?php } ?>
                    </div>
                    <?php } ?>
                    <!-- suggestion -->
                    <div class="ms-form__suggestion">
                      Don’t have an account? <a href="<?php echo home_url(); ?>/register">Sign Up</a>
                    </div>
                  </div>
                  <?php } else { ?>
                    <div class="ms-form__heading">
                      <?php if ( ! empty( $logo ) ) : ?>
                      <a href="<?php echo home_url(); ?>" class="ms-logo"
                        ><img src="<?php echo $logo['url']; ?>" alt=""
                      /></a>
                      <?php endif; ?>
                      <h2>You are already <span>logged in!</span></h2>
                      <p>You can go to your <a href="<?php echo home_url(); ?>/board">Dashboard</a></p>
                    </div>
                  <?php } ?>

                </div>
              </div>
              <!-- slider -->
              <div class="col-12 col-md-7 d-none d-md-block">
                <div class="ms-form__slider__wrapper">
                  <div class="ms-form__slider swiper">
                    <div class="swiper-wrapper">
                      <?php foreach ( $slides as $slide ) : ?>
                      <div class="swiper-slide ms-form__slide">
                        <img src="<?php echo $slide['slide_image']['url']; ?>" alt="" />
                        <div class="ms-form__heading">
                          <h1><?php echo $slide['slide_heading']; ?></h1>
                          <p><?php echo $slide['slide_description']; ?></p>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- end:  Login  -->

      <?php get_template_part('template-parts/login-register/modal-reset-password-form'); ?>

      <script>
        // form slider
        function useFormSlider() {
          var formSlider = new Swiper(".ms-form__slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            loop: true,
            autoplay: {
              delay: 10000,
            },
          });
        }
        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {?>
          useFormSlider();
        <?php } else { ?>
          jQuery(document).ready(function($) {
            useFormSlider();
          });
        <?php } ?>
      </script>