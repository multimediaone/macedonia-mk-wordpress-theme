<?php
/**
 * YouTube / TikTok sidebar rail.
 *
 * @package Macedonia_MK
 */
if (! defined('ABSPATH')) {
    exit;
}
$provider = isset($args['provider']) ? $args['provider'] : 'youtube';
$clips    = isset($args['clips']) ? $args['clips'] : array();
if (! $clips) {
    return;
}
$title = 'youtube' === $provider ? macedonia_mk_t('youtubeVideos') : macedonia_mk_t('tiktokVideos');
$all   = 'youtube' === $provider
    ? macedonia_mk_option('youtube', 'https://www.youtube.com/@macedonia.mk')
    : macedonia_mk_option('tiktok', 'https://www.tiktok.com/@macedonia.mk');
?>
<aside class="mk-rail" data-video-rail>
    <header class="mk-rail__head">
        <h2>
            <?php macedonia_mk_icon($provider); ?>
            <?php echo esc_html($title); ?>
        </h2>
        <a class="mk-rail__all" href="<?php echo esc_url($all); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(macedonia_mk_t('seeAll')); ?></a>
    </header>
    <div class="mk-rail__body">
        <div data-rail-stage></div>
        <p class="mk-rail__more"><?php echo esc_html(macedonia_mk_t('moreVideos')); ?></p>
        <ul class="mk-rail__list">
            <?php foreach ($clips as $i => $clip) :
                $url = $clip['url'];
                $thumb = $clip['thumb'];
                if (! $thumb && 'youtube' === $provider) {
                    $id = macedonia_mk_youtube_id($url);
                    if ($id) {
                        $thumb = 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
                    }
                }
                ?>
                <li>
                    <button type="button" data-rail-item data-url="<?php echo esc_url($url); ?>" data-title="<?php echo esc_attr($clip['title']); ?>" data-thumb="<?php echo esc_url($thumb); ?>" class="<?php echo 0 === $i ? 'is-on' : ''; ?>">
                        <span class="mk-rail__thumb">
                            <?php if ($thumb) : ?><img src="<?php echo esc_url($thumb); ?>" alt=""><?php endif; ?>
                            <span class="mk-rail__play"><?php macedonia_mk_icon('play'); ?></span>
                        </span>
                        <span>
                            <span class="mk-rail__item-title"><?php echo esc_html($clip['title']); ?></span>
                            <span class="mk-rail__item-meta"><?php echo esc_html($clip['duration']); ?></span>
                        </span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>
