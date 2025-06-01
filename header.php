<!DOCTYPE html>
<html <?php language_attributes(); ?> data-barba-namespace="<?php echo (is_front_page() || is_home()) ? 'home' : get_post_type(); ?>">
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
  // コメントアウトされたPHPコードはそのままにしておきます
?>
<body <?php body_class(is_front_page() ? 'no-scroll' : ''); ?> style="font-family: 'Noto Sans JP', sans-serif;" data-page="<?= is_front_page() ? 'home' : 'subpage' ?>" data-barba="wrapper" data-post-type="<?php echo esc_attr(get_post_type()); ?>" aria-live="polite">
  <?php wp_body_open(); ?>

  <?php if ( is_front_page() ) : ?>
    <div id="loader-white" class="fixed inset-0 bg-white z-[999] animate-fadeOutWhite"></div>
    <div class="fixed inset-0 flex items-center justify-center z-[1001] opacity-0 fade-in-out-custom">
      <img class="w-[20vw] h-auto md:w-40 md:h-[183px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/icon-teraokanatsumi.svg" alt="てらおかなつみアイコン">
    </div>

    <div id="curtain" class="fixed inset-0 z-[1000] flex flex-col justify-end pointer-events-none">
      <div id="curtain-inner" class="w-full bg-[#FFFAD1] translate-y-full" style="height: 100vh;"></div>
    </div>
  <?php endif; ?>

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
