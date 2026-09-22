<?php
/**
 * Template helpers.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_option($key, $default = '') {
    $value = get_theme_mod('macedonia_mk_' . $key, $default);
    return ('' === $value || null === $value) ? $default : $value;
}

function macedonia_mk_reading_minutes($post = null) {
    $post = get_post($post);
    if (! $post) {
        return 1;
    }
    $meta = (int) get_post_meta($post->ID, '_macedonia_mk_minutes', true);
    if ($meta > 0) {
        return $meta;
    }
    $words = str_word_count(wp_strip_all_tags($post->post_content));
    return max(1, (int) ceil($words / 200));
}

function macedonia_mk_today_label() {
    if ('en' === macedonia_mk_lang()) {
        return wp_date('l, j F Y');
    }
    $days = array('недела', 'понеделник', 'вторник', 'среда', 'четврток', 'петок', 'сабота');
    $months = array('', 'јануари', 'февруари', 'март', 'април', 'мај', 'јуни', 'јули', 'август', 'септември', 'октомври', 'ноември', 'декември');
    $ts = current_time('timestamp');
    $w = (int) wp_date('w', $ts);
    $n = (int) wp_date('n', $ts);
    $j = wp_date('j', $ts);
    $y = wp_date('Y', $ts);
    $day = isset($days[ $w ]) ? $days[ $w ] : '';
    $month = isset($months[ $n ]) ? $months[ $n ] : '';
    return sprintf('%s, %s %s %s', ucfirst($day), $j, ucfirst($month), $y);
}

function macedonia_mk_date($post = null, $long = false) {
    $post = get_post($post);
    if (! $post) {
        return '';
    }
    $ts = get_post_timestamp($post);
    if ('en' === macedonia_mk_lang()) {
        return $long ? wp_date('j F Y', $ts) : wp_date('d.m.Y', $ts);
    }
    $months = array('', 'јануари', 'февруари', 'март', 'април', 'мај', 'јуни', 'јули', 'август', 'септември', 'октомври', 'ноември', 'декември');
    $n = (int) wp_date('n', $ts);
    if ($long) {
        return sprintf('%s %s %s', wp_date('j', $ts), isset($months[ $n ]) ? $months[ $n ] : '', wp_date('Y', $ts));
    }
    return wp_date('d.m.Y', $ts);
}

function macedonia_mk_category($post = null) {
    $cats = get_the_category($post);
    return $cats ? $cats[0] : null;
}

function macedonia_mk_thumb($post, $size, $class = '', $priority = false) {
    $post = get_post($post);
    $alt  = get_the_title($post);
    if (has_post_thumbnail($post)) {
        $attr = array(
            'class'    => $class,
            'alt'      => $alt,
            'loading'  => $priority ? 'eager' : 'lazy',
            'decoding' => 'async',
        );
        if ($priority) {
            $attr['fetchpriority'] = 'high';
        }
        echo get_the_post_thumbnail($post, $size, $attr); // phpcs:ignore WordPress.Security.EscapeOutput
        return;
    }
    echo '<span class="mk-ph ' . esc_attr($class) . '" aria-hidden="true"><span>' . esc_html(macedonia_mk_t('placeholderThumb')) . '</span></span>';
}

function macedonia_mk_card($post, $variant = 'grid') {
    if (! $post) {
        return;
    }
    get_template_part('template-parts/card', null, array(
        'post'    => $post,
        'variant' => $variant,
    ));
}

function macedonia_mk_sun($class = 'mk-sun') {
    echo '<svg viewBox="0 0 32 32" class="' . esc_attr($class) . '" aria-hidden="true" focusable="false">';
    echo '<circle cx="16" cy="16" r="5.2" fill="currentColor"/>';
    for ($i = 0; $i < 8; $i++) {
        $a  = ($i * M_PI) / 4 - M_PI / 2;
        $x1 = 16 + cos($a) * 7.4;
        $y1 = 16 + sin($a) * 7.4;
        $x2 = 16 + cos($a) * 14.2;
        $y2 = 16 + sin($a) * 14.2;
        printf(
            '<line x1="%s" y1="%s" x2="%s" y2="%s" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>',
            esc_attr($x1),
            esc_attr($y1),
            esc_attr($x2),
            esc_attr($y2)
        );
    }
    echo '</svg>';
}

function macedonia_mk_logo($compact = false) {
    $class = $compact ? 'mk-logo mk-logo--compact' : 'mk-logo';
    echo '<a class="' . esc_attr($class) . '" href="' . esc_url(home_url('/')) . '" rel="home">';
    if (has_custom_logo()) {
        $logo = get_theme_mod('custom_logo');
        echo wp_get_attachment_image((int) $logo, 'full', false, array('class' => 'mk-logo__img'));
    } else {
        macedonia_mk_sun($compact ? 'mk-sun mk-sun--sm' : 'mk-sun');
        echo '<span class="mk-logo__text"><span class="mk-logo__name">Macedonia</span><span class="mk-logo__tld">.mk</span></span>';
    }
    echo '</a>';
}

function macedonia_mk_icon($name) {
    $icons = array(
        'search'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/></svg>',
        'menu'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
        'close'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>',
        'bookmark'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M6 4h12v16l-6-3-6 3z"/></svg>',
        'rss'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1.6" fill="currentColor"/></svg>',
        'cloud'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="17" cy="8" r="3.2"/><path d="M6 16h11a3.5 3.5 0 0 0 .4-7 5 5 0 0 0-9.6 1.4A3.6 3.6 0 0 0 6 16z"/></svg>',
        'clock'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>',
        'play'      => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l12-7z"/></svg>',
        'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h2.6l.4-3H13v-2c0-.6.4-1 1-1z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.4"/><circle cx="17.2" cy="6.8" r="0.8" fill="currentColor"/></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 12.2s0-3.2-.4-4.6c-.2-.8-.9-1.5-1.7-1.7C19.4 5.5 12 5.5 12 5.5s-7.4 0-8.9.4c-.8.2-1.5.9-1.7 1.7C1 9 1 12.2 1 12.2s0 3.2.4 4.6c.2.8.9 1.5 1.7 1.7 1.5.4 8.9.4 8.9.4s7.4 0 8.9-.4c.8-.2 1.5-.9 1.7-1.7.4-1.4.4-4.6.4-4.6zM9.8 15.5V8.9l6.2 3.3-6.2 3.3z"/></svg>',
        'tiktok'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>',
        'link'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 0 0 7.5.1l1.4-1.4a5 5 0 0 0-7.1-7.1L10.5 6"/><path d="M14 11a5 5 0 0 0-7.5-.1L5.1 12.3a5 5 0 0 0 7.1 7.1L13.5 18"/></svg>',
    );
    if (isset($icons[ $name ])) {
        echo $icons[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput
    }
}

function macedonia_mk_social_follow($compact = false, $light = false) {
    $class = 'mk-follow';
    if ($compact) {
        $class .= ' mk-follow--compact';
    }
    if ($light) {
        $class .= ' mk-follow--light';
    }
    $profiles = array(
        'facebook'  => macedonia_mk_option('facebook', 'https://www.facebook.com/macedonia.mk'),
        'instagram' => macedonia_mk_option('instagram', 'https://www.instagram.com/macedonia.mk'),
        'youtube'   => macedonia_mk_option('youtube', 'https://www.youtube.com/@macedonia.mk'),
        'tiktok'    => macedonia_mk_option('tiktok', 'https://www.tiktok.com/@macedonia.mk'),
    );
    echo '<div class="' . esc_attr($class) . '">';
    if (! $compact) {
        echo '<span class="mk-follow__label">' . esc_html(macedonia_mk_t('follow')) . '</span>';
    }
    foreach ($profiles as $key => $href) {
        echo '<a href="' . esc_url($href) . '" target="_blank" rel="me noopener noreferrer" aria-label="' . esc_attr(ucfirst($key)) . '">';
        macedonia_mk_icon($key);
        echo '</a>';
    }
    echo '</div>';
}

function macedonia_mk_posts($args) {
    $q = new WP_Query(array_merge(array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ), $args));
    return $q->posts;
}

function macedonia_mk_breaking_posts() {
    $flagged = macedonia_mk_posts(array(
        'posts_per_page' => 6,
        'meta_key'       => '_macedonia_mk_breaking',
        'meta_value'     => '1',
    ));
    if ($flagged) {
        return $flagged;
    }
    $slug = macedonia_mk_option('breaking_category', 'itno');
    $cat  = get_category_by_slug($slug);
    if ($cat) {
        $items = macedonia_mk_posts(array(
            'posts_per_page' => 6,
            'cat'            => (int) $cat->term_id,
        ));
        if ($items) {
            return $items;
        }
    }
    $tagged = macedonia_mk_posts(array(
        'posts_per_page' => 6,
        'tag'            => 'breaking,itno',
    ));
    if ($tagged) {
        return $tagged;
    }
    return macedonia_mk_posts(array('posts_per_page' => 5));
}

function macedonia_mk_trending_posts() {
    $ranked = macedonia_mk_posts(array(
        'posts_per_page' => 5,
        'meta_key'       => '_macedonia_mk_trending',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_macedonia_mk_trending',
                'compare' => 'EXISTS',
            ),
        ),
    ));
    if (count($ranked) >= 3) {
        return $ranked;
    }
    $items = macedonia_mk_posts(array(
        'posts_per_page' => 5,
        'orderby'        => 'comment_count',
        'order'          => 'DESC',
        'date_query'     => array(
            array('after' => '30 days ago'),
        ),
    ));
    if (count($items) >= 3) {
        return $items;
    }
    return macedonia_mk_posts(array(
        'posts_per_page' => 5,
        'orderby'        => 'comment_count',
        'order'          => 'DESC',
    ));
}

function macedonia_mk_parse_videos($raw) {
    $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
    $out   = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if ('' === $line || 0 === strpos($line, '#')) {
            continue;
        }
        $parts = array_map('trim', explode('|', $line));
        $url   = isset($parts[0]) ? esc_url_raw($parts[0]) : '';
        if (! $url) {
            continue;
        }
        $out[] = array(
            'url'      => $url,
            'title'    => isset($parts[1]) ? $parts[1] : $url,
            'duration' => isset($parts[2]) ? $parts[2] : '',
            'thumb'    => isset($parts[3]) ? esc_url_raw($parts[3]) : '',
        );
    }
    return $out;
}

function macedonia_mk_youtube_id($url) {
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    if (preg_match('/youtube\.com\/(?:embed|shorts|live)\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    return '';
}

function macedonia_mk_tiktok_id($url) {
    if (preg_match('/tiktok\.com\/@[^\/]+\/video\/(\d+)/', $url, $m)) {
        return $m[1];
    }
    if (preg_match('/tiktok\.com\/embed\/(?:v2\/)?(\d+)/', $url, $m)) {
        return $m[1];
    }
    return '';
}

function macedonia_mk_pagination() {
    the_posts_pagination(array(
        'mid_size'  => 1,
        'prev_text' => macedonia_mk_t('newer'),
        'next_text' => macedonia_mk_t('older'),
        'class'     => 'mk-pager',
    ));
}

function macedonia_mk_fallback_menu() {
    echo '<ul class="mk-nav__list">';
    echo '<li class="' . (is_front_page() ? 'current-menu-item' : '') . '"><a href="' . esc_url(home_url('/')) . '">' . esc_html(macedonia_mk_t('home')) . '</a></li>';
    $cats = get_categories(array(
        'hide_empty' => true,
        'number'     => 12,
        'parent'     => 0,
        'orderby'    => 'name',
    ));
    foreach ($cats as $cat) {
        $active = is_category($cat->term_id) ? ' current-menu-item' : '';
        echo '<li class="' . esc_attr($active) . '"><a href="' . esc_url(get_category_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
    }
    echo '</ul>';
}

function macedonia_mk_existing_page($slugs) {
    foreach ((array) $slugs as $slug) {
        $page = get_page_by_path($slug);
        if ($page && 'publish' === $page->post_status) {
            return $page;
        }
    }
    return null;
}
