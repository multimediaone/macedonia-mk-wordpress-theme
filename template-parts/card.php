<?php
/**
 * Article card variants: hero, overlay, grid, row, compact.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$post    = isset($args['post']) ? get_post($args['post']) : get_post();
$variant = isset($args['variant']) ? $args['variant'] : 'grid';
if (! $post) {
    return;
}
$cat  = macedonia_mk_category($post);
$link = get_permalink($post);
$title = get_the_title($post);
$excerpt = wp_strip_all_tags(get_the_excerpt($post));
$author = get_the_author_meta('display_name', $post->post_author);
$date = macedonia_mk_date($post);
$mins = macedonia_mk_reading_minutes($post);
$chip_class = in_array($variant, array('hero', 'overlay'), true) ? 'mk-chip mk-chip--light' : 'mk-chip';
?>
<a class="mk-card mk-card--<?php echo esc_attr($variant); ?>" href="<?php echo esc_url($link); ?>">
    <?php if ('compact' !== $variant) : ?>
        <span class="mk-card__media">
            <?php
            $size = 'hero' === $variant ? 'mk-hero' : ('overlay' === $variant ? 'mk-overlay' : ('row' === $variant ? 'mk-row' : 'mk-card'));
            macedonia_mk_thumb($post, $size, 'img-zoom', 'hero' === $variant);
            ?>
        </span>
    <?php endif; ?>
    <?php if (in_array($variant, array('hero', 'overlay'), true)) : ?>
        <span class="mk-card__shade"></span>
        <span class="mk-card__body">
            <?php if ($cat) : ?><span class="<?php echo esc_attr($chip_class); ?>"><?php echo esc_html($cat->name); ?></span><?php endif; ?>
            <<?php echo 'hero' === $variant ? 'h2' : 'h3'; ?> class="mk-card__title"><?php echo esc_html($title); ?></<?php echo 'hero' === $variant ? 'h2' : 'h3'; ?>>
            <?php if ('hero' === $variant && $excerpt) : ?>
                <span class="mk-card__excerpt"><?php echo esc_html($excerpt); ?></span>
            <?php endif; ?>
            <?php if ('hero' === $variant) : ?>
                <span class="mk-card__meta"><?php echo esc_html($author . ' · ' . $date); ?></span>
            <?php endif; ?>
        </span>
    <?php else : ?>
        <span>
            <?php if ($cat && 'compact' !== $variant) : ?><span class="mk-chip"><?php echo esc_html($cat->name); ?></span><?php endif; ?>
            <span class="mk-card__title"><?php echo esc_html($title); ?></span>
            <?php if ('grid' === $variant && $excerpt) : ?>
                <span class="mk-card__excerpt"><?php echo esc_html($excerpt); ?></span>
            <?php endif; ?>
            <span class="mk-card__meta">
                <?php
                if ('grid' === $variant) {
                    echo esc_html($author . ' · ' . $date);
                } else {
                    echo esc_html($date . ' · ' . $mins . ' ' . macedonia_mk_t('minutes'));
                }
                ?>
            </span>
        </span>
    <?php endif; ?>
</a>
