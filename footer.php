<?php
/**
 * Theme footer.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$latest   = macedonia_mk_posts(array('posts_per_page' => 4, 'orderby' => 'date', 'order' => 'DESC'));
$popular  = macedonia_mk_trending_posts();
$about    = macedonia_mk_existing_page(array('about', 'za-nas', 'za_nas'));
$contact  = macedonia_mk_existing_page(array('contact', 'kontakt'));
$saved    = macedonia_mk_existing_page(array('saved', 'zachuvano', 'favorites'));
$embed    = macedonia_mk_existing_page(array('embed', 'embd'));
?>
    </main>

    <footer class="mk-footer">
        <div class="mk-wrap mk-footer__grid">
            <div>
                <?php macedonia_mk_logo(); ?>
                <p class="mk-footer__blurb"><?php echo esc_html(macedonia_mk_t('footerBlurb')); ?></p>
                <div style="margin-top:1rem"><?php macedonia_mk_social_follow(false, true); ?></div>
            </div>
            <div>
                <h3 class="mk-footer__heading"><?php echo esc_html(macedonia_mk_t('latestArticles')); ?></h3>
                <ul class="mk-footer__list">
                    <?php foreach ($latest as $p) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="mk-footer__heading"><?php echo esc_html(macedonia_mk_t('mostRead')); ?></h3>
                <ul class="mk-footer__list">
                    <?php foreach ($popular as $p) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="mk-footer__heading"><?php echo esc_html(macedonia_mk_t('newsroom')); ?></h3>
                <ul class="mk-footer__list">
                    <?php if ($about) : ?><li><a href="<?php echo esc_url(get_permalink($about)); ?>"><?php echo esc_html(macedonia_mk_t('about')); ?></a></li><?php endif; ?>
                    <?php if ($contact) : ?><li><a href="<?php echo esc_url(get_permalink($contact)); ?>"><?php echo esc_html(macedonia_mk_t('contact')); ?></a></li><?php endif; ?>
                    <?php if ($saved) : ?><li><a href="<?php echo esc_url(get_permalink($saved)); ?>"><?php echo esc_html(macedonia_mk_t('saved')); ?></a></li><?php endif; ?>
                    <li><a href="<?php echo esc_url(get_feed_link()); ?>"><?php echo esc_html(macedonia_mk_t('rssFeed')); ?></a></li>
                    <?php if ($embed) : ?><li><a href="<?php echo esc_url(get_permalink($embed)); ?>"><?php echo esc_html(macedonia_mk_t('embed')); ?></a></li><?php endif; ?>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'fallback_cb'    => '__return_empty_string',
                        'depth'          => 1,
                    ));
                    ?>
                </ul>
                <?php get_template_part('template-parts/subscribe', null, array('dark' => true)); ?>
                <?php dynamic_sidebar('sidebar-footer'); ?>
            </div>
        </div>
        <div class="mk-footer__copy">
            <p class="mk-wrap"><?php echo esc_html(sprintf(macedonia_mk_t('copyright'), wp_date('Y'))); ?></p>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
