<?php
/**
 * Theme header.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="mk-site">
    <div class="mk-topbar">
        <div class="mk-wrap mk-topbar__inner">
            <div class="mk-topbar__left">
                <span class="mk-topbar__date"><?php echo esc_html(macedonia_mk_today_label()); ?></span>
                <span class="mk-topbar__weather">
                    <?php macedonia_mk_icon('cloud'); ?>
                    <?php echo esc_html(macedonia_mk_option('weather', macedonia_mk_t('weather'))); ?>
                    <span class="mk-topbar__hint">· <?php echo esc_html(macedonia_mk_option('weather_hint', macedonia_mk_t('weatherHint'))); ?></span>
                </span>
            </div>
            <div class="mk-topbar__right">
                <?php macedonia_mk_social_follow(true, true); ?>
                <a href="<?php echo esc_url(get_feed_link()); ?>" aria-label="RSS"><?php macedonia_mk_icon('rss'); ?></a>
                <?php
                $lang = macedonia_mk_lang();
                $here = remove_query_arg('lang');
                ?>
                <div class="mk-lang" role="group" aria-label="Language">
                    <a class="<?php echo 'mk' === $lang ? 'is-on' : ''; ?>" href="<?php echo esc_url(add_query_arg('lang', 'mk', $here)); ?>">MK</a>
                    <a class="<?php echo 'en' === $lang ? 'is-on' : ''; ?>" href="<?php echo esc_url(add_query_arg('lang', 'en', $here)); ?>">EN</a>
                </div>
            </div>
        </div>
    </div>

    <header class="mk-header">
        <div class="mk-wrap mk-header__bar">
            <button type="button" class="mk-icon-btn mk-icon-btn--menu" data-open-menu aria-label="<?php echo esc_attr(macedonia_mk_t('menu')); ?>">
                <?php macedonia_mk_icon('menu'); ?>
            </button>
            <?php macedonia_mk_logo(); ?>
            <div class="mk-header__actions">
                <?php
                $saved = get_page_by_path('saved');
                $saved_url = $saved ? get_permalink($saved) : home_url('/saved/');
                ?>
                <a class="mk-icon-btn" style="position:relative" href="<?php echo esc_url($saved_url); ?>" aria-label="<?php echo esc_attr(macedonia_mk_t('saved')); ?>">
                    <?php macedonia_mk_icon('bookmark'); ?>
                    <span class="mk-badge" data-saved-count hidden></span>
                </a>
                <button type="button" class="mk-icon-btn" data-open-search aria-label="<?php echo esc_attr(macedonia_mk_t('search')); ?>">
                    <?php macedonia_mk_icon('search'); ?>
                </button>
            </div>
        </div>
        <nav class="mk-nav" aria-label="<?php echo esc_attr(macedonia_mk_t('menu')); ?>">
            <div class="mk-wrap">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'mk-nav__list',
                    'fallback_cb'    => 'macedonia_mk_fallback_menu',
                    'depth'          => 1,
                ));
                ?>
            </div>
        </nav>
    </header>

    <div class="mk-overlay" data-mk-menu>
        <button type="button" class="mk-overlay__backdrop" data-close-overlay aria-label="<?php echo esc_attr(macedonia_mk_t('close')); ?>"></button>
        <div class="mk-drawer">
            <div class="mk-drawer__head">
                <?php macedonia_mk_logo(true); ?>
                <button type="button" class="mk-icon-btn" data-close-overlay aria-label="<?php echo esc_attr(macedonia_mk_t('close')); ?>">
                    <?php macedonia_mk_icon('close'); ?>
                </button>
            </div>
            <nav>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'mk-drawer-nav',
                    'fallback_cb'    => 'macedonia_mk_fallback_menu',
                    'depth'          => 1,
                ));
                ?>
            </nav>
        </div>
    </div>

    <div class="mk-overlay" data-mk-search>
        <button type="button" class="mk-overlay__backdrop" data-close-overlay aria-label="<?php echo esc_attr(macedonia_mk_t('close')); ?>"></button>
        <div class="mk-search-panel">
            <form class="mk-search-panel__row" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <?php macedonia_mk_icon('search'); ?>
                <input type="search" name="s" data-live-search placeholder="<?php echo esc_attr(macedonia_mk_t('searchPlaceholder')); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
                <button type="button" class="mk-icon-btn" data-close-overlay aria-label="<?php echo esc_attr(macedonia_mk_t('close')); ?>">
                    <?php macedonia_mk_icon('close'); ?>
                </button>
            </form>
            <div class="mk-search-hits" data-search-hits></div>
        </div>
    </div>

    <?php get_template_part('template-parts/breaking-ticker'); ?>

    <main class="mk-main" id="content">
