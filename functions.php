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
//     汎用的なAjaxフィルター関数（works と goods などで使用）
// =================================================================

function filter_posts_by_custom_type_and_taxonomy() {
    $post_type_slug = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
    $tag_slug       = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $query_taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
    $paged          = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // 無効な投稿タイプやタクソノミーが指定された場合はエラーで終了
    if (empty($post_type_slug) || empty($query_taxonomy)) {
        wp_send_json_error( 'Missing post type or taxonomy for generic filter.' );
        wp_die();
    }

    $args = array(
        'post_type'      => $post_type_slug,
        'posts_per_page' => 8, // 必要に応じて調整
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array(),
    );

    // タグ指定 ('すべて' 以外)
    if (!empty($tag_slug) && $tag_slug !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => $query_taxonomy,
            'field'    => 'slug',
            'terms'    => $tag_slug,
        );
    }

    $the_query = new WP_Query($args);
    $response = array(); // JSONで返すデータを格納する配列

    if ($the_query->have_posts()) {
        ob_start(); // 出力バッファリングを開始

        echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $tag_terms = wp_get_post_terms(get_the_ID(), $query_taxonomy, ['fields' => 'slugs']);
            $tag_slugs_str = implode(' ', $tag_terms); // スペース区切りに変更（data-tags用）

            get_template_part('templates/parts/image-with-text', null, array(
                'rounded'    => 'lg:rounded-[20px]',
                'mt_below'   => 'mt-2 lg:mt-5',
                'data_tags'  => esc_attr($tag_slugs_str),
                'post_type'  => $post_type_slug
            ));
        }
        echo '</div>';
        wp_reset_postdata();
        $response['posts_html'] = ob_get_clean(); // バッファの内容を取得し、JSONデータとして格納

        // ページネーションHTMLを生成
        $pagination_html = '';
        $big = 999999999; // need an unlikely integer
        // ★修正箇所★
        // 該当する投稿タイプのアーカイブページのURLを base に設定
        // 例: 'works' の場合は get_post_type_archive_link('works')
        // これにより、リンクは /works/?paged=2 のような形式になります
        $archive_base_url = get_post_type_archive_link($post_type_slug);
        if (!$archive_base_url) {
            // アーカイブが存在しない場合や取得できない場合のフォールバック
            $archive_base_url = home_url('/' . $post_type_slug . '/');
        }
        $paginate_links = paginate_links(array(
            'base'    => add_query_arg('paged', '%#%', $archive_base_url), // ここを修正
            'format'  => '?paged=%#%', // このままでOK (JavaScriptがクエリパラメータを見るため)
            'current' => max(1, $paged),
            'total'   => $the_query->max_num_pages,
            'prev_text' => '&lt;',
            'next_text' => '&gt;',
            'type'    => 'array',
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 1,
        ));

        if ($paginate_links) {
            $pagination_html .= '<ul class="pagination-list flex flex-wrap justify-center gap-2 mt-10 lg:mt-15">';
            foreach ($paginate_links as $link) {
                // 'current' クラスはWordPressが自動でつけてくれるので、TailwindCSSのis-activeクラスに変換
                $link = str_replace('page-numbers current', 'page-numbers is-active', $link);
                $pagination_html .= '<li class="pagination-item">' . $link . '</li>';
            }
            $pagination_html .= '</ul>';
        }

        $response['pagination_html'] = $pagination_html;
        $response['max_pages'] = $the_query->max_num_pages;

        wp_send_json_success($response); // JSON形式で成功応答を返す

    } else {
        $response['posts_html'] = '<p class="mt-10 text-center text-sm text-gray-500">投稿が見つかりませんでした。</p>';
        $response['pagination_html'] = '';
        $response['max_pages'] = 0;
        wp_send_json_success($response); // JSON形式で成功応答を返す
    }

    wp_die(); // これがないとWordPress全体が出力される
}
add_action('wp_ajax_filter_posts_by_custom_type_and_taxonomy', 'filter_posts_by_custom_type_and_taxonomy');
add_action('wp_ajax_nopriv_filter_posts_by_custom_type_and_taxonomy', 'filter_posts_by_custom_type_and_taxonomy');


// =================================================================
// イベント専用のAjaxフィルター関数（archive-events.php で使用）
// =================================================================

function get_event_months_for_archive($start_date_str, $end_date_str) {
    $months = [];
    if (empty($start_date_str) || empty($end_date_str)) {
        return $months;
    }
    try {
        $start = new DateTime($start_date_str);
        $end = new DateTime($end_date_str);
    } catch (Exception $e) {
        error_log("Date parsing error in get_event_months_for_archive: " . $e->getMessage());
        return $months;
    }
    $end->modify('first day of next month');
    while ($start < $end) {
        $months[] = $start->format('Y-m');
        $start->modify('first day of next month');
    }
    return array_unique($months);
}


function filter_events_by_month_and_tag() {
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $month = isset($_POST['month']) ? sanitize_text_field($_POST['month']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $query_post_type = 'events';
    $query_taxonomy  = 'event_type';

    // --- デバッグログの開始 ---
    error_log('--- DEBUG: filter_events_by_month_and_tag called ---');
    error_log('Received POST data: ' . print_r($_POST, true)); // 受け取ったPOSTデータを確認
    // --- デバッグログの終了 ---

    $args = array(
        'post_type'      => $query_post_type,
        'posts_per_page' => 8,
        'paged'          => $paged,
        'orderby'        => 'meta_value',
        'meta_key'       => 'event-start',
        'order'          => 'ASC',
        'tax_query'      => array(),
        'meta_query'     => array(),
    );

    if (!empty($tag) && $tag !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => $query_taxonomy,
            'field'    => 'slug',
            'terms'    => $tag,
        );
    }

    if (!empty($month) && $month !== 'all') {
        $year = substr($month, 0, 4);
        $mon = substr($month, 5, 2);
        $last_day_of_month = date('t', strtotime("$year-$mon-01")); // 例: 31 for March

        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key'     => 'event-start',
                'value'   => $year . $mon . date('t', strtotime("$year-$mon-01")),
                'compare' => '<=',
                'type'    => 'DATE',
            ),
            array(
                'key'     => 'event-end',
                'value'   => $year . $mon . '01',
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        );
    }

    // --- デバッグログの開始 ---
    error_log('DEBUG: Final WP_Query Args for Events: ' . print_r($args, true)); // 最終的な$argsを確認
    $the_query = new WP_Query($args);
    error_log('DEBUG: Events WP_Query Found Posts: ' . $the_query->found_posts); // 見つかった投稿数を確認
    // --- デバッグログの終了 ---
    $response = array(); // JSONで返すデータを格納する配列

    if ($the_query->have_posts()) {
        ob_start(); // 出力バッファリングを開始
        $template_slug_base = 'filtered-items-event';

        while ($the_query->have_posts()) {
            $the_query->the_post();

            $tag_terms = wp_get_post_terms(get_the_ID(), 'event_type', ['fields' => 'slugs']);
            $tag_slugs_str = implode(',', $tag_terms);

            $start_date_field = get_field('event-start', get_the_ID());
            $end_date_field   = get_field('event-end', get_the_ID());
            $event_months = get_event_months_for_archive($start_date_field, $end_date_field);
            $months_str = implode(',', $event_months);

            get_template_part(
                'templates/parts/' . $template_slug_base,
                null,
                [
                    'months_str' => esc_attr($months_str),
                    'tag_slugs'  => esc_attr($tag_slugs_str),
                ]
            );
        }
        wp_reset_postdata();
        $response['posts_html'] = ob_get_clean(); // バッファの内容を取得し、JSONデータとして格納

        // ページネーションHTMLを生成
        $pagination_html = '';
        $big = 999999999;
        // ★修正箇所★
        // 'events' のアーカイブページのURLを base に設定
        $archive_base_url = get_post_type_archive_link('events');
        if (!$archive_base_url) {
            $archive_base_url = home_url('/events/');
        }

        $paginate_links = paginate_links(array(
            'base'    => add_query_arg('paged', '%#%', $archive_base_url), // ここを修正
            'format'  => '?paged=%#%', // このままでOK
            'current' => max(1, $paged),
            'total'   => $the_query->max_num_pages,
            'prev_text' => '&lt;',
            'next_text' => '&gt;',
            'type'    => 'array',
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 1,
        ));

        if ($paginate_links) {
            $pagination_html .= '<ul class="pagination-list flex flex-wrap justify-center gap-2 mt-10 lg:mt-15">';
            foreach ($paginate_links as $link) {
                $link = str_replace('page-numbers current', 'page-numbers is-active', $link);
                $pagination_html .= '<li class="pagination-item">' . $link . '</li>';
            }
            $pagination_html .= '</ul>';
        }

        $response['pagination_html'] = $pagination_html;
        $response['max_pages'] = $the_query->max_num_pages;

        wp_send_json_success($response); // JSON形式で成功応答を返す

    } else {
        $response['posts_html'] = '<p class="text-sm lg:text-base leading-[1.4] px-3 lg:px-4  -pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">表示可能なイベントが見つかりませんでした。</p>';
        $response['pagination_html'] = '';
        $response['max_pages'] = 0;
        wp_send_json_success($response); // JSON形式で成功応答を返す
    }

    wp_die();
}
add_action('wp_ajax_filter_events_by_month_and_tag', 'filter_events_by_month_and_tag');
add_action('wp_ajax_nopriv_filter_events_by_month_and_tag', 'filter_events_by_month_and_tag');

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

// ショートコードで 'designer-list.php' の内容を読み込む関数
function display_designer_list() {
  ob_start();
  get_template_part('templates/contact/designer-list');
  return ob_get_clean();
}
add_shortcode('designer-list', 'display_designer_list');


// =================================================================
//  すべてのスクリプトとスタイルのエンキューをこの関数にまとめる
// =================================================================
function my_script_init() {
    // Google Fonts (変更なし)
    wp_enqueue_style('google-fonts1', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap', '', 'all');
    wp_enqueue_style('google-fonts2', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap', '', 'all');
    wp_enqueue_style('google-fonts3', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap', '', 'all');

    // メインのCSSファイル (変更なし)
    wp_enqueue_style(
        'theme-style',
        get_template_directory_uri() . '/assets/css/style.css',
        array(),
        filemtime(get_theme_file_path('/assets/css/style.css')),
        'all'
    );

    // Swiper (変更なし)
    if (!is_singular('event')) {
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css', array(), '', 'all');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js', array(), '', true);
    }

    // barba.js (変更なし)
    wp_enqueue_script('barba', 'https://unpkg.com/@barba/core@2.9.7/dist/barba.umd.js', array(), '', true);

    // filter.js を main.js の前に、かつ ajax_object が必要なのでここでエンキュー
    wp_enqueue_script(
        'my-filter-script',
        get_template_directory_uri() . '/assets/js/filter.js',
        array(), // 依存スクリプトがなければ空
        filemtime(get_theme_file_path('/assets/js/filter.js')),
        true
    );
    wp_localize_script('my-filter-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    // main.js は filter.js が読み込まれた後に実行されるように依存関係を設定
    // Swiperはイベントページ以外でしか読み込まれないが、依存関係に入れても問題ない
    wp_enqueue_script(
        'my-main-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array('swiper', 'barba', 'my-filter-script'), // ここに 'my-filter-script' を追加
        // filemtime(get_theme_file_path('/assets/js/main.js')),
        time(), // ★★★ここを time() に変更★★★
        true
    );
}
add_action('wp_enqueue_scripts', 'my_script_init');

// enqueue_my_ajax_script 関数は不要になるので削除
// function enqueue_my_ajax_script() { ... }
// add_action('wp_enqueue_scripts', 'enqueue_my_ajax_script');

// =================================================================
//     single-page クラス名「new-post」
// =================================================================

// function add_new_post_class_to_body($classes) {
//   if (is_single()) {
//       $post_date = get_the_date('Y-m-d');
//       $one_month_ago = date('Y-m-d', strtotime('-1 month'));

//       if (strtotime($post_date) >= strtotime($one_month_ago)) {
//           $classes[] = 'new-post';
//       }
//   }
//   return $classes;
// }
// add_filter('body_class', 'add_new_post_class_to_body');

// =================================================================
//  body タグにカスタム投稿タイプを示す data-post-type 属性を追加
// =================================================================
function add_custom_data_attributes_to_body( $classes ) {
    if ( is_post_type_archive() ) {
        $post_type = get_query_var( 'post_type' );
        if ( $post_type ) {
            $classes[] = 'data-post-type="' . esc_attr( $post_type ) . '"';
        }
    } elseif ( is_singular() ) {
        $post_type = get_post_type();
        if ( $post_type ) {
            $classes[] = 'data-post-type="' . esc_attr( $post_type ) . '"';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'add_custom_data_attributes_to_body' );


// =================================================================
// お知らせ（デフォルト投稿タイプ）からカテゴリーとタグを削除
// =================================================================
function remove_default_post_taxonomies() {
    // カテゴリーを削除
    unregister_taxonomy_for_object_type('category', 'post');
    // タグを削除
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'remove_default_post_taxonomies');


// =================================================================
// カスタム投稿タイプとタクソノミーの追加
// =================================================================
function teraokanatsumi_custom_post_types() {
    // グッズ
    register_post_type('goods', [
        'label' => 'グッズ',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => [
            'slug' => 'goods',
            'with_front' => false,
        ],
        'show_in_rest' => true,
        'taxonomies' => ['goods_category', 'store'], // 'pickup' を削除
        'labels' => [
            'name' => 'グッズ',
            'singular_name' => 'グッズ',
            'add_new_item' => '新しいグッズを追加',
            'edit_item' => 'グッズを編集',
            'view_item' => 'グッズを表示',
            'all_items' => 'すべてのグッズ',
        ],
    ]);

    // イベント
    register_post_type('events', [
        'label' => 'イベント',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => [
            'slug' => 'events',
            'with_front' => false,
        ],
        'show_in_rest' => true,
        'taxonomies' => ['event_type', 'event_venue'],
        'labels' => [
            'name' => 'イベント',
            'singular_name' => 'イベント',
            'add_new_item' => '新しいイベントを追加',
            'edit_item' => 'イベントを編集',
            'view_item' => 'イベントを表示',
            'all_items' => 'すべてのイベント',
        ],
    ]);

    // お仕事
    register_post_type('works', [
        'label' => 'お仕事',
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'rewrite' => [
            'slug' => 'works',
            'with_front' => false,
        ],
        'show_in_rest' => true,
        'taxonomies' => ['works_category', 'works_client', 'works_year'],
        'labels' => [
            'name' => 'お仕事',
            'singular_name' => 'お仕事',
            'add_new_item' => '新しいお仕事を追加',
            'edit_item' => 'お仕事を編集',
            'view_item' => 'お仕事を表示',
            'all_items' => 'すべてのお仕事',
        ],
    ]);

    // グッズ：カテゴリー
    register_taxonomy('goods_category', ['goods'], [
      'label' => 'グッズカテゴリー',
      'hierarchical' => true,
      'public' => true,
      'rewrite' => ['slug' => 'goods-category'],
      'show_in_rest' => true,
    ]);

    // グッズ：取扱店舗
    register_taxonomy('store', ['goods'], [
      'label' => '取扱店舗',
      'hierarchical' => true,
      'public' => true,
      'rewrite' => ['slug' => 'store'],
      'show_in_rest' => true,
    ]);

    // イベント：種類
    register_taxonomy('event_type', ['events'], [
      'label' => 'イベントの種類',
      'hierarchical' => true,
      'public' => true,
      'rewrite' => ['slug' => 'event-type'],
      'show_in_rest' => true,
    ]);

    // イベント：会場
    register_taxonomy('event_venue', ['events'], [
        'label' => 'イベント会場',
        'hierarchical' => true,
        'public' => true,
        'rewrite' => ['slug' => 'event-venue'],
        'show_in_rest' => true,
    ]);

    // お仕事：カテゴリー
    register_taxonomy('works_category', ['works'], [
      'label' => 'お仕事カテゴリー',
      'hierarchical' => true,
      'public' => true,
      'rewrite' => ['slug' => 'works-category'],
      'show_in_rest' => true,
    ]);

    // お仕事：クライアント名
    register_taxonomy('works_client', ['works'], [
        'label' => 'クライアント名',
        'hierarchical' => true, // 必要に応じてfalseにすることも可能ですが、階層なしでもTrueで問題ありません
        'public' => true,
        'rewrite' => ['slug' => 'works-client'],
        'show_in_rest' => true,
    ]);

    // お仕事：仕事をした年
    register_taxonomy('works_year', ['works'], [
        'label' => '仕事をした年',
        'hierarchical' => false, // 年は階層にする必要がないのでfalseが適切です
        'public' => true,
        'rewrite' => ['slug' => 'works-year'],
        'show_in_rest' => true,
    ]);

    }
    add_action('init', 'teraokanatsumi_custom_post_types');


// =================================================================
// カスタムカラムの追加
// =================================================================

function teraokanatsumi_add_custom_columns($columns) {
    global $post_type;

    $new_columns = [];
    foreach ($columns as $key => $title) {
        if ($key === 'date') { // '日付' カラムの前にカスタムカラムを追加
            if ($post_type === 'goods') {
                $new_columns['goods_category'] = 'グッズカテゴリー';
                $new_columns['store'] = '取扱店舗';
                $new_columns['pickup'] = 'ピックアップ'; // goods用のピックアップ
            } elseif ($post_type === 'events') {
                $new_columns['event_type'] = 'イベントの種類';
                $new_columns['event_venue'] = '会場'; // ★追加
                $new_columns['pickup'] = 'ピックアップ'; // events用のピックアップ
            } elseif ($post_type === 'works') {
                $new_columns['works_category'] = 'お仕事カテゴリー';
                $new_columns['works_client'] = 'クライアント名';  // ★追加
                $new_columns['works_year'] = '仕事をした年'; // ★追加
                $new_columns['pickup'] = 'ピックアップ'; // works用のピックアップ
            }
        }
        $new_columns[$key] = $title;
    }
    return $new_columns;
}
add_filter('manage_goods_posts_columns', 'teraokanatsumi_add_custom_columns');
add_filter('manage_events_posts_columns', 'teraokanatsumi_add_custom_columns');
add_filter('manage_works_posts_columns', 'teraokanatsumi_add_custom_columns');


// =================================================================
// カスタムカラムの内容を表示
// =================================================================

function teraokanatsumi_custom_column_content($column, $post_id) {
    $post_type = get_post_type($post_id);

    // タクソノミーを表示する共通処理
    $taxonomies_to_display = [];
    if ($post_type === 'goods') {
        $taxonomies_to_display = [
            'goods_category' => 'goods_category',
            'store' => 'store',
        ];
    } elseif ($post_type === 'events') {
        $taxonomies_to_display = [
            'event_type' => 'event_type',
            'event_venue' => 'event_venue',
        ];
    } elseif ($post_type === 'works') {
        $taxonomies_to_display = [
            'works_category' => 'works_category',
            'works_client' => 'works_client',
            'works_year' => 'works_year',
        ];
    }

    if (isset($taxonomies_to_display[$column])) {
        $terms = get_the_terms($post_id, $taxonomies_to_display[$column]);
        if (!empty($terms) && !is_wp_error($terms)) {
            $output = [];
            foreach ($terms as $term) {
                $output[] = esc_html($term->name);
            }
            echo implode(', ', $output);
        } else {
            echo '—';
        }
    }

    // 各投稿タイプの pickup カスタムフィールドの表示
    if ($column === 'pickup') { // カラム名が 'pickup' の場合
        $field_name = ''; // 各投稿タイプで使うフィールド名を初期化

        if ($post_type === 'goods') {
            $field_name = 'is_pickup_goods'; // ACFで設定したgoods用のフィールド名
        } elseif ($post_type === 'events') {
            $field_name = 'is_pickup_events'; // ACFで設定したevents用のフィールド名
        } elseif ($post_type === 'works') {
            $field_name = 'is_pickup_works'; // ACFで設定したworks用のフィールド名
        }

        // 方法2のように共通のフィールド名を使っている場合
        // if ($post_type === 'goods' || $post_type === 'events' || $post_type === 'works') {
        //     $field_name = 'is_pickup'; // 共通のフィールド名
        // }

        if (!empty($field_name)) {
            $is_pickup = get_field($field_name, $post_id);
            if ($is_pickup) {
                echo '<span class="dashicons dashicons-yes"></span>';
            } else {
                echo '—';
            }
        } else {
            echo '—'; // フィールド名が設定されていない場合
        }
    }
}
add_action('manage_posts_custom_column', 'teraokanatsumi_custom_column_content', 10, 2);
add_action('manage_pages_custom_column', 'teraokanatsumi_custom_column_content', 10, 2);


// =================================================================
// カスタムカラムをソート可能にする（オプション）
// =================================================================

function teraokanatsumi_sortable_columns($columns) {
    global $post_type;

    if ($post_type === 'goods') {
        $columns['goods_category'] = 'goods_category';
        $columns['store'] = 'store';
        $columns['pickup'] = 'is_pickup_goods'; // goods用のフィールド名
    } elseif ($post_type === 'events') {
        $columns['event_type'] = 'event_type';
        $columns['event_venue'] = 'event_venue'; // ★追加
        $columns['pickup'] = 'is_pickup_events'; // events用のフィールド名
    } elseif ($post_type === 'works') {
        $columns['works_category'] = 'works_category';
        $columns['works_client'] = 'works_client';  // ★追加
        $columns['works_year'] = 'works_year';  // ★追加
        $columns['pickup'] = 'is_pickup_works'; // works用のフィールド名
    }

    return $columns;
}
add_filter('manage_edit-goods_sortable_columns', 'teraokanatsumi_sortable_columns');
add_filter('manage_edit-events_sortable_columns', 'teraokanatsumi_sortable_columns');
add_filter('manage_edit-works_sortable_columns', 'teraokanatsumi_sortable_columns');


// =================================================================
// ピックアップカスタムフィールドでソートするためのクエリ修正（オプション）
// =================================================================

function teraokanatsumi_orderby_pickup_column( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) { // is_main_query() を追加
        return;
    }

    $orderby = $query->get( 'orderby' );
    $post_type = $query->get( 'post_type' );

    // 各投稿タイプに対応するピックアップのフィールド名を指定
    $pickup_field_map = [
        'goods'  => 'is_pickup_goods',
        'events' => 'is_pickup_events',
        'works'  => 'is_pickup_works',
    ];

    // ピックアップカスタムフィールドでのソート
    if (isset($pickup_field_map[$post_type]) && $orderby === $pickup_field_map[$post_type]) {
        $query->set( 'meta_key', $pickup_field_map[$post_type] );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'DESC' );
    }

    // 「仕事をした年」タクソノミーでのソート ★追加
    if ( 'works' === $post_type && 'works_year' === $orderby ) {
        $query->set( 'orderby', 'terms_order' ); // タクソノミーのソートにはこれを使います
        $query->set( 'order', 'DESC' ); // 年は新しいものが上に来るように降順が一般的です
        $query->set( 'taxonomy', 'works_year' ); // ソート対象のタクソノミーを指定
        $query->set( 'terms', '' ); // 特定のタームに限定しない
        $query->set( 'meta_query', array( // 数値としてソートするためにメタクエリも使用
            'relation' => 'OR',
            array(
                'key' => 'works_year', // 'works_year'タクソノミーに紐づくカスタムフィールドがない場合も考慮
                'compare' => 'EXISTS'
            ),
            array(
                'key' => 'works_year',
                'compare' => 'NOT EXISTS'
            )
        ));
    }

    // 「イベント会場」タクソノミーでのソート ★追加 (テキストソート)
    if ( 'events' === $post_type && 'event_venue' === $orderby ) {
        $query->set( 'orderby', 'name' ); // タクソノミー名でソート
        $query->set( 'order', 'ASC' ); // 会場名なら昇順が一般的
        $query->set( 'taxonomy', 'event_venue' );
        $query->set( 'terms', '' );
    }
}
add_action( 'pre_get_posts', 'teraokanatsumi_orderby_pickup_column', 1 ); // 優先度を高く設定

// =================================================================
//  カスタムクエリ変数 'news_page' を追加
// =================================================================
function add_custom_query_vars( $vars ) {
    $vars[] = 'news_page'; // 例: 'news_page'
    return $vars;
}
add_filter( 'query_vars', 'add_custom_query_vars' );

// Paged 変数のリセット（オプション、衝突回避のため）
// これをしないと、query_varsにpagedも含まれるため、
// main queryでpagedが使われている場合に影響を受ける可能性があります。
// ただし、通常は'news_page'があればそちらが優先されるはずです。
// function custom_paged_rewrite_rules() {
//     add_rewrite_rule(
//         'news/page/([0-9]{1,})/?$',
//         'index.php?pagename=your-news-page-slug&news_page=$matches[1]', // 'your-news-page-slug' はお知らせ一覧ページのパーマリンクスラッグに置き換えてください
//         'top'
//     );
// }
// add_action( 'init', 'custom_paged_rewrite_rules' );

// =================================================================
//  "goods" カスタム投稿タイプでGutenbergを無効にする
// =================================================================

function teraokanatsumi_disable_gutenberg_for_goods($can_edit, $post_type) {
  if ($post_type === 'goods') {
      return false; // Gutenbergを無効にする
  }
  return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'teraokanatsumi_disable_gutenberg_for_goods', 10, 2);

// =================================================================
//  Barba.js (Ajax) リクエスト時に、サイトルートURLにHomeページのコンテンツを強制的に返す
 // =================================================================
function handle_barba_home_ajax_request() {
    // Ajax リクエストであること、かつサイトのルートURLへのリクエストであることを確認
    // Barba.js v1 の Ajax リクエストを検出するために、X-Requested-With ヘッダーをチェック
    // より確実にするには、Barba.js の設定でカスタムヘッダー 'X-Barba-Request' を追加する
    // 例: barba.use(new Barba.Pjax(), new Barba.Prefetch(), { headers: { 'X-Barba-Request': 'true' } });
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // 現在のリクエストURIがサイトのルート（/）かどうかをチェック
        // Local環境の場合、$_SERVER['REQUEST_URI'] が '/' のみになることを確認
        if ( $_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php' ) {

            // フロントページとして設定されている固定ページのIDを取得
            $home_page_id = get_option('page_on_front');

            if ( $home_page_id ) {
                // Homeページの投稿オブジェクトを取得
                $post = get_post( $home_page_id );

                if ( $post ) {
                    // WordPressのメインクエリをHomeページに設定し直す（テンプレートが正しく解決されるように）
                    query_posts( array( 'page_id' => $home_page_id ) );

                    // 出力バッファリングを開始
                    ob_start();

                    // Homeページ用のテンプレートを読み込む
                    // (例: front-page.php, page.php, index.php の順で探される)
                    // Barba.js が期待するHTML構造 (barba-container など) を持つテンプレート
                    // 適切なテンプレートがテーマ内に存在することを確認してください
                    // get_template_part('templates/page', 'home'); // もし home 専用のテンプレートパートがあるなら
                    // または、WordPressの標準的なテンプレート階層に従ってpage.phpなどを読み込む
                    include( get_page_template() );


                    $html_content = ob_get_clean(); // バッファからHTMLコンテンツを取得

                    // 取得したHTMLコンテンツを出力し、それ以上のWordPress処理を停止
                    // これにより、余計な出力やリダイレクトを防ぐ
                    echo $html_content;
                    exit;
                }
            }
        }
    }
}
// 'template_redirect' アクションは、WordPressがどのテンプレートをロードするかを決定する直前に実行される
add_action( 'template_redirect', 'handle_barba_home_ajax_request' );

?>
