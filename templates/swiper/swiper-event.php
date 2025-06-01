<?php
// templates/swiper/swiper-event.php

// 1. get_template_part() の第三引数で渡された $args を取得します。
//    $args がセットされていない場合に備えて空の配列をデフォルトとします。
$args = $args ?? [];
$exclude_ids = $args['exclude_ids'] ?? []; // 除外する ID の配列

// 今日の日付をYYYY-MM-DD 形式で取得します。
$today = date('Y-m-d');

$query_args = array(
    'post_type'      => 'events',
    'posts_per_page' => 5, // 表示件数は維持
    'meta_query' => array(
        'relation' => 'AND', // 複数の条件をANDで結合
        array(
            'key'     => 'event-end', // ★ACFフィールドキーであることを明示
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATE'
        ),
        // ピックアップイベントのみを表示したい場合は、以下の行を追加
        /*
        array(
            'key'     => 'is_pickup_events', // ★ACFで定義したピックアップフィールドキー
            'value'   => 1, // '1' または 'true' にチェックがある場合
            'compare' => '=',
            'type'    => 'NUMERIC' // または 'BOOLEAN'
        )
        */
    ),
    'orderby'  => 'meta_value',
    'meta_key' => 'event-start', // ★ACFフィールドキーであることを明示
    'order'    => 'ASC',
    'post__not_in' => $exclude_ids, // ここで ID を除外します。
);

// クエリを実行します。
$pickup_query = new WP_Query($query_args); // 変数名が 'pickup_query' なので、もしピックアップを意図しているなら上記meta_queryのコメントアウト部分を有効にしてください。
?>

<?php if ($pickup_query->have_posts()) : ?>
<section class="swiper-section">
    <div class="swiper swiper-event">
        <div class="swiper-wrapper">
            <?php while ($pickup_query->have_posts()) : $pickup_query->the_post(); ?>
                <div class="swiper-slide lg:w-75 max-w-153">
                    <?php
                    // event-image-with-text.php への引数は変更なしでOK
                    get_template_part('templates/event/event-image-with-text', null, array(
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
    <p>今後のイベントが見つかりませんでした。</p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
