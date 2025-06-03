console.log('filter.js ファイルが読み込まれました！');

let postsContainer; // グローバル変数として宣言
let tagLinks;       // グローバル変数として宣言
let monthSelect;    // グローバル変数として宣言 (eventsページのみ使用)

// Barba.jsのafterEnterフックから呼ばれる関数
function initFilterScripts(barbaNamespace) { // 引数名をbarbaNamespaceに変更
  console.log('initFilterScripts が実行されました！現在の Barba Namespace:', barbaNamespace);

  // Barba.jsの名前空間からカスタム投稿タイプ名を抽出する
  // 例: 'events-archive' -> 'events', 'works-archive' -> 'works', 'goods-archive' -> 'goods'
  let currentPagePostType = barbaNamespace.replace('-archive', '');

  // もし -archive が含まれていなければ、そのままの名前空間を使用
  // 例: 'home' や 'news-page' など
  if (currentPagePostType === barbaNamespace) {
      currentPagePostType = barbaNamespace;
  }

  // 特定の例外処理（例: 'page-news'のような固定ページ用名前空間を 'news' として扱いたい場合）
  if (currentPagePostType === 'news-page') { // header.phpでnews-pageと設定した場合
      currentPagePostType = 'news';
  }


  console.log('Detected currentPagePostType:', currentPagePostType);

  // Barba.js遷移後、常に新しいDOMから要素を再取得する
  postsContainer = document.querySelector('#post-list');
  tagLinks = document.querySelectorAll('.tag-link');
  // eventsページの場合のみmonthSelectを取得
  monthSelect = (currentPagePostType === 'events') ? document.querySelector('#month-filter') : null;

  // #post-list が見つからない場合は処理をスキップ
  if (!postsContainer) {
      console.log('#post-list が見つかりません。初期化をスキップします。');
      return;
  }

  // フィルターの対象となる投稿タイプを明示的に指定
  // ここには header.php で設定した barbaNamespace の接頭辞（例: 'events', 'works', 'goods'）を使用する
  const filterTargets = ['events', 'works', 'goods', 'news']; // 必要に応じて 'news' なども追加
  if (!filterTargets.includes(currentPagePostType)) {
      console.log('現在のページはフィルターの対象ページではありません。初期化をスキップします。', {currentPagePostType: currentPagePostType});
      return;
  }

  // --- イベントリスナーの再登録 ---
  tagLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const tag = link.dataset.tag;
      // eventsページの場合のみmonthSelectの値を考慮
      const selectedMonth = (currentPagePostType === 'events' && monthSelect) ? monthSelect.value : 'all';

      // ここで最新のタグと月情報（必要な場合）を取得し、fetchFilteredPostsを呼び出す
      fetchFilteredPosts(tag, selectedMonth, currentPagePostType);

      // アクティブクラスの切り替え
      tagLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
    });
  });

  // eventsページの場合のみ月のフィルターにイベントリスナーを設定
  if (currentPagePostType === 'events' && monthSelect) {
    monthSelect.addEventListener('change', function () {
      const selectedMonth = this.value;
      const activeTag = document.querySelector('.tag-link.active'); // 現在アクティブなタグを再確認
      const currentTag = activeTag ? activeTag.dataset.tag : 'all';
      fetchFilteredPosts(currentTag, selectedMonth, currentPagePostType);
    });
  }

  // 初回フィルターの実行
  initializeFilterOnLoad(currentPagePostType);
}

// fetchFilteredPosts, applyClientSideFiltering, initializeFilterOnLoad 関数は、
// 以前提示した最新のfilter.jsの内容をそのまま使用してください。
// これらの関数は currentPagePostType を引数として受け取っているので、
// 上記の initFilterScripts の修正で問題なく連携します。
