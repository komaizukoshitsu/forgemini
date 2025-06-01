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
function initHeaderDrawer() {
    const currentNamespace = document.documentElement.dataset.barbaNamespace;
    const isTopPage = currentNamespace === 'home';

    console.log('initHeaderDrawer: isTopPage is:', isTopPage);
    console.log('initHeaderDrawer: Barba Namespace is:', currentNamespace);

    const homeDrawer = document.querySelector('.home-drawer'); // ハンバーガーメニューのアイコンなどが入るコンテナ
    const drawerNav = document.querySelector('.home-drawer-nav'); // 開閉するメニュー本体
    const bar1 = homeDrawer?.querySelector('.drawer-iconBar1');
    const bar2 = homeDrawer?.querySelector('.drawer-iconBar2');

    // 必須要素が見つからない場合は処理を中断
    if (!homeDrawer || !drawerNav || !bar1 || !bar2) {
        console.warn('initHeaderDrawer: Required elements (homeDrawer, drawerNav, bar1, bar2) not found.');
        return;
    }

    // ドロワー（メニュー）の開閉を制御する関数
    const toggleDrawer = (isOpen) => {
        console.log('toggleDrawer called with isOpen:', isOpen); // ★追加ログ★
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
    document._outsideClickHandler = function (e) {
        const isActive = drawerNav.classList.contains('is-active');
        const isHomeDrawerClick = homeDrawer.contains(e.target);
        const isDrawerNavClick = drawerNav.contains(e.target);

        if (isActive && !isHomeDrawerClick && !isDrawerNavClick) {
            toggleDrawer(false);
        }
    };
    document.addEventListener('click', document._outsideClickHandler);

    const fixedContent = document.querySelector(".fixed-content");
    const hiddenArea = document.querySelector("footer");

    if (fixedContent && hiddenArea) {
        if (hiddenArea._intersectionObserver) {
            hiddenArea._intersectionObserver.disconnect();
            hiddenArea._intersectionObserver = null;
        }

        const handleVisibility = () => {
            const currentNamespaceForVisibility = document.documentElement.dataset.barbaNamespace;
            const isTopPageForVisibility = currentNamespaceForVisibility === 'home';

            const isMobile = window.innerWidth < 768;
            const isSubpage = !isTopPageForVisibility;

            console.log('handleVisibility: isTopPage:', isTopPageForVisibility, 'isMobile:', isMobile);
            console.log('handleVisibility: currentNamespace:', currentNamespaceForVisibility); // ★追加ログ★

            // まず、drawerNav の全ての表示/非表示関連クラスとスタイルを完全にリセット
            drawerNav.classList.remove(
                'is-active',
                'opacity-0',
                'opacity-100',
                'pointer-events-none',
                'pointer-events-auto',
                'hidden',
                'block',
                'left-full', 'translate-x-full', 'fixed', 'absolute', 'relative',
                'inset-0', 'w-full', 'h-full', 'transform', 'transition'
            );
            drawerNav.style.cssText = ''; // ★インラインスタイルを完全にクリア★

            // homeDrawer の display も初期化
            homeDrawer.style.cssText = ''; // ★インラインスタイルを完全にクリア★

            // ハンバーガーメニューのバーをリセット
            bar1.classList.remove('is-active');
            bar2.classList.remove('is-active');

            // --- 各ページタイプに合わせた初期状態を設定 ---
            if (isSubpage && !isMobile) {
                // 下層ページ（PC）の場合：メニューを常に表示し、ハンバーガーアイコンは非表示
                console.log('handleVisibility: Subpage (PC) detected. Adding is-active.'); // ★追加ログ★
                drawerNav.classList.add('is-active'); // メニューを常に開いた状態にする

                // ハンバーガーアイコンを強制的に非表示にする
                homeDrawer.style.setProperty('display', 'none', 'important');

            } else if (isTopPageForVisibility && !isMobile) {
                // トップページ（PC）の場合：ハンバーガーメニューを表示し、メニューは閉じた状態
                console.log('handleVisibility: Top page (PC) detected. Removing is-active.'); // ★追加ログ★
                drawerNav.classList.remove('is-active'); // メニューを閉じた状態にする

                // ハンバーガーアイコンを強制的に表示する（デフォルトに戻す）
                homeDrawer.style.setProperty('display', '', 'important');

            } else if (isMobile) {
                // モバイルの場合（トップ/下層共通）：常にハンバーガーメニューを表示し、メニューは閉じる
                console.log('handleVisibility: Mobile detected. Removing is-active.'); // ★追加ログ★
                drawerNav.classList.remove('is-active');

                // ハンバーガーアイコンを強制的に表示する（デフォルトに戻す）
                homeDrawer.style.setProperty('display', '', 'important');
            }
            console.log('handleVisibility: Final drawerNav classes:', drawerNav.classList); // ★追加ログ★
            console.log('handleVisibility: Final homeDrawer style:', homeDrawer.style.display); // ★追加ログ★
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
        // Barba.jsのafterフックで呼ばれるので、通常は不要だが、念のため。
        // setTimeout(() => {
            handleVisibility();
        // }, 0); // わずかな遅延を入れることで、他のスクリプトが完了するのを待つ

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
    initHeaderDrawer();
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
function setupBarba() { // 関数名を initBarba から setupBarba に変更（必須ではないが良い習慣）
    barba.init({
        transitions: [{
            name: 'no-animation-fade',
            leave() { /* ... */ },
            enter() { /* ... */ },
            before(data) { // ★★★ この before フックを修正 ★★★
                console.log('--- Barba before フックが実行されました！ ---');
                const prevDrawerNav = document.querySelector('.home-drawer-nav');
                const prevHomeDrawer = document.querySelector('.home-drawer');

                if (prevDrawerNav) {
                    // 遷移前に全ての表示関連クラスとインラインスタイルをリセット
                    prevDrawerNav.classList.remove(
                        'is-active', 'opacity-0', 'opacity-100', 'pointer-events-none', 'pointer-events-auto',
                        'hidden', 'block', // Tailwind クラスも念のため削除
                        'left-full', 'translate-x-full', 'fixed', 'absolute', 'relative', 'inset-0', 'w-full', 'h-full', 'transform', 'transition'
                    );
                    prevDrawerNav.style.cssText = ''; // 全てのインラインスタイルをクリア
                    console.log('Prev drawerNav styles and classes cleared.');
                }
                if (prevHomeDrawer) {
                    // ハンバーガーアイコンのインラインスタイルもクリア
                    prevHomeDrawer.style.cssText = '';
                    console.log('Prev homeDrawer styles cleared.');
                }
            },
            after(data) {
                console.log('--- Barba after フックが実行されました！ ---');
                console.log('次のページのnamespace:', data.next.namespace);

                // initHeaderDrawer を呼び出す
                initHeaderDrawer(); // ★ここを呼び出す★

                // filter.js の初期化関数を呼び出す
                if (typeof initFilterScripts === 'function') {
                    initFilterScripts(data.next.namespace);
                }
                // Barba.js 遷移後も initAllScripts を実行して、DOMに依存する他のスクリプトを再初期化
                initAllScripts();
            },
        }]
    });

    // --- ここから追加 ---
    // Barba.js がコンテナ外のリンクもインターセプトするようにする
    barba.hooks.before((data) => {
        // クリックされた要素がリンク（またはその子要素）であるか確認
        const link = data.trigger; // クリックされた要素が取得できる

        // フッターのナビゲーションリンクかどうかを判別
        // 親要素のクラス名や構造で判別するのが確実
        // 例: link.closest('.footer-nav') のように、フッターナビ全体のセレクタを使う
        // 今回のHTMLだと、単純に <a> タグなら Barba.js で処理しても良さそう
        if (link && link.tagName === 'A' && link.closest('footer')) { // `link.closest('footer')` でフッター内のリンクを特定
            // これにより、Barba.js がこのリンクを通常の遷移として処理する
            // 何も返さなければ、Barba.js はデフォルトの処理（インターセプト）を行う
            console.log('Barba.js: フッターナビのリンクをインターセプトしました:', link.href);
        } else {
            // フッターナビ以外のリンクの場合（通常は Barba.js が自動でインターセプト）
            console.log('Barba.js: 通常のリンクをインターセプトしました:', link ? link.href : '不明');
        }
    });
    // --- ここまで追加 ---
}

// ----------------------------------------------------------------
// ★重要: Barba.js フックの設定は barba.init() の前に配置する★
// ----------------------------------------------------------------
barba.hooks.once(() => {
    console.log('--- Barba once フックが実行されました！ (初回ページロード時) ---');
    const initialNamespace = document.documentElement.dataset.barbaNamespace;
    if (initialNamespace === 'events' && typeof initFilterScripts === 'function') {
        initFilterScripts(initialNamespace);
    }
    // 初回ロード時は initAllScripts は DOMContentLoaded で既に呼び出されているため、
    // ここで再度呼び出す必要はない
});

// ----------------------------------------------------------------
//     DOMContentLoaded で一度だけ実行する処理
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoadedイベント発火！');

    // Barba.js の初期化
    if (document.querySelector('[data-barba="container"]')) {
        console.log('Barbaコンテナが見つかりました！');
        setupBarba(); // ★関数名を変更した場合はここも変更
        console.log('setupBarba()が呼び出されました！'); // ★ログも変更
        // initAllScripts() は Barba.js がある/ないに関わらず初回ロードで実行
        initAllScripts();
    } else {
        console.warn('Barba.js container not found on this page. Barba.js will not be initialized.');
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
