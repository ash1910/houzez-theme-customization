<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Search Widget
class MS_Search_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'ms_search_widget',
            'MS Search Widget',
            array('description' => 'Display search form with custom title')
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Search Blog';
        
        echo $args['before_widget'];
        ?>
        <div class="ms-apartments-main__sidebar__single__wrapper d-none d-md-block">
            <h5><?php echo esc_html($title); ?></h5>
            <form action="<?php echo home_url(); ?>" method="get" class="ms-filter__modal__form ms-filter__modal__form--search ms-apartments-main__sidebar__single">
                <div class="ms-input__wrapper">
                    <div class="ms-input__wrapper__inner">
                        <div class="ms-input ms-input--serach">
                            <label for="ms-hero__search-loaction">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.53 20.47L17.689 16.629C18.973 15.106 19.75 13.143 19.75 11C19.75 6.175 15.825 2.25 11 2.25C6.175 2.25 2.25 6.175 2.25 11C2.25 15.825 6.175 19.75 11 19.75C13.143 19.75 15.106 18.973 16.629 17.689L20.47 21.53C20.616 21.676 20.808 21.75 21 21.75C21.192 21.75 21.384 21.677 21.53 21.53C21.823 21.238 21.823 20.763 21.53 20.47ZM3.75 11C3.75 7.002 7.002 3.75 11 3.75C14.998 3.75 18.25 7.002 18.25 11C18.25 14.998 14.998 18.25 11 18.25C7.002 18.25 3.75 14.998 3.75 11Z"
                                        fill="#1B1B1B" />
                                </svg>
                            </label>
                            <input type="search" placeholder="Search Location" class="ms-hero__search-loaction"
                                name="s" value="<?php echo get_search_query(); ?>" id="ms-hero__search-loaction" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Search Blog';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}