<?php
/*
Template Name: About Page
*/

get_header(); ?>
<main data-barba="container" data-barba-namespace="page">
    <div class="w-[90%] mx-auto xl:max-w-180 mt-16">
        <article class="">
            <header class="">
                <h1 class="text-center xl:text-left text-[32px] xl:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">About</h1>
            </header>
            <div class="youtube mt-7 xl:mt-16 w-full xl:max-w-180 aspect-[16/9]">
                <?php the_field('about-page-youtube'); ?>
            </div>
            <div class="mt-10 xl:mt-25 xl:flex">
                <div class="xl:w-[290px]">
                    <?php
                        get_template_part('templates/heading/heading-with-brackets', null, [
                            'heading_text' => 'プロフィール',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="xl:w-[calc(100%-290px)] mt-5 xl:mt-0">
                    <div class="text-base xl:text-xl font-medium" style="letter-spacing:0.1em;">てらおかなつみ</div>
                    <div class="mt-1 xl:mt-2 text-xs xl:text-sm" style="letter-spacing:0.1em;">Teraoka Natsumi</div>
                    <div class="mt-5 xl:mt-7 w-[30px] xl:w-10 h-[1px] bg-[#333]"></div>
                    <p class="mt-5 text-sm xl:text-base" style="line-height:1.75;">
                        <?php the_field('about-page-profile-text'); ?>
                    </p>
                    <?php
                    get_template_part('templates/common/sns-links', null, [
                    'bg_base' => 'bg-white',
                    'hover_bg' => 'hover:bg-[#F5F5F5]',
                    'class' => 'is-about'
                    ]);
                    ?>
                </div>
            </div>

            <?php
            get_template_part('templates/parts/accordion-list-section', null, [
                'heading_text'          => '過去の主なお仕事',
                'post_type'             => 'works',
                'meta_key'              => 'work_show_on_about',
                'taxonomy'              => 'works_year',
                'view_all_url'          => home_url('/works'),
                'empty_message'         => '仕事経歴はありません。',
                'accordion_group_class' => 'accordion-group-1',
            ]);
            ?>

            <?php
            get_template_part('templates/parts/accordion-list-section', null, [
                'heading_text'          => '過去の主なイベント',
                'post_type'             => 'events',
                'meta_key'              => 'event_show_on_about',
                'taxonomy'              => 'event_year', // あなたのイベント年タクソノミー名
                'view_all_url'          => home_url('/events'),
                'empty_message'         => 'イベント経歴はありません。',
                'accordion_group_class' => 'accordion-group-2',
            ]);
            ?>
        </article>
    </div>
</main>

<?php get_footer(); ?>
