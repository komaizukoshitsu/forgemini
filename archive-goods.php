<?php get_header(); ?>

<main data-barba="container" data-barba-namespace="goods-archive">
    <div class="flex flex-row justify-between items-center xl:ml-[25%] xl:mr-[7.5%] mt-16">
        <div class="w-full lg:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Goods'
            ]);
            ?>
        </div>
        <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:w-[75%] xl:ml-[25%] mt-7 lg:mt-16">
        <?php
        // スライダーセクション
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'goods'
        ));
        ?>
    </section>
    <section class="w-full xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <div class="mb-5 lg:mb-6 px-[5%] lg:px-0">
            <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'グッズ一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>

        <?php
        // ★修正点1: タクソノミースラッグを 'goods_category' に変更
        $taxonomy_slug = 'goods_category';

        // このタクソノミーに属するすべてのタグを取得
        $tags = get_terms( array(
            'taxonomy'   => $taxonomy_slug,
            'hide_empty' => false,
        ) );

        if (!empty($tags) && !is_wp_error($tags)) :
        ?>
        <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 lg:gap-2 px-5 lg:px-0 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
            <li>
                <a href="#" class="active tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($taxonomy_slug); ?>">
                すべて
                </a>
            </li>
            <?php foreach ($tags as $tag) : ?>
                <li>
                    <a href="#" class="tag-link flex items-center h-[26px] lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-2 lg:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>" data-category="<?= esc_attr($taxonomy_slug); ?>">
                        <?= esc_html($tag->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div id="post-list">
            <?php
            if (have_posts()) :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
                while (have_posts()) : the_post();
                    // ★修正点2: get_the_category() の代わりにカスタムタクソノミーからタームを取得 (もし必要なら)
                    // グッズカテゴリーをimage-with-text.phpに渡す場合
                    $goods_categories = get_the_terms(get_the_ID(), 'goods_category');
                    $post_category_slug = !empty($goods_categories) ? $goods_categories[0]->slug : '';

                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded' => 'lg:rounded-[20px]',
                        'mt_below' => 'mt-2 lg:mt-5',
                        'post_category_slug' => $post_category_slug // カテゴリーを渡す (image-with-text.php側で受け取る想定)
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
        <?php the_posts_pagination(); // ページネーションを追加 ?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <?php
        get_template_part('templates/heading/heading-with-brackets', null, [
            'heading_text' => 'SNSアイコン制作',
            'heading_tag'  => 'h2',
        ]);
        ?>
        <?php get_template_part('templates/category/takurami');?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <?php get_template_part('templates/goods/shop-list'); ?>
    </section>
</main>
<?php get_footer(); ?>
