<?php
/**
 * Author archive.
 *
 * @package Macedonia_MK
 */
get_header();
$author = get_queried_object();
?>
<div class="mk-wrap">
    <?php
    get_template_part('template-parts/breadcrumbs', null, array(
        'items' => array(
            array('label' => macedonia_mk_t('home'), 'url' => home_url('/')),
            array('label' => $author ? $author->display_name : macedonia_mk_t('authorArticles')),
        ),
    ));
    ?>
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('authorArticles')); ?></p>
        <h1><?php echo esc_html($author ? $author->display_name : ''); ?></h1>
        <?php if ($author && $author->description) : ?>
            <p><?php echo esc_html($author->description); ?></p>
        <?php endif; ?>
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
