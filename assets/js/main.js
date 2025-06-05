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

// // ----------------------------------------------------------------
// //     ラジオボタンの初期化を関数化
// // ----------------------------------------------------------------
// function initDefaultRadio() {
//     const radios = document.querySelectorAll('input[name="radio-01"]');
//     radios.forEach(radio => {
//         radio.checked = false;
//     });

//     const defaultRadio = document.querySelector('input[name="radio-01"][value="イラストのみのご依頼やお仕事"]');
//     if (defaultRadio) {
//         defaultRadio.checked = true;
//     }
// }

// ----------------------------------------------------------------
//     Swiperを初期化する関数
// ----------------------------------------------------------------
function initSwipers() {
    const initSingleSwiper = (selector, options) => {
        const el = document.querySelector(selector);
        if (el && el.swiper) {
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
function initEventFilter() {
    const filterContainer = document.querySelector('.event-filter');
    const postsContainer = document.querySelector('.event-list');

    if (!filterContainer || !postsContainer) return;

    let activeTag = 'all';
    const monthFilterElement = filterContainer.querySelector('#month-filter');
    let activeMonth = monthFilterElement?.value || 'all';

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

    filterPosts(); // 初回フィルター
}

// ----------------------------------------------------------------
//     ラジオボタンの checked 属性を手動で更新する関数
// ----------------------------------------------------------------
// function initRadioCheckedAttribute() {
//     const radioGroup = document.querySelectorAll('input[name="radio-01"]');

//     radioGroup.forEach(radio => {
//         if (radio._radioChangeHandler) {
//             radio.removeEventListener('change', radio._radioChangeHandler);
//         }
//         radio._radioChangeHandler = () => {
//             radioGroup.forEach(r => r.removeAttribute('checked'));
//             if (radio.checked) {
//                 radio.setAttribute('checked', 'checked');
//             }
//         };
//         radio.addEventListener('change', radio._radioChangeHandler);
//     });
// }

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
            track.style.animation = 'none';
            void track.offsetWidth;
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

    applyParallax();
}

// ----------------------------------------------------------------
//     ヘッダーナビのアクティブ状態を管理する関数
// ----------------------------------------------------------------
function initHeaderNavActive() {
    const headerNav = document.querySelector('.header-nav');
    if (!headerNav) return;

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
function initHeaderDrawer(forcedNamespace = null) { // 引数を受け取るように変更
    let currentNamespace;

    // 1. まずは forcedNamespace (Barba.jsフックから渡される値) を確認
    if (forcedNamespace) {
        currentNamespace = forcedNamespace;
    } else {
        // 2. forcedNamespace がない場合（初回ロード時など）、data-barba="container" から取得を試みる
        const barbaContainer = document.querySelector('[data-barba="container"]');
        if (barbaContainer && barbaContainer.dataset.barbaNamespace) {
            currentNamespace = barbaContainer.dataset.barbaNamespace;
        } else {
            // 3. どちらも見つからない場合の最終的なフォールバック
            currentNamespace = 'default'; // または他の適切なデフォルト値
        }
    }

    // ここで currentNamespace が home になっていない場合に、
    // URLに基づいて強制的に home に設定するロジックを再検討します。
    if (currentNamespace !== 'home' && (window.location.pathname === '/' || window.location.pathname === '/home/')) {
        console.log('initHeaderDrawer: Force setting namespace to home based on URL.');
        currentNamespace = 'home';
    }
    const isTopPage = currentNamespace === 'home'; // この isTopPage は関数のスコープ内で使われる

    console.log('initHeaderDrawer: isTopPage is:', isTopPage);
    console.log('initHeaderDrawer: Barba Namespace is:', currentNamespace);

    const homeDrawer = document.querySelector('.home-drawer');
    const drawerNav = document.querySelector('.home-drawer-nav');
    const bar1 = homeDrawer?.querySelector('.drawer-iconBar1');
    const bar2 = homeDrawer?.querySelector('.drawer-iconBar2');

    if (!homeDrawer || !drawerNav || !bar1 || !bar2) {
        console.warn('initHeaderDrawer: Required elements (homeDrawer, drawerNav, bar1, bar2) not found.');
        return;
    }

    // ドロワー（メニュー）の開閉を制御する関数
    const toggleDrawer = (isOpen) => {
        console.log('toggleDrawer called with isOpen:', isOpen);
        drawerNav.classList.toggle('is-active', isOpen);
        bar1.classList.toggle('is-active', isOpen);
        bar2.classList.toggle('is-active', isOpen);

        if (isOpen) {
            drawerNav.classList.add('opacity-100', 'pointer-events-auto');
            drawerNav.classList.remove('opacity-0', 'pointer-events-none');
        } else {
            drawerNav.classList.add('opacity-0', 'pointer-events-none');
            drawerNav.classList.remove('opacity-100', 'pointer-events-auto');
        }
    };

    // ハンバーガーメニューアイコンのクリックイベントリスナー
    if (homeDrawer._toggleClickHandler) {
        homeDrawer.removeEventListener('click', homeDrawer._toggleClickHandler);
    }
    homeDrawer._toggleClickHandler = function (e) {
        toggleDrawer(!drawerNav.classList.contains('is-active'));
    };
    homeDrawer.addEventListener('click', homeDrawer._toggleClickHandler);

    // ドロワー外クリックで閉じるイベントリスナー
    if (document._outsideClickHandler) {
        document.removeEventListener('click', document._outsideClickHandler);
    }

    // ドロワー外クリックで閉じる処理を適用するかどうかを判定
    const isMobile = window.innerWidth < 768; // この isMobile は関数のスコープ内で使われる
    const isSubpage = !isTopPage; // currentNamespace が home 以外なら下層ページ

    if (!isSubpage || isMobile) {
        console.log('initHeaderDrawer: Outside click handler IS REGISTERED.');
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

    const fixedContent = document.querySelector(".fixed-content");
    const hiddenArea = document.querySelector("footer");

    if (fixedContent && hiddenArea) {
        if (hiddenArea._intersectionObserver) {
            hiddenArea._intersectionObserver.disconnect();
            hiddenArea._intersectionObserver = null;
        }

        // handleVisibility 関数は、外側の initHeaderDrawer のスコープにある
        // isTopPage と currentNamespace を参照します。
        const handleVisibility = () => {
            // ここで isTopPage と currentNamespace を改めて取得する必要はありません。
            // 外側の initHeaderDrawer 関数スコープのものを参照します。
            const isMobileCurrent = window.innerWidth < 768; // リサイズ時に最新のモバイル判定

            console.log('handleVisibility: isTopPage:', isTopPage, 'isMobile:', isMobileCurrent); // isTopPage は外のスコープのものを利用
            console.log('handleVisibility: currentNamespace:', currentNamespace); // currentNamespace は外のスコープのものを利用

            // まず、drawerNav の全ての表示/非表示関連クラスとスタイルを完全にリセット
            drawerNav.classList.remove(
                'is-active', 'opacity-0', 'opacity-100', 'pointer-events-none', 'pointer-events-auto',
                'hidden', 'block', 'left-full', 'translate-x-full', // 'fixed', 'absolute', 'relative' を削除
                'inset-0', 'w-full', 'h-full', 'transform', 'transition'
            );
            drawerNav.style.cssText = '';

            // homeDrawer の display も初期化
            homeDrawer.style.cssText = '';

            // ハンバーガーメニューのバーをリセット
            bar1.classList.remove('is-active');
            bar2.classList.remove('is-active');

            // --- 各ページタイプに合わせた初期状態を設定 ---
            if (!isTopPage && !isMobileCurrent) { // 下層ページ（PC）の場合
                console.log('handleVisibility: Subpage (PC) detected. Adding is-active.');
                drawerNav.classList.add('is-active');
                homeDrawer.style.setProperty('display', 'none', 'important'); // ハンバーガーアイコンを非表示

            } else if (isTopPage && !isMobileCurrent) { // トップページ（PC）の場合
                console.log('handleVisibility: Top page (PC) detected. Removing is-active.');
                drawerNav.classList.remove('is-active');
                homeDrawer.style.setProperty('display', '', 'important'); // ハンバーガーアイコンを表示

            } else if (isMobileCurrent) { // モバイルの場合（トップ/下層共通）
                console.log('handleVisibility: Mobile detected. Removing is-active.');
                drawerNav.classList.remove('is-active');
                homeDrawer.style.setProperty('display', '', 'important'); // ハンバーガーアイコンを表示
            }
            console.log('handleVisibility: Final drawerNav classes:', drawerNav.classList);
            console.log('handleVisibility: Final homeDrawer style:', homeDrawer.style.display);
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

        if (window._drawerResizeHandler) {
            window.removeEventListener('resize', window._drawerResizeHandler);
        }
        window._drawerResizeHandler = handleVisibility;
        window.addEventListener('resize', window._drawerResizeHandler);

        // ★★★ DOMが完全に準備された後に handleVisibility を実行する（重要） ★★★
        // 初回ロード時と Barba.js 遷移後で確実に実行されるようにする
        handleVisibility();
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
    applyMvBlur();
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
    applyEventFade();
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
            // jQuery オブジェクトが必要な場合はここで変換
            var $form = jQuery(formElement); // Contact Form 7 の init にはネイティブDOM、wpcf7cf.initForm には jQuery オブジェクトが必要なようです

            if (typeof wpcf7 !== 'undefined' && typeof wpcf7.init === 'function') {
                wpcf7.init(formElement); // ネイティブDOM要素を渡す
                console.log('contactForm7Run: Contact Form 7 フォームを再初期化しました:', formElement);

                // ★ここが最も重要: Conditional Fields for CF7 の初期化！★
                if (typeof wpcf7cf !== 'undefined' && typeof wpcf7cf.initForm === 'function') {
                    // ドキュメントの例に合わせて $form (jQuery オブジェクト) を渡す
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
    // initDefaultRadio();
    initModal();
    initSwipers();
    initAccordion();
    initPriceFormatter();
    // initRadioCheckedAttribute();
    initTopPageGoodsAnimation();
    initParallax();

    initHeaderNavActive();
    initMvBlurOnScroll();
    initEventFadeOnScroll();

    window.scrollTo(0, 0);
}

// ----------------------------------------------------------------
//      updateHead 関数は削除するか、呼び出し元からコメントアウトしてください
//      Barba.js で head を手動で操作することは通常推奨されません。
// ----------------------------------------------------------------
// function updateHead(data) { /* ... 削除または呼び出し元をコメントアウト */ }

// ----------------------------------------------------------------
//     Barba.js 初期化 (関数定義)
// ----------------------------------------------------------------
let barbaInitialized = false; // Barba.jsが初期化済みかどうかのフラグ
function setupBarba() {
    if (barbaInitialized) {
        console.warn('Barba.js は既に初期化されています。');
        return;
    }

    console.log('setupBarba 関数が呼び出されました！');

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
            name: 'no-animation-fade',
            // ★重要な修正点: leave フックでアニメーション完了を待つ★
            beforeEnter({ next }) {
                console.log('--- Barba transition beforeEnter フックが実行されました！ ---');
                contactForm7Run(next); // Contact Form 7 と Conditional Fields の初期化関数を呼び出す
            },
            leave({ current }) {
                return new Promise(resolve => {
                    const container = current.container;
                    container.style.transition = 'opacity 0.1s ease-out'; // CSSトランジションを明示的に適用
                    container.style.opacity = '0'; // フェードアウト開始

                    const onTransitionEnd = () => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve(); // アニメーションが完了したらPromiseを解決
                    };
                    container.addEventListener('transitionend', onTransitionEnd);

                    // フォールバック: 万が一 transitionend が発火しない場合のために、少し長めのsetTimeout
                    setTimeout(() => {
                        container.removeEventListener('transitionend', onTransitionEnd); // フォールバックでもイベントリスナーを解除
                        resolve();
                    }, 150); // 0.3秒のアニメーション + 50msの余裕
                });
            },
            // ★重要な修正点: enter フックでアニメーション完了を待つ★
            enter({ next }) {
                return new Promise(resolve => {
                    const container = next.container;
                    container.style.transition = 'opacity 0.1s ease-in'; // CSSトランジションを明示的に適用
                    container.style.opacity = '0'; // 最初は非表示に設定

                    // DOMに要素が追加された次のフレームで opacity を 1 に設定してトランジションを開始
                    requestAnimationFrame(() => {
                        container.style.opacity = '1';
                    });

                    const onTransitionEnd = () => {
                        container.removeEventListener('transitionend', onTransitionEnd);
                        resolve(); // アニメーションが完了したらPromiseを解決
                    };
                    container.addEventListener('transitionend', onTransitionEnd);

                    // フォールバック
                    setTimeout(() => {
                        container.removeEventListener('transitionend', onTransitionEnd); // フォールバックでもイベントリスナーを解除
                        resolve();
                    }, 150);
                });
            },
            before(data) {
                console.log('--- Barba transition before フックが実行されました！ ---');
                const prevDrawerNav = document.querySelector('.home-drawer-nav');
                const prevHomeDrawer = document.querySelector('.home-drawer');

                if (prevDrawerNav) {
                    prevDrawerNav.classList.remove(
                        'is-active', 'opacity-0', 'opacity-100', 'pointer-events-none', 'pointer-events-auto',
                        'hidden', 'block',
                        'left-full', 'translate-x-full'
                    );
                    console.log('Prev drawerNav styles and classes cleared (if any).');
                }
                if (prevHomeDrawer) {
                    prevHomeDrawer.style.cssText = '';
                    console.log('Prev homeDrawer styles cleared.');
                }
            },
            after(data) {
                console.log('--- Barba transition after フックが実行されました！ ---');
                console.log('次のページのnamespace:', data.next.namespace);

                try {
                    const homeDrawerNav = data.next.container.querySelector('.home-drawer-nav');
                    const homeDrawer = data.next.container.querySelector('.home-drawer');

                    if (homeDrawerNav) {
                        if (homeDrawerNav.classList.contains('is-active')) {
                            homeDrawerNav.classList.remove('is-active');
                        }
                        // homeDrawerNav.style.opacity = '';
                        // homeDrawerNav.style.pointerEvents = '';
                        // homeDrawerNav.style.display = '';

                        homeDrawerNav.classList.add(
                            'fixed',
                            'px-5', 'lg:px-0',
                            'pt-[50px]', 'lg:pt-0',
                            'pb-5', 'lg:pb-0',
                            'bottom-[120px]',
                            'right-[80px]',
                            'left-auto',
                            'transition-opacity', 'duration-200', 'ease-in-out',
                            'z-[100]',
                            'w-[90%]', 'lg:w-[190px]'
                        );
                        console.log('homeDrawerNav classes reapplied in after hook.');
                    }

                    if (typeof initHeaderDrawer === 'function') {
                        initHeaderDrawer(data.next.namespace);
                    } else {
                        console.warn('initHeaderDrawer 関数が定義されていません。');
                    }

                    if (typeof initFilterScripts === 'function') {
                        initFilterScripts(data.next.namespace);
                    } else {
                        console.warn('initFilterScripts 関数が定義されていません。');
                    }

                    // initAllScripts の呼び出し順序は、他のスクリプトが全て初期化されてからが安全
                    if (typeof initAllScripts === 'function') {
                        initAllScripts();
                    } else {
                        console.warn('initAllScripts 関数が定義されていません。');
                    }

                    const bodyElement = document.body;
                    const newNamespace = data.next.namespace;

                    if (newNamespace) {
                        bodyElement.setAttribute('data-page', newNamespace);
                    } else {
                        if (data.next.container.classList.contains('home')) {
                            bodyElement.setAttribute('data-page', 'home');
                        } else {
                            bodyElement.setAttribute('data-page', 'subpage');
                        }
                    }
                    console.log('Barba.js after hook: data-page set to', bodyElement.getAttribute('data-page'));

                    // ★setTimeout の時間を調整して再試行★
                    // setTimeout(() => {
                    //     document.dispatchEvent(new CustomEvent('wpcf7.dom_updated'));
                    //     console.log('DEBUG: wpcf7.dom_updated イベントが transition.after でディスパッチされました (setTimeout)。');
                    // }, 300);

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
                // ★ここを追加★ Contact Form 7 の再初期化イベントをディスパッチ
                // document.dispatchEvent(new CustomEvent('wpcf7.dom_updated'));
                // console.log('wpcf7.dom_updated イベントがディスパッチされました。');
            }
        },
    });

    console.log('barba.init の呼び出しが完了しました！');

    console.log('setupBarba: Calling initHeaderDrawer for initial load.');
    const initialNamespace = document.body.getAttribute('data-barba-namespace') || (document.body.classList.contains('home') ? 'home' : 'subpage');
    initHeaderDrawer(initialNamespace);

    if (initialNamespace && typeof initFilterScripts === 'function') {
        initFilterScripts(initialNamespace);
    }
}

// ----------------------------------------------------------------
// updateBodyClasses() 関数の定義
// ----------------------------------------------------------------
// この関数は Barba.js のコンテナやボディのクラスを直接操作するもので、
// 通常は Barba.js の 'after' フックやカスタムロジック内で適切に呼び出す必要があります。
// 記載いただいたコードでは 'transitions.after' フック内で data-page の更新が
// 既に直接行われているため、この関数は削除するか、他の用途で必要なければ維持する必要はありません。
// 現状のコードでは、Barba.jsの動作を阻害する可能性があるので、コメントアウトまたは削除を推奨します。
/*
function updateBodyClasses(barbaNamespace = null, nextHtmlString = null) {
    // ... （この関数全体をコメントアウトするか削除）
}
*/

// ----------------------------------------------------------------
// barba.hooks.once() の定義
// ----------------------------------------------------------------
barba.hooks.once(() => {
    console.log('--- Barba once フックが実行されました！ (初回ページロード時) ---');
    // このフックは、setupBarba() の呼び出しとは独立して、Barba.jsが初期化された「後」に一度だけ実行されます。
    // ここで initFilterScripts を呼び出すのは、Barba.js の初回ロード時としては適切です。
    const initialBarbaContainer = document.querySelector('[data-barba="container"]');
    let initialNamespace = 'default';
    if (initialBarbaContainer && initialBarbaContainer.dataset.barbaNamespace) {
        initialNamespace = initialBarbaContainer.dataset.barbaNamespace;
    }

    if (initialNamespace === 'events' && typeof initFilterScripts === 'function') {
        initFilterScripts(initialNamespace);
    }
});

// ----------------------------------------------------------------
//     DOMContentLoaded で一度だけ実行する処理
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoadedイベント発火！');

    const barbaContainer = document.querySelector('[data-barba="container"]');
    let initialNamespace = 'default';

    if (barbaContainer && barbaContainer.dataset.barbaNamespace) {
        initialNamespace = barbaContainer.dataset.barbaNamespace;
        console.log('Barbaコンテナが見つかりました！');
        setupBarba(); // Barba.js の初期化を実行

        // setupBarba() 内部で initHeaderDrawer と initFilterScripts は呼び出されているので、
        // ここでの重複呼び出しは削除。
        // initHeaderDrawer(initialNamespace); // 不要
        // initFilterScripts(initialNamespace); // setupBarba() 内で呼び出されるので不要

        // initAllScripts は DOMContentLoaded でも呼び出す
        initAllScripts();
    } else {
        console.warn('Barba.js container not found or namespace missing on this page. Barba.js will not be initialized.');
        // Barbaコンテナがない場合でも、必要なスクリプトは初期化する
        initHeaderDrawer(null); // Barbaがないので null を渡す
        initFilterScripts(null); // Barbaがないので null を渡す
        initAllScripts();
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
