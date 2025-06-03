<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>てらおか なつみ</title>
  <meta name="description" content="イラストレーター てらおか なつみ">

  <?php wp_head(); ?>

  <style>
        /* ページ全体を最初に非表示にする */
        html {
            visibility: hidden;
        }
    </style>

    <script>
      // ページの読み込みが完了したらHTMLの表示を切り替える
      document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.visibility = 'visible';
      });
    </script>

</head>
<?php
// Barba.js の名前空間を動的に決定するロジック
$barba_namespace = 'default'; // デフォルト値

// 現在のページの投稿タイプを取得
$current_post_type = get_query_var('post_type');

// カスタム投稿タイプアーカイブページの場合
if (is_post_type_archive() && !empty($current_post_type)) {
    $barba_namespace = $current_post_type . '-archive'; // 例: 'works-archive', 'goods-archive', 'events-archive'
}
// フロントページの場合
elseif (is_front_page()) {
    $barba_namespace = 'home';
}
// 特定の固定ページ (例: page-news.php が 'news' というスラッグの固定ページの場合)
// もし 'page-news' がカスタム投稿タイプ 'news' のアーカイブであれば、上記の is_post_type_archive() で処理されます。
// ここでは、特定の固定ページのスラッグで名前空間を制御したい場合を想定。
elseif (is_page('news')) { // 'news' というスラッグの固定ページの場合
    $barba_namespace = 'news-page'; // または 'news-archive' など、一貫性のある名前にする
}
// それ以外の単一投稿ページや一般的な固定ページなど
else {
    global $post;
    if ($post && $post->post_type) {
        $barba_namespace = $post->post_type; // 例: 'post' (通常投稿), 'page' (固定ページ)
    } else {
        // 何らかの理由で投稿タイプが取得できない場合のフォールバック
        // 例えば、404ページなど
        $barba_namespace = 'subpage';
    }
}
?>
<body <?php body_class(''); ?>
    style="font-family: 'Noto Sans JP', sans-serif;"
    data-page="<?= esc_attr($barba_namespace); ?>"
    data-barba="wrapper"
    data-barba-namespace="<?= esc_attr($barba_namespace); ?>"
    data-post-type="<?php echo esc_attr(get_post_type()); ?>"
    aria-live="polite">
  <?php wp_body_open(); ?>

  <!-- <?php if ( is_front_page() ) : ?>
    <div id="loader-white" class="fixed inset-0 bg-white z-[999] animate-fadeOutWhite"></div>
    <div class="fixed inset-0 flex items-center justify-center z-[1001] opacity-0 fade-in-out-custom">
      <img class="w-[20vw] h-auto md:w-40 md:h-[183px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/icon-teraokanatsumi.svg" alt="てらおかなつみアイコン">
    </div>

    <div id="curtain" class="fixed inset-0 z-[1000] flex flex-col justify-end pointer-events-none">
      <div id="curtain-inner" class="w-full bg-[#FFFAD1] translate-y-full" style="height: 100vh;"></div>
    </div>
  <?php endif; ?> -->

  <?php if (wp_is_mobile()) : ?>
    <div class="lg:hidden flex justify-center items-start mt-5">
      <?php if (is_front_page()) : ?>
        <h1 class="block w-auto h-6">
          <a href="<?php bloginfo('url'); ?>" class="block w-auto h-full">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおかなつみロゴ" loading="lazy" class="h-full w-auto">
          </a>
        </h1>
      <?php else : ?>
        <div class="block w-auto h-6">
          <a href="<?php bloginfo('url'); ?>" class="block w-auto h-full">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみ" loading="lazy" class="h-full w-auto">
          </a>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
