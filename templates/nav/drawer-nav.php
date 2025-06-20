<?php
/**
 * トップページのドロワーナビ（開閉付き）
 * or その他ページで常時表示（トグルなし）
 */
$is_home = is_front_page();
?>

<div class="home-drawer-nav fixed">
  <div class="xl:hidden pb-7 flex justify-center">
    <a href="<?php bloginfo('url'); ?>">
      <img class="xl:hidden w-auto h-auto max-w-[200px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみさん">
    </a>
</div>
  <nav class="header-nav">
    <ul class="tracking-[0.15em] italic font-garamond grid grid-cols-2 xl:grid-cols-1 space-x-4 xl:space-x-0 space-y-0">
      <?php
        get_template_part('templates/nav/menu-items', null, [
          'list_item_class_from_drawer' => 'header-nav-item border-t border-[#D9D9D9] xl:border-none',
          'link_class_from_drawer' => 'py-[6px] inline-block text-[20px] transition-all duration-150 ease-linear',
      ]);
      ?>
    </ul>
  </nav>
  <?php get_template_part('templates/common/sns-links'); ?>
</div>
