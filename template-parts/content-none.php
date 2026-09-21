<?php
/**
 * Empty state.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="mk-empty">
    <p><?php echo esc_html(macedonia_mk_t('noResults')); ?></p>
    <?php get_search_form(); ?>
</div>
