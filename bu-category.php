<?php get_header(); ?>
<?php
// category-event.php の先頭あたりに追加
function get_event_months($start_date, $end_date) {
    $months = [];

    if (!$start_date || !$end_date) return $months;

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $end->modify('first day of next month');

    while ($start < $end) {
    $months[] = $start->format('Y-m');
    $start->modify('first day of next month');
    }

    return $months;
}
?>
<main data-barba="container" data-barba-namespace="archive">
    <!-- <section class="container mt-24 lg:mt-30 lg:ml-[25%] max-w-[1100px] mx-auto"> -->
    <section class="mt-24 lg:mt-30 mx-auto lg:ml-[25%] lg:mr-[7.5%] 2xl:ml-[25%] 2xl:mr-[7.5%] max-w-[1100px]">
        <h1 class="font-medium px-5 lg:px-0 mb-5 lg:mb-7 tracking-[0.1em]">（&ensp;<?php single_cat_title(); ?>&ensp;）</h1>

        <!-- タグフィルター -->
        <?php
        $category = get_queried_object(); // 現在のカテゴリ情報を取得
        $category_slug = $category->slug;

        // カテゴリ内の投稿を取得（数は多すぎないよう制限）
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'category_name' => $category_slug,
            'fields' => 'ids',
        );
        $query = new WP_Query($args);
        $post_ids = $query->posts;

        $tags = wp_get_object_terms($post_ids, 'post_tag');

        if (!empty($tags) && !is_wp_error($tags)) :
        ?>
            <ul class="tag-list flex flex-wrap w-max gap-1 lg:gap-2 px-5 lg:px-0">
            <li>
                <a href="#" class="tag-link active flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border border-[#D9D9D9] transition-all duration-250 ease-in-out cursor-pointer hover:bg-[#F5F5F5] hover:border-[#999] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all" data-category="<?= esc_attr($category_slug); ?>">
                すべて
                </a>
            </li>
            <?php foreach ($tags as $tag) : ?>
                <li>
                    <a href="#" class="tag-link flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>" data-category="<?= esc_attr($category_slug); ?>">
                        <?= esc_html($tag->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- 投稿一覧 -->
        <div id="post-list">
            <?php
            // カテゴリによるレイアウト切り替え
            $is_text_list = ($category_slug === 'event');

            if (have_posts()) :
            if ($is_text_list) :
                echo '<div class="space-y-4 lg:space-y-6 mt-5 lg:mt-15 pt-4 lg:pt-6 border-t border-b border-[#D9D9D9]">';
            else :
                echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-9 gap-y-15">';
            endif;

            while (have_posts()) : the_post();

                // 投稿ごとのカテゴリ判定（テンプレート出し分け）
                $categories = get_the_category();
                $post_category_slug = $categories ? $categories[0]->slug : '';

                if (in_array($post_category_slug, ['news', 'works', 'goods'])) {
                get_template_part('template-parts/image-with-text');
                } elseif ($post_category_slug === 'event') {
                get_template_part('template-parts/event-list');
                } else {
                get_template_part('template-parts/image-with-text');
                }

            endwhile;
            echo '</div>';
            else :
            echo '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
