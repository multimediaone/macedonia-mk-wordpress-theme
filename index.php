<?php
/**
 * Fallback index — blog / posts listing.
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('allNews')); ?></p>
        <h1><?php echo esc_html(is_home() ? get_bloginfo('name') : macedonia_mk_t('latest')); ?></h1>
    </header>
    <?php if (have_posts()) : ?>
        <div class="mk-archive-grid">
            <?php
            while (have_posts()) :
                the_post();
                macedonia_mk_card(get_post(), 'grid');
            endwhile;
            ?>
        </div>
        <?php macedonia_mk_pagination(); ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content-none'); ?>
    <?php endif; ?>
</div>
<?php
get_footer();
