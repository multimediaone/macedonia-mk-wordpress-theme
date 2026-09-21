<?php
/**
 * Newsletter form.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$dark = ! empty($args['dark']);
?>
<form class="mk-subscribe<?php echo $dark ? ' mk-subscribe--dark' : ''; ?>" data-subscribe>
    <h3 class="mk-box__title"><?php echo esc_html(macedonia_mk_t('subscribeTitle')); ?></h3>
    <p class="mk-subscribe__lead"><?php echo esc_html(macedonia_mk_t('subscribeLead')); ?></p>
    <div class="mk-subscribe__row">
        <input type="email" name="email" required placeholder="<?php echo esc_attr(macedonia_mk_t('subscribePlaceholder')); ?>" autocomplete="email">
        <button type="submit" class="mk-btn"><?php echo esc_html(macedonia_mk_t('subscribeCta')); ?></button>
    </div>
</form>
