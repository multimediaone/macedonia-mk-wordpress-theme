<?php
/**
 * Search form.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<form role="search" method="get" class="mk-subscribe__row" action="<?php echo esc_url(home_url('/')); ?>" style="max-width:28rem">
    <label class="mk-screen" for="mk-s"><?php echo esc_html(macedonia_mk_t('search')); ?></label>
    <input id="mk-s" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr(macedonia_mk_t('searchPlaceholder')); ?>">
    <button type="submit" class="mk-btn"><?php echo esc_html(macedonia_mk_t('search')); ?></button>
</form>
