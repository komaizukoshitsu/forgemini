<?php get_header(); ?>

<main data-barba="container" data-barba-namespace="archive">
    <div class="flex lg:flex-row justify-center lg:justify-between items-center lg:ml-[25%] lg:mr-[7.5%] mt-16">
        <div class="w-full lg:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Works'
            ]);
            ?>
        </div>
        <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:ml-[25%] xl:w-[75%] mt-7 lg:mt-16">
        <?php
        // スライダーセクション
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'works'
        ));
        ?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <div class="mb-5 lg:mb-6 px-[5%] lg:px-0">
            <?php
            // Works一覧の見出し
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'お仕事一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>

        <?php
        // ★修正点1: タクソノミースラッグを 'works_category' に変更
        $taxonomy_slug = 'works_category';

        // このタクソノミーに属するすべてのタグを取得
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy_slug,
            'hide_empty' => false, // 投稿がないタグも表示する場合は true
        ) );

        if (!empty($terms) && !is_wp_error($terms)) :
        ?>
        <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 lg:gap-2 px-5 lg:px-0 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
            <li>
                <a href="#" class="active tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($taxonomy_slug); ?>">
                すべて
                </a>
            </li>
            <?php foreach ($terms as $term) : ?>
                <li>
                    <a href="#" class="tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="<?= esc_attr($term->slug); ?>" data-category="<?= esc_attr($taxonomy_slug); ?>">
                        <?= esc_html($term->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div id="post-list">
            <?php
            // メインクエリはWorks投稿タイプの結果を自動的に含みます
            if (have_posts()) :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                while (have_posts()) : the_post();
                    // Worksの表示形式に合わせて調整
                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded' => 'lg:rounded-[20px]',
                        'mt_below' => 'mt-2 lg:mt-5'
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">作品が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
        <?php the_posts_pagination(); // ページネーションを追加 ?>
    </section>
</main>
<?php get_footer(); ?>
