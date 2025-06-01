<?php
/*
Template Name: Contact Page
*/

get_header(); ?>
<main data-barba="container" data-barba-namespace="page">
    <div class="mt-16">
        <div class="w-[90%] xl:max-w-180 mx-auto">
            <header class="">
                <h1 class="text-center lg:text-left text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">Contact</h1>
            </header>
            <ul class="contact-step mt-7 lg:mt-21 flex justify-between items-center max-w-160 mx-auto font-medium">
                <li class="text-sm lg:text-base">​① 入力画面</li>
                <li class="text-sm lg:text-base"><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
                <li class="text-sm lg:text-base text-[#999]">​​② 確認画面</li>
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-arrow.svg" alt=""></li>
                <li class="text-sm lg:text-base text-[#999]">​③ 完了画面</li>
            </ul>
            <div class="mt-7 lg:mt-15">
                <h2 class="text-base font-medium tracking-[0.1em flex items-center">(&ensp;​お問い合わせ項目<span class="hidden lg:inline-block">をお選びください</span><span class="must">必須</span>&ensp;)</h2>
            </div>
            <div class="mt-5 lg:mt-10">
                <?php echo do_shortcode('[contact-form-7 id="74a7d27" title="お問い合わせ"]'); ?>
            </div>
            <div class="mx-auto mt-10 lg:mt-30 w-full max-w-153">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/image/contact-page.webp" alt="人の膝に座っている犬のイラスト" loading="lazy" width="100%" height="100%">
            </div>
        </div>
    </div>
</mian>

<?php get_footer(); ?>
