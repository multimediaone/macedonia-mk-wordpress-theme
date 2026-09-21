(function () {
  "use strict";

  var MK = window.MacedoniaMK || {};
  var SAVED_KEY = "macedonia-mk-saved";

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }
  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }
  function on(el, ev, fn) {
    if (el) el.addEventListener(ev, fn);
  }

  function savedIds() {
    try {
      var raw = localStorage.getItem(SAVED_KEY);
      var list = raw ? JSON.parse(raw) : [];
      return Array.isArray(list) ? list.map(String) : [];
    } catch (e) {
      return [];
    }
  }
  function writeSaved(ids) {
    localStorage.setItem(SAVED_KEY, JSON.stringify(ids));
    updateSavedBadge();
  }
  function updateSavedBadge() {
    var n = savedIds().length;
    qsa("[data-saved-count]").forEach(function (el) {
      el.textContent = n ? String(n) : "";
      el.hidden = n === 0;
    });
  }

  function parseSocial(text) {
    if (!text) return null;
    var yt =
      text.match(/[?&]v=([a-zA-Z0-9_-]{11})/) ||
      text.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/) ||
      text.match(/youtube\.com\/(?:embed|shorts|live)\/([a-zA-Z0-9_-]{11})/) ||
      text.match(/youtube-nocookie\.com\/embed\/([a-zA-Z0-9_-]{11})/);
    if (yt) {
      return {
        provider: "youtube",
        kind: "video",
        id: yt[1],
        url: "https://www.youtube.com/watch?v=" + yt[1],
        embedSrc: "https://www.youtube-nocookie.com/embed/" + yt[1] + "?rel=0",
        poster: "https://i.ytimg.com/vi/" + yt[1] + "/hqdefault.jpg",
      };
    }
    var tk =
      text.match(/tiktok\.com\/@[^/]+\/video\/(\d+)/) ||
      text.match(/tiktok\.com\/embed\/(?:v2\/)?(\d+)/);
    if (tk) {
      var handle = (text.match(/tiktok\.com\/(@[^/]+)/) || [])[1] || "";
      return {
        provider: "tiktok",
        kind: "video",
        id: tk[1],
        url: handle
          ? "https://www.tiktok.com/" + handle + "/video/" + tk[1]
          : "https://www.tiktok.com/embed/v2/" + tk[1],
        embedSrc: "https://www.tiktok.com/embed/v2/" + tk[1],
        poster: null,
      };
    }
    var ig = text.match(/instagram\.com\/(?:p|reel|tv)\/([A-Za-z0-9_-]+)/);
    if (ig) {
      var isReel = /\/reel\//.test(text);
      var path = isReel ? "reel" : "p";
      return {
        provider: "instagram",
        kind: "post",
        id: ig[1],
        url: "https://www.instagram.com/" + path + "/" + ig[1] + "/",
        embedSrc: "https://www.instagram.com/" + path + "/" + ig[1] + "/embed",
        poster: null,
      };
    }
    return null;
  }

  function bindOverlays() {
    var menu = qs("[data-mk-menu]");
    var search = qs("[data-mk-search]");
    qsa("[data-open-menu]").forEach(function (btn) {
      on(btn, "click", function () {
        if (menu) menu.classList.add("is-open");
      });
    });
    qsa("[data-open-search]").forEach(function (btn) {
      on(btn, "click", function () {
        if (search) search.classList.add("is-open");
        var input = qs("[data-live-search]");
        if (input) input.focus();
      });
    });
    qsa("[data-close-overlay]").forEach(function (btn) {
      on(btn, "click", function () {
        qsa(".mk-overlay.is-open").forEach(function (el) {
          el.classList.remove("is-open");
        });
      });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        qsa(".mk-overlay.is-open").forEach(function (el) {
          el.classList.remove("is-open");
        });
      }
    });
  }

  function bindLiveSearch() {
    var input = qs("[data-live-search]");
    var hits = qs("[data-search-hits]");
    if (!input || !hits || !MK.rest) return;
    var timer;
    on(input, "input", function () {
      clearTimeout(timer);
      var q = input.value.trim();
      if (q.length < 2) {
        hits.innerHTML = "";
        return;
      }
      timer = setTimeout(function () {
        fetch(MK.rest + "search?search=" + encodeURIComponent(q) + "&per_page=8&subtype=post")
          .then(function (r) {
            return r.json();
          })
          .then(function (rows) {
            if (!rows || !rows.length) {
              hits.innerHTML = '<p class="mk-empty">' + (MK.i18n.noResults || "") + "</p>";
              return;
            }
            hits.innerHTML = rows
              .map(function (row) {
                return (
                  '<a href="' +
                  row.url +
                  '"><strong>' +
                  row.title +
                  "</strong></a>"
                );
              })
              .join("");
          })
          .catch(function () {
            hits.innerHTML = "";
          });
      }, 220);
    });
  }

  function bindRails() {
    qsa("[data-video-rail]").forEach(function (rail) {
      var stage = qs("[data-rail-stage]", rail);
      var buttons = qsa("[data-rail-item]", rail);
      function show(btn) {
        if (!stage || !btn) return;
        buttons.forEach(function (b) {
          b.classList.toggle("is-on", b === btn);
        });
        var url = btn.getAttribute("data-url") || "";
        var title = btn.getAttribute("data-title") || "";
        var parsed = parseSocial(url);
        var poster = btn.getAttribute("data-thumb") || (parsed && parsed.poster) || "";
        var frameClass = parsed && parsed.provider === "tiktok" ? "social-frame is-portrait is-compact" : "social-frame is-video is-compact";
        var html =
          '<figure class="mk-embed mk-embed--compact"><div class="' +
          frameClass +
          '"><button type="button" class="mk-embed__play" data-embed-src="' +
          (parsed && parsed.embedSrc ? parsed.embedSrc : "") +
          '" data-embed-title="' +
          title.replace(/"/g, """) +
          '">' +
          (poster ? '<img src="' + poster + '" alt="">' : "") +
          '<span class="mk-embed__veil"></span><span class="mk-embed__cta"><span class="mk-play-disc"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l12-7z"/></svg></span><em>' +
          (MK.i18n.playEmbed || "Play") +
          " " +
          (parsed ? parsed.provider : "") +
          "</em></span></button></div><figcaption>" +
          title +
          "</figcaption></figure>";
        stage.innerHTML = html;
      }
      buttons.forEach(function (btn) {
        on(btn, "click", function () {
          show(btn);
        });
      });
      if (buttons[0]) show(buttons[0]);
    });
  }

  function bindEmbedPlay(root) {
    on(root || document, "click", function (e) {
      var btn = e.target.closest && e.target.closest("[data-embed-src]");
      if (!btn) return;
      var src = btn.getAttribute("data-embed-src");
      var title = btn.getAttribute("data-embed-title") || "";
      if (!src) return;
      var frame = document.createElement("iframe");
      frame.src = src;
      frame.title = title;
      frame.allow =
        "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
      frame.allowFullscreen = true;
      frame.referrerPolicy = "strict-origin-when-cross-origin";
      btn.parentNode.replaceChild(frame, btn);
    });
  }

  function bindShare() {
    qsa("[data-share]").forEach(function (btn) {
      on(btn, "click", function () {
        var kind = btn.getAttribute("data-share");
        var url = window.location.href;
        var title = document.title;
        if (kind === "facebook") {
          window.open(
            "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url),
            "_blank",
            "noopener,noreferrer,width=640,height=540"
          );
          return;
        }
        if (kind === "copy" || kind === "instagram" || kind === "tiktok") {
          if (navigator.clipboard) navigator.clipboard.writeText(url);
          alert(
            kind === "instagram"
              ? MK.i18n.copiedInstagram
              : kind === "tiktok"
                ? MK.i18n.copiedTiktok
                : MK.i18n.copied
          );
        }
        if (kind === "youtube" && MK.social && MK.social.youtube) {
          window.open(MK.social.youtube, "_blank", "noopener,noreferrer");
        }
      });
    });
  }

  function bindBookmark() {
    qsa("[data-bookmark]").forEach(function (btn) {
      var id = String(btn.getAttribute("data-bookmark"));
      function paint() {
        var onNow = savedIds().indexOf(id) !== -1;
        btn.classList.toggle("is-on", onNow);
        var label = qs("[data-bookmark-label]", btn);
        if (label) label.textContent = onNow ? MK.i18n.bookmarked : MK.i18n.bookmark;
      }
      paint();
      on(btn, "click", function () {
        var ids = savedIds();
        var i = ids.indexOf(id);
        if (i === -1) ids.push(id);
        else ids.splice(i, 1);
        writeSaved(ids);
        paint();
      });
    });
  }

  function bindSubscribe() {
    qsa("[data-subscribe]").forEach(function (form) {
      on(form, "submit", function (e) {
        e.preventDefault();
        var email = (qs('input[type="email"]', form) || {}).value || "";
        var body = new FormData();
        body.append("action", "macedonia_mk_subscribe");
        body.append("nonce", MK.nonce);
        body.append("email", email);
        fetch(MK.ajax, { method: "POST", body: body, credentials: "same-origin" })
          .then(function (r) {
            return r.json();
          })
          .then(function (res) {
            var msg = (res.data && res.data.message) || MK.i18n.subscribeOk;
            form.innerHTML = '<p class="mk-ok">' + msg + "</p>";
          });
      });
    });
  }

  function bindContact() {
    var form = qs("[data-contact]");
    if (!form) return;
    on(form, "submit", function (e) {
      e.preventDefault();
      var body = new FormData(form);
      body.append("action", "macedonia_mk_contact");
      body.append("nonce", MK.nonce);
      fetch(MK.ajax, { method: "POST", body: body, credentials: "same-origin" })
        .then(function (r) {
          return r.json();
        })
        .then(function (res) {
          var box = qs("[data-contact-status]", form);
          if (box) box.textContent = (res.data && res.data.message) || MK.i18n.contactOk;
        });
    });
  }

  function bindWorkbench() {
    var area = qs("[data-embed-input]");
    if (!area) return;
    var preview = qs("[data-embed-preview]");
    var code = qs("[data-embed-code]");
    function render() {
      var parsed = parseSocial(area.value);
      if (!parsed) {
        if (preview) preview.innerHTML = area.value.trim() ? "<p>" + MK.i18n.embedInvalid + "</p>" : "";
        if (code) code.textContent = "";
        return;
      }
      var ratio = parsed.provider === "tiktok" ? "auto" : "16/9";
      var height = parsed.provider === "tiktok" ? "740" : "500";
      var iframe =
        '<iframe src="' +
        parsed.embedSrc +
        '" title="Macedonia.mk" loading="lazy" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" style="width:100%;aspect-ratio:' +
        ratio +
        ";min-height:" +
        height +
        'px;border:0;"></iframe>';
      if (code) code.textContent = iframe;
      if (preview) {
        var frameClass = parsed.provider === "tiktok" ? "social-frame is-portrait" : "social-frame is-video";
        preview.innerHTML =
          '<div class="' +
          frameClass +
          '"><button type="button" class="mk-embed__play" data-embed-src="' +
          parsed.embedSrc +
          '" data-embed-title="' +
          parsed.provider +
          '">' +
          (parsed.poster ? '<img src="' + parsed.poster + '" alt="">' : "") +
          '<span class="mk-embed__veil"></span><span class="mk-embed__cta"><span class="mk-play-disc"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l12-7z"/></svg></span><em>' +
          MK.i18n.playEmbed +
          " " +
          parsed.provider +
          "</em></span></button></div>";
      }
    }
    on(area, "input", render);
    qsa("[data-embed-example]").forEach(function (btn) {
      on(btn, "click", function () {
        area.value = btn.getAttribute("data-embed-example") || "";
        render();
      });
    });
    var copyBtn = qs("[data-copy-embed]");
    on(copyBtn, "click", function () {
      if (code && code.textContent && navigator.clipboard) {
        navigator.clipboard.writeText(code.textContent);
        alert(MK.i18n.copied);
      }
    });
  }

  function bindSavedPage() {
    var root = qs("[data-saved-list]");
    if (!root) return;
    var ids = savedIds();
    if (!ids.length) {
      root.innerHTML = '<p class="mk-empty">' + MK.i18n.emptySaved + "</p>";
      return;
    }
    fetch(MK.rest + "posts?include=" + ids.join(",") + "&per_page=40&_embed=1")
      .then(function (r) {
        return r.json();
      })
      .then(function (posts) {
        if (!posts || !posts.length) {
          root.innerHTML = '<p class="mk-empty">' + MK.i18n.emptySaved + "</p>";
          return;
        }
        root.innerHTML = posts
          .map(function (p) {
            var img = "";
            try {
              img = p._embedded["wp:featuredmedia"][0].source_url;
            } catch (e) {}
            return (
              '<a class="mk-card mk-card--row" href="' +
              p.link +
              '">' +
              '<span class="mk-card__media">' +
              (img ? '<img src="' + img + '" alt="">' : "") +
              "</span><span><span class=\"mk-card__title\">" +
              p.title.rendered +
              "</span></span></a>"
            );
          })
          .join("");
      });
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateSavedBadge();
    bindOverlays();
    bindLiveSearch();
    bindRails();
    bindEmbedPlay(document);
    bindShare();
    bindBookmark();
    bindSubscribe();
    bindContact();
    bindWorkbench();
    bindSavedPage();
  });
})();
