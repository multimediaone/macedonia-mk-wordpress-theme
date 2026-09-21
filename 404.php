<?php
/**
 * 404.
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker">404</p>
        <h1><?php echo esc_html(macedonia_mk_t('pageNotFound')); ?></h1>
        <p><?php echo esc_html(macedonia_mk_t('pageNotFoundLead')); ?></p>
        <p><a class="mk-btn" href="<?php echo esc_url(home_url('/')); ?>" style="display:inline-flex;align-items:center"><?php echo esc_html(macedonia_mk_t('backHome')); ?></a></p>
    </header>
    <?php get_search_form(); ?>
</div>
<?php
get_footer();
