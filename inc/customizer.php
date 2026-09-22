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
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('macedonia_mk_youtube_list', array(
        'label'       => __('YouTube videos', 'macedonia-mk'),
        'description' => __('Optional. One per line: URL | title | duration. Leave empty to hide the rail.', 'macedonia-mk'),
        'section'     => 'macedonia_mk_newsroom',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('macedonia_mk_tiktok_list', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('macedonia_mk_tiktok_list', array(
        'label'       => __('TikTok videos', 'macedonia-mk'),
        'description' => __('Optional. One per line: URL | title | duration. Leave empty to hide the rail.', 'macedonia-mk'),
        'section'     => 'macedonia_mk_newsroom',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'macedonia_mk_customize_register');
