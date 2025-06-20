<?php get_header(); ?>

<main data-barba="container" data-barba-namespace="goods-archive">
    <div class="flex flex-row justify-between items-center xl:ml-[25%] xl:mr-[7.5%] mt-16">
        <div class="w-full xl:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Goods'
            ]);
            ?>
        </div>
        <div class="hidden xl:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'default']); ?>
        </div>
    </div>
    <section class="w-full xl:w-[75%] xl:ml-[25%] mt-7 xl:mt-16">
        <?php
        // スライダーセクション
        get_template_part('templates/swiper/swiper-default', null, array(
            'post_type_slug' => 'goods'
        ));
        ?>
    </section>
    <section class="w-full xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30">
        <div class="mb-5 xl:mb-6 px-[5%] xl:px-0">
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
        <div class="goods-archive-filter-area">
            <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 xl:gap-2 px-5 xl:px-0 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
                <li>
                    <a href="#" class="is-active tag-link flex items-center h-[26px] xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-2 xl:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] is-active:bg-[#F5F5F5] is-active:border-[#333] is-active:text-[#000] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($taxonomy_slug); ?>">
                    すべて
                    </a>
                </li>
                <?php foreach ($tags as $tag) : ?>
                    <li>
                        <a href="#" class="tag-link flex items-center h-[26px] xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-2 xl:px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] is-active:bg-[#F5F5F5] is-active:border-[#333] is-active:text-[#000] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>" data-category="<?= esc_attr($taxonomy_slug); ?>">
                            <?= esc_html($tag->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="goods-archive-list" id="filtered-posts-container">
            <?php
            if (have_posts()) :
                echo '<div class="grid grid-cols-2 xl:grid-cols-4 mt-5 xl:mt-15 gap-x-2 gap-y-6 xl:gap-x-9 xl:gap-y-15">';
                while (have_posts()) : the_post();
                    // $goods_categories = get_the_terms(get_the_ID(), 'goods_category');
                    // $post_category_slug = !empty($goods_categories) ? $goods_categories[0]->slug : '';
                    // 各投稿のカテゴリを取得し、data_tags として渡す
                    $taxonomy_slug = 'goods_category'; // Goodsのタクソノミースラッグ
                    $post_terms = get_the_terms(get_the_ID(), $taxonomy_slug);
                    $data_tags_value = [];
                    if (!empty($post_terms) && !is_wp_error($post_terms)) {
                        foreach ($post_terms as $term) {
                            $data_tags_value[] = $term->slug;
                        }
                    }
                    $data_tags_string = implode(' ', $data_tags_value); // スペース区切りで文字列化

                    get_template_part('templates/parts/image-with-text', null, array(
                        'rounded' => 'xl:rounded-[20px]',
                        'mt_below' => 'mt-2 xl:mt-5',
                        // 'post_category_slug' => $post_category_slug
                        'data_tags' => $data_tags_string // ★data_tags として渡す★
                    ));
                endwhile;
                echo '</div>';
            else :
                echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
        <div id="pagination-container">
            <?php
            // 初期ページのページネーション表示 (メインクエリ)
            global $wp_query;
            $big = 999999999; // need an unlikely integer
            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $wp_query->max_num_pages,
                'prev_text'    => __('&laquo; Prev'),
                'next_text'    => __('Next &raquo;'),
                'type'         => 'list',
                'before_page_number' => '<span class="screen-reader-text">Page </span>'
            ) );
            ?>
        </div>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30">
        <?php
        get_template_part('templates/heading/heading-with-brackets', null, [
            'heading_text' => 'SNSアイコン制作',
            'heading_tag'  => 'h2',
        ]);
        ?>
        <?php get_template_part('templates/category/takurami');?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30">
        <?php get_template_part('templates/goods/shop-list'); ?>
    </section>
</main>
<?php get_footer(); ?>
