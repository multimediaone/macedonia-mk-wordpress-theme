<?php
/**
 * Newspaper homepage — latest published posts from the existing database.
 *
 * @package Macedonia_MK
 */
get_header();

$latest = macedonia_mk_posts(array(
    'posts_per_page'      => 10,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
));

$hero      = isset($latest[0]) ? $latest[0] : null;
$side      = array_slice($latest, 1, 2);
$rest      = array_slice($latest, 3);
$dont_miss = array_slice($rest, 0, 5);
$more_rows = array_slice($rest, 5);

$modules = array();
$preferred = array('makedonija', 'politika', 'ekonomija', 'svet', 'sport', 'kultura', 'hronika', 'zdravje', 'tehnologija', 'zivot');
foreach ($preferred as $slug) {
    $cat = get_category_by_slug($slug);
    if ($cat && ! is_wp_error($cat) && (int) $cat->count > 0) {
        $modules[] = $cat;
    }
    if (count($modules) >= 4) {
        break;
    }
}
if (count($modules) < 4) {
    foreach (get_categories(array(
        'hide_empty' => true,
        'number'     => 12,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'parent'     => 0,
    )) as $cat) {
        $exists = false;
        foreach ($modules as $m) {
            if ((int) $m->term_id === (int) $cat->term_id) {
                $exists = true;
                break;
            }
        }
        if (! $exists) {
            $modules[] = $cat;
        }
        if (count($modules) >= 4) {
            break;
        }
    }
}

$yt       = macedonia_mk_parse_videos(macedonia_mk_option('youtube_list', ''));
$tt       = macedonia_mk_parse_videos(macedonia_mk_option('tiktok_list', ''));
$trending = macedonia_mk_trending_posts();
$shown    = array();
foreach ($latest as $p) {
    $shown[] = (int) $p->ID;
}
?>

<div class="mk-wrap">
    <?php if ($hero) : ?>
        <section class="mk-hero">
            <div><?php macedonia_mk_card($hero, 'hero'); ?></div>
            <?php if ($side) : ?>
                <div class="mk-hero__side">
                    <?php foreach ($side as $p) : ?>
                        <?php macedonia_mk_card($p, 'overlay'); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <section class="mk-home">
        <div class="mk-home__main">
            <?php if ($dont_miss || $more_rows) : ?>
            <div>
                <div class="mk-section-head"><h2><?php echo esc_html(macedonia_mk_t('dontMiss')); ?></h2></div>
                <?php if (! empty($dont_miss[0])) : ?>
                    <div class="mk-dontmiss">
                        <?php macedonia_mk_card($dont_miss[0], 'grid'); ?>
                        <div class="mk-dontmiss__rows">
                            <?php foreach (array_slice($dont_miss, 1) as $p) : ?>
                                <?php macedonia_mk_card($p, 'row'); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($more_rows) : ?>
                    <div class="mk-more-rows" style="margin-top:1.5rem">
                        <?php foreach ($more_rows as $p) : ?>
                            <?php macedonia_mk_card($p, 'row'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php
            $pairs = array_chunk($modules, 2);
            foreach ($pairs as $pair) :
                ?>
                <div class="mk-cats">
                    <?php foreach ($pair as $cat) :
                        $cat_posts = macedonia_mk_posts(array(
                            'posts_per_page' => 3,
                            'cat'            => (int) $cat->term_id,
                            'post__not_in'   => $shown,
                        ));
                        if (! $cat_posts) {
                            $cat_posts = macedonia_mk_posts(array(
                                'posts_per_page' => 3,
                                'cat'            => (int) $cat->term_id,
                            ));
                        }
                        if (! $cat_posts) {
                            continue;
                        }
                        ?>
                        <div>
                            <div class="mk-section-head">
                                <h2><?php echo esc_html($cat->name); ?></h2>
                                <a href="<?php echo esc_url(get_category_link($cat)); ?>"><?php echo esc_html(macedonia_mk_t('seeAll')); ?></a>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:1rem">
                                <?php foreach ($cat_posts as $p) : ?>
                                    <?php macedonia_mk_card($p, 'row'); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <aside class="mk-home__rail">
            <?php if ($yt) : ?>
                <?php get_template_part('template-parts/video-rail', null, array('provider' => 'youtube', 'clips' => $yt)); ?>
            <?php endif; ?>
            <?php if ($tt) : ?>
                <?php get_template_part('template-parts/video-rail', null, array('provider' => 'tiktok', 'clips' => $tt)); ?>
            <?php endif; ?>
            <?php if ($trending) : ?>
                <div class="mk-box">
                    <h2 class="mk-box__title"><?php echo esc_html(macedonia_mk_t('trending')); ?></h2>
                    <ol class="mk-trend">
                        <?php foreach ($trending as $i => $p) :
                            $c = macedonia_mk_category($p);
                            ?>
                            <li>
                                <span class="mk-trend__n"><?php echo (int) ($i + 1); ?></span>
                                <a href="<?php echo esc_url(get_permalink($p)); ?>">
                                    <?php if ($c) : ?><p class="mk-chip"><?php echo esc_html($c->name); ?></p><?php endif; ?>
                                    <p class="mk-card__title" style="font-size:0.9rem;margin-top:0.25rem"><?php echo esc_html(get_the_title($p)); ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>
            <div class="mk-box">
                <?php get_template_part('template-parts/subscribe'); ?>
            </div>
            <?php dynamic_sidebar('sidebar-home'); ?>
        </aside>
    </section>
</div>

<?php
get_footer();
