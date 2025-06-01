<?php
$today = date('Y-m-d');

// イベントカテゴリの term_id を取得
$event_category = get_category_by_slug('event');

// カテゴリー条件（トップページとカテゴリーページ共通対応）
$category_filter = array(
    array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => 'event',
    ),
);

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'tax_query' => $category_filter,
    'meta_query' => array(
        array(
            'key' => 'event-end', // 終了日が今日以降のもの
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE'
        )
    ),
    'orderby' => 'meta_value',
    'meta_key' => 'event-start',
    'order' => 'ASC',
);

// クエリ実行
$pickup_query = new WP_Query($args);
?>

<?php if ($pickup_query->have_posts()) : ?>
<section class="swiper-section">
    <!-- <h2>イベントスライダー</h2> -->
    <div class="swiper swiper-event">
        <div class="swiper-wrapper">
            <?php while ($pickup_query->have_posts()) : $pickup_query->the_post(); ?>
                <div class="swiper-slide lg:w-75 max-w-153">
                    <?php
                    get_template_part('template-parts/event/event-image-with-text', null, array(
                        'text_position' => 'overlay',
                        'mt_below' => 'mt-2 lg:mt-5',
                        'aspect_ratio' => 'aspect-[3/2]'
                    ));
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php else: ?>
    <p>イベントが見つかりませんでした。</p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
