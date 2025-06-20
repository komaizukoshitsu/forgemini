<?php
/*
Template Name: Contact-confirm Page
*/

get_header(); ?>
<main data-barba="container" data-barba-namespace="page">
    <div class="mt-16">
        <div class="w-[90%] xl:max-w-180 mx-auto">
            <header class="">
                <h1 class="text-center lg:text-left text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">Contact</h1>
            </header>
            <ul class="contact-step mt-7 lg:mt-21 flex justify-between items-center xl:max-w-160 mx-auto">
                <li class="text-sm lg:text-base text-[#999]">​① 入力画面</li>
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
                <li class="text-sm lg:text-base">​​② 確認画面</li>
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
                <li class="text-sm lg:text-base text-[#999]">​③ 完了画面</li>
            </ul>
            <div class="contact-form-wrapper px-5 lg:px-25 pt-7 lg:py-20 pb-9 mt-5 lg:mt-15 border border-gray-300 rounded-md">
                <?php echo do_shortcode('[contact-form-7 id="d2cd303" title="確認画面用フォーム"]'); ?>
            </div>
        </div>
    </div>
    <!-- <div class="w-[90%] max-w-180 mx-auto mt-2 lg:mt-[65px]">
        <h1 class="text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">Contact</h1>
    </div> -->
</main>

<?php get_footer(); ?>
