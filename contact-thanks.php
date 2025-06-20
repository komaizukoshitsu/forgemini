<?php
/*
Template Name: Contact-thanks Page
*/

get_header(); ?>

<div class="w-[90%] max-w-180 mx-auto mt-2 lg:mt-[65px]">
    <h1 class="text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">Contact</h1>

    <ul class="contact-step mt-7 lg:mt-[84px] flex justify-between items-center max-w-[640px] mx-auto">
        <li class="text-[#999]">​① 入力画面</li>
        <li><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
        <li class="text-[#999]">​​② 確認画面</li>
        <li><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
        <li>​③ 完了画面</li>
    </ul>
    <div class="mt-7 lg:mt-[60px] py-[80px] lg:px-[100px] border border-gray-300 rounded-md text-center text-xs lg:text-base">
        <div class="text-xs lg:text-base" style="line-height: 175%; letter-spacing: 0.8px;">お問い合わせが送信されました</div>
        <p class="mt-5 lg:mt-10 pt-5 lg:pt-10 contact-thanks-text relative" style="line-height: 175%; letter-spacing: 0.8px;">お問い合わせいただき、ありがとうございます。<br>
        内容を確認の上、改めてご返信いたします。</p>
        <div class="mt-5 lg:mt-[40px]">
            <a href="<?php bloginfo('url');?>" class="second-btn">
                <span>ホームへ戻る</span>
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
