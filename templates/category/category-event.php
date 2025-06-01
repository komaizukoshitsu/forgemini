<?php get_header(); ?>

<?php
// イベント月取得関数
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

// 現在のカテゴリ情報
$category = get_queried_object();
$category_slug = $category->slug;

// 投稿取得
$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'category_name' => $category_slug,
    'fields' => 'ids',
);
$query = new WP_Query($args);
$post_ids = $query->posts;

// タグ取得
$tags = wp_get_object_terms($post_ids, 'post_tag');

// 開催月一覧の収集（全投稿から）
$month_set = [];

foreach ($post_ids as $post_id) {
    $start = get_field('event-start', $post_id);
    $end = get_field('event-end', $post_id);
    // var_dump($start, $end);
    $months = get_event_months($start, $end);
    foreach ($months as $m) {
        $month_set[$m] = true;
    }
}
krsort($month_set); // 降順に並び替え
$month_list = array_map(function($m) {
    return [$m, date('Y年n月', strtotime($m . '-01'))];
}, array_keys($month_set));

$current_month = date('Y-m');
?>

<main data-barba="container" data-barba-namespace="archive">
    <div class="flex flex-row justify-between items-center lg:ml-[25%] lg:mr-[7.5%] mt-16">
        <div class="w-full lg:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Events'
            ]);
            ?>
        </div>
        <div class="hidden lg:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'event']); ?>
        </div>
    </div>
    <section class="w-full xl:w-[75%] xl:ml-[25%] mt-7 lg:mt-16">
        <?php get_template_part('templates/swiper/swiper-event'); ?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 lg:mt-30">
        <!-- <h1 class="font-medium px-5 lg:px-0 mb-5 lg:mb-[23px] tracking-[0.1em]">（&ensp;<?php single_cat_title(); ?>&ensp;）</h1> -->
        <div class="mb-5 lg:mb-[23px] ">
            <?php
            get_template_part('template-parts/heading-with-brackets', null, [
                'heading_text' => 'イベント一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>
        <!-- タグ＋月セレクト -->
        <div class="flex flex-col-reverse lg:flex-row lg:justify-between lg:items-center flex-wrap gap-3 mb-6">
            <!-- <ul class="tag-list flex flex-wrap w-max gap-1 lg:gap-2"> -->
            <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 lg:gap-2 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
                <li>
                    <a href="#" class="tag-link active flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border border-[#D9D9D9] transition-all duration-250 ease-in-out cursor-pointer hover:bg-[#F5F5F5] hover:border-[#999] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all">
                    すべて
                    </a>
                </li>
                <?php foreach ($tags as $tag): ?>
                    <li>
                        <a href="#" class="tag-link flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] active:bg-[#F5F5F5] active:border-[#333] active:text-[#000] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>">
                            <?= esc_html($tag->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="relative w-full lg:max-w-60">
                <select
                    id="month-filter"
                    class="appearance-none w-full h-10 lg:h-[40px] rounded-full border border-[#D9D9D9] bg-white px-4 pr-10 text-center text-sm tracking-[0.1em] leading-snug transition duration-200 ease-in-out hover:bg-[#F5F5F5] hover:border-[#999] focus:outline-none focus:ring-0 [&_option]:text-align-last-center">
                    <?php foreach ($month_list as [$value, $label]): ?>
                        <option value="<?= esc_attr($value); ?>" <?= ($value === $current_month) ? 'selected' : '' ?>>
                            <?= esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- 投稿一覧 -->
        <div class="">
            <div id="post-list" class="space-y-4 lg:space-y-6 mt-5 lg:mt-15 pt-4 lg:pt-6 border-t border-[#D9D9D9]">
                <?php if (have_posts()) :
                while (have_posts()) : the_post();
                    $tag_slugs = wp_get_post_tags(get_the_ID(), ['fields' => 'slugs']);
                    $start_date = get_field('event-start', get_the_ID());
                    $end_date = get_field('event-end', get_the_ID());
                    $event_months = get_event_months($start_date, $end_date);
                    $months_str = implode(',', $event_months); // ← これを作る！
            ?>
                <?php
                    // 必要な変数をグローバルで渡す（テンプレート内で使えるように）
                    set_query_var('months_str', esc_attr($months_str));
                    set_query_var('tag_slugs', esc_attr(implode(',', $tag_slugs)));
                    get_template_part('templates/event/event-list-item');
                ?>
            <?php endwhile;
            else :
                echo '<p class="pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">表示可能なイベントがありません</p>';
            endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
