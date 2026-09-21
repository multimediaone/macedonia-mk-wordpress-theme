<?php
/**
 * Tag archive.
 *
 * @package Macedonia_MK
 */
get_header();
$tag = get_queried_object();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('tagged')); ?></p>
        <h1><?php echo esc_html($tag ? $tag->name : ''); ?></h1>
        <p><?php echo esc_html($GLOBALS['wp_query']->found_posts . ' ' . macedonia_mk_t('articlesCount')); ?></p>
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
