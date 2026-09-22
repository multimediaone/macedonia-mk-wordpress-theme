=== Macedonia.mk ===
Contributors: macedonia-mk
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: news, magazine, blog, two-columns, custom-menu, featured-images, translation-ready

Newspaper theme for the live Macedonia.mk site. Uses the existing WordPress database. Does not add or delete content.

== Description ==

This theme only changes the design. All articles, categories, menus, images and permalinks stay in the WordPress database.

* Homepage loads the 10 latest published posts (title, excerpt, date, author, category, featured image)
* Existing navigation is assigned automatically when possible; otherwise Appearance → Menus
* Category and archive pages list the existing posts in each category
* Single posts keep their current URLs
* Optional YouTube / TikTok rails in the Customizer (hidden until you add URLs)

The theme never seeds demo articles and never deletes posts, pages, media or menus.

== Installation ==

1. Appearance → Themes → Add New → Upload Theme
2. Upload `macedonia-mk.zip` → Install Now → Activate
3. Existing posts appear on the homepage immediately
4. Appearance → Menus — confirm the primary menu location (existing items are not changed)
5. Optional: Appearance → Customize → Macedonia.mk newsroom for social URLs and video rails

Do not switch permalinks unless you already use Post name. The theme does not change Settings → Permalinks.

== Changelog ==

= 1.2.0 =
* No demo content. Homepage uses the 10 latest published posts from the existing database.
* Activation maps existing menus only; nothing is created or deleted.

= 1.1.0 =
* Demo newspaper seed (removed in 1.2.0).

= 1.0.0 =
* First public release.
