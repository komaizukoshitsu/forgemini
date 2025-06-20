<?php
$position = $args['text_position'] ?? 'below';
$rounded_class = $args['rounded'] ?? 'rounded-[10px] xl:rounded-[20px]';
$mt_below_class = $args['mt_below'] ?? 'mt-4 xl:mt-5';
$aspect_ratio = $args['aspect_ratio'] ?? 'aspect-square';
$template_context = $args['template_context'] ?? 'default';

// 投稿タイプを取得
$post_type = get_post_type();

// ★ここを修正・追加★
// filter_posts_by_custom_type_and_taxonomy から渡される data_tags を受け取る
$data_filter_item_value = $args['data_tags'] ?? '';

// template_context が明示的に渡された場合はそれを優先し、
// そうでなければ通常の投稿タイプを $template_name とする
if (!empty($template_context) && $template_context !== 'default') {
    $template_name = $template_context; // 'news' などがここに入る
} elseif ($template_context === 'top') {
    $template_name = 'top';
} else {
    $template_name = $post_type; // 通常の投稿タイプ (例: 'post', 'goods' など)
}

// 投稿タイプに応じたクラス名を生成 (例: work-item, goods-item, post-item)
// JavaScriptでフィルタリング対象として認識するために必要です。
$item_class = sanitize_html_class($post_type) . '-item';

?>

<article class="w-full <?php echo esc_attr($item_class); ?>" data-tags="<?php echo esc_attr($data_tags); ?>">
    <a href="<?php the_permalink(); ?>" class="block group">
        <div class="relative overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
            <div class="w-full overflow-hidden <?php echo esc_attr($rounded_class); ?> <?php echo esc_attr($aspect_ratio); ?>">
                <?php get_template_part('templates/parts/image-thumbnail', null, array(
                    'aspect_ratio' => $aspect_ratio,
                    'rounded' => ''
                )); ?>
            </div>

            <?php if ($position === 'overlay') : ?>
                <div class="hidden xl:flex absolute inset-0 flex-col justify-end p-4 xl:p-8 text-white transition-opacity duration-250 bg-gradient-to-b from-black/10 to-black/40 opacity-0 group-hover:opacity-100">
                    <div class="translate-y-4 opacity-0 transition-all duration-250 group-hover:translate-y-0 group-hover:opacity-100 flex flex-col xl:justify-end xl:items-baseline gap-1 xl:gap-2">
                        <?php
                        get_template_part('templates/item-contents/item-content', $template_name);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="
            <?php echo esc_attr($mt_below_class); ?>
            px-[5%] xl:px-0 flex flex-col items-baseline gap-1 xl:gap-2
            <?php
            if ($position === 'overlay') {
                echo 'block xl:hidden'; // overlayならスマホだけ表示
            } elseif ($position === 'below') {
                echo 'block'; // belowなら常に表示
            }
            ?>
        ">
            <?php
            get_template_part('templates/item-contents/item-content', $template_name);
            ?>
        </div>
    </a>
</article>
