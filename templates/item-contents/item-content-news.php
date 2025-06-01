<?php
// templates/item-contents/item-content-post.php (と仮定)

// 新着バッジは従来通り
ob_start();
get_template_part('templates/common/badge-new');
$badge_new = trim(ob_get_clean());

// このテンプレートが通常投稿用で、通常の投稿にピックアップバッジが不要な場合、
// $badge_pickup は空のままにするか、以下のブロックを削除
$badge_pickup = '';

// もし通常の投稿にもpickup機能を持たせるなら、別途ACFフィールドを作成し、以下のように取得
/*
$is_pickup_post = get_field('is_pickup_post', get_the_ID()); // 例えば 'is_pickup_post' というACFフィールド
if ($is_pickup_post) {
    ob_start();
    get_template_part('templates/common/badge-pickup');
    $badge_pickup = trim(ob_get_clean());
}
*/
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

<div class="flex text-[10px] lg:text-xs leading-[1.3]">
    <div class="tracking-[0.05em]" style="font-family: 'Open Sans', sans-serif;">
        <?php echo get_the_date(); ?>
    </div>
    <div class="ml-2 lg:ml-3 pl-2 lg:pl-3 border-l border-[#D9D9D9] border-solid">
        <?php
        // 通常投稿のタグは 'post_tag' タクソノミーを使うのでそちらを取得
        $tags = get_the_terms(get_the_ID(), 'post_tag');
        if ($tags && !is_wp_error($tags)) {
            foreach ($tags as $tag) {
                echo '<span>' . esc_html($tag->name) . '</span>';
            }
        }
        ?>
    </div>
</div>
