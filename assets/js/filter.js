// assets/js/filter.js (Ajax version)

console.log('filter.js (Ajax version) ファイルが読み込まれました！');

/**
 * フィルタリングスクリプトを初期化する関数 (Barba.jsと連携)
 * @param {string} barbaNamespace Barba.js の現在の名前空間 (例: 'events-archive')
 */
function initFilterScripts(barbaNamespace) {
    console.log('initFilterScripts (Ajax version) が実行されました！現在の Barba Namespace:', barbaNamespace);

    // Barba.jsの名前空間からカスタム投稿タイプ名を抽出 (例: 'events-archive' -> 'events')
    let currentPagePostType = barbaNamespace.replace('-archive', '');
    if (currentPagePostType === barbaNamespace) {
        // -archive が含まれていなければ、そのままの名前空間を使用 (例: 'news-page' -> 'news')
        if (currentPagePostType === 'news-page') { // news-page の場合
            currentPagePostType = 'news';
        }
    }
    console.log('Detected currentPagePostType:', currentPagePostType);

    // DOM要素の取得 (Barba.js 遷移後も常に最新のDOMから取得する)
    const filterButtonsContainer = document.querySelector(`[data-barba-namespace="${barbaNamespace}"] .tag-list`);
    const filterButtons = filterButtonsContainer ? filterButtonsContainer.querySelectorAll('[data-tag]') : [];
    const postsContainer = document.querySelector('#filtered-posts-container');
    const paginationContainer = document.querySelector('#pagination-container');
    // const monthSelect = (currentPagePostType === 'events') ? document.querySelector('#month-filter') : null;

    // ★ここから追加・変更★
    let monthSelect = null;
    if (currentPagePostType === 'events') {
        // 現在のBarbaコンテナ内の要素を検索するように修正
        const currentContainer = document.querySelector(`[data-barba="container"][data-barba-namespace="${barbaNamespace}"]`);
        if (currentContainer) {
            monthSelect = currentContainer.querySelector('#month-filter');
            console.log('monthSelect found in current container:', monthSelect);
        } else {
            console.warn('Current Barba container not found for monthSelect search.');
        }
    }
    console.log('Final monthSelect value:', monthSelect); // monthSelect の最終的な状態を確認
  // ★ここまで追加・変更★

    if (!postsContainer) {
        console.warn('initFilterScripts: フィルタリングリストコンテナ (#filtered-posts-container) が現在のページで見つかりませんでした。スキップします。');
        return;
    }

    if (filterButtons.length === 0 && (!monthSelect || currentPagePostType !== 'events')) {
        console.warn('initFilterScripts: フィルタリングボタンが見つからないか、イベントページで月フィルターが見つかりません。');
    }

    /**
     * Ajaxリクエストを送信し、結果をレンダリングする関数
     * @param {string} tag 現在選択されているタグのスラッグ
     * @param {string} month 現在選択されている月 (YYYY-MM形式、または 'all')
     * @param {number} paged 表示するページ番号
     */
    const fetchAndRenderPosts = async (tag = 'all', month = 'all', paged = 1) => {
        console.log(`Fetching posts: tag=${tag}, month=${month}, paged=${paged}, post_type=${currentPagePostType}`);
        postsContainer.style.opacity = '0.5'; // ロード中を示すため半透明にする
        if (paginationContainer) {
            paginationContainer.style.opacity = '0.5';
        }

        try {
            const formData = new FormData();
            formData.append('post_type', currentPagePostType);
            formData.append('tag', tag);
            formData.append('paged', paged);

            let ajaxAction;
            let taxonomySlug;

            // 投稿タイプごとにAjaxアクションとタクソノミーを設定
            if (currentPagePostType === 'events') {
                ajaxAction = 'filter_events_by_month_and_tag'; // Events専用のAjaxアクション名
                taxonomySlug = 'event_type'; // ★`functions.php` と一致させるタクソノミースラッグ★
                if (month !== 'all') {
                    formData.append('month', month); // YYYY-MM 形式の月をそのまま渡す
                }
            } else if (currentPagePostType === 'works') {
                ajaxAction = 'filter_posts_by_custom_type_and_taxonomy';
                taxonomySlug = 'works_category'; // Worksのタクソノミー
            } else if (currentPagePostType === 'goods') {
                ajaxAction = 'filter_posts_by_custom_type_and_taxonomy';
                taxonomySlug = 'goods_category'; // Goodsのタクソノミー
            } else {
                console.error('Unknown post type for filtering:', currentPagePostType);
                return; // 未知の投稿タイプの場合は処理を中断
            }

            formData.append('action', ajaxAction);
            formData.append('taxonomy', taxonomySlug);


            const response = await fetch(ajax_object.ajax_url, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const jsonResponse = await response.json(); // JSONレスポンスとしてパース

            if (jsonResponse.success) {
                // PHPから返されたHTML文字列を直接 DOM 要素に代入する
                // jsonResponse.data はオブジェクトなので、その中の posts_html と pagination_html プロパティにアクセスする
                postsContainer.innerHTML = jsonResponse.data.posts_html; // ここを変更
                if (paginationContainer) {
                    paginationContainer.innerHTML = jsonResponse.data.pagination_html; // ここを変更
                }

                // ページネーションリンクにイベントリスナーを再設定 (重要！)
                attachPaginationListeners();

            } else {
                console.error('Ajax Response Error (PHP reported failure):', jsonResponse.data);
                postsContainer.innerHTML = '<p class="mt-10 text-center text-sm text-gray-500">投稿の取得中にエラーが発生しました。</p>';
                if (paginationContainer) paginationContainer.innerHTML = '';
            }

        } catch (error) {
            console.error('Ajaxフィルタリング中にエラーが発生しました:', error);
            postsContainer.innerHTML = '<p class="mt-10 text-center text-sm text-gray-500">投稿の読み込みに失敗しました。</p>';
            if (paginationContainer) paginationContainer.innerHTML = '';
        } finally {
            postsContainer.style.opacity = '1'; // 半透明解除
            if (paginationContainer) {
                paginationContainer.style.opacity = '1';
            }
            console.log('Ajax fetch complete.');
        }
    };

    /**
     * ページネーションリンクにイベントリスナーを設定する関数
     * (Ajaxで取得した新しいリンクにも適用するため、コンテンツ更新後に毎回呼び出す)
     */
    const attachPaginationListeners = () => {
        if (!paginationContainer) return;

        const paginationLinks = paginationContainer.querySelectorAll('a.page-numbers');
        paginationLinks.forEach(link => {
            // イベントリスナーの重複登録を防ぐ
            if (link._paginationClickHandler) {
                link.removeEventListener('click', link._paginationClickHandler);
            }

            link._paginationClickHandler = function(e) {
                e.preventDefault(); // リンクのデフォルト動作をキャンセル

                const url = new URL(this.href);
                const paged = url.searchParams.get('paged') || 1; // URLからページ番号を取得

                // 現在アクティブなタグと月を取得
                const currentTag = document.querySelector(`[data-barba-namespace="${barbaNamespace}"] .tag-list .is-active`)?.dataset.tag || 'all';
                const currentMonth = monthSelect ? monthSelect.value : 'all';

                fetchAndRenderPosts(currentTag, currentMonth, paged); // Ajaxリクエストを送信
            };
            link.addEventListener('click', link._paginationClickHandler);
        });
    };

    // タグボタンのイベントリスナー設定
    filterButtons.forEach(button => {
        // イベントリスナーの重複登録を防ぐ
        if (button._filterClickHandler) {
            button.removeEventListener('click', button._filterClickHandler);
        }
        button._filterClickHandler = function(e) {
            e.preventDefault();
            const selectedTag = this.dataset.tag;

            // アクティブなタグボタンのスタイルを更新
            filterButtons.forEach(btn => btn.classList.remove('is-active'));
            this.classList.add('is-active');

            // 現在選択されている月を取得
            const currentMonth = monthSelect ? monthSelect.value : 'all';
            fetchAndRenderPosts(selectedTag, currentMonth, 1); // タグ変更時は1ページ目にリセット
        };
        button.addEventListener('click', button._filterClickHandler);
    });

    // 月選択のイベントリスナー設定 (Eventsページのみ)
  if (monthSelect) {
    console.log('Attempting to attach change listener to monthSelect.'); // ★この行を追加★
    // イベントリスナーの重複登録を防ぐ
    if (monthSelect._monthChangeHandler) {
      monthSelect.removeEventListener('change', monthSelect._monthChangeHandler);
      console.log('Removed existing monthChangeHandler.'); // ★この行を追加★
    }
    monthSelect._monthChangeHandler = function () {
      console.log('Month select change event fired!'); // ★この行を追加★
      const selectedMonth = this.value;
      // 現在選択されているタグを取得
      const currentTag = document.querySelector(`[data-barba-namespace="${barbaNamespace}"] .tag-list .is-active`)?.dataset.tag || 'all';
      fetchAndRenderPosts(currentTag, selectedMonth, 1); // 月変更時は1ページ目にリセット
      };
    monthSelect.addEventListener('change', monthSelect._monthChangeHandler);
    console.log('Successfully attached change listener to monthSelect.'); // ★この行を追加★
  }

    // 初期ロード時のフィルタリング実行
    // URLパラメータから初期選択状態を読み込む
    let initialTag = 'all';
    let initialMonth = 'all';
    let initialPaged = 1;

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('tag')) {
        initialTag = urlParams.get('tag');
        const initialTagButton = document.querySelector(`[data-barba-namespace="${barbaNamespace}"] .tag-list [data-tag="${initialTag}"]`);
        if (initialTagButton) {
            filterButtons.forEach(btn => btn.classList.remove('is-active'));
            initialTagButton.classList.add('is-active');
        }
    }
    // if (urlParams.has('month') && currentPagePostType === 'events') {
    //     initialMonth = urlParams.get('month');
    //     if (monthSelect) {
    //         monthSelect.value = initialMonth;
    //     }
    // }
    if (currentPagePostType === 'events') { // Eventsページの場合のみ月を考慮
      if (urlParams.has('month')) {
          initialMonth = urlParams.get('month');
          if (monthSelect) {
              monthSelect.value = initialMonth; // セレクトボックスの表示もURLパラメータに合わせる
          }
      } else {
          // URLに月パラメータがない場合、monthSelectの現在の値（PHPでselectedされた値）を初期値とする
          if (monthSelect && monthSelect.value !== 'all') { // monthSelect が存在し、かつその値が 'all' でなければ
              initialMonth = monthSelect.value;
              console.log('Using monthSelect.value for initialMonth:', initialMonth);
          }
      }
  }
    if (urlParams.has('paged')) {
        initialPaged = parseInt(urlParams.get('paged'), 10) || 1;
    }

    // 初期メッセージを表示し、その後 Ajax でコンテンツを読み込む
    postsContainer.innerHTML = '<p class="initial-loading-message text-sm leading-[1.4] px-3 lg:px-4 -pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9] text-center text-gray-500">イベントを読み込み中...</p>';
    if (paginationContainer) paginationContainer.innerHTML = ''; // ページネーションも初期化

    fetchAndRenderPosts(initialTag, initialMonth, initialPaged);

    console.log('initFilterScripts (Ajax version): 初期化完了！');
}
