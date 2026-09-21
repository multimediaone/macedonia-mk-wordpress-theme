<?php
/**
 * Default page.
 *
 * @package Macedonia_MK
 */
get_header();

while (have_posts()) :
    the_post();
    ?>
    <div class="mk-wrap">
        <?php
        get_template_part('template-parts/breadcrumbs', null, array(
            'items' => array(
                array('label' => macedonia_mk_t('home'), 'url' => home_url('/')),
                array('label' => get_the_title()),
            ),
        ));
        ?>
        <header class="mk-page-head">
            <h1><?php the_title(); ?></h1>
        </header>
        <div class="mk-article__body mk-prose">
            <?php the_content(); ?>
        </div>
        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
    </div>
    <?php
endwhile;

get_footer();
