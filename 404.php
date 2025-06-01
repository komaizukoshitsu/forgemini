<?php get_header(); ?>

<div class="flex flex-col justify-center items-center px-5" style="height: calc(100svh - 60px);">
    <div class="w-[104px] lg:w-[162px]">
        <img class="w-[100%]" src="<?php echo get_template_directory_uri(); ?>/assets/image/404.webp" alt="犬のイラスト" loading="lazy" width="100%" height="100%">
    </div>
    <div class="mt-10 lg:mt-[80px] font-medium text-[24px] lg:text-[40px]">404 NOT FOUND</div>
    <p class="mt-3 lg:mt-5 text-xs lg:text-base">お探しのページは見つかりませんでした。</p>
    <div class="mt-8 lg:mt-[60px] max-w-[250px] lg:max-w-[350px] w-[100%]">
        <a href="<?php bloginfo('url');?>" class="top-contact flex justify-center mx-auto h-[40px] lg:h-[52px] items-center rounded-[26px] border border-solid border-[#D9D9D9] transition-all duration-300 ease-linear">
            <div class="link" style="font-family: 'Noto Sans JP', serif; font-style: normal;">ホームへ戻る</div>
        </a>
    </div>
</div>

<?php get_footer(); ?>