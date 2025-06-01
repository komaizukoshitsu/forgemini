<!-- 呼び出し元 => image-with-text.php -->
<?php
// badge-pickup の出力キャプチャ
ob_start();
get_template_part('templates/common/badge-pickup');
$badge_pickup = trim(ob_get_clean());

// badge-new の出力キャプチャ
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
    <?php the_field('goods-name'); ?>
</div>
<div class="flex items-baseline text-xs lg:text-sm leading-[1.3]">
    <div>¥</div>
    <div class="price " style="font-family: 'Open Sans', sans-serif;"><?php the_field('goods-price'); ?></div>
    <div class="text-[10px] lg:text-xs tracking-[0.05em]">（税込）</div>
</div>
<div class="flex text-[10px] lg:text-xs leading-[1.3]">
    <?php
    $tags = get_the_tags();
    foreach ( $tags as $tag ) {
        echo '<span>' . $tag->name . '</span>';
    }
    ?>
</div>
