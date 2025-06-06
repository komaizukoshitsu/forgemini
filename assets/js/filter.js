// assets/js/filter.js
console.log('filter.js ファイルが読み込まれました！');

let currentTaxonomy = ''; // 現在選択されているタクソノミー
let currentMonth = ''; // 現在選択されている月 (events_category 用)
let currentPostType = ''; // 現在の投稿タイプ

/**
* フィルターロジックを初期化する関数
* @param {string} pageType - 現在のページの種類 (例: 'events', 'works', 'goods')
*/
function initFilterScripts(pageType) {
  console.log(`initFilterScripts: ${pageType} ページ用にフィルターを初期化します。`);

  const filterArea = document.querySelector(`.${pageType}-filter-area`);
  const filterButtons = filterArea ? filterArea.querySelectorAll('.tag-link') : [];
  const postsList = document.querySelector(`.${pageType}-list`);
  const monthSelect = document.getElementById(`${pageType}-month-select`); // 月選択は events のみ

  // ★この行を追加してください！★
  console.log(`initFilterScripts: 検索ID: ${pageType}-month-select`);
  console.log('initFilterScripts: monthSelect 要素:', monthSelect);

  if (!filterArea || !postsList) {
    console.log(`initFilterScripts: ${pageType}-filter-area または ${pageType}-list が見つかりません。`);
    return;
  }

  // ここには header.php で設定した barbaNamespace の接頭辞（例: 'events', 'works', 'goods'）を使用する
  const filterTargets = ['events-archive', 'works-archive', 'goods-archive', 'news-page']; // ここを修正！
  // 注意：'news-page' はBarba.jsのnamespaceとして、'news' はAJAXリクエストのpost_typeとして扱われます。
  // もし固定ページで 'news' フィルターを使うなら、'news-page' も必要です。
  if (!filterTargets.includes(pageType)) {
      console.warn(`initFilterScripts: 不明なページタイプ '${pageType}' です。フィルターを初期化できません。`);
      return;
  }

  // ページタイプに応じてタクソノミーと投稿タイプを設定
  let taxonomyName = '';
  let postTypeName = '';

  if (pageType === 'events-archive') {
  taxonomyName = 'events_category';
  postTypeName = 'events';
  } else if (pageType === 'works-archive') {
  taxonomyName = 'works_category';
  postTypeName = 'works';
  } else if (pageType === 'goods-archive') {
  taxonomyName = 'goods_category';
  postTypeName = 'goods';
  } else {
  console.warn(`initFilterScripts: 不明なページタイプ '${pageType}' です。フィルターを初期化できません。`);
  return;
  }

  // 初期状態の設定
  const urlParams = new URLSearchParams(window.location.search);
  currentTaxonomy = urlParams.get('tag') || '';

  // ★ここを修正！月フィルターの初期値設定★
    // URLにmonthパラメータがあればそれを使用
    // なければHTMLのselect要素の初期selected値 (PHPで設定済み) を使用
    if (urlParams.has('month')) {
      currentMonth = urlParams.get('month');
  } else if (monthSelect && monthSelect.value !== 'all') { // 月セレクト要素が存在し、かつ「全て」でなければその値を使う
      currentMonth = monthSelect.value;
  } else {
      currentMonth = 'all'; // どれにも該当しない場合は「全て」に設定
  }
  console.log(`initFilterScripts: 初期月設定 -> ${currentMonth}`);

  // タグボタンのイベントリスナー設定
  filterButtons.forEach(button => {
  button.removeEventListener('click', handleFilterButtonClick); // 既存のリスナーを削除して重複を防ぐ
  button.addEventListener('click', handleFilterButtonClick);
  });

  function handleFilterButtonClick(event) {
    event.preventDefault(); // デフォルトの挙動（#への遷移）をキャンセル

    const clickedButton = event.currentTarget;
    console.log('clickedButton:', clickedButton); // クリックされた要素を確認

    const newTagSlug = clickedButton.dataset.tag;
    console.log('newTagSlug (from data-tag):', newTagSlug); // data-tag から取得した値を確認

    // 全てのボタンから 'is-active' クラスを削除
    filterButtons.forEach(btn => btn.classList.remove('is-active'));
    // クリックされたボタンに 'is-active' クラスを追加
    clickedButton.classList.add('is-active');

    currentTaxonomy = newTagSlug;
    console.log(`initFilterScripts: タグ変更 -> ${currentTaxonomy}`); // currentTaxonomy の値を確認

    fetchPosts();
  }

  // 月選択のイベントリスナー設定 (events_category のみ)
  if (pageType === 'events-archive' && monthSelect) {
    monthSelect.removeEventListener('change', handleMonthChange); // 既存のリスナーを削除して重複を防ぐ
    monthSelect.addEventListener('change', handleMonthChange);

    function handleMonthChange(event) {
      currentMonth = event.target.value;
      console.log(`initFilterScripts: 月変更 -> ${currentMonth}`);
      fetchPosts();
    }
  }

  // 投稿を取得して表示する関数
  function fetchPosts() {
  // ローディングアニメーションなどを表示
    postsList.classList.add('is-loading'); // 例: ローディングクラスを追加

    const data = {
      action: 'filter_posts_by_custom_type_and_taxonomy',
      post_type: postTypeName, // 動的に設定
      taxonomy: taxonomyName, // 動的に設定
      tag: currentTaxonomy,
      month: currentMonth, // events_category のみ有効
      paged: 1
    };

    console.log('initFilterScripts: AJAXリクエストデータ:', data);

    fetch(ajax_object.ajax_url, {
      method: 'POST',
      headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams(data).toString(),
    })
    .then(response => {
      // HTTPステータスが200番台以外の場合、エラーをスローする
      if (!response.ok) {
          // response.statusText は HTTPステータスコードのテキスト表現（例: "Bad Request"）
          // response.text() はレスポンスボディをテキストとして取得
          return response.text().then(text => { throw new Error(`HTTP error! status: ${response.status}, message: ${text}`); });
      }
      return response.text();
    })
    .then(html => {
      postsList.innerHTML = html;
      postsList.classList.remove('is-loading'); // ローディングクラスを削除
      console.log('initFilterScripts: 投稿が正常に読み込まれました。');

      // URLを更新
      const newUrl = new URL(window.location.href);
      if (currentTaxonomy) {
          newUrl.searchParams.set('tag', currentTaxonomy);
      } else {
          newUrl.searchParams.delete('tag');
      }
      if (currentMonth) {
          newUrl.searchParams.set('month', currentMonth);
      } else {
          newUrl.searchParams.delete('month');
      }
      window.history.replaceState({ path: newUrl.href }, '', newUrl.href);

      // 初期ロード時のアクティブクラス設定
      console.log('fetchPosts: アクティブクラス設定を開始します。');
      console.log('fetchPosts: currentTaxonomy は:', currentTaxonomy);

      filterButtons.forEach(btn => {
          btn.classList.remove('is-active');
          console.log(`fetchPosts: ボタンの data-tag: ${btn.dataset.tag}`);

          if (btn.dataset.tag === currentTaxonomy) {
            btn.classList.add('is-active');
            console.log(`fetchPosts: ${btn.dataset.tag} に is-active を追加しました。`);
          } else if (!currentTaxonomy && btn.dataset.tag === 'all') {
            btn.classList.add('is-active');
            console.log(`fetchPosts: 「全て」ボタンに is-active を追加しました。`);
          }
      });
      // ★月セレクトの初期値設定（HTMLに設定されているものを上書きしないように調整）★
      // ただし、URLパラメータで月が指定された場合は、JavaScriptがその値に設定し直す。
      // URLパラメータがない場合は、HTMLのselected状態を尊重する。
      if (monthSelect) {
        if (urlParams.has('month')) { // URLにmonthパラメータがあればそれを設定
            monthSelect.value = urlParams.get('month');
        } else { // なければ、HTMLで設定されたデフォルト値を維持
            // 何もしない
        }
      }

      // Barba.js のビューが更新された後に、スクロール位置を調整する場合
      // barba.hooks.after(() => { window.scrollTo(0, 0); }); のようなロジックが他にあれば
    })
    .catch(error => {
      console.error('initFilterScripts: 投稿の取得中にエラーが発生しました:', error);
      postsList.classList.remove('is-loading');
    });
  }

  // ページの初期ロード時に全投稿を表示
  // Barba.js の遷移後にもこれが呼び出されるようにする
  // 現在選択されているタクソノミー（全投稿表示）がデフォルトのままになるように
  // 最初のフィルタリングを実行して表示を更新
  fetchPosts();
}
