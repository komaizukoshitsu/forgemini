<?php

// =================================================================
//     タイムゾーンを日本時間に設定
// =================================================================

date_default_timezone_set('Asia/Tokyo');


// =================================================================
//     記事の自動整形を無効にする
// =================================================================

remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

// =================================================================
//     各ページのbodyにクラス名を自動設定
// =================================================================

function add_slug_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
    // ページのスラッグをクラス名として設定
    $classes[] = $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/**
 * アーカイブタイトル書き換え
 */
function my_archive_title($title) {

  if (is_category()) { // カテゴリーアーカイブの場合
    $title = single_cat_title('', false);
  } elseif (is_tag()) { // タグアーカイブの場合
    $title = single_tag_title('', false);
  } elseif (is_post_type_archive()) { // 投稿タイプのアーカイブの場合
    $title = post_type_archive_title('', false);
  } elseif (is_tax()) { // タームアーカイブの場合
    $title = single_term_title('', false);
  } elseif (is_author()) { // 作者アーカイブの場合
    $title = get_the_author();
  } elseif (is_date()) { // 日付アーカイブの場合
    $title = '';
    if (get_query_var('year')) {
      $title .= get_query_var('year') . '年';
    }
    if (get_query_var('monthnum')) {
      $title .= get_query_var('monthnum') . '月';
    }
    if (get_query_var('day')) {
      $title .= get_query_var('day') . '日';
    }
  }
  return $title;
};
add_filter('get_the_archive_title', 'my_archive_title');


// =================================================================
//     event-page Ajax
// =================================================================

function filter_events() {
    // POSTリクエストからデータを取得
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $month = isset($_POST['month']) ? sanitize_text_field($_POST['month']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    if (empty($month)) {
        $month = date('Y.n'); // 例: 2025.3
    }

    // 投稿クエリの引数を設定
    $args = array(
        'category_name' => 'event', // カテゴリフィルターを追加
        'post_type' => 'post',
        'posts_per_page' => 4,  // 表示する投稿数
        'paged' => $paged,
        'meta_key' => 'event-start', // 開始日でソート
        'orderby' => 'meta_value',
        'order' => 'DESC',
    );

    // タグフィルタが選択されている場合
    if (!empty($tag) && $tag !== 'all') {
        $args['tag'] = $tag;
    }

    // 月フィルタが選択されている場合
    if (!empty($month)) {
        $month_parts = explode('.', $month);

        if (count($month_parts) == 2) {
            $year = intval($month_parts[0]);
            $month_numeric = intval($month_parts[1]);

            // 月の初日と最終日を計算
            $month_start = date('Y-m-01', strtotime("$year-$month_numeric"));
            $month_end = date('Y-m-t', strtotime("$year-$month_numeric"));

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'event-start',
                    'value' => array($month_start, $month_end),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ),
                array(
                    'key' => 'event-end',
                    'value' => array($month_start, $month_end),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ),
                // イベントが月をまたぐ場合
                array(
                    'relation' => 'AND',
                    array(
                        'key' => 'event-start',
                        'value' => $month_start,
                        'compare' => '<=',
                        'type' => 'DATE',
                    ),
                    array(
                        'key' => 'event-end',
                        'value' => $month_end,
                        'compare' => '>=',
                        'type' => 'DATE',
                    ),
                ),
            );
        }
    }

    // クエリを実行してイベントを取得
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ob_start(); // 出力をバッファリング

        // 投稿リストを出力
        echo '<ul id="post-list" class="event-list-container mt-5 lg:mt-10 border-t border-[#D9D9D9]">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
            <li class="post-item schedule-item">
                <a href="<?php the_permalink(); ?>" class="flex">
                    <div class="left test-[11px] lg:text-[14px]">
                        <div class="text-sm" style="font-family: 'Open Sans', sans-serif; letter-spacing:0.1em; line-height:1.3;">
                            <?php
                            $event_start = get_field('event-start', false, false);
                            if ($event_start) {
                                $formatted_start = date('Y.n.j', strtotime($event_start));
                                $start_day = date('w', strtotime($event_start));
                                $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
                                echo esc_html($formatted_start . '（' . $weekdays[$start_day] . '）');
                            }
                            $event_end = get_field('event-end', false, false);
                            if ($event_end) {
                                $formatted_end = date('n.j', strtotime($event_end));
                                $end_day = date('w', strtotime($event_end));
                                echo '<span class="mr-2">－</span>' . esc_html($formatted_end . '（' . $weekdays[$end_day] . '）');
                            }
                            ?>
                        </div>
                            <?php

                            // 今日の日付を 'Y-m-d' フォーマットで取得して比較用に使用
                            $today = date('Y-m-d');

                            // ACFのカスタムフィールドからイベントの開始日と終了日を取得
                            $start = get_field('event-start'); // イベント開始日
                            $end = get_field('event-end');     // イベント終了日

                            // 比較用にタイムスタンプを作成
                            $today_timestamp = strtotime($today);
                            $start_timestamp = $start ? strtotime($start) : null;
                            $end_timestamp = $end ? strtotime($end) : null;

                            // ステータスの初期値
                            $status = '';
                            $bg_class = 'bg-[#FFF546]'; // デフォルトの背景色（黄色）

                            // イベント終了日がある場合の判定
                            if ($end_timestamp) {
                                if ($today_timestamp >= $start_timestamp && $today_timestamp <= $end_timestamp) {
                                    $status = 'ただいま開催中'; // 今日がイベント期間内の場合
                                } elseif ($today_timestamp < $start_timestamp) {
                                    $status = '開催予定'; // 今日がイベント開始日より前の場合
                                    $bg_class = 'bg-[#FFFAD1]';
                                } elseif ($today_timestamp > $end_timestamp) {
                                    $status = '終了しました'; // 今日がイベント終了日より後の場合
                                    $bg_class = 'bg-[#DADADA]'; // 終了時に背景をグレーに変更
                                }
                            } else {
                                // 終了日が設定されていない場合
                                if ($today_timestamp < $start_timestamp) {
                                    $status = '開催予定'; // 今日が開始日前の場合
                                    $bg_class = 'bg-[#FFFAD1]';
                                } elseif ($today_timestamp == $start_timestamp) {
                                    $status = 'ただいま開催中'; // 今日が開始日と同じ場合
                                } elseif ($today_timestamp > $start_timestamp) {
                                    $status = '終了しました'; // 今日が開始日より後の場合
                                    $bg_class = 'bg-[#DADADA]'; // 終了時に背景をグレーに変更
                                }
                            }

                            // 表示用に日付を 'Y.n.j' 形式でフォーマットして表示
                            $start_display = $start ? date('Y.n.j', strtotime($start)) : '日付が未設定です';
                            $end_display = $end ? date('n.j', strtotime($end)) : '日付が未設定です';
                        ?>

                        <p class="lg:mt-[5px] text-[9px] lg:text-xs h-[20px] lg:h-[24px] px-[6px] rounded-[15px] text-center leading-[1] flex justify-center items-center w-fit <?php echo $bg_class; ?>">
                            <?php echo $status; ?>
                        </p>
                    </div>
                    <div class="right">
                        <h2 class="schedule-item-title text-[14px] lg:text-base lg:px-0 mt-1 lg:mt-0 transition-all duration-250 ease-linear inline-block" style="line-height:1.4;"><?php the_title(); ?></h2>
                        <div class="flex mt-1 lg:mt-2 items-center">
                            <div class="text-[9px] lg:text-sm" style="line-height:1.4;">
                            <?php
                            $event_place = get_field('event-place');
                            if ($event_place) {
                                echo esc_html($event_place);
                            }
                            ?>
                            </div>
                            <?php
                            $tags = get_the_tags();
                            if ($tags) : ?>
                                <div class="text-[9px] lg:text-xs tag ml-2 pl-2 border-l border-solid border-[#D9D9D9]" style="line-height:1.4;">
                                    <?php foreach ($tags as $tag) {
                                        echo '<span>' . esc_html($tag->name) . '</span>';
                                    } ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';

        // ページネーション
        echo '<div class="post-pagination-wrapper" aria-label="イベント">';
        $paged = max(1, intval($_POST['paged']));

        $prev_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 6H2" stroke="#333" stroke-width="2"/>
        <path d="M7 11L2 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        $next_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6H12" stroke="#333" stroke-width="2"/>
        <path d="M7 11L12 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => $prev_icon,
            'next_text' => $next_icon
        ));

        echo '</div>';

        wp_reset_postdata();

        $response = array(
            'success' => true,
            'content' => ob_get_clean(),
        );

    } else {
        $response = array(
            'success' => false,
            'content' => '<p>イベントが見つかりませんでした。</p>',
        );
    }

    wp_send_json($response);
}

add_action('wp_ajax_filter_events', 'filter_events');
add_action('wp_ajax_nopriv_filter_events', 'filter_events');


// =================================================================
//     news-page Ajax
// =================================================================

function filter_news() {
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // 投稿クエリの引数を設定
    $args = array(
        'category_name' => 'news', // カテゴリフィルターを追加
        'post_type' => 'post',
        'posts_per_page' => 8,  // 表示する投稿数
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // タグフィルタが選択されている場合
    if (!empty($tag) && $tag !== 'all') {
        $args['tag'] = $tag;
    }

    // クエリを実行してニュース投稿を取得
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ob_start(); // 出力をバッファリング

        // 投稿リストを出力
        echo '<ul class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-9 gap-y-15">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
            <li class="post-item <?php echo $is_new; ?>">
                <a href="<?php the_permalink(); ?>" class="block-link">
                    <div class="w-full overflow-hidden rounded-[10px] lg:rounded-[20px] aspect-[1/1]">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="img"><?php the_post_thumbnail('medium'); ?></div>
                        <?php else : ?>
                            <div class="img"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/image/no-image.webp'); ?>" alt="No Image"></div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 lg:mt-5 px-[5%] lg:px-0 flex flex-col items-baseline gap-1 lg:gap-2">
                        <!-- <h2 class="text-[14px] lg:text-base"><?php the_title(); ?></h2> -->
                         <?php get_template_part('template-parts/badge-new'); ?>
                        <div class="text-sm lg:text-base leading-[1.4]"><?php the_title(); ?></div>
                        <div class="flex text-[10px] lg:text-xs leading-[1.3]">
                            <p class="post-date" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></p>
                            <?php
                            $post_tags = get_the_tags();
                            if ($post_tags) : ?>
                                <p class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9] border-solid>
                                    <?php foreach ($post_tags as $tag) {
                                        echo '<span class="tag">' . esc_html($tag->name) . '</span>';
                                    } ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';

        // ページネーション
        echo '<div class="post-pagination-wrapper mt-[97px] flex justify-end" aria-label="お知らせ">';
        $paged = max(1, intval($_POST['paged']));

        $prev_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 6H2" stroke="#333" stroke-width="2"/>
        <path d="M7 11L2 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        $next_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6H12" stroke="#333" stroke-width="2"/>
        <path d="M7 11L12 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => $prev_icon,
            'next_text' => $next_icon
        ));

        echo '</div>';

        wp_reset_postdata();

        $response = array(
            'success' => true,
            'content' => ob_get_clean(),
        );

    } else {
        $response = array(
            'success' => false,
            'content' => '<p>投稿が見つかりませんでした。</p>',
        );
    }

    wp_send_json($response);
}
add_action('wp_ajax_filter_news', 'filter_news');
add_action('wp_ajax_nopriv_filter_news', 'filter_news');


// =================================================================
//     works-page Ajax
// =================================================================

function filter_works() {
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // 投稿クエリの引数を設定
    $args = array(
        'category_name' => 'works', // カテゴリフィルターを追加
        'post_type' => 'post',
        'posts_per_page' => 8,  // 表示する投稿数
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // タグフィルタが選択されている場合
    if (!empty($tag) && $tag !== 'all') {
        $args['tag'] = $tag;
    }

    // クエリを実行してニュース投稿を取得
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ob_start(); // 出力をバッファリング

        // 投稿リストを出力
        echo '<ul class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-9 gap-y-15">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>

            <li class="post-item <?php echo $is_new; ?>">
                <a href="<?php the_permalink(); ?>" class="block-link">
                    <div class="w-full overflow-hidden rounded-[10px] lg:rounded-[20px] aspect-[1/1]">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="img"><?php the_post_thumbnail('medium'); ?></div>
                        <?php else : ?>
                            <div class="img"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/image/no-image.webp'); ?>" alt="No Image"></div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 lg:mt-5 px-[5%] lg:px-0 flex flex-col items-baseline gap-1 lg:gap-2">
                        <?php get_template_part('template-parts/badge-new'); ?>
                        <h2 class="text-sm lg:text-base leading-[1.4]">
                            <?php the_title(); ?>
                        </h2>
                        <div class="flex items-baseline text-xs lg:text-sm leading-[1.3]">
                            <?php the_field('works-shop-name'); ?>
                        </div>
                        <div class="flex text-[10px] lg:text-xs leading-[1.3]">
                            <p class="post-date" style="font-family: 'Open Sans', sans-serif;"><?php the_field('works-year'); ?></p>
                            <?php
                            $post_tags = get_the_tags();
                            if ($post_tags) : ?>
                                <p class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9] border-solid">
                                    <?php foreach ($post_tags as $tag) {
                                        echo '<span class="tag">' . esc_html($tag->name) . '</span>';
                                    } ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';

        // ページネーション
        echo '<div class="post-pagination-wrapper" aria-label="お仕事">';
        $paged = max(1, intval($_POST['paged']));

        $prev_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 6H2" stroke="#333" stroke-width="2"/>
        <path d="M7 11L2 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        $next_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6H12" stroke="#333" stroke-width="2"/>
        <path d="M7 11L12 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => $prev_icon,
            'next_text' => $next_icon
        ));

        echo '</div>';

        wp_reset_postdata();

        $response = array(
            'success' => true,
            'content' => ob_get_clean(),
        );

    } else {
        $response = array(
            'success' => false,
            'content' => '<p>投稿が見つかりませんでした。</p>',
        );
    }

    wp_send_json($response);
}
add_action('wp_ajax_filter_works', 'filter_works');
add_action('wp_ajax_nopriv_filter_works', 'filter_works');


// =================================================================
//     goods-page Ajax
// =================================================================

function filter_goods() {
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // 投稿クエリの引数を設定
    $args = array(
        'category_name' => 'goods', // カテゴリフィルターを追加
        'post_type' => 'post',
        'posts_per_page' => 12,  // 表示する投稿数
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // タグフィルタが選択されている場合
    if (!empty($tag) && $tag !== 'all') {
        $args['tag'] = $tag;
    }

    // クエリを実行してニュース投稿を取得
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ob_start(); // 出力をバッファリング

        // 投稿リストを出力
        echo '<ul class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-[36px] gap-y-[60px]">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
            <?php
            // 繰り返しフィールド（goods-image）から最初の画像を取得
            $goods_images = SCF::get('goods_image', $post->ID);
            ?>


            <li class="post-item <?php echo $is_new; ?>" data-tag="<?php echo esc_attr($tag_classes_string); ?>">
                <a href="<?php the_permalink(); ?>" class="block-link">
                    <!-- <div class="post-thumbnail overflow-hidden lg:rounded-[20px] aspect-square">

                    </div> -->
                    <div class="w-full overflow-hidden rounded-[10px] lg:rounded-[20px] aspect-[1/1]">
                        <?php
                            // 'goods-img' メタフィールドから画像のIDを取得
                            $img = get_post_meta($post->ID, 'goods-img', true);

                            // 画像がある場合はそのURLを取得、ない場合はデフォルトの画像を使用
                            $img_url = !empty($img) ? wp_get_attachment_url($img) : get_template_directory_uri() . '/assets/image/no-image.webp';
                        ?>
                        <img src="<?php echo esc_url($img_url); ?>" alt="商品画像" class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105">
                    </div>
                    <!-- <div class="mt-2 lg:mt-[5%] mx-[10px] lg:mx-0">
                        <h2 class="text-[14px] lg:text-base"><?php the_title(); ?></h2>
                        <div class="mt-1 lg:mt-2 flex items-baseline font-medium text-[12px] lg:text-base">
                            <div>¥</div>
                            <div class="price" style="font-family: 'Open Sans', sans-serif;"><?php the_field('goods-price'); ?></div>
                            <div class="text-[10px] lg:text-[12px] font-normal" style="letter-spacing:0.05em; line-height:1.5;">（税込）</div>
                        </div>
                        <div class="meta-info mt-1 lg:mt-2 text-[10px] lg:text-xs">
                            <?php
                            $post_tags = get_the_tags();
                            if ($post_tags) : ?>
                                <p class="post-tags gap-2">
                                    <?php foreach ($post_tags as $tag) {
                                        echo '<span class="tag">' . esc_html($tag->name) . '</span>';
                                    } ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div> -->
                    <div class="mt-4 lg:mt-5 px-[5%] lg:px-0 flex flex-col items-baseline gap-1 lg:gap-2">
                        <?php get_template_part('template-parts/badge-new'); ?>
                        <div class="text-sm lg:text-base leading-[1.4]">
                            <?php the_field('goods-name'); ?>
                        </div>
                        <div class="flex items-baseline text-xs lg:text-sm leading-[1.3]">
                            <div>¥</div>
                            <div class="price " style="font-family: 'Open Sans', sans-serif;"><?php the_field('goods-price'); ?></div>
                            <div class="text-[10px] lg:text-xs tracking-[0.05em]">（税込）</div>
                        </div>
                        <div class="flex text-[10px] lg:text-xs leading-[1.3]">
                            <?php
                            $tags = get_the_tags();
                            foreach ( $tags as $tag ) {
                                echo '<span>' . $tag->name . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';

        // ページネーション
        echo '<div class="post-pagination-wrapper mt-[60px] lg:mt-[100px] flex justify-end" aria-label="グッズ">';
        $paged = max(1, intval($_POST['paged']));

        $prev_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 6H2" stroke="#333" stroke-width="2"/>
        <path d="M7 11L2 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        $next_icon = '<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6H12" stroke="#333" stroke-width="2"/>
        <path d="M7 11L12 6L7 1" stroke="#333" stroke-width="2"/>
        </svg>';

        echo paginate_links(array(
            'total' => $the_query->max_num_pages,
            'current' => $paged,
            'format' => '?paged=%#%',
            'prev_text' => $prev_icon,
            'next_text' => $next_icon
        ));

        echo '</div>';

        wp_reset_postdata();

        $response = array(
            'success' => true,
            'content' => ob_get_clean(),
        );

    } else {
        $response = array(
            'success' => false,
            'content' => '<p>投稿が見つかりませんでした。</p>',
        );
    }

    wp_send_json($response);
}
add_action('wp_ajax_filter_goods', 'filter_goods');
add_action('wp_ajax_nopriv_filter_goods', 'filter_goods');


// =================================================================
//     Ajax
// =================================================================

function enqueue_my_ajax_script() {
    wp_enqueue_script('my-main-script', get_template_directory_uri() . '/assets/js/main.js', array(), null, true);

    // Ajax URLを渡す
    wp_localize_script('my-main-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_my_ajax_script');

// =================================================================
//     single-page クラス名「new-post」
// =================================================================

function add_new_post_class_to_body($classes) {
    if (is_single()) {
        $post_date = get_the_date('Y-m-d');
        $one_month_ago = date('Y-m-d', strtotime('-1 month'));

        if (strtotime($post_date) >= strtotime($one_month_ago)) {
            $classes[] = 'new-post';
        }
    }
    return $classes;
}
add_filter('body_class', 'add_new_post_class_to_body');

// =================================================================
//     アイキャッチの設定を有効化
// =================================================================

function setup_theme() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'setup_theme');


// =================================================================
//     コンタクトフォーム7 自動pタグ無効
// =================================================================

add_filter('wpcf7_autop_or_not', 'wpcf7_autop_return_false');
function wpcf7_autop_return_false() {
  return false;
}

// =================================================================
//     コンタクトフォーム7のモーダル
// =================================================================

// Contact Form 7 のフォームでショートコードを有効にする
add_filter('wpcf7_form_elements', function($content) {
  $content = do_shortcode($content);
  return $content;
});

// ショートコードで 'contact-design-works.php' の内容を読み込む関数
function display_contact_design_works() {
  ob_start();
  get_template_part('contact-design-works'); // 'contact-design-works.php' を読み込む
  return ob_get_clean();
}

// ショートコードを登録
add_shortcode('contact_design_works', 'display_contact_design_works');


// =================================================================
//     フォントの読み込み
// =================================================================
function my_script_init() {
    // Google Fonts
    wp_enqueue_style('google-fonts1', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap', '', 'all');
    wp_enqueue_style('google-fonts2', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap', '', 'all');
    wp_enqueue_style('google-fonts3', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap', '', 'all');

    // メインのCSSファイル
    wp_enqueue_style(
        'theme-style',
        get_template_directory_uri() . '/assets/css/style.css',
        array(),
        filemtime(get_theme_file_path('/assets/css/style.css')),
        'all'
    );

    // GSAPファイル
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '', true);

    // イベントページ以外で Swiper を読み込む
    if (!is_singular('event')) {  // 'event' 投稿タイプのページではない場合に読み込む
        // SwiperのCSSとJS
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css', array(), '', 'all');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js', array(), '', true);
    }

    // barba.jsのJavaScriptファイル
    wp_enqueue_script('barba', 'https://unpkg.com/@barba/core@2.9.7/dist/barba.umd.js', array(), '', true);

    // JavaScriptファイル
    wp_enqueue_script('my-main-script', get_template_directory_uri() . '/assets/js/main.js', array('swiper', 'barba', 'gsap'), filemtime(get_theme_file_path('/assets/js/main.js')), true);

    // Ajax URLを渡す
    wp_localize_script('my-main-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

}
add_action('wp_enqueue_scripts', 'my_script_init');

?>
