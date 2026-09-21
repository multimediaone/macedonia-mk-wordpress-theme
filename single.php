<?php
/**
 * Single post — news article.
 *
 * @package Macedonia_MK
 */
get_header();

while (have_posts()) :
    the_post();
    $cat     = macedonia_mk_category(get_post());
    $related = array();
    if ($cat) {
        $related = macedonia_mk_posts(array(
            'posts_per_page' => 3,
            'cat'            => (int) $cat->term_id,
            'post__not_in'   => array(get_the_ID()),
        ));
    }
    if (count($related) < 3) {
        $more = macedonia_mk_posts(array(
            'posts_per_page' => 3 - count($related),
            'post__not_in'   => array_merge(array(get_the_ID()), wp_list_pluck($related, 'ID')),
        ));
        $related = array_merge($related, $more);
    }
    ?>
    <article <?php post_class(); ?> itemscope itemtype="https://schema.org/NewsArticle">
        <div class="mk-wrap">
            <div class="mk-article">
                <?php
                get_template_part('template-parts/breadcrumbs', null, array(
                    'items' => array(
                        array('label' => macedonia_mk_t('home'), 'url' => home_url('/')),
                        $cat ? array('label' => $cat->name, 'url' => get_category_link($cat)) : null,
                        array('label' => get_the_title()),
                    ),
                ));
                ?>
                <?php if ($cat) : ?>
                    <a class="mk-chip" href="<?php echo esc_url(get_category_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                <?php endif; ?>
                <h1 class="mk-article__title" itemprop="headline"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="mk-article__dek" itemprop="description"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div class="mk-article__byline">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" itemprop="author">
                        <?php the_author(); ?>
                    </a>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished"><?php echo esc_html(macedonia_mk_date(get_post(), true)); ?></time>
                    <span style="display:inline-flex;align-items:center;gap:0.3rem">
                        <?php macedonia_mk_icon('clock'); ?>
                        <?php echo esc_html(macedonia_mk_reading_minutes() . ' ' . macedonia_mk_t('minutes')); ?>
                    </span>
                    <button type="button" data-bookmark="<?php echo esc_attr((string) get_the_ID()); ?>" style="margin-left:auto;display:inline-flex;height:2.75rem;align-items:center;gap:0.4rem">
                        <?php macedonia_mk_icon('bookmark'); ?>
                        <span data-bookmark-label><?php echo esc_html(macedonia_mk_t('bookmark')); ?></span>
                    </button>
                </div>
                <?php get_template_part('template-parts/share'); ?>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="mk-article__hero">
                    <?php the_post_thumbnail('full', array('itemprop' => 'image')); ?>
                    <?php if (get_the_post_thumbnail_caption()) : ?>
                        <figcaption class="mk-wrap" style="padding:0.5rem 0;font-size:0.8rem;color:var(--mk-muted)"><?php echo esc_html(get_the_post_thumbnail_caption()); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <div class="mk-article__body" itemprop="articleBody">
                <?php the_content(); ?>
                <?php
                wp_link_pages(array(
                    'before' => '<div class="mk-pager">' . esc_html(macedonia_mk_t('pages')) . ': ',
                    'after'  => '</div>',
                ));
                ?>
            </div>

            <?php
            $tags = get_the_tags();
            if ($tags) :
                ?>
                <div class="mk-article" style="margin-top:1.5rem">
                    <?php foreach ($tags as $tag) : ?>
                        <a class="mk-chip" href="<?php echo esc_url(get_tag_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($related) : ?>
                <section class="mk-related mk-wrap">
                    <div class="mk-section-head"><h2><?php echo esc_html(macedonia_mk_t('related')); ?></h2></div>
                    <div class="mk-related__grid">
                        <?php foreach ($related as $p) : ?>
                            <?php macedonia_mk_card($p, 'grid'); ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <div class="mk-article">
                <?php
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>
            </div>
        </div>
    </article>
    <?php
endwhile;

get_footer();
