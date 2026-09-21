<?php
/**
 * Customizer: social, weather, video rails, breaking category.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_customize_register($wp_customize) {
    $wp_customize->add_section('macedonia_mk_newsroom', array(
        'title'    => __('Macedonia.mk newsroom', 'macedonia-mk'),
        'priority' => 30,
    ));

    $text_fields = array(
        'tagline'            => array('label' => __('Tagline', 'macedonia-mk'), 'default' => 'Вести од Македонија и светот'),
        'weather'            => array('label' => __('Weather line', 'macedonia-mk'), 'default' => 'Скопје 19°'),
        'weather_hint'       => array('label' => __('Weather hint', 'macedonia-mk'), 'default' => 'променливо облачно'),
        'facebook'           => array('label' => __('Facebook URL', 'macedonia-mk'), 'default' => 'https://www.facebook.com/macedonia.mk'),
        'instagram'          => array('label' => __('Instagram URL', 'macedonia-mk'), 'default' => 'https://www.instagram.com/macedonia.mk'),
        'youtube'            => array('label' => __('YouTube URL', 'macedonia-mk'), 'default' => 'https://www.youtube.com/@macedonia.mk'),
        'tiktok'             => array('label' => __('TikTok URL', 'macedonia-mk'), 'default' => 'https://www.tiktok.com/@macedonia.mk'),
        'breaking_category'  => array('label' => __('Breaking category slug', 'macedonia-mk'), 'default' => 'itno'),
    );

    foreach ($text_fields as $key => $field) {
        $id = 'macedonia_mk_' . $key;
        $wp_customize->add_setting($id, array(
            'default'           => $field['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));
        $wp_customize->add_control($id, array(
            'label'   => $field['label'],
            'section' => 'macedonia_mk_newsroom',
            'type'    => 'text',
        ));
    }

    $wp_customize->add_setting('macedonia_mk_youtube_list', array(
        'default'           => "https://www.youtube.com/watch?v=BBiZYJW7wbQ | Охрид — пет совети за градот и езерото | 7:10\nhttps://www.youtube.com/watch?v=we7nLWIHvoY | Стара скопска чаршија — прошетка во 4K | 42:36\nhttps://www.youtube.com/watch?v=xpJHPAvXftA | Еден ден во Охрид — Канео и стариот град | 8:24\nhttps://www.youtube.com/watch?v=e1DK5QP4Be4 | Скопје пеш — плоштад, Камен мост, Вардар | 37:36",
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('macedonia_mk_youtube_list', array(
        'label'       => __('YouTube videos', 'macedonia-mk'),
        'description' => __('One per line: URL | title | duration', 'macedonia-mk'),
        'section'     => 'macedonia_mk_newsroom',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('macedonia_mk_tiktok_list', array(
        'default'           => "https://www.tiktok.com/@ristespiroski/video/7405651822540606725 | Охридско Езеро во 15 секунди | 0:14\nhttps://www.tiktok.com/@finnmacedonia/video/7518701632549358870 | Што да пробате следно во Македонија | 1:24\nhttps://www.tiktok.com/@pelagonec7/video/7641071211287530772 | Охрид 1984 — архива што сè уште дише | 0:14",
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('macedonia_mk_tiktok_list', array(
        'label'       => __('TikTok videos', 'macedonia-mk'),
        'description' => __('One per line: URL | title | duration', 'macedonia-mk'),
        'section'     => 'macedonia_mk_newsroom',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'macedonia_mk_customize_register');
