<?php
/**
 * Template Name: Saved articles
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('saved')); ?></p>
        <h1><?php echo esc_html(macedonia_mk_t('saved')); ?></h1>
    </header>
    <div data-saved-list class="mk-more-rows"></div>
</div>
<?php
get_footer();
