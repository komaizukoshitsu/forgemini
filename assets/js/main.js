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

    initSingleSwiper('.swiper-default', {
        slidesPerView: 1.2, spaceBetween: 12, slidesOffsetBefore: 20, slidesOffsetAfter: 16,
        navigation: { nextEl: '.default-swiper-next', prevEl: '.default-swiper-prev' },
        breakpoints: { 1024: { slidesPerView: 'auto', spaceBetween: 36, slidesOffsetBefore: 0, slidesOffsetAfter: 36 } },
    });

    initSingleSwiper('.swiper-event', {
        slidesPerView: 1.2, spaceBetween: 12, slidesOffsetBefore: 20, slidesOffsetAfter: 16,
        navigation: { nextEl: '.event-swiper-next', prevEl: '.event-swiper-prev' },
        breakpoints: { 768: { slidesPerView: 'auto', spaceBetween: 16 }, 1024: { slidesPerView: 'auto', spaceBetween: 36, slidesOffsetBefore: 0, slidesOffsetAfter: 36 } },
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
//     月×タグのフィルターを初期化する関数
// ----------------------------------------------------------------
/*
function initEventFilter() {
    console.log('initEventFilter: 初期化開始！'); // 追加
    const filterContainer = document.querySelector('.event-filter');
    const postsContainer = document.querySelector('.event-list');

    if (!filterContainer || !postsContainer) {
        console.warn('initEventFilter: フィルタリングコンテナまたは投稿コンテナが見つかりませんでした。'); // 変更
        return;
    }

    let activeTag = 'all';
    const monthFilterElement = filterContainer.querySelector('#month-filter');
    let activeMonth = monthFilterElement?.value || 'all';

    // Barba.js 遷移後、posts も新しい DOM から再取得されるようにする
    const posts = postsContainer.querySelectorAll('.event-item');

    const filterPosts = () => {
        const currentMonth = monthFilterElement?.value || 'all';
        const currentTag = activeTag;

        posts.forEach(post => {
            const postMonths = (post.dataset.months || '').split(',');
            const postTags = (post.dataset.tags || '').split(',');

            const matchesMonth = currentMonth === 'all' || postMonths.includes(currentMonth);
            const matchesTag = currentTag === 'all' || postTags.includes(currentTag);

            post.style.display = (matchesMonth && matchesTag) ? 'block' : 'none';
        });
        activeMonth = currentMonth;
    };

    // イベントリスナーの再登録（重複を防ぐ）
    if (filterContainer._filterClickHandler) {
        filterContainer.removeEventListener('click', filterContainer._filterClickHandler);
    }
    filterContainer._filterClickHandler = (e) => {
        if (e.target.classList.contains('tag-link')) {
            e.preventDefault();
            filterContainer.querySelectorAll('.tag-link').forEach(link => link.classList.remove('active'));
            e.target.classList.add('active');
            activeTag = e.target.dataset.tag;
            filterPosts();
        }
    };
    filterContainer.addEventListener('click', filterContainer._filterClickHandler);

    if (filterContainer._filterChangeHandler) {
        filterContainer.removeEventListener('change', filterContainer._filterChangeHandler);
    }
    filterContainer._filterChangeHandler = (e) => {
        if (e.target.id === 'month-filter') {
            filterPosts();
        }
    };
    filterContainer.addEventListener('change', filterContainer._filterChangeHandler);

    filterPosts(); // 初回フィルター適用
    console.log('initEventFilter: 初期化完了！'); // 追加
}
*/

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
    const applyParallax = () => {
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
        const screenWidth = window.innerWidth;
        let baseOffset = screenWidth <= 768 ? 450 : 550;

        parallaxElements2.forEach(element => {
            if (screenWidth > 767) {
                const offset = window.scrollY * 0.1 - baseOffset;
                element.style.transform = `translateY(${offset}px)`;
            } else {
                element.style.transform = `translateY(0px)`;
            }
        });

        if (screenWidth > 767) {
            const elemImg = document.getElementById('parallaxBgImg');
            if (elemImg) {
                const scrollOffset = window.scrollY / 10;
                elemImg.style.transform = `translateY(${scrollOffset}px) scale(1.0)`;
            }
        }
    };

    // イベントリスナーの再登録（重複を防ぐ）
    if (document._parallaxScrollHandler) {
        document.removeEventListener('scroll', document._parallaxScrollHandler);
    }
    document._parallaxScrollHandler = applyParallax;
    document.addEventListener('scroll', document._parallaxScrollHandler);

    if (window._parallaxResizeHandler) {
        window.removeEventListener('resize', window._parallaxResizeHandler);
    }
    window._parallaxResizeHandler = () => {
        const parallaxElements2 = document.querySelectorAll('.parallax-2');
        const screenWidth = window.innerWidth;
        parallaxElements2.forEach(element => {
            if (screenWidth <= 767) {
                element.style.transform = `translateY(0px)`;
            }
        });
        applyParallax();
    };
    window.addEventListener('resize', window._parallaxResizeHandler);

    applyParallax(); // 初期呼び出し
}

// ----------------------------------------------------------------
//     ヘッダーナビのアクティブ状態を管理する関数
// ----------------------------------------------------------------
function initHeaderNavActive() {
    const headerNav = document.querySelector('.header-nav');
    if (!headerNav) return;

    // イベントリスナーの再登録（重複を防ぐ）
    if (headerNav._headerNavClickHandler) {
        headerNav.removeEventListener('click', headerNav._headerNavClickHandler);
    }
    headerNav._headerNavClickHandler = function(e) {
        if (e.target.classList.contains('header-nav-item') || e.target.closest('.header-nav-item')) {
            const clickedItem = e.target.closest('.header-nav-item');
            if (clickedItem) {
                this.querySelectorAll('.header-nav-item a').forEach(item => item.classList.remove('active'));
                clickedItem.querySelector('a')?.classList.add('active');
            }
        }
    };
    headerNav.addEventListener('click', headerNav._headerNavClickHandler);
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

    if (!homeDrawer || !drawerNav || !bar1 || !bar2) {
        console.warn('initHeaderDrawer: Required elements (homeDrawer, drawerNav, bar1, bar2) not found.');
        return;
    }

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
        if (isTopPage) { // トップページでのみクリックイベントを処理
            toggleDrawer(!drawerNav.classList.contains('is-active'));
        }
    };
    homeDrawer.addEventListener('click', homeDrawer._toggleClickHandler);

    // ドロワー外クリックで閉じるイベントリスナー (重複登録防止)
    if (document._outsideClickHandler) {
        document.removeEventListener('click', document._outsideClickHandler);
    }
    if (isTopPage) { // トップページでのみ適用
        console.log('initHeaderDrawer: Outside click handler IS REGISTERED (Top page).');
        document._outsideClickHandler = function (e) {
            const isActive = drawerNav.classList.contains('is-active');
            const isHomeDrawerClick = homeDrawer.contains(e.target);
            const isDrawerNavClick = drawerNav.contains(e.target);

            if (isActive && !isHomeDrawerClick && !isDrawerNavClick) {
                toggleDrawer(false);
            }
        };
        document.addEventListener('click', document._outsideClickHandler);
    } else {
        console.log('initHeaderDrawer: Outside click handler NOT REGISTERED (Subpage PC).');
    }

    // ドロワー内のリンクをクリックで閉じる処理 (トップページのみ、重複登録防止)
    drawerNav.querySelectorAll('a').forEach(link => {
        if (link._clickHandler) {
            link.removeEventListener('click', link._clickHandler);
        }
        link._clickHandler = function() {
            if (isTopPage && drawerNav.classList.contains('is-active')) {
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
            const isMobileCurrent = window.innerWidth < 768;

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
    initHeaderNavActive();
    initMvBlurOnScroll();
    initEventFadeOnScroll();
    // initFilterScripts と initEventFilter は個別にBarba.jsのafterフックで制御するため、ここには含めない
    // ただし、Barba.jsを使わないフォールバックの場合はここに追加することも検討する
    window.scrollTo(0, 0);
}

// ----------------------------------------------------------------
//     Barba.js 初期化 (関数定義)
// ----------------------------------------------------------------
let barbaInitialized = false;
function setupBarba() {
    if (barbaInitialized) {
        console.warn('Barba.js は既に初期化されています。');
        return;
    }

    console.log('setupBarba 関数が呼び出されました！');

    // Barba.js のデバッグモードを有効化
    barba.use({ debug: true });

    barba.init({
        views: [
            { namespace: 'home' },
            { namespace: 'works-archive' },
            { namespace: 'goods-archive' },
            { namespace: 'events-archive' },
            { namespace: 'news-page' },
            { namespace: 'page' }
        ],
        transitions: [{
            name: 'default-transition',
            beforeEnter({ next }) {
                console.log('--- Barba transition beforeEnter フックが実行されました！ ---');
                contactForm7Run(next);
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
            before(data) {
                console.log('--- Barba transition before フックが実行されました！ ---');
                const prevDrawerNav = document.querySelector('.home-drawer-nav');

                // 下層ページから下層ページへの遷移では is-active を維持し、それ以外は削除
                if (prevDrawerNav) {
                    const isPrevSubpage = data.current.namespace !== 'home';
                    const isNextSubpage = data.next.namespace !== 'home';

                    if (isPrevSubpage && isNextSubpage) {
                        console.log('before: 下層ページ間遷移のため drawerNav の is-active を維持。');
                        // 何もしない（is-active を維持）
                    } else {
                        prevDrawerNav.classList.remove('is-active');
                        console.log('before: 非下層ページ間遷移のため drawerNav から is-active を削除。');
                    }
                }

                window.scrollTo(0, 0);
                document.body.classList.remove('loading');
            },
            after(data) {
                console.log('--- Barba transition after フックが実行されました！ ---');
                console.log('次のページのnamespace:', data.next.namespace);

                try {
                    const homeDrawerNav = document.querySelector('.home-drawer-nav');
                    const newNamespace = data.next.namespace;

                    // ハンバーガーメニューの状態をnamespaceに基づいて設定
                    if (homeDrawerNav) {
                        if (newNamespace === 'home') {
                            homeDrawerNav.classList.remove('is-active');
                            console.log('homeDrawerNav reset for home page (closed).');
                        } else {
                            homeDrawerNav.classList.add('is-active');
                            console.log('homeDrawerNav set to active for subpage.');
                        }
                    }

                    // initHeaderDrawer を呼び出す
                    // DOMが完全に更新されるのを待つため、わずかな遅延を入れる
                    setTimeout(() => {
                        if (typeof initHeaderDrawer === 'function') {
                            initHeaderDrawer(newNamespace);
                            console.log('Barba after hook: initHeaderDrawer called with namespace:', newNamespace);
                        } else {
                            console.warn('initHeaderDrawer 関数が定義されていません。');
                        }
                    }, 50);

                    // Works / Goods archive ページのフィルタリングを初期化
                    if (newNamespace === 'works-archive' || newNamespace === 'goods-archive') {
                        setTimeout(() => {
                            if (typeof initFilterScripts === 'function') {
                                initFilterScripts(newNamespace);
                                console.log('Barba after hook: initFilterScripts called with namespace:', newNamespace);
                            } else {
                                console.warn('initFilterScripts 関数が定義されていません。');
                            }
                        }, 100);
                    }

                    // Events archive ページのフィルタリングを初期化
                    if (newNamespace === 'events-archive') {
                        setTimeout(() => {
                            if (typeof initEventFilter === 'function') {
                                initEventFilter(); // initEventFilter は namespace を引数にとらないため、引数なしで呼び出す
                                console.log('Barba after hook: initEventFilter called for events-archive.');
                            } else {
                                console.warn('initEventFilter 関数が定義されていません。');
                            }
                        }, 100);
                    }

                    // その他の共通スクリプトを初期化
                    // initAllScripts の中身が上記の個別初期化と重複しないように注意
                    if (typeof initAllScripts === 'function') {
                        initAllScripts();
                        console.log('Barba after hook: initAllScripts called');
                    } else {
                        console.warn('initAllScripts 関数が定義されていません。');
                    }

                    // body の data-page 属性を更新
                    if (data.next.container) {
                        const nextBarbaNamespace = data.next.container.dataset.barbaNamespace || 'default';
                        document.body.setAttribute('data-page', nextBarbaNamespace);
                        console.log('Body data-page attribute updated to:', nextBarbaNamespace);
                    }

                } catch (error) {
                    console.error('Barba.js after hook でエラーが発生しました:', error);
                    throw error;
                }
            },
        }],
        // グローバルフック
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
                // Contact Form 7 の動的ロード/再初期化が必要な場合
                // wpcf7.dom_updated イベントをディスパッチすることで、CF7 が新しいDOMを認識する
                if (typeof document.dispatchEvent === 'function' && typeof CustomEvent === 'function') {
                    document.dispatchEvent(new CustomEvent('wpcf7.dom_updated'));
                    console.log('wpcf7.dom_updated イベントがディスパッチされました。');
                } else {
                    console.warn('CustomEvent または document.dispatchEvent がサポートされていません。wpcf7.dom_updated は発火できません。');
                }
            }
        },
    });

    console.log('barba.init の呼び出しが完了しました！');

    // Barba.js 初期化時に一度だけヘッダードロワーとフィルタースクリプトを初期化
    // ここで initHeaderDrawer と initFilterScripts を呼び出すことで、
    // 初回ページロード時の初期化を確実にする
    console.log('setupBarba: Calling initHeaderDrawer for initial load.');
    const initialBarbaContainerForSetup = document.querySelector('[data-barba="container"]');
    const initialNamespaceForSetup = initialBarbaContainerForSetup?.dataset.barbaNamespace || 'default';
    initHeaderDrawer(initialNamespaceForSetup);

    // 初回ロード時もフィルタリングスクリプトを呼び出す
    if (initialNamespaceForSetup === 'works-archive' || initialNamespaceForSetup === 'goods-archive') {
        if (typeof initFilterScripts === 'function') {
            initFilterScripts(initialNamespaceForSetup);
            console.log('setupBarba: initFilterScripts called for initial page load (works/goods).');
        }
    }
    if (initialNamespaceForSetup === 'events-archive') {
        if (typeof initEventFilter === 'function') {
            initEventFilter();
            console.log('setupBarba: initEventFilter called for initial page load (events).');
        }
    }
}

// ----------------------------------------------------------------
// updateBodyClasses() 関数は削除またはコメントアウト
// ----------------------------------------------------------------
// function updateBodyClasses(barbaNamespace = null, nextHtmlString = null) { /* ... */ }

// ----------------------------------------------------------------
// barba.hooks.once() は Barba.js の初回ロード時に一度だけ実行される
// ----------------------------------------------------------------
barba.hooks.once(() => {
    console.log('--- Barba once フックが実行されました！ (初回ページロード時) ---');
    // setupBarba() 内で既にこれらの関数が呼び出されているため、基本的にはここでの重複呼び出しは不要です。
    // ただし、特定の Barba.js ライフサイクルでしか実行されない処理がある場合はここに記述します。
    // 現状のinitHeaderDrawerとinitFilterScriptsはsetupBarba()内で呼び出す方が適切です。
});


// ----------------------------------------------------------------
//     DOMContentLoaded で一度だけ実行する処理
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoadedイベント発火！');

    // Barba.js が利用可能か確認し、初期化
    if (typeof barba !== 'undefined') {
        setupBarba(); // Barba.js の初期化を実行
        console.log('DOMContentLoaded: Barba.js setup called.');
    } else {
        console.warn('Barba.js が見つかりませんでした。Barba.js なしのフォールバックモードで初期化します。');
        // Barba.js が利用できない場合の通常の初期化
        const initialNamespaceFallback = document.querySelector('[data-barba="container"]')?.dataset.barbaNamespace || 'default';
        initHeaderDrawer(initialNamespaceFallback);
        initFilterScripts(initialNamespaceFallback); // Barba.js を使用しない場合のみ呼び出す
        initEventFilter(); // Barba.js を使用しない場合のみ呼び出す
        initAllScripts();
        contactForm7Run({ container: document }); // フルリロード時もContact Form 7を初期化
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
