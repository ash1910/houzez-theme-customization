<?php
  $settings = get_query_var('settings', []);

  $title = $settings['title'];  
  $description = $settings['description'];
  $whatsapp_button_text = $settings['whatsapp_button_text'];
  $whatsapp_button_link = $settings['whatsapp_button_link'];
  $mailing_list_button_text = $settings['mailing_list_button_text'];
  $mailing_list_button_link = $settings['mailing_list_button_link'];
  $image = $settings['image'];
?>

      
      <!-- start: Offer  -->
      <section class="ms-offer section--wrapper">
        <div class="ms-offer__shape">
          <div class="ms-offer__shape__inner"></div>
        </div>
        <div class="ms-offer__inner">
          <div class="container">
            <div class="row">
              <!-- section heading -->
              <div class="col-12 col-lg-5 col-xl-7">
                <div class="ms-section-heading">
                  <?php if ( ! empty( $title ) ) : ?>
                    <h2><?php echo $title; ?></h2>
                  <?php endif; ?>
                  <?php if ( ! empty( $description ) ) : ?>
                      <?php echo $description; ?>
                  <?php endif; ?>

                  
                    <div class="ms-offer__action">
                    <?php if ( ! empty( $whatsapp_button_text ) && ! empty( $whatsapp_button_link ) ) : ?>
                      <a
                        href="<?php echo $whatsapp_button_link; ?>"
                        class="ms-btn ms-btn--primary"
                        target="_blank"
                      >
                      <svg
                        width="32"
                        height="32"
                        viewBox="0 0 32 32"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <g clip-path="url(#clip0_2974_2437)">
                          <path
                            d="M16.004 0H15.996C7.174 0 0 7.176 0 16C0 19.5 1.128 22.744 3.046 25.378L1.052 31.322L7.202 29.356C9.732 31.032 12.75 32 16.004 32C24.826 32 32 24.822 32 16C32 7.178 24.826 0 16.004 0Z"
                            fill="white"
                          />
                          <path
                            d="M25.3131 22.5944C24.9271 23.6844 23.3951 24.5884 22.1731 24.8524C21.3371 25.0304 20.2451 25.1724 16.5691 23.6484C11.8671 21.7004 8.83913 16.9224 8.60313 16.6124C8.37713 16.3024 6.70312 14.0824 6.70312 11.7864C6.70312 9.49036 7.86913 8.37236 8.33913 7.89236C8.72513 7.49836 9.36312 7.31836 9.97512 7.31836C10.1731 7.31836 10.3511 7.32836 10.5111 7.33636C10.9811 7.35636 11.2171 7.38436 11.5271 8.12636C11.9131 9.05636 12.8531 11.3524 12.9651 11.5884C13.0791 11.8244 13.1931 12.1444 13.0331 12.4544C12.8831 12.7744 12.7511 12.9164 12.5151 13.1884C12.2791 13.4604 12.0551 13.6684 11.8191 13.9604C11.6031 14.2144 11.3591 14.4864 11.6311 14.9564C11.9031 15.4164 12.8431 16.9504 14.2271 18.1824C16.0131 19.7724 17.4611 20.2804 17.9791 20.4964C18.3651 20.6564 18.8251 20.6184 19.1071 20.3184C19.4651 19.9324 19.9071 19.2924 20.3571 18.6624C20.6771 18.2104 21.0811 18.1544 21.5051 18.3144C21.9371 18.4644 24.2231 19.5944 24.6931 19.8284C25.1631 20.0644 25.4731 20.1764 25.5871 20.3744C25.6991 20.5724 25.6991 21.5024 25.3131 22.5944Z"
                            fill="#00A86B"
                          />
                        </g>
                        <defs>
                          <clipPath id="clip0_2974_2437">
                            <rect width="32" height="32" fill="white" />
                          </clipPath>
                        </defs>
                      </svg>
                      <?php echo $whatsapp_button_text; ?></a>
                    <?php endif; ?>

                    <?php if ( ! empty( $mailing_list_button_text ) && ! empty( $mailing_list_button_link ) ) : ?>
                    <a href="<?php echo $mailing_list_button_link; ?>" class="ms-btn">
                      <?php echo $mailing_list_button_text; ?>
                        <i class="fa-regular fa-arrow-right-long"></i>
                      </a>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Offer image -->
        <?php if ( ! empty( $image ) ) : ?>
        <div class="ms-offer__img">
          <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
        </div>
        <?php endif; ?>
      </section>