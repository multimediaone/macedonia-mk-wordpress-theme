<?php
/**
 * Built-in MK / EN strings (works without .mo files).
 *
 * @package Macedonia_MK
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Active UI language: mk or en.
 */
function macedonia_mk_lang() {
    if (! empty($_GET['lang'])) { // phpcs:ignore WordPress.Security.NonceVerification
        $raw = sanitize_key(wp_unslash($_GET['lang'])); // phpcs:ignore WordPress.Security.NonceVerification
        if (in_array($raw, array('mk', 'en'), true)) {
            return $raw;
        }
    }
    if (! empty($_COOKIE['macedonia_mk_lang'])) {
        $cookie = sanitize_key(wp_unslash($_COOKIE['macedonia_mk_lang']));
        if (in_array($cookie, array('mk', 'en'), true)) {
            return $cookie;
        }
    }
    $locale = get_locale();
    return (0 === strpos($locale, 'en')) ? 'en' : 'mk';
}

/**
 * Translate a UI key.
 *
 * @param string $key Dictionary key.
 */
function macedonia_mk_t($key) {
    static $dict = null;
    if (null === $dict) {
        $dict = array(
            'mk' => array(
                'tagline'             => 'Вести од Македонија и светот',
                'breaking'            => 'Итно',
                'latest'              => 'Најново',
                'trending'            => 'Топ 5 оваа недела',
                'dontMiss'            => 'Не пропуштајте',
                'seeAll'              => 'Сите',
                'subscribeTitle'      => 'Билтен',
                'subscribeLead'       => 'Најважното од денот, секое утро во 7 часот.',
                'subscribeCta'        => 'Сакам',
                'subscribePlaceholder'=> 'вашата е-пошта',
                'subscribeOk'         => 'Ви благодариме. Билтенот е активен.',
                'search'              => 'Пребарај',
                'searchPlaceholder'   => 'Пребарај вести, автори, теми…',
                'saved'               => 'Зачувано',
                'about'               => 'За нас',
                'contact'             => 'Контакт',
                'share'               => 'Сподели',
                'related'             => 'Поврзани вести',
                'comments'            => 'Коментари',
                'writeComment'        => 'Напишете коментар',
                'send'                => 'Објави',
                'name'                => 'Име',
                'email'               => 'Е-пошта',
                'message'             => 'Порака',
                'weather'             => 'Скопје 19°',
                'weatherHint'         => 'променливо облачно',
                'copyright'           => '© %s Macedonia.mk. Сите права задржани.',
                'readMore'            => 'Прочитај повеќе',
                'minutes'             => 'мин. читање',
                'noResults'           => 'Нема резултати за оваа пребарување.',
                'bookmark'            => 'Зачувај',
                'bookmarked'          => 'Зачувано',
                'emptySaved'          => 'Сè уште немате зачувани статии.',
                'allNews'             => 'Сите вести',
                'follow'              => 'Следете нè',
                'mostRead'            => 'Најчитани',
                'latestArticles'      => 'Последни статии',
                'home'                => 'Почетна',
                'menu'                => 'Мени',
                'close'               => 'Затвори',
                'by'                  => 'од',
                'published'           => 'Објавено',
                'contactOk'           => 'Пораката е примена. Редакцијата ќе ви одговори.',
                'contactLead'         => 'За деманти, дописи и соработка пишете ни. Одговараме во рок од еден работен ден.',
                'sendMessage'         => 'Испрати',
                'required'            => 'Ова поле е задолжително.',
                'invalidEmail'        => 'Внесете валидна е-пошта.',
                'commentOk'           => 'Коментарот е објавен.',
                'noComments'          => 'Бидете први што ќе коментирате.',
                'copied'              => 'Линкот е копиран.',
                'resultsFor'          => 'Резултати за',
                'articlesCount'       => 'статии',
                'authorArticles'      => 'Статии од авторот',
                'newsroom'            => 'Редакција',
                'rssFeed'             => 'RSS канал',
                'embed'               => 'Embed код',
                'embedLead'           => 'Залепете URL или embed код од Facebook, Instagram, YouTube или TikTok — порталот го препознава и го чисти.',
                'embedPaste'          => 'URL или embed код',
                'embedPlaceholder'    => 'https://www.youtube.com/watch?v=…  или  <iframe src="…">',
                'embedPreview'        => 'Преглед',
                'embedClean'          => 'Чист iframe',
                'embedHint'           => 'Поддржани се линкови и службен embed код. Скрипти од трети страни не се вметнуваат — само безбеден iframe.',
                'embedInvalid'        => 'Не препознавме мрежа. Проверете дали линкот е од Facebook, Instagram, YouTube или TikTok.',
                'copyEmbed'           => 'Копирај embed код',
                'copyLink'            => 'Копирај линк',
                'playEmbed'           => 'Пушти',
                'openOn'              => 'Отвори на',
                'publishTo'           => 'Објави',
                'youtubeVideos'       => 'YouTube видеа',
                'tiktokVideos'        => 'TikTok видеа',
                'moreVideos'          => 'Повеќе видеа',
                'nowPlaying'          => 'Сега',
                'copiedInstagram'     => 'Линкот е копиран. Залепете го во објава на Instagram.',
                'copiedTiktok'        => 'Линкот е копиран. Залепете го во објава на TikTok.',
                'clear'               => 'Исчисти',
                'howYoutube'          => 'Share → Embed, или само линкот watch?v= / youtu.be.',
                'howInstagram'        => 'Три точки на објавата → Embed, или линк /p/ и /reel/.',
                'howFacebook'         => 'Share → Embed, линк до објава/видео, или адреса на страница за додаток.',
                'howTiktok'           => 'Share → Embed, или линк /@корисник/video/ID.',
                'footerBlurb'         => 'Macedonia.mk е дигитален весник за секој што сака јасни вести од земјата и регионот — без платен ѕид.',
                'pageNotFound'        => 'Страницата не е пронајдена.',
                'pageNotFoundLead'    => 'Линкот е застарен или адресата е погрешна. Вратете се на почетната или пребарајте.',
                'backHome'            => 'Кон почетна',
                'archives'            => 'Архива',
                'tagged'              => 'Ознака',
                'older'               => 'Постари',
                'newer'               => 'Понови',
                'pages'               => 'Страници',
                'placeholderThumb'    => 'Нема фотографија',
            ),
            'en' => array(
                'tagline'             => 'News from Macedonia and the world',
                'breaking'            => 'Breaking',
                'latest'              => 'Latest',
                'trending'            => 'Top 5 this week',
                'dontMiss'            => "Don't miss",
                'seeAll'              => 'See all',
                'subscribeTitle'      => 'Newsletter',
                'subscribeLead'       => "The day's essentials, every morning at 7.",
                'subscribeCta'        => 'I want in',
                'subscribePlaceholder'=> 'your email',
                'subscribeOk'         => 'Thank you. The newsletter is on.',
                'search'              => 'Search',
                'searchPlaceholder'   => 'Search news, authors, topics…',
                'saved'               => 'Saved',
                'about'               => 'About',
                'contact'             => 'Contact',
                'share'               => 'Share',
                'related'             => 'Related news',
                'comments'            => 'Comments',
                'writeComment'        => 'Write a comment',
                'send'                => 'Publish',
                'name'                => 'Name',
                'email'               => 'Email',
                'message'             => 'Message',
                'weather'             => 'Skopje 19°',
                'weatherHint'         => 'partly cloudy',
                'copyright'           => '© %s Macedonia.mk. All rights reserved.',
                'readMore'            => 'Read more',
                'minutes'             => 'min read',
                'noResults'           => 'No results for this search.',
                'bookmark'            => 'Save',
                'bookmarked'          => 'Saved',
                'emptySaved'          => 'You have no saved articles yet.',
                'allNews'             => 'All news',
                'follow'              => 'Follow us',
                'mostRead'            => 'Most read',
                'latestArticles'      => 'Latest articles',
                'home'                => 'Home',
                'menu'                => 'Menu',
                'close'               => 'Close',
                'by'                  => 'by',
                'published'           => 'Published',
                'contactOk'           => 'Message received. The newsroom will reply.',
                'contactLead'         => 'For corrections, letters and cooperation — we reply within one working day.',
                'sendMessage'         => 'Send',
                'required'            => 'This field is required.',
                'invalidEmail'        => 'Enter a valid email.',
                'commentOk'           => 'Comment published.',
                'noComments'          => 'Be the first to comment.',
                'copied'              => 'Link copied.',
                'resultsFor'          => 'Results for',
                'articlesCount'       => 'articles',
                'authorArticles'      => 'Articles by this author',
                'newsroom'            => 'Newsroom',
                'rssFeed'             => 'RSS feed',
                'embed'               => 'Embed code',
                'embedLead'           => 'Paste a URL or embed code from Facebook, Instagram, YouTube or TikTok — the site recognises it and cleans it.',
                'embedPaste'          => 'URL or embed code',
                'embedPlaceholder'    => 'https://www.youtube.com/watch?v=…  or  <iframe src="…">',
                'embedPreview'        => 'Preview',
                'embedClean'          => 'Clean iframe',
                'embedHint'           => 'Links and official embed code are supported. Third-party scripts are not injected — only a safe iframe.',
                'embedInvalid'        => 'Network not recognised. Use a Facebook, Instagram, YouTube or TikTok link.',
                'copyEmbed'           => 'Copy embed code',
                'copyLink'            => 'Copy link',
                'playEmbed'           => 'Play',
                'openOn'              => 'Open on',
                'publishTo'           => 'Share',
                'youtubeVideos'       => 'YouTube videos',
                'tiktokVideos'        => 'TikTok videos',
                'moreVideos'          => 'More videos',
                'nowPlaying'          => 'Now',
                'copiedInstagram'     => 'Link copied. Paste it into an Instagram post.',
                'copiedTiktok'        => 'Link copied. Paste it into a TikTok post.',
                'clear'               => 'Clear',
                'howYoutube'          => 'Share → Embed, or a watch?v= / youtu.be link.',
                'howInstagram'        => 'Three dots on the post → Embed, or a /p/ and /reel/ link.',
                'howFacebook'         => 'Share → Embed, a post/video link, or a page URL.',
                'howTiktok'           => 'Share → Embed, or a /@user/video/ID link.',
                'footerBlurb'         => 'Macedonia.mk is a digital newspaper for anyone who wants clear news from the country and the region — no paywall.',
                'pageNotFound'        => 'Page not found.',
                'pageNotFoundLead'    => 'The link is outdated or the address is wrong. Go home or search.',
                'backHome'            => 'Back home',
                'archives'            => 'Archive',
                'tagged'              => 'Tag',
                'older'               => 'Older',
                'newer'               => 'Newer',
                'pages'               => 'Pages',
                'placeholderThumb'    => 'No photograph',
            ),
        );
    }
    $lang = macedonia_mk_lang();
    if (isset($dict[ $lang ][ $key ])) {
        return $dict[ $lang ][ $key ];
    }
    if (isset($dict['mk'][ $key ])) {
        return $dict['mk'][ $key ];
    }
    return $key;
}

/**
 * Persist ?lang= in a cookie.
 */
function macedonia_mk_persist_lang() {
    if (empty($_GET['lang'])) { // phpcs:ignore WordPress.Security.NonceVerification
        return;
    }
    $raw = sanitize_key(wp_unslash($_GET['lang'])); // phpcs:ignore WordPress.Security.NonceVerification
    if (! in_array($raw, array('mk', 'en'), true)) {
        return;
    }
    setcookie('macedonia_mk_lang', $raw, time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), false);
    $_COOKIE['macedonia_mk_lang'] = $raw;
}
add_action('init', 'macedonia_mk_persist_lang', 1);
