<?php
/**
 * Template Name: Contact
 *
 * @package Macedonia_MK
 */
get_header();
?>
<div class="mk-wrap">
    <?php
    get_template_part('template-parts/breadcrumbs', null, array(
        'items' => array(
            array('label' => macedonia_mk_t('home'), 'url' => home_url('/')),
            array('label' => macedonia_mk_t('contact')),
        ),
    ));
    ?>
    <header class="mk-page-head">
        <h1><?php echo esc_html(macedonia_mk_t('contact')); ?></h1>
        <p><?php echo esc_html(macedonia_mk_t('contactLead')); ?></p>
    </header>
    <div class="mk-prose">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
    <form class="mk-form" data-contact style="max-width:36rem;margin-top:1.5rem">
        <label><?php echo esc_html(macedonia_mk_t('name')); ?>
            <input type="text" name="name" required>
        </label>
        <label><?php echo esc_html(macedonia_mk_t('email')); ?>
            <input type="email" name="email" required>
        </label>
        <label><?php echo esc_html(macedonia_mk_t('message')); ?>
            <textarea name="message" required></textarea>
        </label>
        <button type="submit" class="mk-btn"><?php echo esc_html(macedonia_mk_t('sendMessage')); ?></button>
        <p data-contact-status class="mk-ok"></p>
    </form>
</div>
<?php
get_footer();
