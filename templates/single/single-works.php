<main data-barba="container" data-barba-namespace="works-single">
    <div class="mt-17">
        <div class="w-[90%] mx-auto xl:max-w-180">
            <a href="<?php bloginfo('url'); ?>/works"
            class="relative pl-[29px] text-[24px] italic tracking-[0.15em] font-garamond group">
                Works
                <span class="absolute left-[5px] top-1/2 w-3 h-[10px] -translate-y-1/2 rotate-180 bg-[url('../image/view-more-icon.svg')] bg-center bg-no-repeat bg-contain transition-all duration-[250ms] ease-in-out group-hover:left-0"></span>
            </a>
        </div>

        <article class="w-[90%] mx-auto xl:max-w-180 mt-5 xl:mt-10">
            <header class="space-y-2 xl:space-y-5">
                <?php get_template_part('templates/badge-new'); ?>
                <h1 class="text-lg xl:text-2xl font-medium tracking-[0.05em] leading-[1.4]"><?php the_title(); ?></h1>
                <div class="flex text-xs xl:text-base leading-[1.3]">
                    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
                        <?php
                        // ★修正点2: works_year タクソノミーから年を取得
                        $years = get_the_terms(get_the_ID(), 'works_year');
                        if ($years && !is_wp_error($years)) {
                            echo esc_html($years[0]->name); // 最初の年を表示
                        } else {
                            echo '年なし'; // デフォルト表示
                        }
                        ?>
                    </div>
                    <div class="ml-2 xl:ml-3 pl-2 xl:pl-3 border-l border-[#D9D9D9]">
                    <?php
                    // カスタムタクソノミー 'works_category' のターム（カテゴリー）を取得
                    $categories = get_the_terms(get_the_ID(), 'works_category');

                    // カテゴリーが存在し、かつWordPressのエラーでない場合
                    if ($categories && !is_wp_error($categories)) {
                        $output = [];
                        foreach ($categories as $category) {
                            // カテゴリー名をHTMLエスケープして配列に追加
                            $output[] = '<span>' . esc_html($category->name) . '</span>';
                        }
                        // 複数のカテゴリがある場合はカンマとスペースで区切って表示
                        echo implode(', ', $output);
                    } else {
                        // カテゴリーが存在しない場合、または取得に失敗した場合
                        echo '<span>カテゴリーなし</span>';
                    }
                    ?>
                    </div>
                </div>
            </header>
            <div class="my-5 xl:my-10 rounded-[10px] xl:rounded-[20px]">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="single-content"><?php the_content(); ?></div>
            <?php get_template_part( 'templates/parts/single-footer' ); ?>
        </article>
        <aside class="w-full mx-auto xl:max-w-180 mt-15 xl:mt-[150px]">
            <div class="flex justify-between px-[5%] xl:px-0">
                <?php
                get_template_part('templates/heading/heading-with-brackets', null, [
                    'heading_text' => 'お仕事',
                    'heading_tag'  => 'h2',
                ]);
                ?>
                <?php get_template_part('templates/common/link-button', null, [
                    'url' => home_url('/works'),
                    'label' => 'View All'
                ]); ?>
            </div>
            <?php get_template_part('templates/parts/related-posts'); ?>
        </aside>
    </div>
</main>
