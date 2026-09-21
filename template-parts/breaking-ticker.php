<?php
/**
 * Breaking news ticker.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$items = macedonia_mk_breaking_posts();
if (! $items) {
    return;
}
$loop = array_merge($items, $items, $items, $items);
?>
<div class="mk-ticker">
    <div class="mk-wrap mk-ticker__inner">
        <div class="mk-ticker__label"><?php echo esc_html(macedonia_mk_t('breaking')); ?></div>
        <div class="mk-ticker__track-wrap">
            <div class="mk-ticker__track">
                <?php foreach ($loop as $i => $p) : ?>
                    <a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
