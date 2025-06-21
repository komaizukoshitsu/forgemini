<?php
// templates/swiper/swiper-home-works.php (新しいファイル - ホームページ Works 投稿専用)

// このテンプレートはホームページの Works スライダー専用なので、
// 投稿タイプは 'works' に固定。
$post_type_slug = 'works';

// Works 投稿タイプ用のタクソノミーは 'works_tags' に固定。
$target_taxonomy_slug = 'works_tags';

// クエリ引数 (ピックアップタグ優先)
$pickup_args = array(
    'posts_per_page' => 5, // ピックアップする投稿数
    'post_type'      => $post_type_slug,
    'tax_query'      => array(
        array(
            'taxonomy' => $target_taxonomy_slug,
            'field'    => 'slug',
            'terms'    => 'pickup', // 'pickup' タグを検索
        ),
    ),
);

// フォールバック（'pickup' タグの条件なし）
$fallback_query_args = array(
    'posts_per_page' => 5,
    'post_type'      => $post_type_slug,
);

$pickup_query = new WP_Query($pickup_args);

// 'pickup' タグが見つからなかった場合、タグ条件なしで再クエリ
if (!$pickup_query->have_posts()) {
    $pickup_query = new WP_Query($fallback_query_args);
}

// 投稿が見つからない場合は処理を終了
if (!$pickup_query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<section class="swiper-section">
    <div class="swiper swiper-home-works"> <div class="swiper-wrapper">
            <?php while ($pickup_query->have_posts()) : $pickup_query->the_post(); ?>
                <div class="swiper-slide xl:w-75 max-w-153">
                    <?php
                    // Works 投稿なので、常に image-with-text.php を使う
                    get_template_part('templates/parts/image-with-text', null, array(
                        'text_position' => 'overlay',
                        'mt_below' => 'mt-2 xl:mt-5',
                        'aspect_ratio' => 'aspect-[3/2]',
                        'template_context' => 'works' // 'works' を渡す
                    ));
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); ?>
