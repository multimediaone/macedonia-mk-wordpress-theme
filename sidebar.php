<?php
/**
 * Default sidebar.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
if (is_singular('post') && is_active_sidebar('sidebar-single')) {
    dynamic_sidebar('sidebar-single');
    return;
}
if (is_active_sidebar('sidebar-home')) {
    dynamic_sidebar('sidebar-home');
}
