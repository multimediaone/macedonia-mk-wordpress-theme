<?php
/**
 * Comments template.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
if (post_password_required()) {
    return;
}
?>
<section class="mk-comments" id="comments">
    <h2><?php echo esc_html(macedonia_mk_t('comments')); ?>
        <?php if (get_comments_number()) : ?>
            (<?php echo esc_html((string) get_comments_number()); ?>)
        <?php endif; ?>
    </h2>

    <?php if (have_comments()) : ?>
        <ol class="mk-comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 48,
                'callback'   => function ($comment, $args, $depth) {
                    ?>
                    <li <?php comment_class('mk-comment', $comment); ?> id="comment-<?php comment_ID(); ?>">
                        <p class="mk-comment__meta">
                            <strong><?php echo esc_html(get_comment_author($comment)); ?></strong>
                            · <time datetime="<?php echo esc_attr(get_comment_date('c', $comment)); ?>"><?php echo esc_html(get_comment_date('', $comment)); ?></time>
                        </p>
                        <div class="mk-comment__body"><?php comment_text($comment); ?></div>
                        <?php
                        comment_reply_link(array_merge($args, array(
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                        )));
                        ?>
                    <?php
                },
            ));
            ?>
        </ol>
        <?php the_comments_pagination(); ?>
    <?php else : ?>
        <p><?php echo esc_html(macedonia_mk_t('noComments')); ?></p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'title_reply'          => macedonia_mk_t('writeComment'),
        'label_submit'         => macedonia_mk_t('send'),
        'class_submit'         => 'mk-btn',
        'comment_notes_before' => '',
        'comment_field'        => '<p><label for="comment">' . esc_html(macedonia_mk_t('message')) . '</label><textarea id="comment" name="comment" required></textarea></p>',
        'fields'               => array(
            'author' => '<p><label for="author">' . esc_html(macedonia_mk_t('name')) . '</label><input id="author" name="author" type="text" required></p>',
            'email'  => '<p><label for="email">' . esc_html(macedonia_mk_t('email')) . '</label><input id="email" name="email" type="email" required></p>',
            'url'    => '',
        ),
    ));
    ?>
</section>
