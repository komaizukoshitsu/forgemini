// DEBUG: This is a test line. Delete later.
console.log("main.js: A new line has been added to the top.");
console.log('main.jsファイルが読み込まれました！');

// ----------------------------------------------------------------
//     モーダルを初期化する関数
// ----------------------------------------------------------------
function initModal() {
    const handleModal = (targetSelector, open = true) => {
        document.querySelectorAll(targetSelector).forEach(el => el.classList.toggle('hidden', !open));
    };

    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        // Barba.js 遷移でイベントリスナーが重複しないように、既存のものを削除してから追加
        if (trigger._modalClickHandler) {
            trigger.removeEventListener('click', trigger._modalClickHandler);
        }
        trigger._modalClickHandler = () => handleModal(`#${trigger.dataset.modalTarget}`, true);
        trigger.addEventListener('click', trigger._modalClickHandler);
    });

    document.querySelectorAll('.js-modal-close').forEach(closeBtn => {
        if (closeBtn._modalCloseHandler) {
            closeBtn.removeEventListener('click', closeBtn._modalCloseHandler);
        }
        closeBtn._modalCloseHandler = () => handleModal('.modal', false);
        closeBtn.addEventListener('click', closeBtn._modalCloseHandler);
    });

    document.querySelectorAll('.modal').forEach(modal => {
        if (modal._modalOverlayClickHandler) {
            modal.removeEventListener('click', modal._modalOverlayClickHandler);
        }
        modal._modalOverlayClickHandler = e => {
            if (e.target === modal) {
                handleModal('.modal', false);
            }
        };
        modal.addEventListener('click', modal._modalOverlayClickHandler);
    });
}

// ----------------------------------------------------------------
//     Swiperを初期化する関数
// ----------------------------------------------------------------
function initSwipers() {
    const initSingleSwiper = (selector, options) => {
        const el = document.querySelector(selector);
        if (el && el.swiper) {
            // 既存のSwiperインスタンスがあれば破棄
            el.swiper.destroy(true, true);
        }
        if (el) new Swiper(el, options);
    };

    // 下層ページ (archive-*) で使用する
    initSingleSwiper('.swiper-default', {
        slidesPerView: 1.2, spaceBetween: 12, slidesOffsetBefore: 20, slidesOffsetAfter: 16,
        navigation: { nextEl: '.default-swiper-next', prevEl: '.default-swiper-prev' },
        breakpoints: { 1280: { slidesPerView: 'auto', spaceBetween: 36, slidesOffsetBefore: 0, slidesOffsetAfter: 36 } },
    });

    initSingleSwiper('.swiper-event', {
        slidesPerView: 1.2, spaceBetween: 12, slidesOffsetBefore: 20, slidesOffsetAfter: 16,
        navigation: { nextEl: '.event-swiper-next', prevEl: '.event-swiper-prev' },
        breakpoints: { 768: { slidesPerView: 'auto', spaceBetween: 16 }, 1280: { slidesPerView: 'auto', spaceBetween: 36, slidesOffsetBefore: 0, slidesOffsetAfter: 36 } },
    });

    // ホームページ専用の .swiper-home の設定 (ループ・中央揃え)
    initSingleSwiper('.swiper-home-works', { // ★ここを追加/変更★
        slidesPerView: 1.2,
        spaceBetween: 12,
        // slidesOffsetBefore: 20,
        // slidesOffsetAfter: 16,
        loop: true,               // ★ ループ再生を有効にする
        centeredSlides: true,     // ★ アクティブなスライドを中央に配置する
        initialSlide: 0,          // ★ 最初のスライドから開始
        navigation: { nextEl: '.default-swiper-next', prevEl: '.default-swiper-prev' },
        breakpoints: {
            1280: {
                slidesPerView: 'auto',
                spaceBetween: 36,
                // slidesOffsetBefore: 0,
                // slidesOffsetAfter: 36,
            }
        },
    });

    const thumbEl = document.querySelector('.goods-thumb');
    const mainEl = document.querySelector('.goods-main');

    if (thumbEl && mainEl) {
        if (thumbEl.swiper) thumbEl.swiper.destroy(true, true);
        if (mainEl.swiper) mainEl.swiper.destroy(true, true);

        const thumbSwiper = new Swiper(thumbEl, {
            slidesPerView: 4, spaceBetween: 12, watchSlidesProgress: true,
            breakpoints: { 0: { slidesPerView: 5, spaceBetween: 8 }, 640: { slidesPerView: 4 } }
        });

        new Swiper(mainEl, {
            spaceBetween: 36,
            navigation: { nextEl: '.goods-swiper-next', prevEl: '.goods-swiper-prev' },
            pagination: { el: '.goods-swiper-pagination', clickable: true },
            thumbs: { swiper: thumbSwiper },
            breakpoints: { 0: { slidesPerView: 1 }, 640: { slidesPerView: 1 } }
        });
    }

    document.querySelectorAll('.js-modal').forEach(modal => {
        const swiperEl = modal.querySelector('.swiper');
        if (swiperEl) {
            if (swiperEl.swiper) swiperEl.swiper.destroy(true, true);

            const paginationEl = swiperEl.querySelector('.swiper-pagination');
            const nextEl = modal.querySelector('.modal-swiper-next');
            const prevEl = modal.querySelector('.modal-swiper-prev');

            if (paginationEl) {
                new Swiper(swiperEl, {
                    slidesPerView: 1, spaceBetween: 36, centeredSlides: true, loop: true, initialSlide: 0,
                    pagination: { el: paginationEl, clickable: true },
                    navigation: { nextEl: nextEl, prevEl: prevEl },
                });
            }
        }
    });
}

// ----------------------------------------------------------------
//     アコーディオンを初期化する関数
// ----------------------------------------------------------------
function initAccordion() {
    const accordionContainers = document.querySelectorAll('.border-b');

    accordionContainers.forEach((container, index) => {
        const button = container.querySelector('.js-accordion-button');
        if (!button) return;

        const content = container.querySelector('.js-accordion-content');
        const iconContainer = button.querySelector('.js-accordion-icon');
        if (!iconContainer) {
            console.warn('Accordion icon container not found for this accordion item.');
        }

        const plusIcon = iconContainer ? iconContainer.querySelector('.icon-plus') : null;
        const minusIcon = iconContainer ? iconContainer.querySelector('.icon-minus') : null;

        if (index === 0 && content) {
            content.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
            button.classList.add('open');
            if (plusIcon && minusIcon) {
                plusIcon.classList.add('hidden');
                minusIcon.classList.remove('hidden');
            }
        }

        // イベントリスナーの再登録（重複を防ぐ）
        if (button._accordionClickHandler) {
            button.removeEventListener('click', button._accordionClickHandler);
        }
        button._accordionClickHandler = () => {
            if (content) {
                content.classList.toggle('hidden');
                const isExpanded = !content.classList.contains('hidden');
                button.setAttribute('aria-expanded', isExpanded.toString());
                button.classList.toggle('open', isExpanded);
                if (plusIcon && minusIcon) {
                    plusIcon.classList.toggle('hidden');
                    minusIcon.classList.toggle('hidden');
                }
            }
        };
        button.addEventListener('click', button._accordionClickHandler);
    });
}

// ----------------------------------------------------------------
//     価格にカンマをつける（Goods-page）を初期化する関数
// ----------------------------------------------------------------
function initPriceFormatter() {
    const formatPrice = (el) => {
        const value = el.textContent.trim();
        if (!isNaN(value) && value !== '') {
            el.textContent = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    };
    document.querySelectorAll('.price').forEach(formatPrice);
}

// ----------------------------------------------------------------
//     top-page goodsアニメーション
// ----------------------------------------------------------------
function initTopPageGoodsAnimation() {
    const track = document.querySelector('.slide-track');
    if (track) {
        const slide = track.querySelector('.top-slide1');
        if (slide) {
            const slideWidth = slide.offsetWidth;
            track.style.setProperty('--slideWidth', `${slideWidth}px`);
            // アニメーションを一時停止・再開することで、Barba.js 遷移後も正しく動作させる
            track.style.animation = 'none';
            void track.offsetWidth; // 強制的にリフロー
            track.style.animation = `slide-left ${slideWidth / 50}s linear infinite`;
        }
    }
}

// ----------------------------------------------------------------
//     top-page パララックス
// ----------------------------------------------------------------
function initParallax() {
    // スロットリング関数
    const throttle = (func, limit) => {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    };

    const applyParallax = () => {
        const screenWidth = window.innerWidth;
        const XL_BREAKPOINT = 1280; // Tailwind CSSのXLブレークポイントに合わせる

        // 1280px未満の場合はすべてのパララックス効果を無効化
        if (screenWidth < XL_BREAKPOINT) {
            document.querySelectorAll('.parallax-small, .parallax, .parallax-2').forEach(element => {
                element.style.transform = `translateY(0px)`; // 位置をリセット
            });
            const elemImg = document.getElementById('parallaxBgImg');
            if (elemImg) {
                elemImg.style.transform = `translateY(0px) scale(1.0)`; // 位置をリセット
            }
            return; // これ以上計算を実行しない
        }

        // --- 1280px以上の場合にのみ以下のパララックス効果が適用される ---

        document.querySelectorAll('.parallax-small').forEach(element => {
            const speed = 0.2;
            const offset = window.scrollY * speed - 600;
            element.style.transform = `translateY(${offset}px)`;
        });

        const parallaxElements = document.querySelectorAll('.parallax');
        parallaxElements.forEach(element => {
            const offset = window.scrollY * 0.1 - 300;
            element.style.transform = `translateY(${offset}px)`;
        });

        const parallaxElements2 = document.querySelectorAll('.parallax-2');
        // baseOffset の計算もXLブレークポイントに合わせるか検討
        // 現在の768px基準は維持しても良いが、一貫性を持たせるならXL_BREAKPOINTを使う
        let baseOffset = screenWidth <= 768 ? 450 : 550; // この行は現在のロジックを維持

        parallaxElements2.forEach(element => {
            // ここも screenWidth > 767 のままで良い。
            // 全体のif (screenWidth < XL_BREAKPOINT)で既に制御されているため
            if (screenWidth > 767) {
                const offset = window.scrollY * 0.1 - baseOffset;
                element.style.transform = `translateY(${offset}px)`;
            } else {
                // ここは screenWidth < 768px の場合に translateY(0) になる
                // 全体の XL_BREAKPOINT 条件でカバーされるが、既存ロジックとして残す
                element.style.transform = `translateY(0px)`;
            }
        });

        const elemImg = document.getElementById('parallaxBgImg');
        if (elemImg) {
            const scrollOffset = window.scrollY / 10;
            elemImg.style.transform = `translateY(${scrollOffset}px) scale(1.0)`;
        }
    };

    // スクロールイベントにスロットリングを適用 (例: 16msごとに実行)
    const throttledParallax = throttle(applyParallax, 16); // 16msは概ね60fps

    // イベントリスナーの再登録（重複を防ぐ）
    if (document._parallaxScrollHandler) {
        document.removeEventListener('scroll', document._parallaxScrollHandler);
    }
    // ★変更点★ スロットリングされた関数をイベントリスナーに登録
    document._parallaxScrollHandler = throttledParallax;
    document.addEventListener('scroll', document._parallaxScrollHandler);

    if (window._parallaxResizeHandler) {
        window.removeEventListener('resize', window._parallaxResizeHandler);
    }
    window._parallaxResizeHandler = () => {
        // リサイズ時にすべてのパララックス要素をリセットしてから applyParallax を呼ぶ
        document.querySelectorAll('.parallax-small, .parallax, .parallax-2').forEach(element => {
            element.style.transform = `translateY(0px)`;
        });
        const elemImg = document.getElementById('parallaxBgImg');
        if (elemImg) {
            elemImg.style.transform = `translateY(0px) scale(1.0)`;
        }

        applyParallax(); // リサイズ後に再度適用条件をチェック
    };
    window.addEventListener('resize', window._parallaxResizeHandler);

    applyParallax(); // 初期呼び出し
}

// ----------------------------------------------------------------
//     ヘッダードロワーを初期化する関数
// ----------------------------------------------------------------
function initHeaderDrawer(forcedNamespace = null) {
    let currentNamespace;

    if (forcedNamespace) {
        currentNamespace = forcedNamespace;
    } else {
        const barbaContainer = document.querySelector('[data-barba="container"]');
        if (barbaContainer && barbaContainer.dataset.barbaNamespace) {
            currentNamespace = barbaContainer.dataset.barbaNamespace;
        } else {
            currentNamespace = 'default';
        }
    }

    // URLがトップページでnamespaceがhomeでない場合、強制的にhomeにするロジック
    // Barba.jsのdata-barba-namespaceが優先されるべきだが、フォールバックとして残す
    if (currentNamespace !== 'home' && (window.location.pathname === '/' || window.location.pathname === '/home/')) {
        console.log('initHeaderDrawer: Force setting namespace to home based on URL.');
        currentNamespace = 'home';
    }
    const isTopPage = currentNamespace === 'home';

    console.log('initHeaderDrawer: isTopPage is:', isTopPage);
    console.log('initHeaderDrawer: Barba Namespace is:', currentNamespace);

    const homeDrawer = document.querySelector('.home-drawer'); // ハンバーガーアイコンのラッパー
    const drawerNav = document.querySelector('.home-drawer-nav'); // ドロワーメニュー本体
    const bar1 = homeDrawer?.querySelector('.drawer-iconBar1');
    const bar2 = homeDrawer?.querySelector('.drawer-iconBar2');
    // ★★★ ここにヘッダーナビのコンテナとリンクのセレクタを追加 ★★★
    const headerNav = document.querySelector('.header-nav'); // .header-nav を取得
    const headerNavLinks = headerNav ? headerNav.querySelectorAll('.header-nav-item a') : []; // .header-nav-item 内のすべての <a> 要素を取得

    if (!homeDrawer || !drawerNav || !bar1 || !bar2 || !headerNav) {
        console.warn('initHeaderDrawer: Required elements (homeDrawer, drawerNav, bar1, bar2, headerNav) not found.');
        return;
    }

    // --- ここから新しいアクティブリンク設定ロジックを追加 ---
    // 1. まず、すべてのアクティブクラスをリセットする
    headerNavLinks.forEach(link => {
        link.classList.remove('active'); // `active` クラスを削除
    });

    // 2. 現在のページの名前空間に基づいて、アクティブなリンクを設定する
    //    link.href と currentNamespace (または window.location.pathname) を比較します
    const currentPath = window.location.pathname; // 現在のページのパス

    headerNavLinks.forEach(link => {
        // const linkHref = link.getAttribute('href'); // リンクの href 属性
        const linkHref = new URL(link.href).pathname; // ★修正: link.href からパス部分のみを取得★
        // 例: "http://teraokanatsumicom.local/events" -> "/events"

        let shouldBeActive = false;

        // 【優先度の高い、厳密なマッチング】
        // Home
        if (currentNamespace === 'home' && (linkHref === '/' || linkHref === '/home/')) {
            shouldBeActive = true;
        }
        // About (完全に一致する固定ページの場合)
        // もし /about/ でBarba namespaceが 'page' なら
        else if (currentNamespace === 'page' && linkHref === '/about/' && currentPath === '/about/') {
             shouldBeActive = true;
        }
        // Contact (contact, contact-confirm, thanks のどれでも)
        else if (['contact', 'contact-confirm', 'thanks'].includes(currentNamespace) && linkHref.startsWith('/contact')) {
            shouldBeActive = true;
        }

        // 【アーカイブページと単一ページの両方をカバーするロジック】
        // News (news-page と news-single の両方で /news/ をアクティブに)
        else if (linkHref.startsWith('/news') && (currentNamespace === 'news-page' || currentNamespace === 'news-single')) {
            shouldBeActive = true;
        }
        // Events (events-archive と events-single の両方で /events/ をアクティブに)
        else if (linkHref.startsWith('/events') && (currentNamespace === 'events-archive' || currentNamespace === 'events-single')) {
            shouldBeActive = true;
        }
        // Goods (goods-archive と goods-single の両方で /goods/ をアクティブに)
        else if (linkHref.startsWith('/goods') && (currentNamespace === 'goods-archive' || currentNamespace === 'goods-single')) {
            shouldBeActive = true;
        }
        // Works (works-archive と works-single の両方で /works/ をアクティブに)
        else if (linkHref.startsWith('/works') && (currentNamespace === 'works-archive' || currentNamespace === 'works-single')) {
            shouldBeActive = true;
        }
        // 【その他の汎用的な親パス判定】
        // 上記の特定ロジックでカバーされない「/parent/child/」のようなケースで、
        // 親の '/parent/' リンクをアクティブにしたい場合。
        // ただし、Home ('/') や Contact ('/contact/') など、特定のルートページとは競合しないように注意。
        // これらは上記の専用ルールで既に処理されていることを前提とします。
        // 例: linkHref が '/' ではなく、かつ currentPath が linkHref で始まる場合
        else if (linkHref !== '/' && currentPath.startsWith(linkHref) && linkHref.length > 1) { // linkHrefが単独の'/'ではないことを確認
             // ただし、'page' 名前空間の特定の固定ページ（例: /about/）は、
             // その子ページがある場合に限りこのロジックが適用されるか、
             // もしくは完全に一致する時だけアクティブにしたい場合は注意。
             // 例: /about/ は /about/ の時だけactive、/about/company/ ではactiveにしないならこのロジックは不要
             // ここはサイトのナビゲーションの意図によって変わります。
             // もし /about/ のリンクが /about/company/ でもアクティブになってほしいなら有効。
             shouldBeActive = true;
             console.log(`Fallback active: ${linkHref} as parent of ${currentPath}`);
        }

        if (shouldBeActive) {
            link.classList.add('active'); // `active` クラスを付与
            console.log(`Active link set: ${linkHref} for namespace: ${currentNamespace}`);
        }
    });
    // --- ここまで新しいアクティブリンク設定ロジックを追加 ---

    const toggleDrawer = (isOpen) => {
        drawerNav.classList.toggle('is-active', isOpen);
        bar1.classList.toggle('is-active', isOpen);
        bar2.classList.toggle('is-active', isOpen);
        console.log('toggleDrawer called with isOpen:', isOpen, 'drawerNav.classList:', drawerNav.classList.contains('is-active'));
    };

    // ハンバーガーメニューアイコンのクリックイベントリスナー (重複登録防止)
    if (homeDrawer._toggleClickHandler) {
        homeDrawer.removeEventListener('click', homeDrawer._toggleClickHandler);
    }
    homeDrawer._toggleClickHandler = function (e) {
        // if (isTopPage) { // トップページでのみクリックイベントを処理
            toggleDrawer(!drawerNav.classList.contains('is-active'));
        // }
    };
    homeDrawer.addEventListener('click', homeDrawer._toggleClickHandler);

    // ドロワー外クリックで閉じるイベントリスナー (重複登録防止)
    if (document._outsideClickHandler) {
        document.removeEventListener('click', document._outsideClickHandler);
    }

    // isTopPage の条件を削除し、常にイベントリスナーを登録する
    console.log('initHeaderDrawer: Outside click handler IS REGISTERED (All pages).');
    document._outsideClickHandler = function (e) {
        const isActive = drawerNav.classList.contains('is-active');
        const isHomeDrawerClick = homeDrawer.contains(e.target);
        const isDrawerNavClick = drawerNav.contains(e.target);

        // PCの下層ページの場合、メニューは常に開いている状態なので、
        // エリア外クリックで閉じる処理は適用しない
        const isMobile = window.innerWidth < 1280;
        const isSubpagePC = !isTopPage && !isMobile;

        if (isActive && !isHomeDrawerClick && !isDrawerNavClick && !isSubpagePC) {
            toggleDrawer(false);
        }
    };
    document.addEventListener('click', document._outsideClickHandler);

    // ドロワー内のリンクをクリックで閉じる処理 (トップページのみ、重複登録防止)
    drawerNav.querySelectorAll('a').forEach(link => {
        if (link._clickHandler) {
            link.removeEventListener('click', link._clickHandler);
        }
        link._clickHandler = function() {
            // 下層ページでもリンククリックで閉じるようにするが、
            // PC下層ページでメニューが常に開いている場合は閉じないようにする
            const isMobile = window.innerWidth < 1280;
            const isSubpagePC = !isTopPage && !isMobile;

            if (drawerNav.classList.contains('is-active') && !isSubpagePC) {
                toggleDrawer(false);
            }
        };
        link.addEventListener('click', link._clickHandler);
    });

    const fixedContent = document.querySelector(".fixed-content");
    const hiddenArea = document.querySelector("footer");

    if (fixedContent && hiddenArea) {
        // IntersectionObserver の重複登録防止
        if (hiddenArea._intersectionObserver) {
            hiddenArea._intersectionObserver.disconnect();
            hiddenArea._intersectionObserver = null;
        }

        const handleVisibility = () => {
            const isMobileCurrent = window.innerWidth < 1280;

            console.log('handleVisibility: isTopPage is:', isTopPage, 'isMobileCurrent is:', isMobileCurrent);
            console.log('handleVisibility: currentNamespace is:', currentNamespace);

            homeDrawer.style.removeProperty('display'); // まずリセット

            if (!isTopPage && !isMobileCurrent) { // 下層ページ（PC）の場合
                console.log('handleVisibility: Subpage (PC) detected. Hiding homeDrawer.');
                drawerNav.classList.add('is-active'); // メニューを常に開いた状態
                homeDrawer.style.setProperty('display', 'none', 'important'); // ハンバーガーアイコンを非表示
                bar1.classList.remove('is-active');
                bar2.classList.remove('is-active');
            } else { // トップページ（PC）の場合 OR モバイル（トップ/下層共通）の場合
                console.log('handleVisibility: Top page (PC) or Mobile detected. Showing homeDrawer.');
                drawerNav.classList.remove('is-active'); // メニューを閉じた状態
                homeDrawer.style.removeProperty('display'); // ハンバーガーアイコンは表示
                bar1.classList.remove('is-active');
                bar2.classList.remove('is-active');
            }
            console.log('handleVisibility: Final homeDrawer style (after handleVisibility):', homeDrawer.style.display);
            console.log('handleVisibility: Final drawerNav classes (after handleVisibility):', drawerNav.classList);
        };

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        fixedContent.classList.add("is-hidden");
                    } else {
                        fixedContent.classList.remove("is-hidden");
                    }
                });
            },
            { root: null, threshold: 0 }
        );
        observer.observe(hiddenArea);
        hiddenArea._intersectionObserver = observer;

        // リサイズイベントリスナーの重複登録防止
        if (window._drawerResizeHandler) {
            window.removeEventListener('resize', window._drawerResizeHandler);
        }
        window._drawerResizeHandler = handleVisibility;
        window.addEventListener('resize', window._drawerResizeHandler);

        handleVisibility(); // 初期実行
    } else {
        console.warn('initHeaderDrawer: fixedContent or hiddenArea not found for observer.');
    }
}

// ----------------------------------------------------------------
//  スクロールダウンでメインビジュアルをブラー
// ----------------------------------------------------------------
function initMvBlurOnScroll() {
    const applyMvBlur = () => {
        const mvImage = document.querySelector('#hero img.pc-only');
        const spImage = document.querySelector('#hero img.sp-only');
        const scrollY = window.scrollY;
        const maxBlur = 8;
        const maxScroll = 300;
        const blurValue = Math.min(scrollY / maxScroll * maxBlur, maxBlur);

        if (mvImage) mvImage.style.filter = `blur(${blurValue}px)`;
        if (spImage) spImage.style.filter = `blur(${blurValue}px)`;
    };

    if (document._mvBlurScrollHandler) {
        document.removeEventListener('scroll', document._mvBlurScrollHandler);
    }
    document._mvBlurScrollHandler = applyMvBlur;
    document.addEventListener('scroll', document._mvBlurScrollHandler);
    applyMvBlur(); // 初期呼び出し
}

// ----------------------------------------------------------------
//  スクロールでトップページeventを徐々に消す
// ----------------------------------------------------------------
function initEventFadeOnScroll() {
    const applyEventFade = () => {
        const eventBox = document.getElementById('event-info');
        if (!eventBox) return;

        const scrollY = window.scrollY;
        const fadeStart = 100;
        const fadeEnd = 600;
        const opacity = scrollY > fadeStart ? Math.max(1 - (scrollY - fadeStart) / (fadeEnd - fadeStart), 0) : 1;

        eventBox.style.opacity = opacity;
    };

    if (document._eventFadeScrollHandler) {
        document.removeEventListener('scroll', document._eventFadeScrollHandler);
    }
    document._eventFadeScrollHandler = applyEventFade;
    document.addEventListener('scroll', document._eventFadeScrollHandler);
    applyEventFade(); // 初期呼び出し
}

// ----------------------------------------------------------------
//  Contact Form 7を正常動作させるための関数 (ブログ記事から引用)
// ----------------------------------------------------------------
function contactForm7Run(next) {
    console.log('contactForm7Run: 関数が呼び出されました。');
    var cfSelector = 'div.wpcf7 > form';
    var cfForms = next.container.querySelectorAll(cfSelector);

    if (cfForms.length) {
        console.log('contactForm7Run: ' + cfForms.length + ' 個の Contact Form 7 フォームが見つかりました。');
        cfForms.forEach(function(formElement) {
            var $form = jQuery(formElement);

            if (typeof wpcf7 !== 'undefined' && typeof wpcf7.init === 'function') {
                wpcf7.init(formElement);
                console.log('contactForm7Run: Contact Form 7 フォームを再初期化しました:', formElement);

                if (typeof wpcf7cf !== 'undefined' && typeof wpcf7cf.initForm === 'function') {
                    wpcf7cf.initForm($form);
                    console.log('contactForm7Run: Conditional Fields for CF7 を初期化しました:', formElement);
                } else {
                    console.warn('contactForm7Run: wpcf7cf.initForm 関数が見つかりません。Conditional Fields プラグインがまだロードされていないか、エラーが発生しています。');
                }
            } else {
                console.warn('contactForm7Run: wpcf7.init 関数が見つかりません。Contact Form 7 がまだロードされていないか、エラーが発生しています。');
            }
        });
    } else {
        console.log('contactForm7Run: Contact Form 7 フォームが next.container 内に見つかりませんでした。');
    }
}

// ----------------------------------------------------------------
// フッターナビゲーションリンクのイベントリスナー設定関数 (ここが良い位置です)
// ----------------------------------------------------------------
function setupFooterNavLinks() {
    console.log('setupFooterNavLinks 関数が呼び出されました。');

    // .footer クラス内の nav-item クラスを持つ要素内のリンクを取得 (menu-items.php で生成されるリンク)
    const footerNavLinks = document.querySelectorAll('footer .nav-item a');
    footerNavLinks.forEach(link => {
        // 既にイベントリスナーが追加されていないかチェックするためのデータ属性
        if (!link.dataset.barbaCustomListener) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                // Barbajs で処理すべき内部リンクかを判定
                // target="_blank", #アンカー, mailto:, tel:, download, 外部URLは除外
                if (
                    href &&
                    !href.startsWith('#') &&
                    !href.startsWith('mailto:') &&
                    !href.startsWith('tel:') &&
                    this.target !== '_blank' &&
                    !this.hasAttribute('download') &&
                    href.includes(window.location.origin) // 同じドメイン内のリンクのみ
                ) {
                    e.preventDefault(); // デフォルトの遷移を阻止
                    console.log('フッターナビリンクのクリックを Barbajs で処理:', href);
                    try {
                        barba.go(href).then(() => {
                            console.log('Barbajs 遷移が正常に開始されました:', href);
                        }).catch(error => {
                            console.error('Barbajs 遷移の開始中にエラーが発生しました:', error);
                            // エラーが発生した場合でも、念のためフルリロードに戻す
                            window.location.href = href;
                        });
                    } catch (err) {
                        console.error('barba.go() の呼び出し自体でエラーが発生しました:', err);
                        // ここに到達した場合は、barbaオブジェクトが未定義か、致命的な初期化問題がある
                        window.location.href = href; // フルリロードにフォールバック
                    }
                } else {
                    console.log('フッターナビリンクを通常処理 (Barba.js対象外):', href);
                }
            });
            link.dataset.barbaCustomListener = 'true'; // カスタムリスナーを追加したマーク
        }
    });

    // フッターの「お問い合わせ」ボタン (.footer-contact a)
    const footerContactLink = document.querySelector('footer .footer-contact a');
    if (footerContactLink && !footerContactLink.dataset.barbaCustomListener) {
        footerContactLink.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (
                href &&
                !href.startsWith('#') &&
                !href.startsWith('mailto:') &&
                !href.startsWith('tel:') &&
                this.target !== '_blank' &&
                !this.hasAttribute('download') &&
                href.includes(window.location.origin)
            ) {
                e.preventDefault();
                console.log('フッターお問い合わせリンクのクリックを Barbajs で処理:', href);
                try { // ★ barba.go() に try...catch を追加
                    barba.go(href);
                } catch (err) {
                    console.error('barba.go() の呼び出し自体でエラーが発生しました:', err);
                    window.location.href = href;
                }
            } else {
                console.log('フッターお問い合わせリンクを通常処理 (Barba.js対象外):', href);
            }
        });
        footerContactLink.dataset.barbaCustomListener = 'true';
    }

    // プライバシーポリシーリンク (.footer-privacy-policy a)
    const privacyPolicyLink = document.querySelector('footer .footer-privacy-policy a');
    if (privacyPolicyLink && !privacyPolicyLink.dataset.barbaCustomListener) {
        privacyPolicyLink.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (
                href &&
                !href.startsWith('#') &&
                !href.startsWith('mailto:') &&
                !href.startsWith('tel:') &&
                this.target !== '_blank' &&
                !this.hasAttribute('download') &&
                href.includes(window.location.origin)
            ) {
                e.preventDefault();
                console.log('プライバシーポリシーリンクのクリックを Barbajs で処理:', href);
                try { // ★ barba.go() に try...catch を追加
                    barba.go(href);
                } catch (err) {
                    console.error('barba.go() の呼び出し自体でエラーが発生しました:', err);
                    window.location.href = href;
                }
            } else {
                console.log('プライバシーポリシーリンクを通常処理 (Barba.js対象外):', href);
            }
        });
        privacyPolicyLink.dataset.barbaCustomListener = 'true';
    }
}

// ----------------------------------------------------------------
//     すべてのスクリプトを再初期化する関数
// ----------------------------------------------------------------
function initAllScripts() {
    console.log('--- initAllScripts が実行されました！ ---');
    initModal();
    initSwipers();
    initAccordion();
    initPriceFormatter();
    initTopPageGoodsAnimation();
    initParallax();
    // initHeaderNavActive();
    initMvBlurOnScroll();
    initEventFadeOnScroll();
    // initFilterScripts と initEventFilter は個別にBarba.jsのafterフックで制御するため、ここには含めない
    // ただし、Barba.jsを使わないフォールバックの場合はここに追加することも検討する
    // window.scrollTo(0, 0);
}

// ----------------------------------------------------------------
// Barba.js 初期化 (関数定義)
// ----------------------------------------------------------------
let barbaInitialized = false;
function setupBarba() {
    if (barbaInitialized) {
        console.warn('Barba.js は既に初期化されています。');
        return;
    }

    console.log('setupBarba 関数が呼び出されました！');

     // ★★★ ここに initialNamespaceForSetup の定義を移動しました！ ★★★
    // Barba.js 初期化時に一度だけヘッダードロワーとフィルタースクリプトを初期化するために、
    // 最初の名前空間をここで取得します。
    const initialBarbaContainerForSetup = document.querySelector('[data-barba="container"]');
    const initialNamespaceForSetup = initialBarbaContainerForSetup?.dataset.barbaNamespace || 'default';
    console.log('setupBarba: initialNamespaceForSetup を次のように決定しました:', initialNamespaceForSetup);
    // ★★★ ここまで移動 ★★★

    // Barba.js のデバッグモードを有効化
    barba.use({ debug: true });

    barba.init({
        views: [
            { namespace: 'home' },
            { namespace: 'works-archive' },
            { namespace: 'works-single' }, // ★追加: Worksの単一ページ
            { namespace: 'goods-archive' },
            { namespace: 'goods-single' }, // ★追加: Goodsの単一ページ
            { namespace: 'events-archive' },
            { namespace: 'events-single' }, // ★追加: Eventsの単一ページ
            { namespace: 'news-page' },
            { namespace: 'news-single' }, // ★追加: Newsの単一ページ (もしあれば)

            {
                namespace: 'contact', // contact.php に対応
                beforeEnter() { console.log('Entering contact page view.'); }
            },
            {
                namespace: 'contact-confirm', // contact-confirm.php に対応
                beforeEnter() { console.log('Entering contact-confirm page view.'); }
            },
            {
                namespace: 'thanks', // thanks.php に対応
                beforeEnter() { console.log('Entering thanks page view.'); }
            },
            { namespace: 'page' } // その他の一般的な固定ページ
        ],
        transitions: [{
            name: 'default-transition',
            // leaveフックの前にメニューを非表示にする
            beforeLeave({ current }) {
                console.log('--- Barba transition beforeLeave フックが実行されました！ ---');
                const prevDrawerNav = current.container.querySelector('.home-drawer-nav');
                if (prevDrawerNav) {
                    // 全てのページ遷移でメニューを確実に非表示にする
                    // is-active クラスを削除し、必要であれば非表示にするスタイルを適用
                    prevDrawerNav.classList.remove('is-active');
                    console.log('beforeLeave: prevDrawerNav を確実に非表示にしました。');
                }
            },
            leave({ current }) {
                return new Promise(resolve => {
                    const container = current.container;
                    container.style.transition = 'opacity 0.1s ease-out';
                    container.style.opacity = '0';

                    const onTransitionEnd = () => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve();
                    };
                    container.addEventListener('transitionend', onTransitionEnd);
                    setTimeout(() => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve();
                    }, 150);
                });
            },
            enter({ next }) {
                return new Promise(resolve => {
                    const container = next.container;
                    container.style.transition = 'opacity 0.1s ease-in';
                    container.style.opacity = '0';

                    // enterフックの最初で、新しいページのメニュー要素を一旦隠す
                    // const nextDrawerNav = container.querySelector('.home-drawer-nav');
                    // if (nextDrawerNav) {
                    //     console.log('enter: nextDrawerNav を確実に非表示にしました。');
                    // }

                    requestAnimationFrame(() => {
                        container.style.opacity = '1';
                    });

                    const onTransitionEnd = () => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve();
                    };
                    container.addEventListener('transitionend', onTransitionEnd);
                    setTimeout(() => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve();
                    }, 150);
                });
            },
            after(data) {
                console.log('--- Barba transition after フックが実行されました！ ---');
                console.log('次のページのnamespace:', data.next.namespace);

                try {
                    const homeDrawerNav = document.querySelector('.home-drawer-nav');
                    const newNamespace = data.next.namespace;

                    if (homeDrawerNav) {
                        if (newNamespace === 'home') {
                            homeDrawerNav.classList.remove('is-active');
                            console.log('homeDrawerNav reset for home page (closed).');
                        } else {
                            // homeDrawerNav.classList.add('is-active'); // handleVisibilityでPC下層ではhiddenになる
                            console.log('homeDrawerNav set to active for subpage, but initially hidden.');
                        }
                    }

                    // initHeaderDrawer を呼び出す
                    // DOMが完全に更新されるのを待つため、わずかな遅延を入れる
                    // この遅延は、新しいDOMが準備され、initHeaderDrawerが安全に実行されるために必要
                    setTimeout(() => {
                        if (typeof initHeaderDrawer === 'function') {
                            initHeaderDrawer(newNamespace);
                            console.log('Barba after hook: initHeaderDrawer called with namespace:', newNamespace);
                        } else {
                            console.warn('initHeaderDrawer 関数が定義されていません。');
                        }

                        // contactForm7Run の呼び出し条件をより正確に
                        if (newNamespace === 'contact' || newNamespace === 'contact-confirm' || newNamespace === 'thanks') {
                            console.log(`Barba after hook: Calling contactForm7Run for ${newNamespace} page.`);
                            contactForm7Run(data.next);
                        } else if (newNamespace === 'page') {
                            const hasWpcf7Form = data.next.container.querySelector('div.wpcf7 > form');
                            if (hasWpcf7Form) {
                                console.log('Barba after hook: Calling contactForm7Run for generic page with CF7 form.');
                                contactForm7Run(data.next);
                            }
                        }

                        // .footer-contact の表示制御
                        const footerContact = data.next.container.querySelector('.footer-contact');
                        if (footerContact) {
                            if (newNamespace === 'contact' || newNamespace === 'contact-confirm' || newNamespace === 'thanks') {
                                footerContact.style.display = 'none';
                                console.log(`Barba after hook: .footer-contact を非表示にしました (${newNamespace}ページ).`);
                            } else {
                                footerContact.style.removeProperty('display');
                                console.log(`Barba after hook: .footer-contact の表示をリセットしました (${newNamespace}ページ).`);
                            }
                        }

                    }, 50);

                    // filter.js にある initFilterScripts を呼び出す
                    if (typeof initFilterScripts === 'function') {
                        initFilterScripts(newNamespace);
                        console.log('Barba after hook: initFilterScripts (from filter.js) called with namespace:', newNamespace);
                    } else {
                        console.warn('initFilterScripts 関数が定義されていません。filter.js が正しくロードされているか確認してください。');
                    }

                    // Body data-page attribute update (元のコードから継続、`newNamespace` を使う)
                    const nextBarbaNamespace = data.next.container.dataset.barbaNamespace || 'default';
                    document.body.setAttribute('data-page', nextBarbaNamespace);
                    console.log('Body data-page attribute updated to:', nextBarbaNamespace);


                    // Top page video autoplay (元のコードから継続、`newNamespace` を使う)
                    if (newNamespace === 'home') {
                        const videoSection = document.querySelector('.js-autoplay-video-section');
                        if (videoSection) {
                            const videoElement = videoSection.querySelector('video');
                            if (videoElement) {
                                videoElement.play().then(() => {
                                    console.log('Video started playing on home page.');
                                }).catch(error => {
                                    console.warn('Video play() failed:', error);
                                });
                            }
                        }
                    }

                    initAllScripts();

                    window.scrollTo(0, 0);
                    console.log('Barba after hook: window.scrollTo(0, 0) executed.');

                } catch (error) {
                    console.error('Barba.js after hook でエラーが発生しました:', error);
                    throw error;
                }
            },
        }],
        hooks: {
            before: (data) => {
                console.log('--- Barba global before フックが実行されました！ ---');
                const link = data.trigger;
                if (link && link.tagName === 'A' && link.closest('footer')) {
                    console.log('Barba.js: フッターナビのリンクをインターセプトしました:', link.href);
                } else {
                    console.log('Barba.js: 通常のリンクをインターセプトしました:', link ? link.href : '不明');
                }
            },
            after: ({ next }) => {
                console.log('--- Barba global after フックが実行されました！ ---');
                if (typeof document.dispatchEvent === 'function' && typeof CustomEvent === 'function') {
                    document.dispatchEvent(new CustomEvent('wpcf7.dom_updated'));
                    console.log('wpcf7.dom_updated イベントがディスパッチされました。');
                } else {
                    console.warn('CustomEvent または document.dispatchEvent がサポートされていません。wpcf7.dom_updated は発火できません。');
                }

                // Barba.js 遷移後にもフッターリンクのリスナーを再設定
                setupFooterNavLinks(); // ★ここに追加
            }
        },
    });

    initHeaderDrawer(initialNamespaceForSetup);

    // console.log('barba.init の呼び出しが完了しました！');
    barbaInitialized = true;
    console.log('Barba.js が初期化されました。');
}

// ----------------------------------------------------------------
//     DOMContentLoaded で一度だけ実行する処理
// ----------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded: ページが完全にロードされました。');

    // Barbajs の初期状態ログ (デバッグ用なので残しておいてもOK)
    console.log('DOMContentLoaded: barbaオブジェクトの初期状態:', typeof barba, barba);
    if (typeof barba === 'object' && barba !== null) {
        console.log('DOMContentLoaded: barba.go メソッドの存在:', typeof barba.go);
        console.log('DOMContentLoaded: barba.init メソッドの存在:', typeof barba.init);
    }

    setupBarba(); // Barba.js の初期化

    initAllScripts();

    // 初回ロード時に filter.js の initFilterScripts を呼び出す
    // Barba.js はまだ transition を実行していないため、data-barba-namespace を直接取得
    const initialNamespace = document.querySelector('[data-barba="container"]')?.dataset.barbaNamespace;
    if (initialNamespace && typeof initFilterScripts === 'function') {
        initFilterScripts(initialNamespace);
        console.log('DOMContentLoaded: initFilterScripts (from filter.js) called for initial page load with namespace:', initialNamespace);
    } else {
        console.warn('DOMContentLoaded: initFilterScripts 関数が定義されていないか、初期namespaceが取得できませんでした。');
    }

    // 初回ロード時の .footer-contact 表示制御
    // これは transition.after と同じロジックをここで実行
    const initialFooterContact = document.querySelector('.footer-contact');
    if (initialFooterContact) {
        if (initialNamespace === 'contact' || initialNamespace === 'contact-confirm' || initialNamespace === 'thanks') {
            initialFooterContact.style.display = 'none';
            console.log(`DOMContentLoaded: Initial .footer-contact を非表示にしました (${initialNamespace}ページ).`);
        } else {
            initialFooterContact.style.removeProperty('display');
            console.log(`DOMContentLoaded: Initial .footer-contact の表示をリセットしました (${initialNamespace}ページ).`);
        }
    }

    // 初回ロード時のContact Form 7の初期化
    if (initialNamespace === 'contact' || initialNamespace === 'contact-confirm' || initialNamespace === 'thanks') {
        console.log(`DOMContentLoaded: Initial contactForm7Run called for ${initialNamespace} page.`);
        contactForm7Run({ container: document });
    } else if (initialNamespace === 'page') {
        const hasWpcf7Form = document.querySelector('div.wpcf7 > form');
        if (hasWpcf7Form) {
            console.log('DOMContentLoaded: Initial contactForm7Run called for generic page with CF7 form.');
            contactForm7Run({ container: document });
        }
    }

    // Escapeキーでモーダルを閉じる処理 (documentレベルで一度だけ設定)
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal:not(.hidden)');
            if (openModals.length > 0) {
                openModals.forEach(modal => modal.classList.add('hidden'));
            }
        }
    });

    // ローディングアニメーション (一度しか実行されないため DOMContentLoaded の中に残す)
    const whiteScreen = document.querySelector('#loader-white');
    const heroSection = document.querySelector('#hero');
    const curtainInner = document.querySelector('#curtain-inner');
    const logoElement = document.querySelector('.fade-in-out-custom');

    if (whiteScreen && heroSection && curtainInner && logoElement) {
        logoElement.addEventListener('animationend', () => {
            curtainInner.classList.remove('translate-y-full');
            curtainInner.classList.add('animate-curtainReveal');
            logoElement.style.display = 'none';
        });

        curtainInner.addEventListener('animationend', () => {
            whiteScreen.style.animationPlayState = 'running';
        });

        whiteScreen.addEventListener('animationend', () => {
            whiteScreen.style.display = 'none';
            heroSection.classList.remove('opacity-0');
            heroSection.classList.add('opacity-100');
            document.body.classList.remove('no-scroll');
        });
    }
});
