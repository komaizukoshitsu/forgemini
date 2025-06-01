<main data-barba="container" data-barba-namespace="single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:max-w-180">
            <a href="<?php bloginfo('url'); ?>/news"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                News
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>
        <article class="w-[90%] mx-auto xl:max-w-180 mt-5 lg:mt-10">
            <header class="space-y-2 lg:space-y-5">
                <?php get_template_part('templates/badge-new'); ?>
                <h1 class="text-lg lg:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>
                <div class="flex text-sm lg:text-base leading-[1.3]">
                    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
                        <?php echo get_the_date(); ?>
                        <?php
                        // 記事の公開日と更新日を取得
                        $modified_date = get_the_modified_date('Y-m-d');
                        $published_date = get_the_date('Y-m-d');

                        // 更新日が公開日と異なる場合に「更新マーク」を表示
                        if ( $modified_date !== $published_date ) {
                            // 現在のテーマディレクトリへのURLを取得して画像パスを修正
                            echo '<span class="updated-mark w-4 inline-block">
                                    <img class="w-full h-full object-contain" src="' . get_template_directory_uri() . '/assets/image/update-icon.svg" alt="更新">
                                </span>';
                        }
                        ?>
                    </div>
                    <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9]">
                    <?php
                        $tags = get_the_tags();
                        foreach ( $tags as $tag ) {
                            echo '<span>' . $tag->name . '</span>';
                        }
                    ?>
                    </div>
                </div>
            </header>
            <div class="my-5 lg:my-10 rounded-[10px] lg:rounded-[20px]">
            <?php the_post_thumbnail(); ?>
            </div>

            <div class="mt-4 lg:mt-10 single-content"><?php the_content(); ?></div>
            <?php get_template_part( 'templates/single-footer' ); ?>
        </article>
        <aside class="w-rull mx-auto xl:max-w-180 mt-15 lg:mt-[150px]">
            <div class="flex justify-between px-[5%] lg:px-0">
                <!-- <div class="font-medium tracking-[0.1em]">（　お知らせ　）</div> -->
                <div class="">
                    <?php
                    get_template_part('templates/heading-with-brackets', null, [
                        'heading_text' => 'お知らせ',
                        'heading_tag'  => 'h2',
                    ]);
                ?>
                </div>
                <?php get_template_part('templates/link-button', null, [
                    'url' => home_url('/news'),
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php get_template_part('templates/related-posts'); ?>
        </aside>
    </div>
</main>
