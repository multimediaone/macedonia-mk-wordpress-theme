<?php
/**
 * Simple breadcrumbs.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$items = isset($args['items']) ? array_values(array_filter($args['items'])) : array();
if (! $items) {
    return;
}
?>
<nav class="mk-crumbs" aria-label="Breadcrumb">
    <?php foreach ($items as $i => $item) : ?>
        <?php if ($i > 0) : ?><span>/</span><?php endif; ?>
        <?php if (! empty($item['url'])) : ?>
            <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
        <?php else : ?>
            <span><?php echo esc_html($item['label']); ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
