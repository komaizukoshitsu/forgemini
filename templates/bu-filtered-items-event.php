<!-- 呼び出し元 => function.php  -->
<?php
// イベントの開始・終了日を取得
$start_date = get_field('event-start');
$end_date   = get_field('event-end');

// 投稿に紐づくタグのスラッグを取得（フィルター用）
$tags = get_the_tags();
$tag_slugs = array();

if ($tags && !is_wp_error($tags)) {
    foreach ($tags as $tag) {
        $tag_slugs[] = $tag->slug;
    }
}
$tags_str = implode(',', $tag_slugs);

// イベントが属する年月（Y-m形式）を配列に
$event_months = array();

if ($start_date) {
    $start = new DateTime($start_date);
    $end   = $end_date ? new DateTime($end_date) : clone $start;

    while ($start <= $end) {
        $event_months[] = $start->format('Y-m');
        $start->modify('first day of next month');
    }
}
$months_str = implode(',', $event_months);

// badge-pickup の出力キャプチャ
ob_start();
get_template_part('template-parts/badge-pickup');
$badge_pickup = trim(ob_get_clean());

// badge-new の出力キャプチャ
ob_start();
get_template_part('template-parts/badge-new');
$badge_new = trim(ob_get_clean());
?>

<article class="event-item pb-4 lg:pb-6 pl-3 lg:pl-4 border-b border-[#D9D9D9]" data-months="<?php echo esc_attr($months_str); ?>" data-tags="<?php echo esc_attr($tags_str); ?>">
    <?php if (!empty($badge_new) || !empty($badge_pickup)) : ?>
        <div class="flex gap-1 lg:gap-2 h-5 lg:h-6 items-center mb-1 lg:mb-2">
            <?= $badge_new; ?>
            <?= $badge_pickup; ?>
        </div>
    <?php endif; ?>
    <a href="<?php the_permalink(); ?>">
        <div class="top-news-title text-sm lg:text-base transition-all duration-250 ease-linear border-b border-white inline-block leading-[1.4]">
            <?php the_title(); ?>
        </div>
    </a>
    <div class="mt-1 lg:mt-2 flex text-[10px] lg:text-xs leading-[1.3]">
        <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;"><?php echo get_the_date(); ?></div>
        <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9] border-solid">
        <?php
            $tags = get_the_tags();
            foreach ( $tags as $tag ) {
                echo '<span>' . $tag->name . '</span>';
            }
        ?>
        </div>
    </div>
</article>
