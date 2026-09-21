<?php
/**
 * Generic archives (date, tag, custom).
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('archives')); ?></p>
        <h1><?php echo wp_kses_post(get_the_archive_title()); ?></h1>
        <?php the_archive_description('<p>', '</p>'); ?>
        <p><?php echo esc_html($GLOBALS['wp_query']->found_posts . ' ' . macedonia_mk_t('articlesCount')); ?></p>
    </header>
    <?php if (have_posts()) : ?>
        <?php
        the_post();
        macedonia_mk_card(get_post(), 'hero');
        ?>
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
