<?php
/**
 * Newsletter + contact AJAX.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

function macedonia_mk_ajax_subscribe() {
    check_ajax_referer('macedonia_mk', 'nonce');
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    if (! is_email($email)) {
        wp_send_json_error(array('message' => macedonia_mk_t('invalidEmail')), 400);
    }
    $list = get_option('macedonia_mk_subscribers', array());
    if (! is_array($list)) {
        $list = array();
    }
    if (! in_array($email, $list, true)) {
        $list[] = $email;
        update_option('macedonia_mk_subscribers', $list, false);
        wp_mail(
            get_option('admin_email'),
            sprintf('[%s] Нов претплатник', get_bloginfo('name')),
            $email
        );
    }
    wp_send_json_success(array('message' => macedonia_mk_t('subscribeOk')));
}
add_action('wp_ajax_macedonia_mk_subscribe', 'macedonia_mk_ajax_subscribe');
add_action('wp_ajax_nopriv_macedonia_mk_subscribe', 'macedonia_mk_ajax_subscribe');

function macedonia_mk_ajax_contact() {
    check_ajax_referer('macedonia_mk', 'nonce');
    $name    = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    if (! $name || ! $message) {
        wp_send_json_error(array('message' => macedonia_mk_t('required')), 400);
    }
    if (! is_email($email)) {
        wp_send_json_error(array('message' => macedonia_mk_t('invalidEmail')), 400);
    }
    $sent = wp_mail(
        get_option('admin_email'),
        sprintf('[%s] %s', get_bloginfo('name'), $name),
        $message . "\n\n" . $email,
        array('Reply-To: ' . $name . ' <' . $email . '>')
    );
    if (! $sent) {
        wp_send_json_error(array('message' => macedonia_mk_t('required')), 500);
    }
    wp_send_json_success(array('message' => macedonia_mk_t('contactOk')));
}
add_action('wp_ajax_macedonia_mk_contact', 'macedonia_mk_ajax_contact');
add_action('wp_ajax_nopriv_macedonia_mk_contact', 'macedonia_mk_ajax_contact');
