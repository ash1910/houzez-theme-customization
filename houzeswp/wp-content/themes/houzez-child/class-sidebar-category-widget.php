<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class MS_Category_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'ms_category_widget',
            'MS Category Widget',
            array('description' => 'Display categories with custom title and limit')
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Category';
        $limit = !empty($instance['limit']) ? absint($instance['limit']) : 0; // 0 means all categories
        
        echo $args['before_widget'];
        ?>
        <div class="ms-apartments-main__sidebar__single__wrapper">
            <h5><?php echo esc_html($title); ?></h5>
            <div class="ms-apartments-main__sidebar__single ms-apartments-main__sidebar__single--lg">
                <div class="sidebar-category-list">
                    <?php
                    $categories = get_categories(array(
                        'number' => $limit
                    ));
                    foreach ($categories as $category) {
                        echo '<li>';
                        echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                        echo '<span>(' . str_pad($category->count, 2, '0', STR_PAD_LEFT) . ')</span>';
                        echo '</li>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Category';
        $limit = !empty($instance['limit']) ? absint($instance['limit']) : 0;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>">Number of categories (0 for all):</label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" 
                   name="<?php echo $this->get_field_name('limit'); ?>" type="number" 
                   value="<?php echo esc_attr($limit); ?>" min="0">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['limit'] = !empty($new_instance['limit']) ? absint($new_instance['limit']) : 0;
        return $instance;
    }
}