<?php
// templates/parts/related-posts.php

// 現在の投稿のIDと投稿タイプを取得
$current_post_id = get_the_ID();
$current_post_type = get_post_type($current_post_id);

$args = array(
    'post__not_in' => array($current_post_id), // 現在の投稿を除く
    'posts_per_page' => 4,
    'orderby' => 'date',
    'order' => 'DESC',
);

$is_event_post = false; // イベント投稿かどうかを判別するフラグ
$related_query = null; // WP_Query オブジェクトを初期化

// ★修正点1: イベント投稿の場合のロジック
if ($current_post_type === 'events') {
    $is_event_post = true;
    $args['post_type'] = 'events'; // イベント投稿タイプを指定

    // 同じ event_type タクソノミーのタームを取得
    $event_types = get_the_terms($current_post_id, 'event_type');
    if ($event_types && !is_wp_error($event_types)) {
        $event_type_slugs = wp_list_pluck($event_types, 'slug'); // スラッグの配列を取得
        if (!empty($event_type_slugs)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => $event_type_slugs,
                    'operator' => 'IN',
                ),
            );
        }
    }
} else {
    // ★修正点2: 通常投稿や他のカスタム投稿タイプの場合のロジック
    // goods や works の場合は、それぞれのタクソノミーを考慮することもできますが、
    // ここでは一旦、現在の投稿タイプに関連するカテゴリ/タグを取得
    $args['post_type'] = $current_post_type; // 現在の投稿タイプを使用

    // 通常投稿であればカテゴリ、worksやgoodsであればworks_tags/goods_tagsなど
    // ここではシンプルなカテゴリベースの関連投稿としています。
    // 必要に応じて works_tags / goods_tags を考慮するロジックを追加してください。
    $categories = get_the_category($current_post_id);
    if ($categories && !empty($categories[0])) {
        $args['category_name'] = $categories[0]->slug; // 最初のカテゴリのスラッグを使用
    }
}

$related_query = new WP_Query($args);

if ($related_query->have_posts()) :
    // ★修正点3: 親要素のクラスの変更ロジック
    // イベント投稿かどうかでクラスを切り替える
    if ($is_event_post) {
      echo '<div class="space-y-4 xl:space-y-6 mt-5 xl:mt-15 pt-4 xl:pt-6 border-t border-b border-[#D9D9D9]">';
    } else {
      echo '<div class="grid grid-cols-2 xl:grid-cols-4 mt-5 xl:mt-10 gap-x-2 gap-y-6 xl:gap-x-5">';
    }

    while ($related_query->have_posts()) : $related_query->the_post();

    // ★修正点4: テンプレートパーツの呼び出しロジック
    if ($is_event_post) { // イベント投稿の場合
        get_template_part('templates/event/event-list-item');
    } else { // それ以外の投稿タイプの場合
        $args_for_image_with_text = array(
            'rounded' => 'xl:rounded-[20px]',
            'mt_below' => 'mt-2 xl:mt-5'
        );

        // ★★★ この部分を修正します ★★★
        // ループ内の現在の投稿タイプが 'post' (通常の投稿) の場合に 'news' コンテキストを渡す
        if (get_post_type() === 'post') { // ここでループ内の投稿タイプを再取得
          $args_for_image_with_text['template_context'] = 'news';
      }
      // works や goods など、他のカスタム投稿タイプで特別な表示が必要な場合はここに追加

      get_template_part('templates/parts/image-with-text', null, $args_for_image_with_text);

    }

    endwhile;

    echo '</div>'; // 閉じタグ

    wp_reset_postdata();
endif;
?>
