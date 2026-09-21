<?php
/**
 * Share bar.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="mk-share">
    <span class="mk-share__label"><?php echo esc_html(macedonia_mk_t('publishTo')); ?></span>
    <button type="button" data-share="facebook" aria-label="Facebook"><?php macedonia_mk_icon('facebook'); ?> <span>Facebook</span></button>
    <button type="button" data-share="instagram" aria-label="Instagram"><?php macedonia_mk_icon('instagram'); ?> <span>Instagram</span></button>
    <button type="button" data-share="youtube" aria-label="YouTube"><?php macedonia_mk_icon('youtube'); ?> <span>YouTube</span></button>
    <button type="button" data-share="tiktok" aria-label="TikTok"><?php macedonia_mk_icon('tiktok'); ?> <span>TikTok</span></button>
    <button type="button" data-share="copy" aria-label="<?php echo esc_attr(macedonia_mk_t('copyLink')); ?>"><?php macedonia_mk_icon('link'); ?></button>
</div>
