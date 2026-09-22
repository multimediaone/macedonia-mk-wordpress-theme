<?php
/**
 * Macedonia.mk theme bootstrap.
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

define('MACEDONIA_MK_VERSION', '1.2.0');
define('MACEDONIA_MK_DIR', get_template_directory());
define('MACEDONIA_MK_URI', get_template_directory_uri());

require_once MACEDONIA_MK_DIR . '/inc/i18n.php';
require_once MACEDONIA_MK_DIR . '/inc/setup.php';
require_once MACEDONIA_MK_DIR . '/inc/template-tags.php';
require_once MACEDONIA_MK_DIR . '/inc/customizer.php';
require_once MACEDONIA_MK_DIR . '/inc/seo.php';
require_once MACEDONIA_MK_DIR . '/inc/ajax.php';
