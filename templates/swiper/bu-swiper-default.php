<?php
// 明示的にスラッグでカテゴリーを渡す（例: 'news', 'goods' , 'works'）
$category_slug = $args['category_slug'] ?? null;

// カテゴリーページの場合は自動取得
if (is_category() || is_tag() || is_tax()) {
    $queried = get_queried_object();
    if ($queried && isset($queried->slug)) {
        $category_slug = $queried->slug;
    }
}

$pickup_args = array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'tag' => 'pickup',
);

// カテゴリースラッグがある場合
if ($category_slug) {
    $pickup_args['category_name'] = $category_slug;
}

$pickup_query = new WP_Query($pickup_args);

$has_posts = $pickup_query->have_posts();

// fallback（タグ条件なし）
if (!$has_posts) {
    unset($pickup_args['tag']);
    $pickup_query = new WP_Query($pickup_args);
    $has_posts = $pickup_query->have_posts();
}

if ($has_posts) :
?>
<section class="swiper-section">
    <div class="swiper swiper-default">
        <div class="swiper-wrapper">
            <?php while ($pickup_query->have_posts()) : $pickup_query->the_post(); ?>
                <div class="swiper-slide lg:w-75 max-w-153">
                    <?php
                    get_template_part('template-parts/image-with-text', null, array(
                        'text_position' => 'overlay',
                        'aspect_ratio' => 'aspect-[3/2]'
                    ));
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php else : ?>
    <p><?php esc_html_e('ピックアップされた投稿はありません。', 'your-theme'); ?></p>
<?php endif; wp_reset_postdata(); ?>
