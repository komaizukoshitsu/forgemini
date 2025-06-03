<?php
/**
 * トップページのドロワーナビ（開閉付き）
 * or その他ページで常時表示（トグルなし）
 */
$is_home = is_front_page();
?>

<div class="home-drawer-nav fixed px-5 lg:px-0 pt-[50px] lg:pt-0 pb-5 lg:pb-0 bottom-0 lg:bottom-30 left-5 lg:left-20 right-[18px] lg:right-0 transition-opacity duration-200 ease-in-out z-[100] w-[90%] lg:w-[190px]">

  <div class="lg:hidden pb-7 flex justify-center">
    <a href="<?php bloginfo('url'); ?>">
      <img class="lg:hidden w-auto h-auto max-w-[200px]" src="<?php echo get_template_directory_uri(); ?>/assets/image/logo.svg" alt="てらおか なつみさん">
    </a>
</div>
  <nav class="header-nav">
    <ul class="tracking-[0.15em] italic font-garamond grid grid-cols-2 lg:grid-cols-1 gap-x-4 lg:gap-x-0 gap-y-2 lg:gap-y-0">
      <?php
        // $list_item_class = 'header-nav-item border-t border-[#D9D9D9] lg:border-none';
        // $link_class = 'inline-block text-[20px] transition-all duration-150 ease-linear';
        // get_template_part('templates/nav/menu-items');
        get_template_part('templates/nav/menu-items', null, [
          'list_item_class_from_drawer' => 'header-nav-item border-t border-[#D9D9D9] lg:border-none',
          'link_class_from_drawer' => 'inline-block text-[20px] transition-all duration-150 ease-linear',
      ]);
      ?>
    </ul>
  </nav>
  <?php get_template_part('templates/common/sns-links'); ?>
</div>
