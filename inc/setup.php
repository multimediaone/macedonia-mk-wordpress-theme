<?php
/**
 * Theme setup, assets, menus, sidebars.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_setup() {
    load_theme_textdomain('macedonia-mk', MACEDONIA_MK_DIR . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor.css');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('custom-background', array(
        'default-color' => 'f6f3ee',
    ));
    add_theme_support('customize-selective-refresh-widgets');

    add_image_size('mk-hero', 1400, 900, true);
    add_image_size('mk-overlay', 900, 680, true);
    add_image_size('mk-card', 800, 500, true);
    add_image_size('mk-row', 360, 248, true);

    register_nav_menus(array(
        'primary' => __('Primary menu', 'macedonia-mk'),
        'footer'  => __('Footer menu', 'macedonia-mk'),
    ));
}
add_action('after_setup_theme', 'macedonia_mk_setup');

function macedonia_mk_content_width() {
    $GLOBALS['content_width'] = 720;
}
add_action('after_setup_theme', 'macedonia_mk_content_width', 0);

function macedonia_mk_sidebars() {
    register_sidebar(array(
        'name'          => __('Homepage sidebar', 'macedonia-mk'),
        'id'            => 'sidebar-home',
        'description'   => __('Widgets below the YouTube / TikTok rails on the homepage.', 'macedonia-mk'),
        'before_widget' => '<section id="%1$s" class="mk-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="mk-widget__title">',
        'after_title'   => '</h2>',
    ));
    register_sidebar(array(
        'name'          => __('Article sidebar', 'macedonia-mk'),
        'id'            => 'sidebar-single',
        'description'   => __('Optional widgets on single posts.', 'macedonia-mk'),
        'before_widget' => '<section id="%1$s" class="mk-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="mk-widget__title">',
        'after_title'   => '</h2>',
    ));
    register_sidebar(array(
        'name'          => __('Footer', 'macedonia-mk'),
        'id'            => 'sidebar-footer',
        'description'   => __('Optional extra footer column.', 'macedonia-mk'),
        'before_widget' => '<section id="%1$s" class="mk-footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="mk-footer__heading">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'macedonia_mk_sidebars');

function macedonia_mk_assets() {
    wp_enqueue_style(
        'macedonia-mk-fonts',
        'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:wght@500;600;700&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'macedonia-mk',
        MACEDONIA_MK_URI . '/assets/css/theme.css',
        array('macedonia-mk-fonts'),
        MACEDONIA_MK_VERSION
    );

    wp_enqueue_script(
        'macedonia-mk',
        MACEDONIA_MK_URI . '/assets/js/theme.js',
        array(),
        MACEDONIA_MK_VERSION,
        true
    );

    $data = array(
        'rest'      => esc_url_raw(rest_url('wp/v2/')),
        'ajax'      => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('macedonia_mk'),
        'home'      => home_url('/'),
        'lang'      => macedonia_mk_lang(),
        'i18n'      => array(
            'search'           => macedonia_mk_t('search'),
            'searchPlaceholder'=> macedonia_mk_t('searchPlaceholder'),
            'noResults'        => macedonia_mk_t('noResults'),
            'close'            => macedonia_mk_t('close'),
            'copied'           => macedonia_mk_t('copied'),
            'copiedInstagram'  => macedonia_mk_t('copiedInstagram'),
            'copiedTiktok'     => macedonia_mk_t('copiedTiktok'),
            'subscribeOk'      => macedonia_mk_t('subscribeOk'),
            'invalidEmail'     => macedonia_mk_t('invalidEmail'),
            'contactOk'        => macedonia_mk_t('contactOk'),
            'playEmbed'        => macedonia_mk_t('playEmbed'),
            'openOn'           => macedonia_mk_t('openOn'),
            'embedInvalid'     => macedonia_mk_t('embedInvalid'),
            'nowPlaying'       => macedonia_mk_t('nowPlaying'),
            'bookmark'         => macedonia_mk_t('bookmark'),
            'bookmarked'       => macedonia_mk_t('bookmarked'),
            'emptySaved'       => macedonia_mk_t('emptySaved'),
        ),
        'social'    => array(
            'facebook'  => macedonia_mk_option('facebook', 'https://www.facebook.com/macedonia.mk'),
            'instagram' => macedonia_mk_option('instagram', 'https://www.instagram.com/macedonia.mk'),
            'youtube'   => macedonia_mk_option('youtube', 'https://www.youtube.com/@macedonia.mk'),
            'tiktok'    => macedonia_mk_option('tiktok', 'https://www.tiktok.com/@macedonia.mk'),
        ),
    );
    wp_localize_script('macedonia-mk', 'MacedoniaMK', $data);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'macedonia_mk_assets');

function macedonia_mk_resource_hints($urls, $relation) {
    if ('preconnect' === $relation) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'macedonia_mk_resource_hints', 10, 2);

function macedonia_mk_body_class($classes) {
    $classes[] = 'mk-lang-' . macedonia_mk_lang();
    if (! is_active_sidebar('sidebar-home')) {
        $classes[] = 'mk-no-home-widgets';
    }
    return $classes;
}
add_filter('body_class', 'macedonia_mk_body_class');

function macedonia_mk_excerpt_length() {
    return 26;
}
add_filter('excerpt_length', 'macedonia_mk_excerpt_length');

function macedonia_mk_excerpt_more() {
    return '…';
}
add_filter('excerpt_more', 'macedonia_mk_excerpt_more');

/**
 * After first activation, load the complete newspaper and menus.
 */
function macedonia_mk_after_switch() {
    macedonia_mk_seed_site(false);
}
add_action('after_switch_theme', 'macedonia_mk_after_switch');
