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

// ----------------------------------------------------------------
//     ラジオボタンの初期化を関数化
// ----------------------------------------------------------------
function initDefaultRadio() {
    const radios = document.querySelectorAll('input[name="radio-01"]');
    radios.forEach(radio => {
        radio.checked = false;
    });

    const defaultRadio = document.querySelector('input[name="radio-01"][value="イラストのみのご依頼やお仕事"]');
    if (defaultRadio) {
        defaultRadio.checked = true;
    }
}

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
function initRadioCheckedAttribute() {
    const radioGroup = document.querySelectorAll('input[name="radio-01"]');

    radioGroup.forEach(radio => {
        if (radio._radioChangeHandler) {
            radio.removeEventListener('change', radio._radioChangeHandler);
        }
        radio._radioChangeHandler = () => {
            radioGroup.forEach(r => r.removeAttribute('checked'));
            if (radio.checked) {
                radio.setAttribute('checked', 'checked');
            }
        };
        radio.addEventListener('change', radio._radioChangeHandler);
    });
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
                'hidden', 'block', 'left-full', 'translate-x-full', 'fixed', 'absolute', 'relative',
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
//     すべてのスクリプトを再初期化する関数
// ----------------------------------------------------------------
function initAllScripts() {
    console.log('--- initAllScripts が実行されました！ ---'); // ★このログが出るか？★
    initDefaultRadio();
    initModal();
    initSwipers();
    initAccordion();
    initPriceFormatter();
    // initEventFilter(); // ★ Barba.js のフック内で直接呼び出すため、ここではコメントアウト
    initRadioCheckedAttribute();
    initTopPageGoodsAnimation();
    initParallax();

    initHeaderNavActive();
    // initHeaderDrawer();
    initMvBlurOnScroll();
    initEventFadeOnScroll();

    window.scrollTo(0, 0);
}

// ----------------------------------------------------------------
//      updateHead 関数 (変更なし)
// ----------------------------------------------------------------
function updateHead(data) {
    const head = document.head;
    const tempDiv = document.createElement('div');
    const headMatch = data.next.html.match(/<head[^>]*>([\s\S.]*)<\/head>/i);
    if (!headMatch || !headMatch[0]) {
        console.warn('Could not extract <head> content from next HTML.');
        return;
    }
    tempDiv.innerHTML = headMatch[0];
    const newHeadContent = Array.from(tempDiv.children);

    const elementsToRemove = head.querySelectorAll('title, meta:not([charset]), link[rel="canonical"], link[rel="alternate"], script:not([src*="gsap"]):not([src*="barba"]):not([src*="main.js"])');
    elementsToRemove.forEach(el => el.remove());

    newHeadContent.forEach(el => {
        if (el.tagName === 'LINK' && el.getAttribute('rel') === 'stylesheet') {
            return;
        } else if (el.tagName === 'SCRIPT' && el.src) {
            if (!head.querySelector(`script[src="${el.src}"]`) && !el.src.includes('main.js')) {
                head.appendChild(el.cloneNode(true));
            }
        } else {
            head.appendChild(el.cloneNode(true));
        }
    });
}

// ----------------------------------------------------------------
//     Barba.js 初期化 (関数定義)
// ----------------------------------------------------------------
function setupBarba() {
    console.log('setupBarba 関数が呼び出されました！');

    barba.init({
        transitions: [{
            name: 'no-animation-fade',
            leave() { /* ... */ },
            enter() { /* ... */ },
            before(data) {
                console.log('--- Barba transition before フックが実行されました！ ---');
                const prevDrawerNav = document.querySelector('.home-drawer-nav');
                const prevHomeDrawer = document.querySelector('.home-drawer');

                if (prevDrawerNav) {
                    prevDrawerNav.classList.remove(
                        'is-active', 'opacity-0', 'opacity-100', 'pointer-events-none', 'pointer-events-auto',
                        'hidden', 'block',
                        'left-full', 'translate-x-full', 'fixed', 'absolute', 'relative',
                        'inset-0', 'w-full', 'h-full', 'transform', 'transition'
                    );
                    prevDrawerNav.style.cssText = '';
                    console.log('Prev drawerNav styles and classes cleared.');
                }
                if (prevHomeDrawer) {
                    prevHomeDrawer.style.cssText = '';
                    console.log('Prev homeDrawer styles cleared.');
                }
            },
            after(data) { // この after は、個別のトランジションに対するものです
                console.log('--- Barba transition after フックが実行されました！ ---');
                console.log('次のページのnamespace:', data.next.namespace);

                initHeaderDrawer(data.next.namespace);

                if (typeof initFilterScripts === 'function') {
                    initFilterScripts(data.next.namespace);
                }
                initAllScripts();

                // ★★★ ここを修正します！ data.next.namespace と data.next.html を渡します ★★★
                console.log('Barba transition after: Calling updateBodyClasses with next HTML and namespace.');
                updateBodyClasses(data.next.namespace, data.next.html); // <= 引数を追加！
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
                console.log('--- Barba global after フックが実行されました！ (もし発火すれば) ---');
                // updateBodyClasses(); // ここはコメントアウトしたままでOKです
            }
        },
    });

    console.log('barba.init の呼び出しが完了しました！ほげ');

    // Barba.js が初期化された直後（最初のページロード時）にもクラスをセット
    console.log('setupBarba: Calling updateBodyClasses for initial load.');
    // 初回ロード時は data.next.html がないので、namespace だけ渡すか、何も渡さない
    updateBodyClasses(document.body.getAttribute('data-barba-namespace'));
}

// ----------------------------------------------------------------
// updateBodyClasses() 関数の定義 (main.jsの他の場所、例えばsetupBarba関数より上に定義)// updateBodyClasses() 関数が引数を受け取れるように変更
// ----------------------------------------------------------------
function updateBodyClasses(barbaNamespace = null, nextHtmlString = null) { // 引数 barbaNamespace と nextHtmlString を追加
    const body = document.body;
    let newClasses = []; // 新しいbodyクラスを格納する配列
    let currentNamespace; // 論理的なネームスペース

    if (barbaNamespace) {
        currentNamespace = barbaNamespace; // Barbaフックから渡されたネームスペース
        console.log('updateBodyClasses: Barbaフックから渡されたnamespaceを使用:', currentNamespace);
    } else {
        currentNamespace = body.getAttribute('data-barba-namespace') || 'home'; // 初回ロード時など、DOMから取得
        console.log('updateBodyClasses: DOMからnamespaceを取得:', currentNamespace);
    }

    if (nextHtmlString) {
        // 次のページのHTML文字列をパースして、新しいbodyタグのクラスを取得
        const parser = new DOMParser();
        const doc = parser.parseFromString(nextHtmlString, 'text/html');
        const nextBodyClass = doc.body.className; // 次のページのbodyのクラス文字列

        if (nextBodyClass) {
            newClasses = nextBodyClass.split(' ').filter(cls => cls.trim() !== '');
            console.log('updateBodyClasses: 次のページHTMLから取得した元のクラス:', newClasses);
        } else {
            console.log('updateBodyClasses: 次のページHTMLからbodyクラスを取得できませんでした。');
        }
    } else {
        // nextHtmlString がない場合 (初回ロード時など) は、現在のbodyクラスから始める
        newClasses = Array.from(body.classList);
        console.log('updateBodyClasses: nextHtmlStringがないため、現在のbodyクラスから開始。');
    }

    // まず、既存のhome/subpageクラスをリストから削除しておく
    newClasses = newClasses.filter(cls => cls !== 'home' && cls !== 'subpage');

    // そして、現在のネームスペースに基づいて 'home' または 'subpage' を追加
    if (currentNamespace === 'home') {
        newClasses.push('home');
        console.log('updateBodyClasses: 論理的なnamespaceに基づき "home" を追加。');
    } else { // Works ページなど
        newClasses.push('subpage');
        console.log('updateBodyClasses: 論理的なnamespaceに基づき "subpage" を追加。');
    }

    // 最終的なクラスリストをbodyタグに適用
    body.className = newClasses.join(' ');

    console.log('--- updateBodyClasses 実行終了 ---');
    console.log('updateBodyClasses: 適用後のbodyクラス:', document.body.className);
    console.log('updateBodyClasses: 適用後のbodyクラスリスト:', document.body.classList);
}

// ----------------------------------------------------------------
// barba.hooks.once() の定義 (setupBarba() 関数の外、かつ setupBarba() が呼び出される後に配置)
// ----------------------------------------------------------------
barba.hooks.once(() => {
    console.log('--- Barba once フックが実行されました！ (初回ページロード時) ---');
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
        initialNamespace = barbaContainer.dataset.barbaNamespace; // Barbaコンテナから取得
        console.log('Barbaコンテナが見つかりました！');
        setupBarba();
        console.log('setupBarba()が呼び出されました！');

        // 初回ロード時にinitHeaderDrawerに現在のnamespaceを渡す
        // Barba.jsのコンテナから取得したinitialNamespaceを渡す
        initHeaderDrawer(initialNamespace);

        initAllScripts();
    } else {
        console.warn('Barba.js container not found or namespace missing on this page. Barba.js will not be initialized.');
        // Barbaコンテナがない場合、initHeaderDrawer は引数なし（null）で呼び出す。
        // initHeaderDrawer内部でDOMからネームスペースを探すフォールバックがあるため。
        initHeaderDrawer(null);

        initAllScripts();
    }

    // ★追加するデバッグコードは削除（原因特定済みのため）
    // document.querySelectorAll('a').forEach(link => { /* ... */ });

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
