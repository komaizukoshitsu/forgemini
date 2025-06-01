<?php
get_header();
?>

<?php
// イベント月取得関数
// この関数はカスタムフィールドの値に基づいて月のリストを生成します。
function get_event_months_for_archive($start_date_str, $end_date_str) {
    $months = [];

    // 日付文字列が有効であることを確認
    if (empty($start_date_str) || empty($end_date_str)) {
        return $months;
    }

    try {
        // YYYYMMDD 形式から DateTime オブジェクトへ変換
        $start = new DateTime($start_date_str);
        $end = new DateTime($end_date_str);
    } catch (Exception $e) {
        // 日付形式が無効な場合のハンドリング
        error_log("Date parsing error in get_event_months_for_archive: " . $e->getMessage());
        return $months;
    }

    // 終了日はその月の末日までを含むように調整し、ループ条件に合わせる
    // 終了日の次月の1日までループすることで、終了日を含む月も確実に取得する
    $end->modify('first day of next month');

    while ($start < $end) {
        $months[] = $start->format('Y-m'); // YYYY-MM 形式で追加
        $start->modify('first day of next month');
    }

    return array_unique($months); // 重複を削除
}

// ページ上のタグ一覧と月一覧を生成するために、
// 'events' 投稿タイプ全体のタグとカスタムフィールドを収集します。
$all_events_args = array(
    'post_type'      => 'events',
    'posts_per_page' => -1,
    'fields'         => 'ids',
);
$all_events_query = new WP_Query($all_events_args);
$all_event_ids = $all_events_query->posts;

// タグ取得: 'events' 投稿タイプに紐付けられたカスタムタクソノミーからタグを取得
$tags_for_filter = get_terms(array(
    'taxonomy'   => 'events_tags', // ★ここをイベントのタグタクソノミースラッグに
    'hide_empty' => true,
));

// 開催月一覧の収集（すべてのイベント投稿から）
$month_set = [];
foreach ($all_event_ids as $post_id) {
    $start_date_field = get_field('event-start', $post_id);
    $end_date_field   = get_field('event-end', $post_id);

    $months = get_event_months_for_archive($start_date_field, $end_date_field);
    foreach ($months as $m) {
        $month_set[$m] = true;
    }
}
krsort($month_set); // 降順に並び替え (例: 2025-05, 2025-04, ...)
$month_list = array_map(function($m) {
    return [$m, date('Y年n月', strtotime($m . '-01'))];
}, array_keys($month_set));

// 現在の月 (セレクトボックスの初期選択用)
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
                    <a href="#" class="tag-link flex items-center h-6 lg:h-[30px] text-xs lg:text-sm tracking-[0.07em] px-[10px] rounded-full border border-[#D9D9D9] transition-all duration-250 ease-in-out cursor-pointer hover:bg-[#F5F5F5] hover:border-[#999] [&.active]:bg-[#F5F5F5] [&.active]:border-[#333] [&.active]:text-[#000]" data-tag="all">
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
                    <option value="all">すべて</option>
                    <?php foreach ($month_list as [$value, $label]): ?>
                        <option value="<?= esc_attr($value); ?>" <?= ($value === $current_month) ? 'selected' : '' ?>>
                            <?= esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="">
            <div id="post-list" class="space-y-4 lg:space-y-6 mt-5 lg:mt-15 pt-4 lg:pt-6 border-t border-[#D9D9D9]">
                <?php
                // ★ここが最も重要です。
                // 初期表示で「現在月」のイベントを表示するために、
                // メインクエリを一時的に変更（プリ・ゲット・ポストフックは使わず、直接 WP_Query を修正）
                // しかし、archive.php はデフォルトでそのアーカイブタイプ用のメインクエリを実行します。
                // そのため、ここではメインクエリを直接変更するのではなく、
                // JavaScriptで初期フィルターを適用する方が一般的です。
                //
                // ここでは、PHP側で初期表示時に現在月の投稿を絞り込むための簡単な方法を提供します。
                // ただし、ページ読み込み後にJavaScriptでAJAXフィルターが実行される場合、
                // そのJavaScriptの初期処理を見直す必要がある可能性があります。

                // まずは、通常のメインループを回します。
                // JavaScript側で「すべて」が初期選択された状態として扱われているのであれば、
                // JavaScriptの初期フィルターロジックを修正する必要があります。

                // PHP側で初期表示を現在月に限定する場合（簡易版）：
                // これは、JavaScriptによるフィルターがない場合にのみ有効な初期表示の制御です。
                // JavaScriptでフィルターを実装している場合、通常はJavaScript側で初期フィルターを実行します。

                // --- 以下のコードは、JavaScriptによるフィルターがない場合にのみ有効なPHP側の初期絞り込みです ---
                // メインクエリを変更せず、別途クエリを発行する
                // $initial_query_args = array(
                //     'post_type'      => 'events',
                //     'posts_per_page' => -1, // 必要に応じて調整
                //     'meta_query' => array(
                //         array(
                //             'key'     => 'event-start',
                //             'value'   => $current_month . '-01',
                //             'compare' => '<=',
                //             'type'    => 'DATE'
                //         ),
                //         array(
                //             'key'     => 'event-end',
                //             'value'   => date('Y-m-t', strtotime($current_month . '-01')), // その月の最終日
                //             'compare' => '>=',
                //             'type'    => 'DATE'
                //         )
                //     ),
                //     'orderby'  => 'meta_value',
                //     'meta_key' => 'event-start',
                //     'order'    => 'ASC',
                // );
                // $initial_events_query = new WP_Query($initial_query_args);

                // if ($initial_events_query->have_posts()) :
                //     while ($initial_events_query->have_posts()) : $initial_events_query->the_post();
                //         // ... テンプレートパーツの読み込み ...
                //     endwhile;
                // else :
                //     echo '<p class="pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">表示可能なイベントがありません</p>';
                // endif;
                // wp_reset_postdata();
                // --- ここまで ---

                // 通常のアーカイブページでは、メインクエリ (the_post() など) をそのまま使うため、
                // 現在のままでは「すべてのイベント」が表示されます。

                // === ここが今回の問題の核です ===
                // `category-event.php` で表示されていた「現在月の記事」は、
                // そのファイルのPHPコード内で直接クエリを絞り込んでいたか、
                // あるいはJavaScriptの初期処理でフィルターが適用されていました。
                // `archive-event.php` では、WPのメインクエリはイベントアーカイブ全体を対象とするため、
                // 初期状態ではフィルターがかかっていません。

                // **最も現実的な解決策**: JavaScript側で初期ロード時のフィルターを実行する
                // これまでのコードでは、JavaScriptで動的にフィルタリングを行っていると推測されます。
                // そのJavaScriptコードを見直し、ページロード時に
                // `month-filter` の `current_month` の値で `post-list` をフィルターする処理を追加・修正する必要があります。

                // 以下は、JavaScript側で初期フィルターを行う前提で、
                // ページロード時のPHPの表示は、そのままメインクエリのすべてのイベントを表示します。
                // JavaScriptで、ページロード時に `month-filter` の `selected` オプションの値（つまり現在月）を
                // 読み取り、それに合わせてリストをフィルタリングするイベントを発火させる必要があります。

                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $tag_terms = wp_get_post_terms(get_the_ID(), 'events_tags', ['fields' => 'slugs']);
                        $tag_slugs_str = implode(',', $tag_terms);

                        $start_date = get_field('event-start', get_the_ID());
                        $end_date = get_field('event-end', get_the_ID());
                        $event_months = get_event_months_for_archive($start_date, $end_date);
                        $months_str = implode(',', $event_months);

                        set_query_var('months_str', esc_attr($months_str));
                        set_query_var('tag_slugs', esc_attr($tag_slugs_str));
                        get_template_part('templates/parts/filtered-items-event');
                    ?>
                <?php endwhile;
                else :
                    echo '<p class="pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">表示可能なイベントがありません</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
