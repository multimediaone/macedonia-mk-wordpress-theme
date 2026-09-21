<?php
/**
 * Template Name: Embed workbench
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <header class="mk-page-head">
        <p class="mk-page-head__kicker"><?php echo esc_html(macedonia_mk_t('newsroom')); ?></p>
        <h1><?php echo esc_html(macedonia_mk_t('embed')); ?></h1>
        <p><?php echo esc_html(macedonia_mk_t('embedLead')); ?></p>
    </header>
    <div class="mk-workbench">
        <div>
            <label><?php echo esc_html(macedonia_mk_t('embedPaste')); ?>
                <textarea data-embed-input placeholder="<?php echo esc_attr(macedonia_mk_t('embedPlaceholder')); ?>"></textarea>
            </label>
            <div class="mk-chips">
                <button type="button" data-embed-example="https://www.youtube.com/watch?v=BBiZYJW7wbQ">YouTube</button>
                <button type="button" data-embed-example="https://www.tiktok.com/@ristespiroski/video/7405651822540606725">TikTok</button>
                <button type="button" data-embed-example="https://www.instagram.com/p/">Instagram</button>
                <button type="button" data-embed-example="https://www.facebook.com/visitskopje">Facebook</button>
            </div>
            <p class="mk-box__title" style="margin-top:2rem"><?php echo esc_html(macedonia_mk_t('embedPreview')); ?></p>
            <div data-embed-preview></div>
        </div>
        <aside class="mk-box">
            <h2 class="mk-box__title"><?php echo esc_html(macedonia_mk_t('embedClean')); ?></h2>
            <pre class="mk-code" data-embed-code></pre>
            <button type="button" class="mk-btn" data-copy-embed style="width:100%;margin-top:0.75rem"><?php echo esc_html(macedonia_mk_t('copyEmbed')); ?></button>
            <p style="margin-top:0.75rem;font-size:0.9rem;color:var(--mk-muted)"><?php echo esc_html(macedonia_mk_t('embedHint')); ?></p>
            <ol style="margin-top:1.5rem;display:flex;flex-direction:column;gap:0.75rem;font-size:0.9rem;color:var(--mk-muted);list-style:decimal;padding-left:1.1rem">
                <li><strong style="color:var(--mk-ink)">YouTube.</strong> <?php echo esc_html(macedonia_mk_t('howYoutube')); ?></li>
                <li><strong style="color:var(--mk-ink)">Instagram.</strong> <?php echo esc_html(macedonia_mk_t('howInstagram')); ?></li>
                <li><strong style="color:var(--mk-ink)">Facebook.</strong> <?php echo esc_html(macedonia_mk_t('howFacebook')); ?></li>
                <li><strong style="color:var(--mk-ink)">TikTok.</strong> <?php echo esc_html(macedonia_mk_t('howTiktok')); ?></li>
            </ol>
        </aside>
    </div>
</div>
<?php
get_footer();
