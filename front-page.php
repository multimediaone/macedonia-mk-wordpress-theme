<?php
/**
 * Newspaper homepage.
 *
 * @package Macedonia_MK
 */
get_header();

$all = macedonia_mk_posts(array(
    'posts_per_page'      => 24,
    'ignore_sticky_posts' => false,
));
$sticky_ids = get_option('sticky_posts');
$hero = null;
$side = array();
if ($sticky_ids) {
    $sticky = macedonia_mk_posts(array(
        'post__in'            => array_slice((array) $sticky_ids, 0, 3),
        'orderby'             => 'post__in',
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
    ));
    if ($sticky) {
        $hero = $sticky[0];
        $side = array_slice($sticky, 1, 2);
    }
}
if (! $hero && $all) {
    $hero = $all[0];
}
if (count($side) < 2) {
    foreach ($all as $p) {
        if ($hero && (int) $p->ID === (int) $hero->ID) {
            continue;
        }
        $already = false;
        foreach ($side as $s) {
            if ((int) $s->ID === (int) $p->ID) {
                $already = true;
                break;
            }
        }
        if ($already) {
            continue;
        }
        $side[] = $p;
        if (count($side) >= 2) {
            break;
        }
    }
}

$used = array();
if ($hero) {
    $used[] = (int) $hero->ID;
}
foreach ($side as $s) {
    $used[] = (int) $s->ID;
}
$rest = array();
foreach ($all as $p) {
    if (! in_array((int) $p->ID, $used, true)) {
        $rest[] = $p;
    }
}
$dont_miss = array_slice($rest, 0, 5);
$more_rows = array_slice($rest, 5, 6);
$grid      = array_slice($rest, 11, 6);
$leftover  = array_slice($rest, 17, 4);

$preferred = array('kultura', 'sport', 'tehnologija', 'zivot');
$modules   = array();
foreach ($preferred as $slug) {
    $cat = get_category_by_slug($slug);
    if ($cat && $cat->count > 0) {
        $modules[] = $cat;
    }
}
if (count($modules) < 4) {
    foreach (get_categories(array('hide_empty' => true, 'number' => 8)) as $cat) {
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

$yt = macedonia_mk_parse_videos(macedonia_mk_option('youtube_list', ''));
$tt = macedonia_mk_parse_videos(macedonia_mk_option('tiktok_list', ''));
if (! $yt) {
    $yt = macedonia_mk_parse_videos("https://www.youtube.com/watch?v=BBiZYJW7wbQ | Охрид — пет совети | 7:10\nhttps://www.youtube.com/watch?v=we7nLWIHvoY | Стара скопска чаршија | 42:36");
}
if (! $tt) {
    $tt = macedonia_mk_parse_videos("https://www.tiktok.com/@ristespiroski/video/7405651822540606725 | Охридско Езеро | 0:14");
}
$trending = macedonia_mk_trending_posts();
?>

<div class="mk-wrap">
    <?php if ($hero) : ?>
        <section class="mk-hero">
            <div><?php macedonia_mk_card($hero, 'hero'); ?></div>
            <div class="mk-hero__side">
                <?php foreach ($side as $p) : ?>
                    <?php macedonia_mk_card($p, 'overlay'); ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="mk-home">
        <div class="mk-home__main">
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

            <?php if ($grid) : ?>
                <div class="mk-grid-2">
                    <?php foreach ($grid as $p) : ?>
                        <?php macedonia_mk_card($p, 'grid'); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($leftover) : ?>
                <div class="mk-more-rows">
                    <?php foreach ($leftover as $p) : ?>
                        <?php macedonia_mk_card($p, 'row'); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php
            $pairs = array_chunk($modules, 2);
            foreach ($pairs as $pair) :
                ?>
                <div class="mk-cats">
                    <?php foreach ($pair as $cat) :
                        $cat_posts = macedonia_mk_posts(array('posts_per_page' => 3, 'cat' => (int) $cat->term_id));
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
            <?php get_template_part('template-parts/video-rail', null, array('provider' => 'youtube', 'clips' => $yt)); ?>
            <?php get_template_part('template-parts/video-rail', null, array('provider' => 'tiktok', 'clips' => $tt)); ?>
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
