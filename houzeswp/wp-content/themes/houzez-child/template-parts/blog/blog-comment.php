<!-- 
    comments
    -->
<div class="ms-blog-details__comments" id="comments-container">
    <!-- heading -->
    <div class="ms-blog-details__comments__heading">
        <?php $get_comments_number = get_comments_number(); ?>
        <h5>Comments (<?php echo $get_comments_number; ?>)</h5>
        <?php if( $get_comments_number ){?>
        <a href="#comments-container">View All Comments</a>
        <?php }?>
    </div>
    
    <?php
    // Get comments for current post
    $comments = get_comments(array(
        'post_id' => get_the_ID(),
        'status' => 'approve'
    ));

    // Loop through each comment
    foreach($comments as $comment) : ?>
        <div class="ms-blog-details__comments__single">
            <div class="ms-blog-details__author">
                <div>
                    <a href="<?php echo get_comment_author_url($comment); ?>">
                        <?php echo get_avatar($comment, 80); ?>
                    </a>
                </div>
                <div class="ms-blog-details__author__desc">
                    <h6><?php echo get_comment_author($comment); ?></h6>
                    <p><?php echo get_comment_date('F j, Y', $comment); ?></p>
                </div>
            </div>
            <p class="ms-blog-details__comments__desc">
                <?php echo get_comment_text($comment); ?>
            </p>
        </div>
    <?php endforeach; ?>

    <?php if(empty($comments)) : ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>
</div>

<!-- write comment -->
<div class="ms-blog-details__write-comment">
    <div class="ms-report-modal__content">
        <?php
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');

        $comments_args = array(
            'title_reply' => '',
            'logged_in_as' => '',
            'must_log_in' => '', 
            'class_form' => 'ms-filter__modal__form',
            'comment_field' => '<div class="ms-input__wrapper">
                                <div class="ms-input__wrapper__inner">
                                    <div class="ms-input ms-input--serach">
                                        <textarea id="comment" name="comment" placeholder="Enter your comment here" 
                                        class="ms-hero__search-location" rows="3" required="required"></textarea>
                                    </div>
                                </div>
                            </div>',
            'fields' => array(
                'author' => '<div class="ms-input__wrapper">
                            <div class="ms-input__wrapper__inner">
                                <div class="ms-input ms-comment-author">
                                    <input type="text" name="author" placeholder="Your Name" 
                                    value="' . esc_attr($commenter['comment_author']) . '"' . $aria_req . ' />
                                </div>
                            ',
                'email' => '
                                <div class="ms-input ms-comment-author">
                                    <input type="email" name="email" placeholder="Your Email" 
                                    value="' . esc_attr($commenter['comment_author_email']) . '"' . $aria_req . ' />
                                </div>
                            </div>
                        </div>',
            ),
            'submit_button' => '<div class="ms-input__submit">
                                <button type="submit" class="ms-btn ms-btn--primary">Submit</button>
                            </div>',
            'comment_notes_before' => '',
            'comment_notes_after' => '',
        );

        comment_form($comments_args);
        ?>
    </div>
</div>