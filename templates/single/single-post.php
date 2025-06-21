<main data-barba="container" data-barba-namespace="news-single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:max-w-180">
            <a href="<?php bloginfo('url'); ?>/news"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                News
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>
        <article class="w-[90%] mx-auto xl:max-w-180 mt-5 xl:mt-10">
            <header class="space-y-2 xl:space-y-5">
                <?php get_template_part('templates/badge-new'); ?>
                <h1 class="text-lg xl:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>

            </header>
            <div class="my-5 xl:my-10 rounded-[10px] xl:rounded-[20px]">
            <?php the_post_thumbnail(); ?>
            </div>

            <div class="mt-4 xl:mt-10 single-content"><?php the_content(); ?></div>
            <?php get_template_part( 'templates/parts/single-footer' ); ?>
        </article>
        <aside class="w-rull mx-auto xl:max-w-180 mt-15 xl:mt-[150px]">
            <div class="flex justify-between px-[5%] xl:px-0">
                <div class="">
                    <?php
                    get_template_part('templates/heading/heading-with-brackets', null, [
                        'heading_text' => 'お知らせ',
                        'heading_tag'  => 'h2',
                    ]);
                ?>
                </div>
                <?php get_template_part('templates/common/link-button', null, [
                    'url' => home_url('/news'),
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php get_template_part('templates/parts/related-posts'); ?>
        </aside>
    </div>
</main>
