<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Blog_Detail_Ads_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'blog_detail_ads_widget', // Base ID
            'Blog Artical Ads Widget', // Widget name in admin
            array('description' => 'A widget to display banner ads with image, title, text and url')
        );
    }

    // Widget Frontend Display
    public function widget($args, $instance) {
        echo $args['before_widget'];
        ?>
        <div class="ms-hero ms-hero--blog" style="background-image: url('<?php echo esc_url($instance['banner_image']); ?>')">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="ms-hero__content">
                            <h1 class="ms-hero__title"><?php echo esc_attr($instance['banner_title']); ?></h1>
                            <div class="ms-apartments-main__breadcrumb ms-apartments-main__breadcrumb--hero">
                                <ul>
                                    <li>
                                        <p><?php echo esc_attr($instance['banner_text']); ?></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- link -->
            <a class="ms-hero__link" href="<?php echo esc_url($instance['banner_link']); ?>"></a>

            <!-- search Mobile -->
            <?php echo houzez_property_blog_search();?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    // Widget Backend Form
    public function form($instance) {
        $banner_image = !empty($instance['banner_image']) ? $instance['banner_image'] : '';
        $banner_title = !empty($instance['banner_title']) ? $instance['banner_title'] : '';
        $banner_link = !empty($instance['banner_link']) ? $instance['banner_link'] : '';
        $banner_text = !empty($instance['banner_text']) ? $instance['banner_text'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('banner_image'); ?>">Banner Image URL:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('banner_image'); ?>" 
                   name="<?php echo $this->get_field_name('banner_image'); ?>" value="<?php echo esc_attr($banner_image); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('banner_title'); ?>">Banner Title:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('banner_title'); ?>" 
                   name="<?php echo $this->get_field_name('banner_title'); ?>" value="<?php echo esc_attr($banner_title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('banner_link'); ?>">Banner Link:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('banner_link'); ?>" 
                   name="<?php echo $this->get_field_name('banner_link'); ?>" value="<?php echo esc_attr($banner_link); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('banner_text'); ?>">Banner Text:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('banner_text'); ?>" 
                   name="<?php echo $this->get_field_name('banner_text'); ?>" value="<?php echo esc_attr($banner_text); ?>">
        </p>
        <?php
    }

    // Updating widget data
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['banner_image'] = (!empty($new_instance['banner_image'])) ? strip_tags($new_instance['banner_image']) : '';
        $instance['banner_title'] = (!empty($new_instance['banner_title'])) ? strip_tags($new_instance['banner_title']) : '';
        $instance['banner_link'] = (!empty($new_instance['banner_link'])) ? strip_tags($new_instance['banner_link']) : '';
        $instance['banner_text'] = (!empty($new_instance['banner_text'])) ? strip_tags($new_instance['banner_text']) : '';
        return $instance;
    }
}