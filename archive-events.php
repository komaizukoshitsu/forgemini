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
    'taxonomy'   => 'event_type', // ★ 'event_type' に修正
    'hide_empty' => true,             // 投稿がないタグは表示しない
));


// 開催月一覧の収集（すべてのイベント投稿から）
$month_set = [];
foreach ($all_event_ids as $post_id) {
    $start_date_field = get_field('event-start', $post_id);
    $end_date_field   = get_field('event-end', $post_id);

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

<main data-barba="container" data-barba-namespace="events">
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
        <div class="mb-5 lg:mb-[23px] ">
            <?php
            get_template_part('templates/heading/heading-with-brackets', null, [
                'heading_text' => 'イベント一覧',
                'heading_tag'  => 'h2',
            ]);
            ?>
        </div>
        <div class="flex flex-col-reverse lg:flex-row lg:justify-between lg:items-center flex-wrap gap-3 mb-6">
            <ul class="tag-list flex flex-nowrap overflow-x-auto overflow-y-hidden whitespace-nowrap max-w-full gap-1 lg:gap-2 scroll-smooth [-webkit-overflow-scrolling:touch] no-scrollbar">
                <li>
                    <a href="#" class="tag-link active flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border border-[#D9D9D9] transition-all duration-250 ease-in-out cursor-pointer hover:bg-[#F5F5F5] hover:border-[#999] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all">
                    すべて
                    </a>
                </li>
                <?php foreach ($tags_for_filter as $tag): ?>
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
                        <option value="<?= esc_attr($value); ?>" <?= ($value === $current_ym_for_select) ? 'selected' : '' ?>>
                            <?= esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="">
            <div id="post-list" class="space-y-4 lg:space-y-6 mt-5 lg:mt-15 pt-4 lg:pt-6 border-t border-[#D9D9D9]">
            <?php
            // この部分のPHPループは削除し、初回表示もJavaScriptのAJAXで制御する方が良いです。
            // 下記のように空にしておくことで、JavaScriptがAJAXでコンテンツを読み込むのを待ちます。
            ?>
            <p class="initial-loading-message text-sm lg:text-base leading-[1.4] px-3 lg:px-4  -pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">イベントを読み込み中...</p>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
