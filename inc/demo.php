<?php
/**
 * Full newspaper seed: categories, authors, 24 articles, images, pages, menus.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_catalog() {
    static $data = null;
    if (null !== $data) {
        return $data;
    }
    $path = MACEDONIA_MK_DIR . '/inc/demo-data.json';
    if (! is_readable($path)) {
        return array();
    }
    $json = json_decode((string) file_get_contents($path), true);
    return is_array($json) ? $json : array();
}

function macedonia_mk_seed_site($force = false) {
    if (! $force && get_option('macedonia_mk_seeded')) {
        macedonia_mk_ensure_menus();
        return get_option('macedonia_mk_seed_stats', array());
    }

    if (! current_user_can('install_themes') && ! current_user_can('switch_themes') && ! current_user_can('edit_theme_options')) {
        return array('error' => 'capability');
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

    $catalog = macedonia_mk_catalog();
    if (! $catalog) {
        return array('error' => 'missing-data');
    }

    $cat_ids    = macedonia_mk_seed_categories(isset($catalog['categories']) ? $catalog['categories'] : array());
    $author_ids = macedonia_mk_seed_authors(isset($catalog['authors']) ? $catalog['authors'] : array());
    $post_ids   = macedonia_mk_seed_articles(
        isset($catalog['articles']) ? $catalog['articles'] : array(),
        $cat_ids,
        $author_ids
    );
    $page_ids   = macedonia_mk_seed_pages($catalog);

    update_option('show_on_front', 'posts');
    update_option('posts_per_page', 12);
    update_option('rss_use_excerpt', 0);
    if ('' === get_option('blogdescription')) {
        update_option('blogdescription', 'Вести од Македонија и светот');
    }
    if (get_option('blogname') === 'WordPress' || get_option('blogname') === 'My Site') {
        update_option('blogname', 'Macedonia.mk');
    }

    $permalink = get_option('permalink_structure');
    if (! $permalink) {
        update_option('permalink_structure', '/%postname%/');
        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules(false);
        }
    }

    macedonia_mk_ensure_menus();

    $stats = array(
        'posts'      => count($post_ids),
        'pages'      => count($page_ids),
        'categories' => count($cat_ids),
        'authors'    => count($author_ids),
        'time'       => current_time('mysql'),
    );
    update_option('macedonia_mk_seeded', 1);
    update_option('macedonia_mk_seed_stats', $stats);
    return $stats;
}

function macedonia_mk_seed_categories($categories) {
    $ids = array();
    $itno = get_category_by_slug('itno');
    if (! $itno) {
        $created = wp_insert_term('Итно', 'category', array(
            'slug'        => 'itno',
            'description' => 'Breaking news',
        ));
        if (! is_wp_error($created)) {
            update_term_meta((int) $created['term_id'], '_macedonia_mk_name_en', 'Breaking');
        }
    } else {
        update_term_meta((int) $itno->term_id, '_macedonia_mk_name_en', 'Breaking');
    }

    foreach ($categories as $cat) {
        $slug = sanitize_title($cat['id']);
        $name = isset($cat['label']['mk']) ? $cat['label']['mk'] : $slug;
        $en   = isset($cat['label']['en']) ? $cat['label']['en'] : $name;
        $term = get_category_by_slug($slug);
        if (! $term) {
            $created = wp_insert_term($name, 'category', array(
                'slug'        => $slug,
                'description' => $en,
            ));
            if (is_wp_error($created)) {
                continue;
            }
            $term_id = (int) $created['term_id'];
        } else {
            $term_id = (int) $term->term_id;
            wp_update_term($term_id, 'category', array('name' => $name, 'description' => $en));
        }
        update_term_meta($term_id, '_macedonia_mk_name_en', $en);
        $ids[ $slug ] = $term_id;
    }
    return $ids;
}

function macedonia_mk_seed_authors($authors) {
    $ids     = array();
    $default = get_current_user_id() ? get_current_user_id() : 1;
    foreach ($authors as $author) {
        $login = sanitize_user($author['id'], true);
        $name  = isset($author['name']['mk']) ? $author['name']['mk'] : $login;
        $email = $login . '@macedonia.mk';
        $user  = get_user_by('login', $login);
        if (! $user) {
            $user = get_user_by('email', $email);
        }
        if (! $user) {
            $uid = wp_insert_user(array(
                'user_login'   => $login,
                'user_pass'    => wp_generate_password(16, true),
                'user_email'   => $email,
                'display_name' => $name,
                'first_name'   => $name,
                'role'         => 'author',
                'description'  => isset($author['bio']['mk']) ? $author['bio']['mk'] : '',
            ));
            if (is_wp_error($uid)) {
                $ids[ $author['id'] ] = $default;
                continue;
            }
        } else {
            $uid = (int) $user->ID;
            wp_update_user(array(
                'ID'           => $uid,
                'display_name' => $name,
                'description'  => isset($author['bio']['mk']) ? $author['bio']['mk'] : $user->description,
            ));
        }
        update_user_meta((int) $uid, '_macedonia_mk_name_en', isset($author['name']['en']) ? $author['name']['en'] : $name);
        update_user_meta((int) $uid, '_macedonia_mk_role_mk', isset($author['role']['mk']) ? $author['role']['mk'] : '');
        update_user_meta((int) $uid, '_macedonia_mk_role_en', isset($author['role']['en']) ? $author['role']['en'] : '');
        update_user_meta((int) $uid, '_macedonia_mk_bio_en', isset($author['bio']['en']) ? $author['bio']['en'] : '');
        $ids[ $author['id'] ] = (int) $uid;
    }
    return $ids;
}

function macedonia_mk_seed_articles($articles, $cat_ids, $author_ids) {
    $ids        = array();
    $sticky     = array();
    $default_id = get_current_user_id() ? get_current_user_id() : 1;
    foreach ($articles as $article) {
        $slug = sanitize_title($article['slug']);
        $existing = get_page_by_path($slug, OBJECT, 'post');
        $title_mk = isset($article['title']['mk']) ? $article['title']['mk'] : $slug;
        $content_mk = macedonia_mk_article_html($article, 'mk');
        $content_en = macedonia_mk_article_html($article, 'en');
        $excerpt_mk = isset($article['excerpt']['mk']) ? $article['excerpt']['mk'] : '';
        $date       = isset($article['publishedAt']) ? gmdate('Y-m-d H:i:s', strtotime($article['publishedAt'])) : current_time('mysql', true);
        $author_key = isset($article['authorId']) ? $article['authorId'] : '';
        $author     = isset($author_ids[ $author_key ]) ? (int) $author_ids[ $author_key ] : $default_id;
        $payload    = array(
            'post_title'    => $title_mk,
            'post_name'     => $slug,
            'post_status'   => 'publish',
            'post_type'     => 'post',
            'post_content'  => $content_mk,
            'post_excerpt'  => $excerpt_mk,
            'post_author'   => $author,
            'post_date'     => get_date_from_gmt($date),
            'post_date_gmt' => $date,
        );
        if ($existing) {
            $payload['ID'] = (int) $existing->ID;
            $post_id       = wp_update_post($payload, true);
        } else {
            $post_id = wp_insert_post($payload, true);
        }
        if (! $post_id || is_wp_error($post_id)) {
            continue;
        }
        $post_id = (int) $post_id;

        $terms = array();
        $cat_slug = isset($article['category']) ? $article['category'] : '';
        if ($cat_slug && isset($cat_ids[ $cat_slug ])) {
            $terms[] = (int) $cat_ids[ $cat_slug ];
        }
        if (! empty($article['breaking'])) {
            $itno = get_category_by_slug('itno');
            if ($itno) {
                $terms[] = (int) $itno->term_id;
            }
        }
        if ($terms) {
            wp_set_post_terms($post_id, $terms, 'category');
        }
        $tags = isset($article['tags']['mk']) ? $article['tags']['mk'] : array();
        if (! empty($article['breaking'])) {
            $tags[] = 'Итно';
        }
        if ($tags) {
            wp_set_post_terms($post_id, $tags, 'post_tag');
        }
        if (! empty($article['tags']['en'])) {
            update_post_meta($post_id, '_macedonia_mk_tags_en', $article['tags']['en']);
        }

        update_post_meta($post_id, '_macedonia_mk_title_en', isset($article['title']['en']) ? $article['title']['en'] : '');
        update_post_meta($post_id, '_macedonia_mk_excerpt_en', isset($article['excerpt']['en']) ? $article['excerpt']['en'] : '');
        update_post_meta($post_id, '_macedonia_mk_content_en', $content_en);
        update_post_meta($post_id, '_macedonia_mk_minutes', isset($article['readMinutes']) ? (int) $article['readMinutes'] : 4);
        if (! empty($article['trending'])) {
            update_post_meta($post_id, '_macedonia_mk_trending', (int) $article['trending']);
        } else {
            delete_post_meta($post_id, '_macedonia_mk_trending');
        }
        if (! empty($article['breaking'])) {
            update_post_meta($post_id, '_macedonia_mk_breaking', 1);
        }
        if (! empty($article['featured'])) {
            $sticky[] = $post_id;
        }

        if (! empty($article['image'])) {
            macedonia_mk_sideload_theme_image($post_id, basename($article['image']), $title_mk);
        }

        $ids[] = $post_id;
    }
    if ($sticky) {
        update_option('sticky_posts', array_values(array_unique(array_slice($sticky, 0, 4))));
    }
    return $ids;
}

function macedonia_mk_article_html($article, $lang) {
    $paras  = isset($article['body'][ $lang ]) ? $article['body'][ $lang ] : array();
    $embeds = isset($article['embeds']) ? $article['embeds'] : array();
    $html   = '';
    foreach ($paras as $i => $para) {
        $html .= '<p>' . esc_html($para) . '</p>' . "\n";
        foreach ($embeds as $embed) {
            $after = isset($embed['after']) ? (int) $embed['after'] : 0;
            if ($after === $i && ! empty($embed['source'])) {
                $caption = '';
                if (! empty($embed['caption'][ $lang ])) {
                    $caption = $embed['caption'][ $lang ];
                }
                $html .= macedonia_mk_embed_block($embed['source'], $caption);
            }
        }
    }
    return $html;
}

function macedonia_mk_embed_block($url, $caption = '') {
    $url  = esc_url_raw($url);
    $cap  = $caption ? '<figcaption>' . esc_html($caption) . '</figcaption>' : '';
    $html = '<!-- wp:embed {"url":"' . esc_attr($url) . '","type":"video"} -->' . "\n";
    $html .= '<figure class="wp-block-embed"><div class="wp-block-embed__wrapper">' . "\n";
    $html .= esc_url($url) . "\n";
    $html .= '</div>' . $cap . '</figure>' . "\n";
    $html .= '<!-- /wp:embed -->' . "\n";
    return $html;
}

function macedonia_mk_sideload_theme_image($post_id, $filename, $title) {
    $src = MACEDONIA_MK_DIR . '/assets/images/' . $filename;
    if (! is_readable($src)) {
        return 0;
    }
    $existing = get_posts(array(
        'post_type'      => 'attachment',
        'posts_per_page' => 1,
        'meta_key'       => '_macedonia_mk_image',
        'meta_value'     => $filename,
        'fields'         => 'ids',
    ));
    if ($existing) {
        $attach_id = (int) $existing[0];
        set_post_thumbnail($post_id, $attach_id);
        return $attach_id;
    }

    $bits = wp_upload_bits($filename, null, (string) file_get_contents($src));
    if (! empty($bits['error'])) {
        return 0;
    }
    $filetype  = wp_check_filetype($filename, null);
    $attach_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/jpeg',
        'post_title'     => $title,
        'post_content'   => '',
        'post_status'    => 'inherit',
    ), $bits['file'], $post_id);
    if (! $attach_id || is_wp_error($attach_id)) {
        return 0;
    }
    $meta = wp_generate_attachment_metadata($attach_id, $bits['file']);
    wp_update_attachment_metadata($attach_id, $meta);
    update_post_meta($attach_id, '_macedonia_mk_image', $filename);
    set_post_thumbnail($post_id, $attach_id);
    return (int) $attach_id;
}

function macedonia_mk_seed_pages($catalog) {
    $ids     = array();
    $authors = isset($catalog['authors']) ? $catalog['authors'] : array();
    $about_mk = '<p>Macedonia.mk е бесплатен, професионален вестички портал. Работиме со јасна редакциска линија: факти прво, јазик прецизен, наслов без сензација.</p>';
    $about_mk .= '<p>Покриваме политика, економија, општество, култура, спорт и свет — со фокус на она што го менува животот во Скопје, Охрид, Битола, Тетово и низ целата земја.</p>';
    $about_mk .= '<p>Порталот е изграден како современ дигитален весник: брз на телефон, читлив на компјутер, со пребарување, зачувани статии, дневен билтен и видео од YouTube и TikTok.</p>';
    $about_mk .= '<h2>Редакција</h2><ul>';
    $about_en = '<p>Macedonia.mk is a free, professional news portal. The newsroom rule is simple: facts first, precise language, no sensational headlines.</p>';
    $about_en .= '<p>We cover politics, the economy, society, culture, sport and the world — with a focus on what changes daily life in Skopje, Ohrid, Bitola, Tetovo and across the country.</p>';
    $about_en .= '<p>The site is built as a modern digital newspaper: fast on a phone, readable on a desktop, with search, saved articles, a morning briefing, and YouTube and TikTok video rails.</p>';
    $about_en .= '<h2>Newsroom</h2><ul>';
    foreach ($authors as $author) {
        $about_mk .= '<li><strong>' . esc_html($author['name']['mk']) . '</strong> — ' . esc_html($author['role']['mk']) . '. ' . esc_html($author['bio']['mk']) . '</li>';
        $about_en .= '<li><strong>' . esc_html($author['name']['en']) . '</strong> — ' . esc_html($author['role']['en']) . '. ' . esc_html($author['bio']['en']) . '</li>';
    }
    $about_mk .= '</ul>';
    $about_en .= '</ul>';

    $pages = array(
        'about'   => array(
            'title'    => 'За нас',
            'title_en' => 'About',
            'template' => '',
            'content'  => $about_mk,
            'en'       => $about_en,
        ),
        'contact' => array(
            'title'    => 'Контакт',
            'title_en' => 'Contact',
            'template' => 'page-templates/contact.php',
            'content'  => '<p>За деманти, дописи и соработка пишете ни. Одговараме во рок од еден работен ден.</p><p>Редакција: news@macedonia.mk</p>',
            'en'       => '<p>Corrections, letters and partnerships — write to us. We reply within one working day.</p><p>Newsdesk: news@macedonia.mk</p>',
        ),
        'embed'   => array(
            'title'    => 'Embed код',
            'title_en' => 'Embed code',
            'template' => 'page-templates/embed.php',
            'content'  => '<p>Залепете URL или службен embed код од Facebook, Instagram, YouTube или TikTok.</p>',
            'en'       => '<p>Paste a URL or official embed code from Facebook, Instagram, YouTube or TikTok.</p>',
        ),
        'saved'   => array(
            'title'    => 'Зачувано',
            'title_en' => 'Saved',
            'template' => 'page-templates/saved.php',
            'content'  => '',
            'en'       => '',
        ),
        'privacy' => array(
            'title'    => 'Приватност',
            'title_en' => 'Privacy',
            'template' => '',
            'content'  => '<p>Macedonia.mk собира само она што е потребно за билтен и коментари: е-пошта и име што самите ги внесувате. Не продаваме лични податоци. Колачиња се користат за јазик и зачувани статии на вашиот уред.</p>',
            'en'       => '<p>Macedonia.mk collects only what is needed for the briefing and comments: an email and a name you type yourself. We do not sell personal data. Cookies store language and saved articles on your device.</p>',
        ),
        'terms'   => array(
            'title'    => 'Услови',
            'title_en' => 'Terms',
            'template' => '',
            'content'  => '<p>Содржината на Macedonia.mk е за јавно информирање. Цитирајте со линк кон оригиналната статија. Коментарите се уредуваат против говор на омраза и клевета.</p>',
            'en'       => '<p>Macedonia.mk is published for public information. Quote with a link to the original article. Comments are moderated against hate speech and libel.</p>',
        ),
    );

    foreach ($pages as $slug => $info) {
        $page = get_page_by_path($slug);
        $payload = array(
            'post_title'   => $info['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => $info['content'],
        );
        if ($page) {
            $payload['ID'] = (int) $page->ID;
            $id            = wp_update_post($payload, true);
        } else {
            $id = wp_insert_post($payload, true);
        }
        if (! $id || is_wp_error($id)) {
            continue;
        }
        $id = (int) $id;
        if ($info['template']) {
            update_post_meta($id, '_wp_page_template', $info['template']);
        }
        update_post_meta($id, '_macedonia_mk_title_en', $info['title_en']);
        update_post_meta($id, '_macedonia_mk_content_en', $info['en']);
        $ids[ $slug ] = $id;
    }
    return $ids;
}

function macedonia_mk_ensure_menus() {
    $menu = wp_get_nav_menu_object('Macedonia.mk');
    if (! $menu) {
        $menu_id = wp_create_nav_menu('Macedonia.mk');
        if (is_wp_error($menu_id)) {
            return;
        }
    } else {
        $menu_id = (int) $menu->term_id;
        $items   = wp_get_nav_menu_items($menu_id);
        if ($items) {
            foreach ($items as $item) {
                wp_delete_post((int) $item->ID, true);
            }
        }
    }

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title'  => 'Почетна',
        'menu-item-url'    => home_url('/'),
        'menu-item-status' => 'publish',
        'menu-item-type'   => 'custom',
    ));
    $preferred = array('makedonija', 'politika', 'ekonomija', 'svet', 'sport', 'kultura', 'hronika', 'zdravje', 'tehnologija', 'zivot');
    foreach ($preferred as $slug) {
        $cat = get_category_by_slug($slug);
        if (! $cat || is_wp_error($cat)) {
            continue;
        }
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title'     => $cat->name,
            'menu-item-object'    => 'category',
            'menu-item-object-id' => (int) $cat->term_id,
            'menu-item-type'      => 'taxonomy',
            'menu-item-status'    => 'publish',
        ));
    }

    $footer = wp_get_nav_menu_object('Macedonia.mk footer');
    if (! $footer) {
        $footer_id = wp_create_nav_menu('Macedonia.mk footer');
    } else {
        $footer_id = (int) $footer->term_id;
        $items     = wp_get_nav_menu_items($footer_id);
        if ($items) {
            foreach ($items as $item) {
                wp_delete_post((int) $item->ID, true);
            }
        }
    }
    if (! is_wp_error($footer_id)) {
        foreach (array('about', 'contact', 'embed', 'saved', 'privacy', 'terms') as $slug) {
            $page = get_page_by_path($slug);
            if (! $page) {
                continue;
            }
            wp_update_nav_menu_item($footer_id, 0, array(
                'menu-item-title'     => $page->post_title,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => (int) $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
            ));
        }
    }

    $locations            = get_theme_mod('nav_menu_locations', array());
    $locations['primary'] = (int) $menu_id;
    if (! is_wp_error($footer_id)) {
        $locations['footer'] = (int) $footer_id;
    }
    set_theme_mod('nav_menu_locations', $locations);
}

function macedonia_mk_localized_title($title, $post_id = 0) {
    if (is_admin() || 'en' !== macedonia_mk_lang() || ! $post_id) {
        return $title;
    }
    $en = get_post_meta($post_id, '_macedonia_mk_title_en', true);
    return $en ? $en : $title;
}
add_filter('the_title', 'macedonia_mk_localized_title', 10, 2);

function macedonia_mk_localized_excerpt($excerpt, $post = null) {
    if (is_admin() || 'en' !== macedonia_mk_lang()) {
        return $excerpt;
    }
    $post = get_post($post);
    if (! $post) {
        return $excerpt;
    }
    $en = get_post_meta($post->ID, '_macedonia_mk_excerpt_en', true);
    return $en ? $en : $excerpt;
}
add_filter('get_the_excerpt', 'macedonia_mk_localized_excerpt', 10, 2);

function macedonia_mk_localized_content($content) {
    if (is_admin() || 'en' !== macedonia_mk_lang() || ! in_the_loop() || ! is_main_query()) {
        return $content;
    }
    $en = get_post_meta(get_the_ID(), '_macedonia_mk_content_en', true);
    return $en ? $en : $content;
}
add_filter('the_content', 'macedonia_mk_localized_content', 5);

function macedonia_mk_localized_term($term) {
    if (is_admin() || is_wp_error($term) || ! $term || 'en' !== macedonia_mk_lang()) {
        return $term;
    }
    if (isset($term->taxonomy) && in_array($term->taxonomy, array('category', 'post_tag'), true)) {
        $en = get_term_meta($term->term_id, '_macedonia_mk_name_en', true);
        if ($en) {
            $term->name = $en;
        }
    }
    return $term;
}
add_filter('get_term', 'macedonia_mk_localized_term');

function macedonia_mk_admin_menu() {
    add_theme_page(
        'Macedonia.mk site',
        'Macedonia.mk site',
        'edit_theme_options',
        'macedonia-mk-site',
        'macedonia_mk_admin_page'
    );
}
add_action('admin_menu', 'macedonia_mk_admin_menu');

function macedonia_mk_admin_page() {
    if (! current_user_can('edit_theme_options')) {
        return;
    }
    $notice = '';
    if (isset($_POST['macedonia_mk_seed']) && check_admin_referer('macedonia_mk_seed')) {
        $stats  = macedonia_mk_seed_site(true);
        $notice = isset($stats['error'])
            ? 'Could not load demo content.'
            : sprintf('Loaded %d articles, %d pages, %d categories.', (int) $stats['posts'], (int) $stats['pages'], (int) $stats['categories']);
    }
    $seeded = (bool) get_option('macedonia_mk_seeded');
    $stats  = get_option('macedonia_mk_seed_stats', array());
    echo '<div class="wrap"><h1>Macedonia.mk — complete site</h1>';
    if ($notice) {
        echo '<div class="notice notice-success"><p>' . esc_html($notice) . '</p></div>';
    }
    echo '<p>This installs the full newspaper you saw in the preview: 24 Macedonian news articles, featured images, six authors, ten categories, About / Contact / Embed pages, menus, and YouTube + TikTok rails.</p>';
    if ($seeded && $stats) {
        echo '<p><strong>Status:</strong> loaded — ' . esc_html((string) ($stats['posts'] ?? 0)) . ' posts, ' . esc_html((string) ($stats['pages'] ?? 0)) . ' pages.</p>';
    } else {
        echo '<p><strong>Status:</strong> not loaded yet. Activate the theme or press the button.</p>';
    }
    echo '<form method="post">';
    wp_nonce_field('macedonia_mk_seed');
    submit_button($seeded ? 'Reload complete newspaper' : 'Load complete newspaper', 'primary', 'macedonia_mk_seed');
    echo '</form></div>';
}

function macedonia_mk_admin_notice() {
    if (! current_user_can('edit_theme_options') || get_option('macedonia_mk_seeded')) {
        return;
    }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && 'themes' !== $screen->id && 'appearance_page_macedonia-mk-site' !== $screen->id) {
        return;
    }
    $url = admin_url('themes.php?page=macedonia-mk-site');
    echo '<div class="notice notice-info"><p><strong>Macedonia.mk</strong> — load the complete newspaper (24 articles, images, pages, menus) from <a href="' . esc_url($url) . '">Appearance → Macedonia.mk site</a>.</p></div>';
}
add_action('admin_notices', 'macedonia_mk_admin_notice');
