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
//     アーカイブページの表示記事を非同期で切り替え
// =================================================================

function filter_category_posts() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $month = isset($_POST['month']) ? sanitize_text_field($_POST['month']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 8,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // カテゴリ指定（デフォルト：event）
    if (!empty($category)) {
        $args['category_name'] = $category;
    } else {
        $args['category_name'] = 'event';
    }

    // タグ指定
    if (!empty($tag) && $tag !== 'all') {
        $args['tag'] = $tag;
    }

    // 月指定（例：202504）
    if (!empty($month) && $month !== 'all') {
        $year = substr($month, 0, 4);
        $mon = substr($month, 4, 2);

        $start_of_month = (int)($year . $mon . '01');
        $end_of_month = (int)($year . $mon . date('t', strtotime("$year-$mon-01")));

        $args['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key' => 'event-start',
                'value' => $end_of_month,
                'compare' => '<=',
                'type' => 'NUMERIC',
            ),
            array(
                'key' => 'event-end',
                'value' => $start_of_month,
                'compare' => '>=',
                'type' => 'NUMERIC',
            ),
        );
    }

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        ob_start();

        $has_event = false;
        foreach ($the_query->posts as $post) {
            $cats = get_the_category($post->ID);
            foreach ($cats as $cat) {
                if ($cat->slug === 'event') {
                    $has_event = true;
                    break 2;
                }
            }
        }

        if (!$has_event) {
            echo '<div class="grid grid-cols-2 lg:grid-cols-4 mt-5 lg:mt-15 gap-x-2 gap-y-6 lg:gap-x-9 lg:gap-y-15">';
        }

        while ($the_query->have_posts()) {
            $the_query->the_post();

            $cats = get_the_category();
            $template_slug = 'filtered-items';
            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    if ($cat->slug === 'event') {
                        $template_slug = 'filtered-items-event';
                        break;
                    }
                }
            }

            get_template_part('templates/category/' . $template_slug);
        }

        if (!$has_event) {
            echo '</div>';
        }

        wp_reset_postdata();
        echo ob_get_clean();
    } else {
        echo '<p class="text-sm lg:text-base leading-[1.4] px-3 lg:px-4  -pl-4 pb-4 lg:pb-6 border-b border-[#D9D9D9]">表示可能なイベントがありません</p>';
    }

    wp_die();
}
add_action('wp_ajax_filter_category_posts', 'filter_category_posts');
add_action('wp_ajax_nopriv_filter_category_posts', 'filter_category_posts');



// =================================================================
//     Ajax
// =================================================================

function enqueue_my_ajax_script() {
    wp_enqueue_script('my-filter-script', get_template_directory_uri() . '/assets/js/filter.js', array(), filemtime(get_template_directory() . '/assets/js/filter.js'), true);

    // Ajax URLを渡す
    wp_localize_script('my-filter-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_my_ajax_script');


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
// function display_contact_design_works() {
//   ob_start();
//   get_template_part('contact-design-works');
//   return ob_get_clean();
// }
// ショートコードで 'designer-list.php' の内容を読み込む関数
function display_designer_list() {
  ob_start();
  get_template_part('designer-list');
  return ob_get_clean();
}
add_shortcode('designer-list', 'display_designer_list');


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
    // wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '', true);

    // イベントページ以外で Swiper を読み込む
    if (!is_singular('event')) {  // 'event' 投稿タイプのページではない場合に読み込む
        // SwiperのCSSとJS
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css', array(), '', 'all');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js', array(), '', true);
    }

    // barba.jsのJavaScriptファイル
    wp_enqueue_script('barba', 'https://unpkg.com/@barba/core@2.9.7/dist/barba.umd.js', array(), '', true);

    // JavaScriptファイル
    wp_enqueue_script('my-main-script', get_template_directory_uri() . '/assets/js/main.js', array('swiper', 'barba'), filemtime(get_theme_file_path('/assets/js/main.js')), true);

    // Ajax URLを渡す
    // wp_localize_script('my-main-script', 'ajax_object', array(
    //     'ajax_url' => admin_url('admin-ajax.php'),
    // ));

}
add_action('wp_enqueue_scripts', 'my_script_init');


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
// カスタム投稿タイプとタグ機能の追加
// =================================================================

// カスタム投稿タイプとタクソノミーの追加
function teraokanatsumi_custom_post_types() {
  // グッズ
  register_post_type('goods', [
      'label' => 'グッズ',
      'public' => true,
      'has_archive' => true,
      'supports' => ['title', 'thumbnail', 'excerpt', 'custom-fields'],
      'rewrite' => ['slug' => 'goods'],
      'show_in_rest' => true,
      'taxonomies' => ['goods_tags', 'pickup'],
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
      'rewrite' => ['slug' => 'events'],
      'show_in_rest' => true,
      'taxonomies' => ['events_tags', 'pickup'],
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
      'rewrite' => ['slug' => 'works'],
      'show_in_rest' => true,
      'taxonomies' => ['works_tags', 'pickup'],
      'labels' => [
          'name' => 'お仕事',
          'singular_name' => 'お仕事',
          'add_new_item' => '新しいお仕事を追加',
          'edit_item' => 'お仕事を編集',
          'view_item' => 'お仕事を表示',
          'all_items' => 'すべてのお仕事',
      ],
  ]);

  // goods用タグ
  register_taxonomy('goods_tags', ['goods'], [
    'label' => 'タグ',
    'hierarchical' => true,
    'public' => true,
    'rewrite' => ['slug' => 'goods-tags'],
    'show_in_rest' => true,
  ]);

  // events用タグ
  register_taxonomy('events_tags', ['events'], [
    'label' => 'タグ',
    'hierarchical' => true,
    'public' => true,
    'rewrite' => ['slug' => 'events-tags'],
    'show_in_rest' => true,
  ]);

  // works用タグ
  register_taxonomy('works_tags', ['works'], [
    'label' => 'タグ',
    'hierarchical' => true,
    'public' => true,
    'rewrite' => ['slug' => 'works-tags'],
    'show_in_rest' => true,
  ]);

  // ピックアップ（チェックボックス）
  register_taxonomy('pickup', ['goods', 'events', 'works'], [
      'label' => 'ピックアップ',
      'hierarchical' => true,
      'public' => true,
      'rewrite' => ['slug' => 'pickup'],
      'show_in_rest' => true,
  ]);
}
add_action('init', 'teraokanatsumi_custom_post_types');


// =================================================================
// 管理画面の投稿一覧にタグとピックアップカラムを追加
// =================================================================

function teraokanatsumi_add_custom_columns($columns, $post_type) {
  $new_columns = [];
  foreach ($columns as $key => $title) {
      if ($key === 'date') {
          if ($post_type === 'goods') {
              $new_columns['goods_tags'] = 'タグ';
          } elseif ($post_type === 'events') {
              $new_columns['events_tags'] = 'タグ';
          } elseif ($post_type === 'works') {
              $new_columns['works_tags'] = 'タグ';
          }
          $new_columns['pickup'] = 'ピックアップ';
      }
      $new_columns[$key] = $title;
  }
  return $new_columns;
}
add_filter('manage_goods_posts_columns', function($columns){ return teraokanatsumi_add_custom_columns($columns, 'goods'); });
add_filter('manage_events_posts_columns', function($columns){ return teraokanatsumi_add_custom_columns($columns, 'events'); });
add_filter('manage_works_posts_columns', function($columns){ return teraokanatsumi_add_custom_columns($columns, 'works'); });


// =================================================================
// タグとピックアップカラムの内容を表示
// =================================================================

function teraokanatsumi_custom_column_content($column, $post_id) {
  $post_type = get_post_type($post_id);

  if ($post_type === 'goods' && $column === 'goods_tags') {
      $terms = get_the_terms($post_id, 'goods_tags');
  } elseif ($post_type === 'events' && $column === 'events_tags') {
      $terms = get_the_terms($post_id, 'events_tags');
  } elseif ($post_type === 'works' && $column === 'works_tags') {
      $terms = get_the_terms($post_id, 'works_tags');
  } elseif ($column === 'pickup') {
      $terms = get_the_terms($post_id, 'pickup');
  } else {
      $terms = false;
  }

  if (!empty($terms) && !is_wp_error($terms)) {
      $term_links = [];
      foreach ($terms as $term) {
          $term_links[] = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($term)), esc_html($term->name));
      }
      echo implode(', ', $term_links);
  } else {
      echo '—';
  }
}
add_action('manage_goods_posts_custom_column', 'teraokanatsumi_custom_column_content', 10, 2);
add_action('manage_events_posts_custom_column', 'teraokanatsumi_custom_column_content', 10, 2);
add_action('manage_works_posts_custom_column', 'teraokanatsumi_custom_column_content', 10, 2);


// =================================================================
// "goods" カスタム投稿タイプでGutenbergを無効にする
// =================================================================

function teraokanatsumi_disable_gutenberg_for_goods($can_edit, $post_type) {
  if ($post_type === 'goods') {
      return false; // Gutenbergを無効にする
  }
  return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'teraokanatsumi_disable_gutenberg_for_goods', 10, 2);


?>
