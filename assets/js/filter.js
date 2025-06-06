// assets/js/filter.js

function initFilterScripts(forcedNamespace = null) {
  console.log('initFilterScripts: 初期化開始！ namespace:', forcedNamespace);

  // フィルタリング対象の要素を常に最新のDOMから取得
  const filterButtons = document.querySelectorAll('[data-filter-button]');
  const filterItems = document.querySelectorAll('[data-filter-item]');

  if (filterButtons.length === 0 || filterItems.length === 0) {
      console.warn('initFilterScripts: フィルタリングボタンまたはアイテムが見つかりませんでした。');
      return; // フィルタリング要素がない場合は処理を終了
  }

  // フィルタリング処理の関数
  const setFilter = (filter) => {
      console.log('setFilter: フィルタリング中 -', filter);

      filterItems.forEach(item => {
          const itemCategory = item.dataset.filterItem; // アイテムのカテゴリ

          if (filter === 'all' || itemCategory === filter) {
              // アイテムを表示
              item.classList.remove('hidden');
          } else {
              // アイテムを非表示
              item.classList.add('hidden');
          }
      });

      // アクティブなボタンのスタイルを更新
      filterButtons.forEach(button => {
          if (button.dataset.filterButton === filter) {
              button.classList.add('is-active'); // Tailwindのクラス名に応じて調整 (例: bg-red-500)
          } else {
              button.classList.remove('is-active'); // Tailwindのクラス名に応じて調整
          }
      });
  };

  // イベントリスナーの再登録（重複を防ぐ）
  filterButtons.forEach(button => {
      // 既存のイベントリスナーがあれば削除
      if (button._filterClickHandler) {
          button.removeEventListener('click', button._filterClickHandler);
      }
      // 新しいイベントリスナーを登録
      button._filterClickHandler = function() {
          const filter = this.dataset.filterButton;
          setFilter(filter);
      };
      button.addEventListener('click', button._filterClickHandler);
  });

  // 初期フィルタリング状態の設定
  // Barba.js 遷移後も、デフォルトで 'all' が選択されるようにする
  // ただし、もしURLパラメータなどで初期フィルタリングがあればそちらを優先
  let initialFilter = 'all';
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('category')) {
      initialFilter = urlParams.get('category');
  }
  setFilter(initialFilter); // 初期状態を適用

  console.log('initFilterScripts: 初期化完了！');
}

// DOMContentLoaded から直接呼び出すのは Barba.js を使わない場合のフォールバックとして残す
// Barba.js を使う場合は Barba.js の after フックから呼ばれる
// document.addEventListener('DOMContentLoaded', () => {
//     initFilterScripts();
// });

// resize イベントリスナーは Barba.js 環境では不要になるか、
// より複雑なロジックが必要になるため削除を検討
// window.addEventListener('resize', initFilterScripts); // ★削除またはコメントアウト★
