<?php
// single.php

get_header();

// 投稿タイプスラッグを取得
$post_type = get_post_type();

// 現在の投稿に紐付けられたタクソノミー（カテゴリ）のslugを取得
// 標準投稿なら 'category'、カスタム投稿タイプなら 'event_category' などを考慮
$term_slug = '';
if ( $post_type === 'post' ) {
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        $term_slug = $categories[0]->slug;
    }
} else {
    // カスタム投稿タイプの場合のタクソノミー取得例
    // 例: events => event_category, goods => goods_category, works => works_category
    // 実際には、それぞれの投稿タイプに紐付けられたタクソノミー名を正確に指定する必要があります。
    // ここでは簡略化のため、投稿タイプ名と同じ接頭辞を持つタクソノミーを想定します。
    $taxonomy_slug = $post_type . '_tags'; // 例: 'event_category', 'goods_category'
    $terms = get_the_terms( get_the_ID(), $taxonomy_slug );
    if ( $terms && ! is_wp_error( $terms ) ) {
        $term_slug = $terms[0]->slug;
    }
}

// 読み込むテンプレートパーツのパスを決定
$template_path = '';

// 1. single-{post_type}-{term_slug}.php を探す（例: single-post-news.php, single-events-concert.php）
if ( ! empty( $post_type ) && ! empty( $term_slug ) ) {
    $potential_template = "templates/single/single-{$post_type}-{$term_slug}.php";
    if ( locate_template( $potential_template ) ) {
        $template_path = $potential_template;
    }
}

// 2. もし見つからなければ single-{post_type}.php を探す（例: single-events.php, single-goods.php）
if ( empty( $template_path ) && ! empty( $post_type ) ) {
    $potential_template = "templates/single/single-{$post_type}.php";
    if ( locate_template( $potential_template ) ) {
        $template_path = $potential_template;
    }
}

// 3. それも見つからなければ単一記事の汎用テンプレート single-default.php を探す
if ( empty( $template_path ) ) {
    $potential_template = "templates/single/single-default.php";
    if ( locate_template( $potential_template ) ) {
        $template_path = $potential_template;
    }
}

// 最終的に見つかったテンプレートをインクルード
if ( ! empty( $template_path ) ) {
    include( locate_template( $template_path ) );
} else {
    // どのテンプレートも見つからなかった場合のフォールバック（最低限のループ）
    if (have_posts()) : while (have_posts()) : the_post();
        the_title('<h1>', '</h1>');
        the_content();
    endwhile; endif;
}

get_footer();
?>
