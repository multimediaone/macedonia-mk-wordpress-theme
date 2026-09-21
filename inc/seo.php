<?php
/**
 * News SEO: JSON-LD, Open Graph, canonical extras.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_json_ld() {
    $org = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'NewsMediaOrganization',
        'name'            => get_bloginfo('name'),
        'url'             => home_url('/'),
        'logo'            => array(
            '@type' => 'ImageObject',
            'url'   => MACEDONIA_MK_URI . '/screenshot.png',
        ),
        'sameAs'          => array_values(array_filter(array(
            macedonia_mk_option('facebook'),
            macedonia_mk_option('instagram'),
            macedonia_mk_option('youtube'),
            macedonia_mk_option('tiktok'),
        ))),
    );

    $graph = array($org);

    if (is_front_page()) {
        $graph[] = array(
            '@type'       => 'WebSite',
            'name'        => get_bloginfo('name'),
            'url'         => home_url('/'),
            'inLanguage'  => 'mk',
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ),
        );
    }

    if (is_singular('post')) {
        $post = get_queried_object();
        $img  = get_the_post_thumbnail_url($post, 'full');
        $item = array(
            '@type'            => 'NewsArticle',
            'headline'         => get_the_title($post),
            'datePublished'    => get_post_time('c', true, $post),
            'dateModified'     => get_post_modified_time('c', true, $post),
            'mainEntityOfPage' => get_permalink($post),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta('display_name', $post->post_author),
                'url'   => get_author_posts_url($post->post_author),
            ),
            'publisher'        => array(
                '@type' => 'NewsMediaOrganization',
                'name'  => get_bloginfo('name'),
                'url'   => home_url('/'),
            ),
            'description'      => wp_strip_all_tags(get_the_excerpt($post)),
        );
        if ($img) {
            $item['image'] = array($img);
        }
        $graph[] = $item;

        $crumbs = array(
            array('name' => macedonia_mk_t('home'), 'item' => home_url('/')),
        );
        $cat = macedonia_mk_category($post);
        if ($cat) {
            $crumbs[] = array('name' => $cat->name, 'item' => get_category_link($cat));
        }
        $crumbs[] = array('name' => get_the_title($post), 'item' => get_permalink($post));
        $list = array();
        foreach ($crumbs as $i => $c) {
            $list[] = array(
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $c['name'],
                'item'     => $c['item'],
            );
        }
        $graph[] = array(
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $list,
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode(array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'macedonia_mk_json_ld', 20);

function macedonia_mk_og() {
    if (! is_singular()) {
        return;
    }
    $post = get_queried_object();
    $img  = get_the_post_thumbnail_url($post, 'full');
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr(get_the_title($post)) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags(get_the_excerpt($post))) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink($post)) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    if ($img) {
        echo '<meta property="og:image" content="' . esc_url($img) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($img) . '" />' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta property="article:published_time" content="' . esc_attr(get_post_time('c', true, $post)) . '" />' . "\n";
}
add_action('wp_head', 'macedonia_mk_og', 5);
