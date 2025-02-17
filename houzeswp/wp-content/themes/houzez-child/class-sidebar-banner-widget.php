<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Sidebar_Banner_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'sidebar_banner_widget', // Base ID
            'Sidebar Banner Widget', // Widget name in admin
            array('description' => 'A widget to display banner with image, title and button')
        );
    }

    // Widget Frontend Display
    public function widget($args, $instance) {
        echo $args['before_widget'];
        ?>
        <div class="ms-apartments-main__sidebar__single__wrapper">
            <div class="ms-apartments-main__sidebar__single">
                <a href="<?php echo esc_url($instance['banner_link']); ?>">
                    <img src="<?php echo esc_url($instance['banner_image']); ?>" alt="<?php echo esc_attr($instance['banner_title']); ?>">
                    <h5 class="ms-apartments-main__sidebar__single__banner-title">
                        <?php echo esc_html($instance['banner_title']); ?>
                    </h5>
                </a>
                <a href="<?php echo esc_url($instance['button_link']); ?>" class="ms-btn ms-btn--primary">
                    <?php echo esc_html($instance['button_text']); ?>
                </a>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    // Widget Backend Form
    public function form($instance) {
        $banner_image = !empty($instance['banner_image']) ? $instance['banner_image'] : '';
        $banner_title = !empty($instance['banner_title']) ? $instance['banner_title'] : '';
        $banner_link = !empty($instance['banner_link']) ? $instance['banner_link'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_link = !empty($instance['button_link']) ? $instance['button_link'] : '';
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
            <label for="<?php echo $this->get_field_id('button_text'); ?>">Button Text:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('button_text'); ?>" 
                   name="<?php echo $this->get_field_name('button_text'); ?>" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_link'); ?>">Button Link:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('button_link'); ?>" 
                   name="<?php echo $this->get_field_name('button_link'); ?>" value="<?php echo esc_attr($button_link); ?>">
        </p>
        <?php
    }

    // Updating widget data
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['banner_image'] = (!empty($new_instance['banner_image'])) ? strip_tags($new_instance['banner_image']) : '';
        $instance['banner_title'] = (!empty($new_instance['banner_title'])) ? strip_tags($new_instance['banner_title']) : '';
        $instance['banner_link'] = (!empty($new_instance['banner_link'])) ? strip_tags($new_instance['banner_link']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? strip_tags($new_instance['button_text']) : '';
        $instance['button_link'] = (!empty($new_instance['button_link'])) ? strip_tags($new_instance['button_link']) : '';
        return $instance;
    }
}