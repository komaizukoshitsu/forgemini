// filter.js

console.log('filter.js ファイルが読み込まれました！');

let postsContainer; // グローバル変数として宣言
let tagLinks;       // グローバル変数として宣言
let monthSelect;    // グローバル変数として宣言

// Barba.jsのafterEnterフックから呼ばれる関数
function initFilterScripts(namespace) {
  console.log('initFilterScripts が実行されました！現在の namespace:', namespace);

  // Barba.js遷移後、常に新しいDOMから要素を再取得する
  // これが最も重要。古いDOMの参照を使い続けないようにする。
  postsContainer = document.querySelector('#post-list');
  tagLinks = document.querySelectorAll('.tag-link');
  monthSelect = document.querySelector('#month-filter'); // <select>要素も再取得

  if (!postsContainer || namespace !== 'events') {
      console.log('#post-list が見つからないか、Eventフィルターの対象ページではありません。初期化をスキップします。', {postsContainerExists: !!postsContainer, currentNamespace: namespace});
      return;
  }

  // --- イベントリスナーの再登録 ---
  // Barba.jsで新しいDOMがロードされるたびにイベントリスナーを再設定
  tagLinks.forEach(link => {
    // 古いリスナーが残るのを避けるため、可能であれば一度削除するロジックを入れるか、
    // Barba.jsの`once`フックや、イベント委譲を検討するのも良いですが、
    // とりあえず再登録で問題がなければこのまま進めます。
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const tag = link.dataset.tag;
      const selectedMonth = monthSelect ? monthSelect.value : 'all';

      // ここで最新のタグと月情報を取得し、fetchFilteredPostsを呼び出す
      fetchFilteredPosts(tag, selectedMonth, namespace);

      // アクティブクラスの切り替え
      tagLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
    });
  });

  if (monthSelect) {
    monthSelect.addEventListener('change', function () {
      const selectedMonth = this.value;
      const activeTag = document.querySelector('.tag-link.active'); // 現在アクティブなタグを再確認
      const currentTag = activeTag ? activeTag.dataset.tag : 'all';
      fetchFilteredPosts(currentTag, selectedMonth, namespace);
    });
  }

  // 初回フィルターの実行
  // ここでinitializeFilterOnLoadを呼び出すのが重要。
  // Barba.jsが新しいDOMを挿入した直後、つまりDOMContentLoaded相当のタイミングで実行される。
  initializeFilterOnLoad(namespace);
}

function fetchFilteredPosts(tag, month, postType) {
  console.log('fetchFilteredPosts が実行されました！', { tag, month, postType });

  // postsContainerが最新のDOMを参照していることを保証
  if (!postsContainer) {
    postsContainer = document.querySelector('#post-list'); // 念のため再取得
    if (!postsContainer) {
      console.error('fetchFilteredPosts: #post-list が見つかりません。');
      return;
    }
  }

  // ここで読み込みメッセージを挿入
  postsContainer.innerHTML = '<p class="initial-loading-message text-sm lg:text-base leading-[1.4] px-3 lg:px-4 -pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">イベントを読み込み中...</p>';

  const formData = new FormData();
  let actionName;
  if (postType === 'events') {
    actionName = 'filter_events_by_month_and_tag';
  } else {
    actionName = 'filter_posts_by_custom_type_and_taxonomy';
  }

  formData.append('action', actionName);
  formData.append('tag', tag);
  if (postType !== 'events') {
      formData.append('post_type', postType);
  }

  const formattedMonth = (month && month !== 'all') ? month.replace('-', '') : 'all';
  formData.append('month', formattedMonth);

  fetch(ajax_object.ajax_url, {
    method: 'POST',
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(data => {
      console.log('AJAXレスポンスデータ:', data);
      if (!data.trim()) {
          console.warn('AJAXレスポンスが空です。PHP側でイベントが取得できていないか、出力されていません。');
          postsContainer.innerHTML = '<p class="no-results">イベントが見つかりませんでした。</p>';
          // AJAX結果が空の場合でもフィルター初期化を試みる
          initializeEventFilters();
          return;
      }

      // DOMを更新
      postsContainer.innerHTML = data;
      console.log('--- postsContainer.innerHTML にデータが挿入されました！ ---');
      console.log('postsContainer の内容:', postsContainer.innerHTML);

      // DOM更新後に initializeEventFilters を実行
      // この時点ではもうsetTimeoutは不要。Barba.jsがDOMを完全に置き換えた後、
      // この関数が呼ばれているはずなので、同期的に処理して問題ないはず。
      if (typeof initializeEventFilters === 'function' && postType === 'events') {
        initializeEventFilters();
      }

    })
    .catch(err => {
      console.error('Error fetching posts:', err);
      postsContainer.innerHTML = '<p class="error-message">イベントの読み込み中にエラーが発生しました。</p>';
      initializeEventFilters(); // エラー時もフィルター初期化を試みる
    });
}

function initializeEventFilters() {
  console.log('initializeEventFilters が実行されました！');

  // ここで `postsContainer` を再取得するのではなく、グローバル変数 `postsContainer` を使用
  // `postsContainer`は`initFilterScripts`で最新のDOMから取得されているはず
  // もし`postsContainer`がnullになる場合は、`initFilterScripts`の呼び出しやHTML構造を再確認
  if (!postsContainer) {
    console.error('initializeEventFilters: postsContainerがnullです。');
    return;
  }

  const posts = postsContainer.querySelectorAll('.event-item'); // ★#post-list を使わず、postsContainerオブジェクトから探索★

  console.log('initializeEventFilters: #post-list の存在:', !!postsContainer);
  console.log('initializeEventFilters: #post-list の innerHTML:', postsContainer.innerHTML);

  console.log('initializeEventFilters: #month-filter の存在:', !!monthSelect);
  console.log('initializeEventFilters: 取得された投稿数 (posts.length):', posts.length);
  console.log('initializeEventFilters: posts:', posts);

  if (!monthSelect || posts.length === 0) {
      console.warn('initializeEventFilters: 必要な要素が見つからないか、投稿がありません。', {monthSelectElementExists: !!monthSelect, postsLength: posts.length});
      return;
  }

  const selectedMonth = monthSelect.value;
  const activeTag = document.querySelector('.tag-link.active');
  const selectedTag = activeTag ? activeTag.dataset.tag : 'all';

  console.log('initializeEventFilters: selectedMonth:', selectedMonth, 'selectedTag:', selectedTag);

  posts.forEach(post => {
    const months = post.dataset.months ? post.dataset.months.split(',') : [];
    const postTags = post.dataset.tags ? post.dataset.tags.split(',') : [];

    const monthMatches = (selectedMonth === 'all' || months.includes(selectedMonth));
    const tagMatches = (selectedTag === 'all' || postTags.includes(selectedTag));

    console.log(`Event Item ID/Title: ${post.querySelector('.schedule-item-title')?.textContent.trim() || 'N/A'}`);
    console.log(`  post.dataset.months: ${post.dataset.months}, months: ${months}`);
    console.log(`  post.dataset.tags: ${post.dataset.tags}, tags: ${postTags}`);
    console.log(`  monthMatches: ${monthMatches}, tagMatches: ${tagMatches}`);
    console.log(`  Overall display: ${monthMatches && tagMatches ? 'SHOW' : 'HIDE'}`);

    if (monthMatches && tagMatches) {
      post.style.display = ''; // または 'block' / 'flex' など、元の display 状態に戻す
    } else {
      post.style.display = 'none';
    }
  });
}

function initializeFilterOnLoad(namespace) {
    console.log('initializeFilterOnLoad が実行されました！');
    // postsContainer, monthSelect, tagLinks は initFilterScripts で既に最新のものを取得済み
    if (!monthSelect || !postsContainer) {
        console.warn('initializeFilterOnLoad: 必要な要素が見つからないためスキップします。');
        return;
    }

    let initialSelectedMonth = monthSelect.value;
    let initialSelectedTag = 'all';

    // 初期状態のタグを 'all' に設定
    tagLinks.forEach(link => {
        if (link.dataset.tag === 'all') {
            link.classList.add('active');
            initialSelectedTag = 'all';
        } else {
            link.classList.remove('active');
        }
    });

    fetchFilteredPosts(initialSelectedTag, initialSelectedMonth, namespace);
}
