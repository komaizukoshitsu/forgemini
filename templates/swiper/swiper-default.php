<?php
// templates/swiper/swiper-default.php

// archive-{post_type}.php から 'post_type_slug' として 'works', 'goods', 'events', 'post' のいずれかが渡される
$post_type_slug = $args['post_type_slug'] ?? null;

// ★重要: 各投稿タイプに紐付けられているタクソノミーのスラッグを定義
// 'post' (お知らせ) に対応するタクソノミーを追加
$taxonomy_map = [
    'works'  => 'works_tags',     // 'works' 投稿タイプ用のタクソノミースラッグ (必要なら変更)
    'goods'  => 'goods_tags',     // 'goods' 投稿タイプ用のタクソノミースラッグ (必要なら変更)
    'events' => 'event_type',     // 'events' 投稿タイプ用のタクソノミースラッグ
    'post'   => 'post_tag',       // ★追加: デフォルト投稿タイプ (お知らせ) 用のタクソノミースラッグ (例: タグ)
    // 必要に応じて他のカスタム投稿タイプとタクソノミーを追加
];

$target_taxonomy_slug = null;
if ($post_type_slug && isset($taxonomy_map[$post_type_slug])) {
    $target_taxonomy_slug = $taxonomy_map[$post_type_slug];
}

// クエリ引数
$pickup_args = array(
    'posts_per_page' => 5, // ピックアップする投稿数
    'post_type'      => $post_type_slug, // 渡されたスラッグを投稿タイプとして使用
    'tax_query'      => array( // タクソノミーによるクエリ
        array(
            'taxonomy' => $target_taxonomy_slug, // ★上記のマップから取得したタクソノミースラッグを使用
            'field'    => 'slug',
            'terms'    => 'pickup', // 'pickup' というスラッグのターム（タグ）を検索
        ),
    ),
);

// フォールバック（'pickup' タグの条件なし）
$fallback_query_args = array(
    'posts_per_page' => 5,
    'post_type'      => $post_type_slug,
    // 'tax_query' はここでは不要 (ピックアップタグなしの場合)
);

$pickup_query = new WP_Query($pickup_args);

// 'pickup' タグが見つからなかった場合、タグ条件なしで再クエリ
if (!$pickup_query->have_posts()) {
    $pickup_query = new WP_Query($fallback_query_args);
}

// 投稿タイプが指定されていない、または投稿が見つからない場合は処理を終了
if (!$post_type_slug || !$pickup_query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="swiper-section">
    <div class="swiper swiper-default">
        <div class="swiper-wrapper">
            <?php while ($pickup_query->have_posts()) : $pickup_query->the_post(); ?>
                <div class="swiper-slide xl:w-75 max-w-153">
                    <?php
                    // 'works' と 'goods' には 'templates/parts/image-with-text.php' を使用
                    // 'events' には 'templates/event/event-image-with-text.php' を使用するよう条件分岐を追加
                    // ★ 'post' (お知らせ) の場合も 'template_context' を渡す ★
                    if ($post_type_slug === 'events') {
                        get_template_part('templates/event/event-image-with-text', null, array(
                            'text_position' => 'overlay',
                            'mt_below' => 'mt-2 xl:mt-5',
                            'aspect_ratio' => 'aspect-[3/2]',
                            // 'template_context' => 'events' は event-image-with-text 内部で処理されるはずなのでここでは省略
                        ));
                    } else {
                        get_template_part('templates/parts/image-with-text', null, array(
                            'text_position' => 'overlay',
                            'mt_below' => 'mt-2 xl:mt-5',
                            'aspect_ratio' => 'aspect-[3/2]',
                            'template_context' => ($post_type_slug === 'post') ? 'news' : $post_type_slug // ★追加: 'post' なら 'news' を渡す
                        ));
                    }
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
