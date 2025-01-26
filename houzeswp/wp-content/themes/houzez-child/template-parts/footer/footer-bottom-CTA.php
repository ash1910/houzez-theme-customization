<?php 
    $cta_image = houzez_option( 'cta_image', false, 'url' );
    $cta_image_mobile = houzez_option( 'cta_image_mobile', false, 'url' );
    $google_app_logo = houzez_option( 'google_app_logo', false, 'url' );
    $ios_app_logo = houzez_option( 'ios_app_logo', false, 'url' );
?>    

      <!-- start: cta   -->
      <?php if( houzez_option('cta_section') == '1' ) { ?>
      <section class="ms-cta section--wrapper">
        <div class="container">
          <div class="row">
            <!-- section heading -->

            <div class="col-12 col-md-6">
              <div class="ms-section-heading ms-section-heading--white">
                <?php if( houzez_option('cta_title') != '' ){ ?>  
                <h2 style="white-space: pre-wrap;"><?php echo houzez_option('cta_title'); ?></h2>
                <?php } ?>
                <?php if( houzez_option('cta_description') != '' ){ ?>
                <p style="white-space: pre-wrap;"><?php echo houzez_option('cta_description'); ?></p>
                <?php } ?>
              </div>
              <div class="ms-cta__footer">
                <?php if( houzez_option('google_app_url') != '' && $google_app_logo != '' ){ ?>
                <a href="<?php echo esc_url('google_app_url'); ?>"
                  ><img src="<?php echo esc_url($google_app_logo); ?>" alt=""
                /></a>
                <?php } ?>
                <?php if( houzez_option('ios_app_url') != '' && $ios_app_logo != '' ){ ?>
                <a href="<?php echo esc_url('ios_app_url'); ?>"
                  ><img src="<?php echo esc_url($ios_app_logo); ?>" alt=""
                /></a>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <!-- image -->
        <div class="ms-cta__img">
          <?php if( $cta_image != '' ){ ?>
          <img src="<?php echo esc_url($cta_image); ?>" alt="" />
          <?php } ?>
          <?php if( $cta_image_mobile != '' ){ ?>
          <img src="<?php echo esc_url($cta_image_mobile); ?>" alt="" />
          <?php } ?>
        </div>

        <div class="ms-cta__shape__wrapper">
          <div class="ms-cta__shape ms-cta__shape--1"></div>
          <div class="ms-cta__shape ms-cta__shape--2"></div>
        </div>
      </section>
      <?php } ?>
      <!-- end:  cta  -->