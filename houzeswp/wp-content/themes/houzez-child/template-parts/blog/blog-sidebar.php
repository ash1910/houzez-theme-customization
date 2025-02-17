<div class="ms-slidebar__wrapper">
    <!-- search -->
    <div class="ms-apartments-main__sidebar__single__wrapper d-none d-md-block">
        <h5>Search Blog</h5>
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
    <!-- categories -->
    <div class="ms-apartments-main__sidebar__single__wrapper">
        <h5>Category</h5>
        <div class="ms-apartments-main__sidebar__single ms-apartments-main__sidebar__single--lg">
            <div class="sidebar-category-list">
                <?php
                $categories = get_categories();
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

    <!-- sidebar single -->
    <?php if (is_active_sidebar('blog-sidebar-banner')) : ?>
        <?php dynamic_sidebar('blog-sidebar-banner'); ?>
    <?php endif; ?>

</div>
