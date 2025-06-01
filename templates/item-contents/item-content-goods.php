<?php
// 呼び出し元 => templates/parts/image-with-text.php (想定)

// ピックアップバッジの処理 (is_pickup_goods ACFフィールドから取得)
$badge_pickup = '';
if (get_field('is_pickup_goods', get_the_ID())) { // ACFフィールドから直接ピックアップ状態を取得
    ob_start();
    get_template_part('templates/common/badge-pickup');
    $badge_pickup = trim(ob_get_clean());
}

// badge-new の処理は元のまま使えます
ob_start();
get_template_part('templates/common/badge-new');
$badge_new = trim(ob_get_clean());
?>

<?php if (!empty($badge_new) || !empty($badge_pickup)) : ?>
    <div class="flex gap-1 lg:gap-2 h-5 lg:h-6 items-center">
        <?= $badge_new; ?>
        <?= $badge_pickup; ?>
    </div>
<?php endif; ?>

<div class="text-sm lg:text-base leading-[1.4]">
    <?php the_title(); ?>
</div>

<div class="flex items-baseline text-xs lg:text-sm leading-[1.3]">
    <div>¥</div>
    <div class="price" style="font-family: 'Open Sans', sans-serif;">
        <?php the_field('goods-price'); ?>
    </div>
    <div class="text-[10px] lg:text-xs tracking-[0.05em]">（税込）</div>
</div>

<div class="flex text-[10px] lg:text-xs leading-[1.3]">
    <?php
    // ★修正点2: goods_tags を goods_category に変更
    $categories = get_the_terms(get_the_ID(), 'goods_category'); // タクソノミー名を変更
    if ($categories && !is_wp_error($categories)) {
        $output = [];
        foreach ($categories as $category) {
            $output[] = '<span>' . esc_html($category->name) . '</span>';
        }
        echo implode(', ', $output); // 複数のカテゴリがある場合はカンマで区切って表示
    }
    ?>
</div>
