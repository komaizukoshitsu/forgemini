<?php
get_header(); // ヘッダーを読み込む
?>

<?php

// ページ上のタグ一覧と月一覧を生成するために、
// 'events' 投稿タイプ全体のタグとカスタムフィールドを収集します。
// これはメインクエリとは別の、タグ・月のリスト生成用のクエリです。
$all_events_args = array(
    'post_type'      => 'events', // ★ここをカスタム投稿タイプ 'events' に変更
    'posts_per_page' => -1,       // すべての投稿を取得
    'fields'         => 'ids',    // ID のみ取得
);
$all_events_query = new WP_Query($all_events_args);
$all_event_ids = $all_events_query->posts;

// タグ取得: 'events' 投稿タイプに紐付けられたカスタムタクソノミーからタグを取得
$tags_for_filter = get_terms(array(
    'taxonomy'   => 'event_type',
    'hide_empty' => true,             // 投稿がないタグは表示しない
));


// 開催月一覧の収集（すべてのイベント投稿から）
$month_set = [];
foreach ($all_event_ids as $post_id) {
    $start_date_field = get_field('event-start', $post_id);
    $end_date_field   = get_field('event-end', $post_id);

    // ★★★ここから追加★★★
    error_log("Debug: Post ID {$post_id} - Start Date: '{$start_date_field}', End Date: '{$end_date_field}'");
    // ★★★ここまで追加★★★

    // get_event_months_for_archive 関数が存在することを前提としています
    $months = get_event_months_for_archive($start_date_field, $end_date_field);
    foreach ($months as $m) {
        $month_set[$m] = true;
    }
}
krsort($month_set); // 降順に並び替え (例: 2025-05, 2025-04, ...)
$month_list = array_map(function($m) {
    return [$m, date('Y年n月', strtotime($m . '-01'))];
}, array_keys($month_set));

// ★★★ここから追加★★★
error_log('Debug: $month_set = ' . print_r($month_set, true));
error_log('Debug: $month_list = ' . print_r($month_list, true));
// ★★★ここまで追加★★★

// 現在の月 (セレクトボックスの初期選択用) 例: 2025-05
$current_ym_for_select = date('Y-m');
// 月リストに現在の月が含まれていない場合（将来のイベントしかない場合など）は、リストの最初の月をデフォルトにする
if (!array_key_exists($current_ym_for_select, $month_set) && !empty($month_list)) {
    $current_ym_for_select = $month_list[0][0]; // 最も新しい月をデフォルトにする
} else if (empty($month_list)) {
    // イベントが一つもない場合
    $current_ym_for_select = 'all'; // または他の適切なデフォルト値
}
?>

<main data-barba="container" data-barba-namespace="events-archive">
    <div class="flex flex-row justify-between items-center xl:ml-[25%] xl:mr-[7.5%] mt-16">
        <div class="w-full xl:w-auto">
            <?php
            get_template_part('templates/heading/custom-heading', null, [
                'title' => 'Events'
            ]);
            ?>
        </div>
        <div class="hidden xl:flex justify-center items-center gap-8 !h-10">
            <?php get_template_part('templates/swiper/nav-btn-swiper', null, ['prefix' => 'event']); ?>
        </div>
    </div>
    <section class="w-full xl:w-[75%] xl:ml-[25%] mt-7 xl:mt-16">
        <?php get_template_part('templates/swiper/swiper-event'); ?>
    </section>
    <section class="w-[90%] xl:w-[67.5%] mx-auto xl:ml-[25%] xl:max-w-275 mt-15 xl:mt-30">
        <div class="mb-5 xl:mb-[23px] ">
            <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'イベント一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>
        <div class="events-archive-filter-area flex flex-col-reverse xl:flex-row xl:justify-between xl:items-center flex-wrap gap-3 mb-6">
            <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 xl:gap-2 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
                <li>
                    <a href="#" class="tag-link is-active flex items-center h-6 xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-[10px] rounded-full border border-[#D9D9D9] transition-all duration-250 ease-in-out cursor-pointer hover:bg-[#F5F5F5] hover:border-[#999] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="all">
                    すべて
                    </a>
                </li>
                <?php foreach ($tags_for_filter as $tag): ?>
                    <li>
                        <a href="#" class="tag-link flex items-center h-6 xl:h-[30px] text-xs xl:text-sm tracking-[0.07em] px-[10px] rounded-full border transition-all duration-250 ease-in-out cursor-pointer border-[#D9D9D9] text-[#333] bg-white hover:bg-[#F5F5F5] hover:border-[#999] is-active:bg-[#F5F5F5] is-active:border-[#333] is-active:text-[#000] [&.is-active]:bg-[#F5F5F5] [&.is-active]:border-[#333] [&.is-active]:text-[#000]" data-tag="<?= esc_attr($tag->slug); ?>">
                            <?= esc_html($tag->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="relative w-full xl:max-w-60">
                <select
                    id="month-filter"
                    class="appearance-none w-full h-10 xl:h-[40px] rounded-full border border-[#D9D9D9] bg-white px-4 pr-10 text-center text-sm tracking-[0.1em] leading-snug transition duration-200 ease-in-out hover:bg-[#F5F5F5] hover:border-[#999] focus:outline-none focus:ring-0 [&_option]:text-align-last-center">
                    <?php foreach ($month_list as [$value, $label]): ?>
                        <option value="<?= esc_attr($value); ?>" <?= ($value === $current_ym_for_select) ? 'selected' : '' ?>>
                            <?= esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="events-archive-list space-y-4 xl:space-y-6 mt-5 xl:mt-15 pt-4 xl:pt-6 border-t border-[#D9D9D9]" id="filtered-posts-container">
            <p class="initial-loading-message text-sm leading-[1.4] px-3 xl:px-4  -pl-4 pb-4 xl:pb-6 border-b border-[#D9D9D9] text-center text-gray-500">イベントを読み込み中...</p>
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
</main>

<?php get_footer(); ?>
